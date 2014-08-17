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


use cloak\Result;
use cloak\result\File;
use cloak\result\Line;
use cloak\event\StartEventInterface;
use cloak\event\StopEventInterface;
use cloak\writer\FileWriter;
use cloak\writer\ConsoleWriter;


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
     * @var \cloak\writer\ConsoleWriter
     */
    private $consoleWriter;


    /**
     * @param string|null $outputFile
     * @throws \cloak\writer\DirectoryNotFoundException
     * @throws \cloak\writer\DirectoryNotWritableException
     */
    public function __construct($outputFilePath)
    {
        $this->fileWriter = new FileWriter($outputFilePath);
        $this->consoleWriter = new ConsoleWriter();
    }

    /**
     * @param \cloak\event\StartEventInterface $event
     */
    public function onStart(StartEventInterface $event)
    {
    }

    /**
     * @param \cloak\event\StopEventInterface $event
     */
    public function onStop(StopEventInterface $event)
    {
        $result = $event->getResult();
        $this->writeResult($result);
    }

    /**
     * @param Result $result
     */
    private function writeResult(Result $result)
    {
        $files = $result->getFiles();

        foreach($files as $file) {
            $this->writeFileResult($file);
        }
    }

    /**
     * @param File $file
     */
    private function writeFileResult(File $file)
    {
        $this->writeFileHeader($file);

        $executedLines = $this->getExecutedLinesFromFile($file);

        foreach ($executedLines as $executedLine) {
            $this->writeLineResult($executedLine);
        }

        $this->writeFileFooter();
    }

    /**
     * @param File $file
     */
    private function writeFileHeader(File $file)
    {
        $parts = [
            'SF:',
            $file->getPath()
        ];

        $record = implode('', $parts);
        $this->fileWriter->writeLine($record);
    }

    private function writeFileFooter()
    {
        $this->fileWriter->writeLine('end_of_record');
    }

    /**
     * @param \cloak\result\Line $line
     */
    private function writeLineResult(Line $line)
    {
        $parts = [
            $line->getLineNumber(),
            1
        ];

        $record = 'DA:' . implode(',', $parts);
        $this->fileWriter->writeLine($record);
    }

    /**
     * @param File $file
     * @return array
     */
    private function getExecutedLinesFromFile(File $file)
    {
        $results = [];
        $lines = $file->getLineResults();

        foreach ($lines as $line) {
            if ($line->isExecuted() === false) {
                continue;
            }
            $results[] = $line;
        }
        return $results;
    }

}
