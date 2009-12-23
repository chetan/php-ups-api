<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once 'config.php';

/** initialize and configure Services_WorkXpress objects **/
// get the configuration settings
$app_role    = Services_WorkXpress::APPLICATION_ROLE_OCD;
$api_version = $services_workxpress_config['api_version'];
$auth_key    = $services_workxpress_config[$app_role]['auth_key'];
$remote_host = $services_workxpress_config[$app_role]['remote_host'];

// load the Services_WorkXpress object
$workxpress  = new Services_WorkXpress();
$workxpress->setAPIVersion($services_workxpress_config['api_version']);
$workxpress->setAuthKey($services_workxpress_config[$app_role]['auth_key']);
$workxpress->setRemoteHost($services_workxpress_config[$app_role]['remote_host']);

// load the request object
$request = $workxpress->loadRequest('StoreItemData',
	Services_WorkXpress::REQUEST_TYPE_MASS);


/** build the request **/
// add items to the request
$request->addItem(1);
$request->addItem(2);

// add fields to the request
$request->addField(3100, Services_WorkXpress::FIELD_ACTION_SAVE, 'John');
$request->addField(3101, Services_WorkXpress::FIELD_ACTION_SAVE, 'Doe');
$request->addField(102, Services_WorkXpress::FIELD_ACTION_CLEAR);
$request->addRelation(134, 3, 'target', 37, 4);


/** make the API call **/
try
{
	// make the call and get the data array
	$response = $request->call();
	$items    = $response->getDataArray();
	
	// show the results
	echo '<pre>'.print_r($items, true).'</pre>';
} // end try
catch (Services_WorkXpress_Exception $e)
{
	echo '<h1>Error</h1><pre>'.$e->getMessage().'</pre>';
} // end catch Services_WorkXpress_Exception

?>
