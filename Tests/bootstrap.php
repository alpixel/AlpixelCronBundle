<?php

$_SERVER['env'] = 'test';
use Doctrine\Common\Annotations\AnnotationRegistry;

if (!is_file($loaderFile = __DIR__.'/../vendor/autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

$loader = require $loaderFile;

AnnotationRegistry::registerLoader([$loader, 'loadClass']);
