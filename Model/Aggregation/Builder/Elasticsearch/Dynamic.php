<?php

namespace Space48\PriceSlider\Model\Aggregation\Builder\Elasticsearch;

use Magento\Framework\Search\Dynamic\DataProviderInterface;
use Magento\Framework\Search\Dynamic\EntityStorage;
use Magento\Framework\Search\Dynamic\EntityStorageFactory;
use Magento\Framework\Search\Request\Aggregation\DynamicBucket;
use Magento\Framework\Search\Request\BucketInterface as RequestBucketInterface;

/**
 * Elasticsearch dynamic aggregation builder.
 */
class Dynamic implements \Magento\Elasticsearch\SearchAdapter\Aggregation\Builder\BucketBuilderInterface
{

    /**
     * @var EntityStorageFactory
     */
    private $entityStorageFactory;

    /**
     * @param EntityStorageFactory $entityStorageFactory
     */
    public function __construct(EntityStorageFactory $entityStorageFactory)
    {
        $this->entityStorageFactory = $entityStorageFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function build(
        RequestBucketInterface $bucket,
        array $dimensions,
        array $queryResult,
        DataProviderInterface $dataProvider
    ) {
        $entityStorage = $this->getEntityStorage($queryResult);
        $aggregations = $dataProvider->getAggregations($entityStorage);

        return [
            'min' => ['value' => 'min', 'count' => floor($aggregations['min'])],
            'max' => ['value' => 'max', 'count' => ceil($aggregations['max'])],
        ];
    }

    /**
     * Extract Document ids
     *
     * @param array $queryResult
     * @return EntityStorage
     */
    private function getEntityStorage(array $queryResult)
    {
        $ids = [];
        foreach ($queryResult['hits']['hits'] as $document) {
            $ids[] = $document['_id'];
        }

        return $this->entityStorageFactory->create($ids);
    }

}
