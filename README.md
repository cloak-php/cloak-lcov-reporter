cloak-lcov-reporter
===================

**cloak-lcov-reporter** is lcov reporter for [cloak](https://github.com/cloak-php/cloak).  
Please refer to the [FILES section](http://ltp.sourceforge.net/coverage/lcov/geninfo.1.php) for [lcov](http://ltp.sourceforge.net/coverage/lcov.php).

[![Build Status](https://travis-ci.org/cloak-php/cloak-lcov-reporter.svg?branch=master)](https://travis-ci.org/cloak-php/cloak-lcov-reporter)
[![Coverage Status](https://coveralls.io/repos/cloak-php/cloak-lcov-reporter/badge.png?branch=master)](https://coveralls.io/r/cloak-php/cloak-lcov-reporter?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cloak-php/cloak-lcov-reporter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cloak-php/cloak-lcov-reporter/?branch=master)
[![Stories in Ready](https://badge.waffle.io/cloak-php/cloak-lcov-reporter.png?label=ready&title=Ready)](https://waffle.io/cloak-php/cloak-lcov-reporter)


Installation
------------------------------------------------

### Composer setting

Cloak can be installed using [Composer](https://getcomposer.org/).  
Please add a description to the **composer.json** in the configuration file.

	{
		"require-dev": {
	        "cloak/cloak": "1.3.2.1"
			"cloak/lcov-reporter": "1.1.1"
		}
	}

### Install

Please execute **composer install** command.

	composer install


How to use
------------------------------------------------

### Setup for the report of code coverage

Setup is required to take a code coverage.  
Run the **configure** method to be set up.

	<?php

	$analyzer = Analyzer::factory(function(ConfigurationBuilder $builder) {

		$builder->reporter(new LcovReporter(__DIR__ . '/report.lcov'));

    	$builder->includeFile(function(File $file) {
        	return $file->matchPath('/src');
	    })->excludeFile(function(File $file) {
    	    return $file->matchPath('/spec') || $file->matchPath('/vendor');
	    });

	});


Example
------------------------------------------------

You can try with the following command.

	vendor/bin/phake example:basic
