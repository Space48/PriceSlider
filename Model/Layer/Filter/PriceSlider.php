<?php

namespace Space48\PriceSlider\Model\Layer\Filter;

use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\CatalogSearch\Model\Layer\Filter\Price;

/**
 * Rebuilds aggregations format to have only min and max values for the slider
 */
class PriceSlider extends Price
{

    /**
     * Initialize filter items
     *
     * @return  \Magento\Catalog\Model\Layer\Filter\AbstractFilter
     */
    protected function _initItems()
    {
        $data = [];

        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()->getProductCollection();

        $facets = $productCollection->getFacetedData('price');
        if ($facets) {
            $data = [
                'min' => $facets['min']['count'],
                'max' => $facets['max']['count'],
            ];
        }

        $this->_items = $data;
        
        return $this;
    }

}
