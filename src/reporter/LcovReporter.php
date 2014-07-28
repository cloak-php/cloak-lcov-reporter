<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\reporter;

use cloak\event\EventInterface;
use cloak\event\StopEventInterface;

/**
 * Class LcovReporter
 * @package cloak\reporter
 */
class LcovReporter implements ReporterInterface
{

    use Reportable;

    /**
     * @param \cloak\event\EventInterface $event
     */
    public function onStart(EventInterface $event)
    {
    }

    /**
     * @param \cloak\event\StopEventInterface $event
     */
    public function onStop(StopEventInterface $event)
    {
    }

}
