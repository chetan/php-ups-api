<?php

require_once "Services/WorkXpress.php";

$services_workxpress_config = array(
	'api_version' => 1,
	
	Services_WorkXpress::APPLICATION_ROLE_OCD => array(
		'auth_key'   => '',
		'remote_host' => '',
	),
	
	Services_WorkXpress::APPLICATION_ROLE_QA => array(
		'auth_key'   => '',
		'remote_host' => '',
	),
	
	Services_WorkXpress::APPLICATION_ROLE_TRAINING => array(
		'auth_key'   => '',
		'remote_host' => '',
	),
	
	Services_WorkXpress::APPLICATION_ROLE_PROD => array(
		'auth_key'   => '',
		'remote_host' => '',
	),
);

?>
