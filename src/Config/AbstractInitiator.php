<?php
/*
*
* (c) Marco Bunge <marco_bunge@web.de>
*
* For the full copyright and license information, please view the LICENSE.txt
* file that was distributed with this source code.
*
* Date: 09.01.2016
* Time: 15:33
*/

namespace Turbine\Config;


use Blast\Config\Factory;
use Blast\Config\Locator;

abstract class AbstractInitiator implements InitiatorInterface
{

    /**
     * @var array
     */
    private $nodes = [];

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Locator
     */
    private $locator;

    /**
     * @var string
     */
    private $environment = self::ENVIRONMENT;

    /**
     * AbstractInitiator constructor.
     * @param Factory $factory
     * @param Locator $locator
     */
    public function __construct(Factory $factory, Locator $locator)
    {
        $this->setFactory($factory);
        $this->setLocator($locator);
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param Factory $factory
     * @return AbstractInitiator
     */
    protected function setFactory(Factory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return Locator
     */
    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * @param Locator $locator
     * @return AbstractInitiator
     */
    protected function setLocator(Locator $locator)
    {
        $this->locator = $locator;

        return $this;
    }


    /**
     * @return array
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param array $nodes
     * @return AbstractInitiator
     */
    public function setNodes($nodes)
    {
        $this->nodes = $nodes;

        return $this;
    }

    /**
     * Get node data from node file
     *
     * @param $nodeFile
     * @return AbstractInitiator
     * @throws EnvironmentNotFoundException
     */
    public function init($nodeFile)
    {
        //set environment to load config
        $this->setEnvironment($this->determineEnvironment());
        $environment = $this->getEnvironment();

        $nodes = $this->getFactory()->load($nodeFile, $this->getLocator());

        if (!isset($nodes[ $environment ])) {
            throw new EnvironmentNotFoundException($environment);
        }

        $this->setNodes($this->sortNodes($nodes[ $environment ]));

        return $this;
    }

    /**
     * Determine environment from getenv. If no Environment is available set default environment
     * @return string
     */
    protected function determineEnvironment()
    {
        $environment = getenv('TURBINE_ENVIRONMENT');

        return (!$environment) ? $this->getEnvironment() : $environment;
    }

    protected function sortNodes($nodes)
    {
        $lowest = 0;
        $highest = 0;

        //get lowest and highest integer
        foreach ($nodes as $node) {
            if (!isset($node['priority'])) {
                continue;
            }
            $priority = $node['priority'];
            if (!is_numeric($priority)) {
                continue;
            }

            $priority = intval($priority);

            if ($priority > $highest) {
                $highest = $priority;
            }

            if ($priority < $lowest) {
                $lowest = $priority;
            }
        }

        //get average integer for invalid priority
        $average = ($lowest + $highest) / 2;
        $highest++;
        $lowest--;

        //determine priority from node
        $getPriority = function ($node) use ($average, $lowest, $highest) {
            if (!isset($node['priority'])) {
                return $average;
            }

            $priority = $node['priority'];

            switch ($priority) {
                case "first":
                    $priority = $lowest;
                    break;
                case "last":
                    $priority = $highest;
                    break;
            }

            if (!is_numeric($priority)) {
                return $average;
            }

            return intval($priority);
        };

        //sort nodes
        uasort($nodes, function ($a, $b) use ($getPriority, $average, $lowest, $highest) {

            $priorityA = $getPriority($a);
            $priorityB = $getPriority($b);

            return ($priorityA > $priorityB) ? 1 : -1;
        });

        return $nodes;
    }

    abstract function execute();

}