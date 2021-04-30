<?php

namespace Space48\PriceSlider\Model\SearchAdapter\Dynamic;

use Magento\Elasticsearch\SearchAdapter\QueryAwareInterface;
use Magento\Elasticsearch\SearchAdapter\QueryContainer;

/**
 * Elastic search data provider
 *
 * @api
 * @since 100.1.0
 */
class DataProvider extends \Magento\Elasticsearch\SearchAdapter\Dynamic\DataProvider
{

    /**
     * @var QueryContainer
     */
    private $queryContainer;

    /**
     * @param \Magento\Elasticsearch\SearchAdapter\ConnectionManager $connectionManager
     * @param \Magento\Elasticsearch\Model\Adapter\FieldMapperInterface $fieldMapper
     * @param \Magento\Catalog\Model\Layer\Filter\Price\Range $range
     * @param \Magento\Framework\Search\Dynamic\IntervalFactory $intervalFactory
     * @param \Magento\Elasticsearch\Model\Config $clientConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Elasticsearch\SearchAdapter\SearchIndexNameResolver $searchIndexNameResolver
     * @param string $indexerId
     * @param \Magento\Framework\App\ScopeResolverInterface $scopeResolver
     * @param QueryContainer|null $queryContainer
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Elasticsearch\SearchAdapter\ConnectionManager $connectionManager,
        \Magento\Elasticsearch\Model\Adapter\FieldMapperInterface $fieldMapper,
        \Magento\Catalog\Model\Layer\Filter\Price\Range $range,
        \Magento\Framework\Search\Dynamic\IntervalFactory $intervalFactory,
        \Magento\Elasticsearch\Model\Config $clientConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Elasticsearch\SearchAdapter\SearchIndexNameResolver $searchIndexNameResolver,
        $indexerId,
        \Magento\Framework\App\ScopeResolverInterface $scopeResolver,
        QueryContainer $queryContainer = null
    ) {
        $this->connectionManager = $connectionManager;
        $this->fieldMapper = $fieldMapper;
        $this->range = $range;
        $this->intervalFactory = $intervalFactory;
        $this->clientConfig = $clientConfig;
        $this->storeManager = $storeManager;
        $this->searchIndexNameResolver = $searchIndexNameResolver;
        $this->indexerId = $indexerId;
        $this->scopeResolver = $scopeResolver;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @inheritdoc
     * @since 100.1.0
     */
    public function getAggregations(\Magento\Framework\Search\Dynamic\EntityStorage $entityStorage)
    {
        $aggregations = [
            'count' => 0,
            'max' => 0,
            'min' => 0,
            'std' => 0,
        ];

        $query = $this->getBasicSearchQuery($entityStorage);

        $fieldName = $this->fieldMapper->getFieldName('price');
        $query['body']['aggregations'] = [
            'prices' => [
                'extended_stats' => [
                    'field' => $fieldName,
                ],
            ],
        ];

       $this->removeCurrentPriceFilter($query, $fieldName);

        $queryResult = $this->connectionManager->getConnection()
            ->query($query);

        if (isset($queryResult['aggregations']['prices'])) {
            $aggregations = [
                'count' => $queryResult['aggregations']['prices']['count'],
                'max' => $queryResult['aggregations']['prices']['max'],
                'min' => $queryResult['aggregations']['prices']['min'],
                'std' => $queryResult['aggregations']['prices']['std_deviation'],
            ];
        }

        return $aggregations;
    }

    private function removeCurrentPriceFilter(array &$query, string $fieldName)
    {
        $filters = $query['body']['query']['bool']['must'] ?? [];
        foreach ($filters as $index => $filter) {
            foreach ($filter as $type => $typeFilters) {
                foreach ($typeFilters as $field => $fieldFilter) {
                    if ($field === $fieldName) {
                        // remove price filter
                        unset($query['body']['query']['bool']['must'][$index][$type][$fieldName]);
                    }
                    // remove empty nodes in request (causes Elastic parser error otherwise)
                    if (count($query['body']['query']['bool']['must'][$index][$type]) === 0) {
                        unset($query['body']['query']['bool']['must'][$index][$type]);
                    }
                    if (count($query['body']['query']['bool']['must'][$index]) === 0) {
                        unset($query['body']['query']['bool']['must'][$index]);
                    }
                    if (count($query['body']['query']['bool']['must']) === 0) {
                        unset($query['body']['query']['bool']['must']);
                    }
                    if (count($query['body']['query']['bool']) === 0) {
                        unset($query['body']['query']['bool']);
                    }
                }
            }
        }

        // Reindex numeric array.
        // After unsetting 'price' filter there might be a gap in index that will cause Elastic parser error
        $query['body']['query']['bool']['must'] = array_values($query['body']['query']['bool']['must']);
    }

    /**
     * Copy/Paste from parent (thanks Magento for having queryContainer as private property)
     *
     * @param \Magento\Framework\Search\Dynamic\EntityStorage $entityStorage
     * @param array $dimensions
     * @return array
     */
    private function getBasicSearchQuery(
        \Magento\Framework\Search\Dynamic\EntityStorage $entityStorage,
        array $dimensions = []
    ) {
        if ($this->queryContainer) {
            return $this->queryContainer->getQuery();
        }

        $entityIds = $entityStorage->getSource();

        $dimension = current($dimensions);
        $storeId = false !== $dimension
            ? $this->scopeResolver->getScope($dimension->getValue())->getId()
            : $this->storeManager->getStore()->getId();

        $query = [
            'index' => $this->searchIndexNameResolver->getIndexName($storeId, $this->indexerId),
            'type' => $this->clientConfig->getEntityType(),
            'body' => [
                'fields' => [
                    '_id',
                    '_score',
                ],
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'terms' => [
                                    '_id' => $entityIds,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $query;
    }
}
