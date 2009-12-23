<?php
/** set the developer and access keys **/
$GLOBALS['ups_api']['access_key'] = '';
$GLOBALS['ups_api']['developer_key'] = '';
$GLOBALS['ups_api']['server'] = '';


/** set the username and password used to connect to UPS **/
$GLOBALS['ups_api']['username'] = '';
$GLOBALS['ups_api']['password'] = '';


// TODO: raise error if any of the above are empty

/** misc. setup options **/
define('BASE_PATH', dirname(dirname(__FILE__)));


/** require misc. files **/
require_once 'autoload.php';
?>