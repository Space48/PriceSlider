<?php

namespace Space48\PriceSlider\Block\Navigation;

use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\LayeredNavigation\Block\Navigation\FilterRendererInterface;
use Magento\LayeredNavigation\Block\Navigation\FilterRenderer;

class FilterRendererComposite extends AbstractBlock implements FilterRendererInterface
{
    /**
     * @param FilterInterface $filter
     * @return string
     */
    public function render(FilterInterface $filter)
    {
        /** @var FilterRendererInterface $block */
        $block = $this->getChildBlock('renderer_' . $filter->getRequestVar());
        if (!$block) {
            $block = $this->getChildBlock('renderer_default');
        }

        $html = $block ? $block->render($filter) : '';

        return $html;
    }
}
