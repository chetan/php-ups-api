<?php
/** set the developer and access keys **/
$GLOBALS['ups_api']['access_key'] = '';
$GLOBALS['ups_api']['developer_key'] = '';
$GLOBALS['ups_api']['server'] = '';


/** set the username and password used to connect to UPS **/
$GLOBALS['ups_api']['username'] = '';
$GLOBALS['ups_api']['password'] = '';


/** misc. setup options **/
define('BASE_PATH', dirname(dirname(__FILE__)));
error_reporting(E_ALL);


/** set the pear include path **/
$include_path = ini_get('include_path').':'.BASE_PATH.'/inc/pear';

// check if this is a windows server 
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
{
	$include_path = str_replace(':', ';', $include_path);
} // end if this is a windows server
ini_set('include_path', $include_path);


/** require misc. files **/
require_once 'autoload.php';
?>