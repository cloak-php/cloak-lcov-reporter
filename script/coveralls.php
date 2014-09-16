<?php

namespace cloak\reporter;

require_once __DIR__ . '/../vendor/autoload.php';

use coverallskit\Configuration;
use coverallskit\ReportBuilder;

$configuration = Configuration::loadFromFile('.coveralls.yml');
$builder = ReportBuilder::fromConfiguration($configuration);
$builder->build()->save()->upload();
