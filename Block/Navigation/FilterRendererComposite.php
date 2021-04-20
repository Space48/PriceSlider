<?php

namespace Space48\PriceSlider\Block\Navigation;

use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\LayeredNavigation\Block\Navigation\FilterRendererInterface;
use Magento\LayeredNavigation\Block\Navigation\FilterRenderer;

class FilterRendererComposite extends AbstractBlock implements FilterRendererInterface
{
    /**
     * @var \Magento\LayeredNavigation\Block\Navigation\FilterRenderer
     */
    private $defaultRenderer;

    /**
     * @var array
     */
    private $filterBlocks;

    public function __construct(
        FilterRenderer $filterRenderer,
        $filterBlocks = []
    ) {
        $this->defaultRenderer = $filterRenderer;
        $this->filterBlocks = $filterBlocks;
    }

    /**
     * @param FilterInterface $filter
     * @return string
     */
    public function render(FilterInterface $filter)
    {
        if (isset($this->filterBlocks[$filter->getRequestVar()])) {
            /** @var FilterRendererInterface $block */
            $block = $this->filterBlocks[$filter->getRequestVar()];
            $html = $block->render($filter);
        } else {
            $html = $this->defaultRenderer->render($filter);
        }

        return $html;
    }
}
