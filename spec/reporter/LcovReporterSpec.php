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
use cloak\report\LcovReport;
use cloak\reporter\LcovReporter;
use \Mockery;
use \DateTime;

describe('LcovReporter', function() {

    describe('onStart', function() {
        before(function() {
            $this->reporter = new LcovReporter();

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
        });
        context('when the path of the file is specified', function() {
            before(function() {
                mkdir(__DIR__ . '/../tmp');

                $this->reportFile = __DIR__ . '/../tmp/report.lcov';
                $this->reporter = new LcovReporter($this->reportFile);

                $this->stopEvent = Mockery::mock('\cloak\event\StopEventInterface');
                $this->stopEvent->shouldReceive('getResult')->once()->andReturn($this->result);

                $this->reporter->onStop($this->stopEvent);
            });
            after(function() {
                unlink($this->reportFile);
                rmdir(__DIR__ . '/../tmp');
            });
            it('should save lcov report file', function() {
                expect(file_exists($this->reportFile))->toBeTrue();
            });
            it('check mock object expectations', function() {
                Mockery::close();
            });
        });
        context('when the path of the file is not specified', function() {
            before(function() {
                $this->reporter = new LcovReporter();

                $this->stopEvent = Mockery::mock('\cloak\event\StopEventInterface');
                $this->stopEvent->shouldReceive('getResult')->once()->andReturn($this->result);

                $this->lcovReport = new LcovReport($this->result);
            });
            it('should output lcov report', function() {
                expect(function() {
                    $this->reporter->onStop($this->stopEvent);
                })->toPrint((string) $this->lcovReport);
            });
            it('check mock object expectations', function() {
                Mockery::close();
            });
        });
    });

});
