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

use cloak\report\LcovReport;
use cloak\event\StartEventInterface;
use cloak\event\StopEventInterface;

/**
 * Class LcovReporter
 * @package cloak\reporter
 */
class LcovReporter implements ReporterInterface
{

    use Reportable;

    /**
     * @var string
     */
    private $outputFilePath;

    /**
     * @param string|null $outputFile
     */
    public function __construct($outputFilePath = null)
    {
        $this->outputFilePath = $outputFilePath;
    }

    /**
     * @param \cloak\event\StartEventInterface $event
     */
    public function onStart(StartEventInterface $event)
    {
    }

    /**
     * @param \cloak\event\StopEventInterface $event
     * @throws \cloak\report\DirectoryNotFoundException
     * @throws \cloak\report\DirectoryNotWritableException
     */
    public function onStop(StopEventInterface $event)
    {
        $result = $event->getResult();

        $report = new LcovReport($result);
        $report->saveAs($this->outputFilePath);
    }

}
