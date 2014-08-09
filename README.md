cloak-lcov-reporter
===================

**cloak-lcov-reporter** is lcov reporter for [cloak](https://github.com/holyshared/cloak).  
Please refer to the ww for [lcov](http://ltp.sourceforge.net/coverage/lcov.php) or [FILES section](http://ltp.sourceforge.net/coverage/lcov/geninfo.1.php). 

[![Build Status](https://travis-ci.org/holyshared/cloak-lcov-reporter.svg?branch=master)](https://travis-ci.org/holyshared/cloak-lcov-reporter)
[![Coverage Status](https://coveralls.io/repos/holyshared/cloak-lcov-reporter/badge.png?branch=master)](https://coveralls.io/r/holyshared/cloak-lcov-reporter?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/holyshared/cloak-lcov-reporter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/holyshared/cloak-lcov-reporter/?branch=master)
[![Stories in Ready](https://badge.waffle.io/holyshared/cloak-lcov-reporter.png?label=ready&title=Ready)](https://waffle.io/holyshared/cloak-lcov-reporter)
[![Dependencies Status](https://depending.in/holyshared/cloak-lcov-reporter.png)](http://depending.in/holyshared/cloak-lcov-reporter)


Installation
------------------------------------------------

### Composer setting

Cloak can be installed using [Composer](https://getcomposer.org/).  
Please add a description to the **composer.json** in the configuration file.

	{
		"require-dev": {
			"cloak/cloak": "1.3.1",
			"cloak/lcov-reporter": "1.0.0"
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

	namespace Example;

	require_once __DIR__ . "/../vendor/autoload.php";
	require_once __DIR__ . "/src/functions.php";

	use cloak\Analyzer;
	use cloak\ConfigurationBuilder;
	use cloak\Result\File;

	use Example as example;

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
