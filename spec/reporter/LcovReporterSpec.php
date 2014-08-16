<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\Result;
use cloak\result\Line;
use cloak\reporter\LcovReporter;
use \Mockery;
use \DateTime;

describe('LcovReporter', function() {

    describe('onStart', function() {
        before(function() {
            $this->reportFile = __DIR__ . '/../tmp/report.lcov';
            $this->reporter = new LcovReporter($this->reportFile);

            $this->dateTime = DateTime::createFromFormat('Y-m-d H:i:s', '2014-07-01 12:00:00');

            $this->startEvent = Mockery::mock('cloak\event\StartEventInterface');
            $this->startEvent->shouldReceive('getSendAt')->andReturn( $this->dateTime );
        });
        it('output start datetime', function() {
            $output = "Start at: 1 July 2014 at 12:00\n";

            expect(function() {
                $this->reporter->onStart($this->startEvent);
            })->toPrint($output);
        });
        it('check mock object expectations', function() {
            Mockery::close();
        });
    });

    describe('onStop', function() {
        before(function() {
            $this->reportFile = __DIR__ . '/../tmp/report.lcov';
            $this->reporter = new LcovReporter($this->reportFile);

            $this->source1 = realpath(__DIR__ . '/../fixture/Example1.php');
            $this->source2 = realpath(__DIR__ . '/../fixture/Example2.php');

            $this->result = Result::from([
                $this->source1 => [
                    10 => Line::EXECUTED,
                    11 => Line::EXECUTED
                ],
                $this->source2 => [
                    10 => Line::EXECUTED,
                    15 => Line::UNUSED
                ]
            ]);

            $this->startDateTime = DateTime::createFromFormat('Y-m-d H:i:s', '2014-07-01 12:00:00');

            $this->startEvent = Mockery::mock('cloak\event\StartEventInterface');
            $this->startEvent->shouldReceive('getSendAt')->andReturn($this->startDateTime);

            $this->stopEvent = Mockery::mock('\cloak\event\StopEventInterface');
            $this->stopEvent->shouldReceive('getResult')->once()->andReturn($this->result);

            $this->reporter->onStart($this->startEvent);
            $this->reporter->onStop($this->stopEvent);

            $output  = "";
            $output .= "SF:" . $this->source1 . PHP_EOL;
            $output .= "DA:10,1" . PHP_EOL;
            $output .= "DA:11,1" . PHP_EOL;
            $output .= "end_of_record" . PHP_EOL;

            $output .= "SF:" . $this->source2 . PHP_EOL;
            $output .= "DA:10,1" . PHP_EOL;
            $output .= "end_of_record" . PHP_EOL;

            $this->output = $output;
        });
        after(function() {
            unlink($this->reportFile);
        });
        it('should output lcov report file', function() {
            $result = file_get_contents($this->reportFile);
            expect($result)->toEqual($this->output);
        });
        it('check mock object expectations', function() {
            Mockery::close();
        });
    });

});
