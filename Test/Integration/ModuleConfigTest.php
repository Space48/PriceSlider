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

namespace Space48\PriceSlider\Test\Integration;

use Magento\Framework\Component\ComponentRegistrar;

class ModuleConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testModuleIsInstalled()
    {
        $registrar = new ComponentRegistrar;
        $path = $registrar->getPaths(ComponentRegistrar::MODULE);
        $this->assertArrayHasKey('Space48_PriceSlider', $path);
    }
}
