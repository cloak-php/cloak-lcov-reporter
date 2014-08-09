<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak\report;

use cloak\Result;
use cloak\result\File;
use PhpCollection\SequenceInterface;

/**
 * Class LcovReport
 * @package cloak\report
 */
class LcovReport implements FileSavableReportInterface
{

    /**
     * @var \cloak\Result
     */
    private $result;

    /**
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * Save the report to a file
     *
     * @param string $path report file name
     * @throws \cloak\report\DirectoryNotFoundException
     * @throws \cloak\report\DirectoryNotWritableException
     */
    public function saveAs($path)
    {
        $directory = dirname($path);

        if (file_exists($directory) === false) {
            throw new DirectoryNotFoundException("Directory not found {$directory}");
        }

        if (is_writable($directory) === false) {
            throw new DirectoryNotWritableException("Directory not writable {$directory}");
        }

        $report = (string) $this;
        file_put_contents($path, $report);
    }

    public function output()
    {
        echo $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getReportFromResult($this->result);
    }

    /**
     * @param Result $result
     * @return string
     */
    private function getReportFromResult(Result $result)
    {
        $results = [];
        $fileReports = $result->getFiles();

        foreach ($fileReports as $fileReport) {
            $results[] = $this->getReportFromFile($fileReport);
        }

        $content = implode(PHP_EOL, $results);

        return $content . PHP_EOL;
    }

    /**
     * @param SequenceInterface $files
     * @return string
     */
    private function getReportFromFile(File $fileReport)
    {
        $lines = $fileReport->getLines();

        $results = [];
        $results[] = "SF:" . $fileReport->getPath();
        $results[] = $this->getReportFromLines($lines);
        $results[] = "end_of_record";

        $content = implode(PHP_EOL, $results);

        return $content;
    }

    /**
     * @param SequenceInterface $lines
     * @return string
     */
    private function getReportFromLines(SequenceInterface $lines)
    {
        $results = [];
        $lineReports = $lines->all();

        foreach ($lineReports as $lineReport) {
            if ($lineReport->isExecuted() === false) {
                continue;
            }
            $results[] = "DA:" . $lineReport->getLineNumber() . ",1";
        }

        $content = implode(PHP_EOL, $results);

        return $content;
    }

}
