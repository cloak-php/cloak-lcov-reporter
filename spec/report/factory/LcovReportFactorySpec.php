<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\report\factory\LcovReportFactory;
use cloak\Result;

describe('LcovReportFactory', function() {
    describe('#createFromResult', function() {
        before(function() {
            $this->result = Result::from([]);
            $this->reportFactory = new LcovReportFactory();
            $this->report = $this->reportFactory->createFromResult($this->result);
        });
        it('return LcovReport instance', function() {
            expect($this->report)->toBeAnInstanceOf('cloak\report\LcovReport');
        });
    });
});
