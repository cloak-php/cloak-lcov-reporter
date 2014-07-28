<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\Analyzer;
use cloak\Configuration;
use cloak\event\StartEvent;
use cloak\event\StopEvent;
use cloak\Result;
use cloak\result\Line;
use cloak\reporter\LcovReporter;


describe('LcovReporter', function() {

    describe('onStop', function() {

        before(function() {
            $this->reporter = new LcovReporter();
            $this->coverages = [
                realpath(__DIR__ . '/../../src/driver/XdebugDriver.php') => [
                    1 => Line::EXECUTED,
                    2 => Line::EXECUTED,
                    3 => Line::UNUSED
                ],
                realpath(__DIR__ . '/../../src/result/Line.php') => [
                    1 => Line::EXECUTED,
                    2 => Line::EXECUTED,
                    3 => Line::EXECUTED,
                    4 => Line::EXECUTED,
                    5 => Line::EXECUTED,
                    6 => Line::EXECUTED,
                    7 => Line::EXECUTED,
                    8 => Line::UNUSED,
                    9 => Line::UNUSED,
                    10 => Line::UNUSED
                ],
                realpath(__DIR__ . '/../../src/result/File.php') => [
                    1 => Line::EXECUTED,
                    2 => Line::EXECUTED,
                    3 => Line::UNUSED,
                    4 => Line::UNUSED,
                    5 => Line::UNUSED,
                    6 => Line::UNUSED,
                    7 => Line::UNUSED
                ]
            ];

            $this->result = Result::from($this->coverages);

            $this->analyzer = new Analyzer(new Configuration());

            $this->startEvent = new StartEvent($this->analyzer);
            $this->stopEvent = new StopEvent($this->analyzer, [
                'result' => $this->result
            ]);
        });
        it('should save lcov report file');
    });

});
