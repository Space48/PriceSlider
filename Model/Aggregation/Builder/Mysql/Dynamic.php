<?php

namespace Space48\PriceSlider\Model\Aggregation\Builder\Mysql;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Search\Adapter\Mysql\Aggregation\DataProviderInterface;
use Magento\Framework\Search\Dynamic\Algorithm\Repository;
use Magento\Framework\Search\Dynamic\EntityStorageFactory;
use Magento\Framework\Search\Request\Aggregation\DynamicBucket;
use Magento\Framework\Search\Request\BucketInterface as RequestBucketInterface;

/**
 * MySQL search dynamic aggregation builder. (For backward compatibility)
 *
 * @deprecated 102.0.0
 * @see \Magento\ElasticSearch
 */
class Dynamic implements \Magento\Framework\Search\Adapter\Mysql\Aggregation\Builder\BucketInterface
{

    /**
     * @var EntityStorageFactory
     */
    private $entityStorageFactory;

    /**
     * @var \Magento\Framework\Search\Dynamic\DataProviderInterface
     */
    private $dynamicDataProvider;

    /**
     * @param EntityStorageFactory $entityStorageFactory
     * @param \Magento\Framework\Search\Dynamic\DataProviderInterface $dynamicDataProvider
     */
    public function __construct(
        EntityStorageFactory $entityStorageFactory,
        \Magento\Framework\Search\Dynamic\DataProviderInterface $dynamicDataProvider
    ) {
        $this->entityStorageFactory = $entityStorageFactory;
        $this->dynamicDataProvider = $dynamicDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function build(
        DataProviderInterface $dataProvider,
        array $dimensions,
        RequestBucketInterface $bucket,
        Table $entityIdsTable
    ) {
        $aggs = $this->dynamicDataProvider->getAggregations($this->entityStorageFactory->create($entityIdsTable));

        return [
            'min' => ['value' => 'min', 'count' => floor($aggs['min'])],
            'max' => ['value' => 'max', 'count' => ceil($aggs['max'])],
        ];
    }

}
