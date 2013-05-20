<?php
/*
 * phpAnvil Framework
 *
 * Copyright (c) 2009-2011 Nick Slevkoff
 *
 * LICENSE
 *
 * This source file is subject to the MIT License that is bundled with this
 * package in the file LICENSE.md.  It is also available online at the following:
 * - http://www.phpanvil.com/LICENSE.md
 * - http://www.opensource.org/licenses/mit-license.php
 */

namespace phpAnvil\Framework;

use phpAnvil\Component\RouterAbstract;

/**
 * Log router class for the %phpAnvil %Framework.
 *
 * @copyright   Copyright (c) 2009-2011 Nick Slevkoff
 * @license     MIT License
 *      Full copyright and license information is available in the LICENSE.md
 *      file that was distributed with this source file or can be found online
 *      at http://www.phpanvil.com/LICENSE.md
 */
class LogRouter extends RouterAbstract
{

    public function endGroup($name)
    {
        $maxRelays = count($this->_relays);
        for ($i = 0; $i < $maxRelays; $i++)
        {
            $this->_relays[$i]->endGroup($name);
        }
    }


    public function process($detail, $title = '', $logLevel = self::LOG_LEVEL_DEBUG, $file = '', $method = '', $line = 0)
    {
        $maxRelays = count($this->_relays);
        for ($i = 0; $i < $maxRelays; $i++)
        {
            $this->_relays[$i]->process($detail, $title, $logLevel, $file, $method, $line);
        }

        return true;
    }


    public function startGroup($name)
    {
        $maxRelays = count($this->_relays);
        for ($i = 0; $i < $maxRelays; $i++)
        {
            $this->_relays[$i]->startGroup($name);
        }
    }

}
