<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 09.01.2016
* Time: 16:26
*/

namespace Turbine\Tests\Config;

use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request;
use Turbine\Config\Http\Configurator as HttpInitiator;
use Turbine\System\Resources;

class InitiatorTest extends \PHPUnit_Framework_TestCase
{

    public function testHttpConfig()
    {
        $psr7Factory = new DiactorosFactory();
        $request = $psr7Factory->createRequest(Request::create('http://turbine.dev/test-node/?query-param=val'));
        $resources = new Resources(__DIR__ . '/../res');
        $initiator = new HttpInitiator('/config/nodes.json', 'default', $resources);
        $initiator->setRequest($request);
        $data = $initiator->configure();

        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('name', $data);
    }


}
