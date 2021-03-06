<?php
/**
 *
 * (c) Marco Bunge <marco_bunge@web.de>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 *
 * Date: 12.01.2016
 * Time: 11:14
 *
 */

namespace Turbine\Container;


use Blast\Facades\FacadeFactory;
use Interop\Container\ContainerInterface;

trait ContainerStaticAwareTrait
{
    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return FacadeFactory::getContainer();
    }

}