<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Base class for all responses
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
 * Base class for all responses
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Response
{
	/**
	 * response XML
	 */
	protected $xml;
	
	/**
	 * array of warnings from the response
	 */
	protected $warnings;
	
	/**
	 * Constructor
	 *
	 * @param string $xml response xml
	 */
	public function __construct($xml)
	{
		// store the XML
		$this->xml = $xml;
		
		// get the data
		$data = $this->unserializeResponse();
		
		// if the call was not successful, throw an exception
		if ($data['call_status']['status'] != 'success')
		{
			$error_msg = '';
			foreach ($data['errors']['error'] as $error)
			{
				$error_msg .= $error['message'] . "\n";
			}
			
			$error_msg .= "\n".'Response XML: ' . $this->xml;
			$error_msg  = htmlentities($error_msg);
			
			throw new Services_WorkXpress_API_Exception($error_msg);
		} // end if the call was not successful
		
		// check if the call has warnings
		if (!empty($data['warnings']))
		{
			$this->warnings = $data['warnings']['warning'];
		} // end if the call has warnings
	} // end function __construct()
	
	/**
	 * Gets the string representation of the response
	 *
	 * @return string XML that was returned from the WorkXpress API
	 */
	public function __toString()
	{
		return $this->xml;
	} // end function __toString()
	
	/**
	 * Unserializes the response XML using XML_Unserializer
	 */
	protected function unserializeResponse()
	{
		$dom = @DOMDocument::loadXML($this->xml);
		$this->xml = $dom->saveXML();
		
		// set the options for XML_Unserializer
		$options = array(
			XML_UNSERIALIZER_OPTION_ATTRIBUTES_PARSE    => true,
			XML_UNSERIALIZER_OPTION_ATTRIBUTES_ARRAYKEY => false,
			XML_UNSERIALIZER_OPTION_FORCE_ENUM          => array(
				'item',
				'field',
				'relation',
				'error',
				'warning',
			),
		);
		
		// unserialize the XML
		$unserializer = &new XML_Unserializer($options);
		$status = $unserializer->unserialize($this->xml);
		
		// check if there was an error unserializing the XML
		if (PEAR::isError($status))
		{
			$error_msg = 'Error unserializing XML: ' . $status->getMessage() . "\n";
			$error_msg .= 'Reponse XML: ' . htmlentities($this->xml);
			throw new Services_WorkXpress_Exception($error_msg);
		}
		
		// get the data
		$data = $unserializer->getUnserializedData();
		
		return $data;
	} // end function unserializeResponse()
	
	/**
	 * Gets the response data in the form of an associative array
	 *
	 * @param  int   $format format of the response data array
	 * @return array response data array indexed based on the requested format
	 */
	public function getDataArray($format = Services_WorkXpress::DATA_ARRAY_FORMAT_FULLY_COLLAPSED)
	{
		return $this->processData($format);
	} // end function getDataArray()
	
	/**
	 * Gets an array of warnings
	 * 
	 * @return array warnings from teh response
	 */
	public function getWarnings()
	{
		return $this->warnings;
	} // end function getWarnings()
	
	/**
	 * Gets the raw response XML as a string
	 *
	 * @return string response XML
	 */
	public function getXML()
	{
		return $this->xml;
	} // end function getXML()
} // end class Services_WorkXpress_Response

?>
