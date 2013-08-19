<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Object;

use phpAnvil\Component\Object\AbstractComponent;

/**
 * Abstract component that adds object plugin support.
 */
abstract class PluginableAbstract extends AbstractComponent
{

    const PLUGIN_TYPE_DEFAULT = 0;


    /**
     * Array that contains all of the added object plugins.
     */
    protected $_plugins = array();


    /**
     * Adds a plugin object to the component for process inclusion.
     *
     * @param PluginAbstract $plugin
     *      The plugin object to add.
     * @param integer $pluginType
     *      (optional) The type of plugin being added.
     *
     * @return NULL
     */
    public function addPlugin($plugin, $pluginType = self::PLUGIN_TYPE_DEFAULT)
    {
        $this->_plugins[$pluginType][] = $plugin;
    }


    public function process($pluginType = self::PLUGIN_TYPE_DEFAULT)
    {
        $maxPlugins = count($this->_plugins[$pluginType][]);
        for ($i = 0; $i < $maxPlugins; $i++)
        {
            $this->_plugins[$pluginType][$i]->process();
        }

        return true;
    }

}
