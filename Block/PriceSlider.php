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

declare(strict_types=1);

namespace Space48\PriceSlider\Block;

use Magento\Catalog\Model\Layer\Category;
use Magento\Catalog\Model\Layer\CategoryFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class PriceSlider extends Template
{

    /**
     * @var Http
     */
    public $request;

    /**
     * @var Registry
     */
    private $registry;
    /**
     * @var CategoryFactory
     */
    private $layerFactory;
    private $maxPrice;
    private $minPrice;

    public function __construct(
        Template\Context $context,
        Registry $registry,
        Http $request,
        CategoryFactory $layerFactory,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->request = $request;
        $this->layerFactory = $layerFactory;
        parent::__construct($context, $data);
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
                'min' => $this->getProductsMinPrice($this->getCurrentCategory()),

                'max' => $this->getProductsMaxPrice($this->getCurrentCategory())
            ];
        }

        return $priceRange;
    }

    /**
     * Get Products Max Price
     *
     * @return int
     */
    public function getProductsMinPrice($currentCategory)
    {
        if ($this->minPrice == null) {
            $layer = $this->layerFactory->create();
            $layer->setCurrentCategory($currentCategory);
            $this->minPrice = floor($layer->getProductCollection()->getMinPrice());
        }

        return $this->minPrice;
    }

    /**
     * Get Current Category
     *
     * @return mixed
     */
    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

    /**
     * Get Products Max Price
     *
     * @return int
     */
    public function getProductsMaxPrice($currentCategory)
    {
        if ($this->maxPrice == null) {
            $this->getLayer()->setCurrentCategory($currentCategory);
            $this->maxPrice = floor($this->getLayer()->getProductCollection()->getMaxPrice());
        };

        return $this->maxPrice;
    }

    /**
     * @return Category
     */
    private function getLayer(): Category
    {
        $layer = $this->layerFactory->create();

        return $layer;
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
}
