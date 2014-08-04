<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\report\LcovReport;
use cloak\result\Line;
use cloak\Result;

describe('LcovReport', function() {

    before(function() {
        $this->source1 = realpath(__DIR__ . '/../fixture/Example1.php');
        $this->source2 = realpath(__DIR__ . '/../fixture/Example2.php');

        $result = Result::from([
            $this->source1 => [
                10 => Line::EXECUTED,
                11 => Line::EXECUTED
            ],
            $this->source2 => [
                10 => Line::EXECUTED,
                15 => Line::UNUSED
            ]
        ]);
        $this->report = new LcovReport($result);
    });

    describe('#saveAs', function() {
        before(function() {
            $this->directory = __DIR__ . '/../tmp/';
            mkdir($this->directory);
        });
        after(function() {
            rmdir($this->directory);
        });
        context('when report savable', function() {
            before(function() {
                $this->filePath = $this->directory . 'report.lcov';
                $this->report->saveAs($this->filePath);
            });
            it('save lcov report ', function() {
                expect(file_exists($this->filePath))->toBeTrue();
            });
        });
        //FIXME throw DirectoryNotFoundException
        //FIXME throw DirectoryNotWritableException
        context('when report not savable', function() {
            before(function() {
                $this->filePath = $this->directory . 'not_found/not_found.lcov';
            });
            it('throw \UnderflowException', function() {
                expect(function() {
                    $this->report->saveAs($this->filePath);
                })->toThrow('\UnderflowException');
            });
        });
    });

    describe('#output', function() {
        it('output lcov report', function() {
            $output  = "";
            $output .= "SF:{$this->source1}\n";
            $output .= "DA:10,1\n";
            $output .= "DA:11,1\n";
            $output .= "end_of_record\n";
            $output .= "SF:{$this->source2}\n";
            $output .= "DA:10,1\n";
            $output .= "end_of_record\n";

            expect(function() {
                $this->report->output();
            })->toPrint($output);
        });
    });

});
