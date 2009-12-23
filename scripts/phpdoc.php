<?php
/**
 * Include the configuration file
 */
require_once dirname(__FILE__).'/../inc/config.php';

$files = array(
	BASE_PATH.'/UpsAPI.php',
); // end $files

$directories = array(
	BASE_PATH.'/UpsAPI'
);

exec('php '.BASE_PATH.'/inc/pear/PhpDocumentor/phpdoc -f '.implode(',', $files).' -d '.implode(',', $directories).' -o HTML:frames:earthli -t '.BASE_PATH.'/doc/phpdoc', $result);
print_r($result);
