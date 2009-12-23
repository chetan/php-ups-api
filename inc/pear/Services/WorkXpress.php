<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Package to access the WorkXpress API
 *
 * PHP version 5
 *
 * <LICENSE>
 * Copyright (c) 2005-2006, Express Dynamics
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * - Redistributions of source code must retain the above copyright notice,
 *   this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * - Neither the name Express Dynamics nor the names of its contributors may be
 *   used to endorse or promote products derived from this software without
 *   specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * </LICENSE>
 * 
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link PACKAGE_URL
 *
 * @todo Set link tags in all files
 */

/**
 * uses exceptions that extend PEAR_Exception
 */
require_once 'PEAR/Exception.php';

/**
 * uses XML_Unserializer to parse the response XML
 */
require_once 'XML/Unserializer.php';

/**
 * directory where Services_WorkXpress is installed
 */
define('SERVICES_WORKXPRESS_BASE_DIR', dirname(__FILE__));

/**
 * Services_WorkXpress exception classes
 */
require_once SERVICES_WORKXPRESS_BASE_DIR.'/WorkXpress/Exception.php';

/**
 * Response base class
 */
require_once SERVICES_WORKXPRESS_BASE_DIR.'/WorkXpress/Response.php';

/**
 * Request base class
 */
require_once SERVICES_WORKXPRESS_BASE_DIR.'/WorkXpress/Request.php';

/**
 * Package to access the WorkXpress API
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link PACKAGE_URL
 * @example feature-data_array_format.php How to use the DATA_ARRAY_FORMAT constants 
 * @example feature-field_format.php How to use the FIELD_FORMAT constants
 */
class Services_WorkXpress
{
	/**
	 * the API is running on the Open Community Development environment
	 */
	const APPLICATION_ROLE_OCD = 'ocd';
	
	/**
	 * the API is running on a Quality Assurance environment
	 */
	const APPLICATION_ROLE_QA = 'qa';
	
	/**
	 * the API is running on a Training environment
	 */
	const APPLICATION_ROLE_TRAINING = 'training';
	
	/**
	 * the API is running on a Production environment
	 */
	const APPLICATION_ROLE_PROD = 'prod';
	
	/**
	 * standard request with data nested under items
	 */
	const REQUEST_TYPE_STANDARD = 1;
	
	/**
	 * mass request with data separated from items
	 */
	const REQUEST_TYPE_MASS = 2;
	
	/**
	 * fully collapsed response data array
	 */
	const DATA_ARRAY_FORMAT_FULLY_COLLAPSED = 1;
	
	/**
	 * partially collapsed response data array
	 */
	const DATA_ARRAY_FORMAT_PARTIALLY_COLLAPSED = 2;
	
	/**
	 * uncollapsed response data array
	 */
	const DATA_ARRAY_FORMAT_NOT_COLLAPSED = 3;
	
	/**
	 * stored value format
	 */
	const FIELD_FORMAT_STORED_VALUE = 'stored_value';
	
	/**
	 * displayed value format
	 */
	const FIELD_FORMAT_DISPLAYED_VALUE = 'displayed_value';
	
	/**
	 * text-only format
	 */
	const FIELD_FORMAT_TEXT_ONLY = 'text_only';
	
	/**
	 * save a field
	 */
	const FIELD_ACTION_SAVE = 'save';
	
	/**
	 * clear a field
	 */
	const FIELD_ACTION_CLEAR = 'clear';
	
	/**
	 * create a new relation
	 */
	const RELATION_ACTION_CREATE = 'create';
	
	/**
	 * recycle an existing relation
	 */
	const RELATION_ACTION_RECYCLE = 'recycle';
	
	/**
	 * delete an existing relation
	 */
	const RELATION_ACTION_DELETE = 'delete';
	
	/**
	 * rule type rule
	 */
	const RULE_TYPE_RULE = 'rule';
	
	/**
	 * rule type execution point
	 */
	const RULE_TYPE_EXECUTION_POINT = 'execpoint';
	
	/**
	 * pre item add rule execution point
	 */
	const RULE_EXECUTION_POINT_PRE_ITEM_ADD = 'PRE_ITEM_ADD';
	
	/**
	 * pre item update rule execution point
	 */
	const RULE_EXECUTION_POINT_PRE_ITEM_UPDATE = 'PRE_ITEM_UPDATE';
	
