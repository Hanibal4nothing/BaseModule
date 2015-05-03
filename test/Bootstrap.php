<?php
/**
 * Bootstrap for unitTests
 *
 * @copyright Copyright (c) 2015 Hanibal
 * @author    Felix Buchheim -> hanibal4nothing@gmail.com
 * @author    Hanibal
 * @version   $Id: $
 */

namespace BaseModuleTest;

ini_set('error_reporting', E_ALL);
$aFiles = [__DIR__.'/../vendor/autoload.php', __DIR__.'/../../../autoload.php'];
foreach ($aFiles as $sFile) {
    if (file_exists($sFile)) {
        $oLoader = require $sFile;
        break;
    }
}
if (false ===  isset($oLoader)) {
    throw new \RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}
/* @var $oLoader \Composer\Autoload\ClassLoader */
$oLoader->add('BaseModule\\', __DIR__.'/../src/');
$oLoader->add('BaseModuleTest\\', __DIR__);