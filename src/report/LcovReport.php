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

/**
 * Class LcovReport
 * @package cloak\report
 */
class LcovReport implements FileSavableReportInterface
{

    /**
     * Save the report to a file
     *
     * @param string $path report file name
     */
    public function saveAs($path)
    {
    }

    public function output()
    {
        echo $this;
    }

    public function __toString()
    {
        return "";
    }

}
