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

use cloak\event\StartEventInterface;
use cloak\event\StopEventInterface;
use cloak\writer\FileWriter;

/**
 * Class LcovReporter
 * @package cloak\reporter
 */
class LcovReporter implements ReporterInterface
{

    use Reportable;

    /**
     * @var \cloak\writer\FileWriter
     */
    private $fileWriter;

    /**
     * @param string|null $outputFile
     */
    public function __construct($outputFilePath)
    {
        $this->fileWriter = new FileWriter($outputFilePath);
    }

    /**
     * @param \cloak\event\StartEventInterface $event
     */
    public function onStart(StartEventInterface $event)
    {
        $startAt = $event->getSendAt()->format('j F Y \a\t H:i');
        echo "Start at: ", $startAt, PHP_EOL;
    }

    /**
     * @param \cloak\event\StopEventInterface $event
     */
    public function onStop(StopEventInterface $event)
    {
        $result = $event->getResult();

        $files = $result->getFiles();

        foreach($files as $file) {
            $this->fileWriter->writeLine('SF:' . $file->getPath());

            $lines = $file->getLineResults();
            foreach ($lines as $line) {
                if ($line->isExecuted() === false) {
                    continue;
                }
                $this->fileWriter->writeLine('DA:' . $line->getLineNumber() . ',1');
            }

            $this->fileWriter->writeLine('end_of_record');
        }
    }

}