	/**
	 * pre item recycle rule execution point
	 */
	const RULE_EXECUTION_POINT_PRE_ITEM_RECYCLE = 'PRE_ITEM_RECYCLE';
	
	/**
	 * post item add rule execution point
	 */
	const RULE_EXECUTION_POINT_POST_ITEM_ADD = 'POST_ITEM_ADD';
	
	/**
	 * post item update rule execution point
	 */
	const RULE_EXECUTION_POINT_POST_ITEM_UPDATE = 'POST_ITEM_UPDATE';
	
	/**
	 * post item recycle rule execution point
	 */
	const RULE_EXECUTION_POINT_POST_ITEM_RECYCLE = 'POST_ITEM_RECYCLE';
	
	/**
	 * post item update on non-relation rule execution point
	 */
	const RULE_EXECUTION_POINT_POST_ITEM_UPDATE_NONRELATION = 'POST_ITEM_UPDATE_NONRELATION';
	
	/**
	 * post item update run-once rule execution point
	 */
	const RULE_EXECUTION_POINT_POST_ITEM_UPDATE_RUNONCE = 'POST_ITEM_UPDATE_RUNONCE';
	
	/**
	 * pre item clone rule execution point
	 */
	const RULE_EXECUTION_POINT_PRE_ITEM_CLONE = 'PRE_ITEM_CLONE';
	
	/**
	 * post item clone rule execution point
	 */
	const RULE_EXECUTION_POINT_POST_ITEM_CLONE = 'POST_ITEM_CLONE';
	
	/**
	 * pre temp-item add rule execution point
	 */
	const RULE_EXECUTION_POINT_PRE_TEMP_ITEM_ADD = 'PRE_TEMP_ITEM_ADD';
	
	/**
	 * post temp-item add rule execution point
	 */
	const RULE_EXECUTION_POINT_POST_TEMP_ITEM_ADD = 'POST_TEMP_ITEM_ADD';
	
	/**
	 * WorkXpress API properties
	 */
	private $_props;
	
	/**
	 * Gets the API version
	 *
	 * @return int API version
	 */
	public function getAPIVersion()
	{
		return $this->_props['api_version'];
	} // end function getAPIVersion()
	
	/**
	 * Gets the auth key
	 *
	 * @return string auth key
	 */
	public function getAuthKey()
	{
		return $this->_props['auth_key'];
	} // end function getAuthKey()
	
	/**
	 * Gets the remote host
	 *
	 * @return string remote host
	 */
	public function getRemoteHost()
	{
		return $this->_props['remote_host'];
	} // end function getRemoteHost()
	
	/**
	 * Sets the API version
	 *
	 * @param int $api_version API version
	 */
	public function setAPIVersion($api_version)
	{
		// validate passed in api version
		if (empty($api_version))
		{
			throw new Services_WorkXpress_Exception('API Version required');
		} // end if no api version
		$this->_props['api_version'] = $api_version;
	} // end function setAPIVersion()
	
	/**
	 * Sets the auth key
	 *
	 * @param string $auth_key auth key
	 */
	public function setAuthKey($auth_key)
	{
		// validate passed in auth key
		if (empty($auth_key))
		{
			throw new Services_WorkXpress_Exception('Auth Key required');
		} // end if no auth key
		$this->_props['auth_key'] = $auth_key;
	} // end function setAuthKey()
	
	/**
	 * Sets the remote host
	 *
	 * @param string $remote_host remote host
	 */
	public function setRemoteHost($remote_host)
	{
		// validate passed in remote host
		if (empty($remote_host))
		{
			throw new Services_WorkXpress_Exception('Remote host required');
		} // end if no remote host
		$this->_props['remote_host'] = $remote_host;
	} // end function setRemoteHost()
	
	/**
	 * Loads a request class
	 *
	 * @param  string $request_name WorkXpress API function to load the request for
	 * @param  int    $request_type which type of request to generate for the API call
	 *                the requests can create standard or mass XML requests
	 * @return object request class
	 */
	public function loadRequest($request_name, $request_type = self::REQUEST_TYPE_STANDARD)
	{
		$orig_name = $request_name;
		
		// adjust the name if we're loading a mass request object
		if ($request_type == self::REQUEST_TYPE_MASS)
		{
			$request_name .= 'Mass';
		}
		
		// load the request and response class files
		$class_name = 'Services_WorkXpress_Request_'.$request_name;
		require_once SERVICES_WORKXPRESS_BASE_DIR.'/WorkXpress/Request/'.$request_name.'.php';
		require_once SERVICES_WORKXPRESS_BASE_DIR.'/WorkXpress/Response/'.$orig_name.'.php';
		
		// check if the class exists
		if (!class_exists($class_name))
		{
			throw new Services_WorkXpress_API_Exception('API-Call \''.$orig_name.'\' could not be found, please check the spelling.');   
		}
		
		// load the request
		$request = new $class_name($this->_props);
		
		return $request;
	} // end function loadRequest()
	
