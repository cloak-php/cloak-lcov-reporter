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
        context('when report savable', function() {
            before(function() {
                $this->directory = __DIR__ . '/../tmp/';
                $this->filePath = $this->directory . 'report.lcov';

                mkdir($this->directory);

                $this->report->saveAs($this->filePath);
            });
            after(function() {
                unlink($this->filePath);
                rmdir($this->directory);
            });
            it('save lcov report ', function() {
                expect(file_exists($this->filePath))->toBeTrue();
            });
        });
        context('when report not savable', function() {
            before(function() {
                $this->directory = __DIR__ . '/../tmp/';
                $this->filePath = $this->directory . 'not_found/not_found.lcov';
                mkdir($this->directory);
            });
            after(function() {
                rmdir($this->directory);
            });
            it('throw \cloak\report\DirectoryNotFoundException', function() {
                expect(function() {
                    $this->report->saveAs($this->filePath);
                })->toThrow('\cloak\report\DirectoryNotFoundException');
            });
        });
        context('when directory writable', function() {
            before(function() {
                $this->directory = __DIR__ . '/../tmp/';
                $this->filePath = $this->directory . 'report.lcov';

                mkdir($this->directory);
                chmod($this->directory, 0544);
            });
            after(function() {
                rmdir($this->directory);
            });
            it('throw \cloak\report\DirectoryNotWritableException', function() {
                expect(function() {
                    $this->report->saveAs($this->filePath);
                })->toThrow('\cloak\report\DirectoryNotWritableException');
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
