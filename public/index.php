<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 30.12.2015
* Time: 14:23
*/

use Turbine\Application\Http\Bootstrap as Bootstrap;



#ini_set('display_errors', 1);
error_reporting(E_ALL);
umask(0);

Bootstrap::create(dirname(__DIR__));