	/**
	 * Gets the item id from an item string containing the item type id
	 * and the item id
	 *
	 * @param  string $item item
	 * @return int    item id
	 */
	public function getItemIDFromItem($item)
	{
		list($item_type_id, $item_id) = explode('|', $item);
		return $item_id;
	} // end function getItemIDFromItem()
	
	/**
	 * Gets the item type id from an item string containing the item type id
	 * and the item id
	 *
	 * @param  string $item item
	 * @return int    item id
	 */
	public function getItemTypeIDFromItem($item)
	{
		list($item_type_id, $item_id) = explode('|', $item);
		return $item_type_id;
	} // end function getItemTypeIDFromItem()
	
	/**
	 * Generates an item string from an item type id and item id
	 *
	 * @param  int    $item_type_id item type id
	 * @param  int    $item_id item id
	 * @return string item
	 */
	public function generateItem($item_type_id, $item_id)
	{
		$item = $item_type_id . '|' . $item_id;
		return $item;
	} // end function generateItem()
	
	/**
	 * Gets the data from an external data storage field's stored value
	 *
	 * @param  string $field_stored_value stored value for the external data storage
	 *                field
	 * @return string the external data storage field's data
	 */
	public function getDataFromExternalDataField($field_stored_value)
	{
		// make sure we have a value to work with
		if (empty($field_stored_value))
		{
			return '';
		} // end if we don't have a stored value
		
		// create the DOM document
		$dom_document = new DOMDocument();
		$value        = '';
		if (@$dom_document->loadXML($field_stored_value))
		{
			// get the data
			$xpath = new DomXPath($dom_document);
			$query = '/external_data_storage/data';
			$node  = $xpath->query($query);
			$value = $node->item(0)->nodeValue;
		} // end if we were able to properly load the XML document
		
		return $value;
	} // end getDataFromExternalDataField()
	
	/**
	 * Gets the description from an external data storage field's stored value
	 *
	 * @param  string $field_stored_value stored value for the external data storage
	 *                field
	 * @return string the external data storage field's description
	 */
	public function getDescriptionFromExternalDataField($field_stored_value)
	{
		// make sure we have a value to work with
		if (empty($field_stored_value))
		{
			return '';
		} // end if we don't have a stored value
		
		// create the DOM document
		$dom_document = new DOMDocument();
		$value        = '';
		if (@$dom_document->loadXML($field_stored_value))
		{
			// get the data
			$xpath = new DomXPath($dom_document);
			$query = '/external_data_storage/description';
			$node  = $xpath->query($query);
			$value = $node->item(0)->nodeValue;
		} // end if we were able to properly load the XML document
		
		return $value;
	} // end getDescriptionFromExternalDataField()
	
	/**
	 * Generates the stored value for an external data storage field from data
	 * and a description
	 *
	 * @param  string $data the data for the external data storage field
	 * @param  string $description the data for the external data storage field
	 * @return string stored value for the external data storage field
	 */
	public function generateExternalDataField($data, $description)
	{
		// create the DOM document
		$base_xml =
			'<?xml version="1.0" encoding="UTF-8"?>'.
			'<!DOCTYPE external_data_storage PUBLIC "-//WorkXpress//LATIN-1 Entities//EN" '.
				'"'.$this->getRemoteHost().'/external/workxpress.dtd">'.
			'<external_data_storage/>';
		
		$dom_document = DOMDocument::loadXML($base_xml);
		$root_node = $dom_document->documentElement;
		
		// create the data node
		$data_node = $dom_document->createElement('data', $data);
		$root_node->appendChild($data_node);
		
		// create the description node
		$description_node = $dom_document->createElement('description', $description);
		$root_node->appendChild($description_node);
		
		// get the stored value
		// remove the XML definition and trailing line break
		$stored_value = $dom_document->saveXML();
		
		return $stored_value;
	} // end function generateExternalDataField()
	
} // end class Services_WorkXpress

?>
