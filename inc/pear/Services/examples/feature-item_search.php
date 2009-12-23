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
$request = $workxpress->loadRequest('LookupData',
	Services_WorkXpress::REQUEST_TYPE_MASS);


/** build the request **/
/** FEATURE: item search **/
// add items to the request
/*
 * search for items of item type 3 (users)
 * join the search parameters with a logical and
 * where field 3100 (first name) is "John"
 * and field 3101 (last name) is "Doe"
 */
$item_lookup =
	'<search>'.
		'<item_type>3</item_type>'.
//		'<param_group>'.
//			'<join>and</join>'.
//			'<field>'.
//				'<id>3100</id>'.
//				'<operator>2</operator>'.
//				'<input>John</input>'.
//			'</field>'.
//			'<field>'.
//				'<id>3101</id>'.
//				'<operator>2</operator>'.
//				'<input>Doe</input>'.
//			'</field>'.
//		'</param_group>'.
	'</search>';
$request->addItemSearch($item_lookup);

// add fields to the request
$request->addField(3100);
$request->addField(3101);
$request->addField(102);


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
