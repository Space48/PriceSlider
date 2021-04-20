<?php

namespace Space48\PriceSlider\Block;

use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Template;
use Magento\LayeredNavigation\Block\Navigation\FilterRendererInterface;

class PriceSlider extends Template implements FilterRendererInterface
{

    /**
     * @var string
     */
    protected $_template = 'Space48_PriceSlider::price-slider.phtml';

    /**
     * @var Http
     */
    public $request;

    /**
     * @var array
     */
    private $filterData = [];

    public function __construct(
        Template\Context $context,
        Http $request,
        array $data = []
    ) {
        $this->request = $request;

        parent::__construct($context, $data);
    }

    public function render(FilterInterface $filter)
    {
        $this->filterData = $filter->getItems();

        return $this->_toHtml();
    }

    /**
     * Get Slider Min Price
     *
     * @return mixed
     */
    public function getSliderMinPrice()
    {
        if (!$this->getPriceRange()['min']) {
            return $this->getProductsMinPrice();
        }
        return $this->getPriceRange()['min'];
    }

    /**
     * Get Price Range
     *
     * @return array|mixed
     */
    private function getPriceRange()
    {
        if ($priceRange = $this->request->getParam('price')) {
            $priceRange = ['min' => explode('-', $priceRange)[0], 'max' => explode('-', $priceRange)[1]];
        } else {
            $priceRange = [
                'min' => $this->getProductsMinPrice(),
                'max' => $this->getProductsMaxPrice()
            ];
        }

        return $priceRange;
    }

    /**
     * Get Products Max Price
     *
     * @return int
     */
    public function getProductsMinPrice()
    {
        return $this->filterData['min'] ?? '';
    }

    /**
     * Get Products Max Price
     *
     * @return int
     */
    public function getProductsMaxPrice()
    {
        return $this->filterData['max'] ?? '';
    }

    /**
     * Get Slider Max Price
     *
     * @return mixed
     */
    public function getSliderMaxPrice()
    {
        if (!$this->getPriceRange()['max']) {
            return $this->getProductsMaxPrice();
        }
        return $this->getPriceRange()['max'];
    }

    public function getCurrencySymbol()
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCurrencySymbol();
    }
}
