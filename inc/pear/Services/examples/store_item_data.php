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
$request = $workxpress->loadRequest('StoreItemData');


/** build the request **/
// add items to the request
$item_array = array(
	'id'     => 1,
	'fields' => array(
		array(
			'id' => 3100,
			'action' => Services_WorkXpress::FIELD_ACTION_SAVE,
			'value'  => 'John',
		),
		array(
			'id' => 3101,
			'action' => Services_WorkXpress::FIELD_ACTION_SAVE,
			'value'  => 'Doe',
		),
	),
	'relations' => array(
		array(
			'action'           => Services_WorkXpress::RELATION_ACTION_RECYCLE,
			'relation_type_id' => 134,
			'id'               => 4,
		),
	),
);
$request->addItem($item_array);
$item_array = array(
	'id'     => 2,
	'fields' => array(
		array(
			'id' => 102,
			'action' => Services_WorkXpress::FIELD_ACTION_CLEAR,
		),
	),
	'relations' => array(
		array(
			'relation_type_id'     => 134,
			'item_type_id'         => 3,
			'related_item_side'    => 'target',
			'related_item_type_id' => 37,
			'related_item_id'      => 4,
		),
	),
);
$request->addItem($item_array);


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
