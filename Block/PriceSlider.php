<?php
/**
 * Space48_QuickView
 *
 * @category    Space48
 * @package     Space48_PriceSlider
 * @Date        02/2017
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */

declare(strict_types = 1);

namespace Space48\PriceSlider\Block;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class PriceSlider extends Template
{

    /**
     * @var Http
     */
    protected $_request;

    /**
     * @var Registry
     */
    private $_registry;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        Http $request,
        array $data = [])
    {
        $this->_registry = $registry;
        $this->_request = $request;
        parent::__construct($context, $data);
    }

    /**
     * Get Products Max Price
     *
     * @return int
     */
    public function getProductsMinPrice()
    {
        return $this->getCategoryProductPrice('ASC');
    }

    /**
     * Get Products MaxP rice
     *
     * @return int
     */
    public function getProductsMaxPrice()
    {
        return $this->getCategoryProductPrice('DESC');
    }

    /**
     * Get Category Product Price
     *
     * @param $sortOrder
     *
     * @return int
     */
    protected function getCategoryProductPrice($sortOrder): int
    {
        return (int) $this->getCurrentCategory()
            ->getProductCollection()
            ->setOrder('price', $sortOrder)
            ->getFirstItem()
            ->getData('price');
    }

    /**
     * Get Current Category
     * @return mixed
     */
    private function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }

    /**
     * Get Slider Min Price
     * @return mixed
     */
    public function getSliderMinPrice()
    {
        return $this->getPriceRange()['min'];
    }

    /**
     * Get Slider Max Price
     *
     * @return mixed
     */
    public function getSliderMaxPrice()
    {
        return $this->getPriceRange()['max'];
    }

    /**
     * Get Price Range
     *
     * @return array|mixed
     */
    protected function getPriceRange()
    {
        if ($priceRange = $this->_request->getParam('price')) {
            $priceRange = array('min' => explode('-', $priceRange)[0], 'max' => explode('-', $priceRange)[1]);

        } else {
            $priceRange = array('min' => $this->getProductsMinPrice(), 'max' => $this->getProductsMaxPrice());
        }

        return $priceRange;
    }
}

