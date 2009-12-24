<?php

$base = dirname(dirname(__FILE__));

$files = array(
    "$base/UpsAPI.php",
); // end $files

$directories = array(
	"$base/UpsAPI"
);

exec('phpdoc -f '.implode(',', $files).' -d '.implode(',', $directories).' -o HTML:frames:earthli -t  '.$base.'/doc/phpdoc', $result);
print_r($result);
