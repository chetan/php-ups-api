<?php
/**
 * functions to autoload the required classes
 * 
 * @package UpsAPI
 * @subpackage Inc
 */

/**
 * defines what the search path is for files not found in the autoload map
 * 
 * @var array
 */
$GLOBALS['autoload_paths'] = array(
	BASE_PATH,
	BASE_PATH.'/inc',
	BASE_PATH.'/inc/pear',
);

/**
 * defines specific items to test
 * 
 * @var array
 */
$GLOBALS['autoload_map'] = array();
//$GLOBALS['autoload_map']['MySQLConnection']      = BASE_PATH.'/lib/common/Database.php';
//$GLOBALS['autoload_map']['IRecordSet']           = BASE_PATH.'/lib/common/Database.php';
//$GLOBALS['autoload_map']['MySQLRecordSet']       = BASE_PATH.'/lib/common/Database.php';
//$GLOBALS['autoload_map']['ArrayRecordSet']       = BASE_PATH.'/lib/common/Database.php';
//$GLOBALS['autoload_map']['Error']                = BASE_PATH.'/lib/common/Error.php';
//$GLOBALS['autoload_map']['WorkXpress_Exception'] = BASE_PATH.'/lib/common/Exception.php';
//$GLOBALS['autoload_map']['Item']                 = BASE_PATH.'/lib/wx/Item.php';
//$GLOBALS['autoload_map']['ItemType']             = BASE_PATH.'/lib/wx/ItemType.php';
//$GLOBALS['autoload_map']['FieldType']            = BASE_PATH.'/lib/wx/FieldType.php';


/**
 * function to autoload the requested class name
 * 
 * @global array $GLOBALS['autoload_paths']
 * @global array $GLOBALS['autoload_map']
 * 
 * @param string $class_name name of the class to be loaded
 * @return bool whether the class was loaded or not
 */
function __autoload($class_name)
{
	$debug        = false;
	$return_val   = false;
	$include_file = '';
	$test_paths   = array();
	
	// security checks on the class name
	if (preg_match('/(\.|http|ftp)/', $class_name))
	{
		__autoload_log_error('NLUMC_ERROR['.time().']: '.
			'__autoload() security failed on class "'.$class_name.'"');
		return $return_val;
	} // end if security checks failed
	
	// sanity checks on the class name
	if (empty($class_name))
	{
		__autoload_log_error('NLUMC_ERROR['.time().']: '.
			'__autoload() empty class name passed in');
		return $return_val;
	} // end if sanity checks failed
	
	
	/** try to find class in map first **/
	// try straight class name
	if (isset($GLOBALS['autoload_map'][$class_name]))
	{
		$include_file = $GLOBALS['autoload_map'][$class_name];
	} // end if class name
	// try lowercased class name
	else if (isset($GLOBALS['autoload_map'][strtolower($class_name)]))
	{
		$include_file = $GLOBALS['autoload_map'][strtolower($class_name)];
	} // end if lowercase class name
	
	/** try to find the file in our autoload path **/
	if (empty($include_file))
	{
		foreach ($GLOBALS['autoload_paths'] as $path)
		{
			$test_paths[] = "$path/$class_name.php";
		} // end foreach autoload path
		
		// loop through each path to try to include file
		foreach ($test_paths as $test_path)
		{
			if (file_exists($test_path))
			{
				if ($debug) echo "Using '$test_path' for '$class_name'<br />\n";
				$include_file = $test_path;
				break;
			} // end if file exists
		} // end foreach $test_paths
	} // end if no include file
	
	/** try to find the file by using the class name to derive the path **/
	if (empty($include_file))
	{
		// get the directory structure from the class name
		$test_paths[] = BASE_PATH.'/'.
			str_replace('_', '/', $class_name).'.php';
		$test_paths[] = BASE_PATH.'/inc'.
			str_replace('_', '/', $class_name).'.php';
		
		// loop through each path to try to include file
		foreach ($test_paths as $test_path)
		{
			if (file_exists($test_path))
			{
				if ($debug) echo "Using '$test_path' for '$class_name'<br />\n";
				$include_file = $test_path;
				break;
			} // end if file exists
		} // end foreach $test_paths
	} // end if no include file
	
	
	/** end if include file is set, try loading it **/
	if (!empty($include_file))
	{
		$return_val = require_once $include_file;
	} // end if include_file
	
	
	/** if no class included **/
	return $return_val;
} // end function __autoload()

/**
 * logs the error for the autoload function
 * 
 * @param string $error_message
 */
function __autoload_log_error($error_message)
{
	error_log($error_message);
	if (class_exists('Error', false))
	{
		$error = new Error();
		$error->generateError($error_message);
	} // end if we have an error class
} // end function __autoload_log_error()

?>
