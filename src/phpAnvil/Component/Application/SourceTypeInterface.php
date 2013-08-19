<?php
/*
 * This file is part of the phpAnvil framework.
 *
 * (c) Nick Slevkoff <nick@phpanvil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace phpAnvil\Component\Application;

/**
 * Source Type Constants
 */
interface SourceTypeInterface
{
    /**
     * From an unknown source.
     */
    const SOURCE_TYPE_UNKNOWN   = 1;

    /**
     * from a user.
     */
    const SOURCE_TYPE_USER      = 2;

    /**
     * From an internal system process.
     */
    const SOURCE_TYPE_SYSTEM    = 3;

    /**
     * From an AJAX call.
     */
    const SOURCE_TYPE_AJAX      = 4;

    /**
     * From the Background Processor.
     */
    const SOURCE_TYPE_BP        = 5;

    /**
     * From an API call.
     */
    const SOURCE_TYPE_API       = 6;

    /**
     * From a code/content generator.
     */
    const SOURCE_TYPE_GENERATED = 7;

    /**
     * From email.
     */
    const SOURCE_TYPE_EMAIL     = 8;

    /**
     * From an import.
     */
    const SOURCE_TYPE_IMPORT    = 9;

}
