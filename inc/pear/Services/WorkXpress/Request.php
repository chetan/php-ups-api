<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Base class for all requests
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
 */

/**
 * Base class for all requests
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Request
{
	/**
	 * WorkXpress API properties
	 */
	protected $props;
	
	/**
	 * request XML DOM document
	 */
	protected $dom;
	
	/**
	 * wx_request element in the DOM document
	 */
	protected $request;
	
	/**
	 * Constructor
	 *
	 * @param array $props WorkXpress API properties
	 */
	public function __construct($props)
	{
		// store the API properties
		$this->props = $props;
		
		// create the DOM document to use for the request
		$base_xml =
			'<?xml version="1.0" encoding="UTF-8"?>'.
			'<!DOCTYPE wx_request PUBLIC "-//WorkXpress//LATIN-1 Entities//EN" '.
				'"'.$props['remote_host'].'/external/workxpress.dtd">'.
			'<wx_request/>';
		
		$this->dom = DOMDocument::loadXML($base_xml);
		$this->request = $this->dom->documentElement;
	} // end function __construct()
	
	/**
	 * Gets the string representation of the request
	 *
	 * Uses the XML that would be passed to the WorkXpress API as the output.
	 *
	 * @return string XML that would be passed to the WorkXpress API
	 */
	public function __toString()
	{
		return $this->dom->saveXML();
	} // end function __toString()
	
	/**
	 * Executes the API call
	 */
	public function call()
	{
		// get the request XML from the DOM document
		$request_xml = $this->__toString();
		
		// get the API function name
		$function_name = get_class($this);
		if (substr($function_name, -4) == 'Mass')
		{
			$function_name = substr($function_name, 0, -4);
		}
		$function_name = substr($function_name, strrpos($function_name, '_') + 1);
		
		// make the API call
		try
		{
			// instantiate the SOAP client and make the API call
			$client = new SoapClient($this->props['remote_host'].'/external/api/api.wsdl', 
				array('location' => $this->props['remote_host'].'/external/api/api.php'));
			$result = $client->$function_name($this->props['api_version'], $this->props['auth_key'], $request_xml);
		} // end try
		// there was an error trying to make the API call
		catch (SoapFault $fault)
		{
			throw new Services_WorkXpress_API_Exception("SOAP Fault: (faultcode: {$fault->faultcode}; faultstring: {$fault->faultstring})");
		} // end catch SoapFault
		
		// create a response object
		$class_name = str_replace('Request', 'Response', get_class($this));
		if (substr($class_name, -4) == 'Mass')
		{
			$class_name = substr($class_name, 0, -4);
		}
		$response = new $class_name($result);
		
		return $response;
	} // end function call()
	
	/**
	 * Sets the request XML
	 *
	 * This overrides any XML that has alrady been set for the request.
	 *
	 * @param string $xml request XML
	 */
	public function setXML($xml)
	{
		$this->dom->loadXML($xml);
	} // end function setXML()
	
	/**
	 * Build the Log_Debug node, and append it to the request
	 * 
	 * @param integer $switch What do we want to set the log debug to?  On (1) or Off (0)
	 */
	public function setLogDebug($switch = 1)
	{
		// create the fields element
		$log_debug_element = $this->dom->createElement('log_debug', $switch);
		$this->request->appendChild($log_debug_element);
		
		return true;
	}
} // end class Services_WorkXpress_Request

?>
