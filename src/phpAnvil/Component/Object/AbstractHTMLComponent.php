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
 * Base abstract class for all HTML Components.
 */
abstract class AbstractHTMLComponent extends AbstractComponent
{

    /**
     * CSS Class Name
     *
     * @var string $class
     */
    public $class;

    /**
     * HTML ID to use for the rendered control.
     *
     * @var string $id
     */
    public $id;

    /**
     * Custom CSS style for the rendered control.
     *
     * @var string $style
     */
    public $style;

    public $dataPlacement;

}
