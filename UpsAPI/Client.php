<?php
/**
 * PHP API for use with UPS OnLine Tools.  This is the client class for sending
 * requests.
 * 
 * Copyright (c) 2009, Chetan Sarva
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the <organization> nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY COPYRIGHT HOLDERS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL COPYRIGHT HOLDERS AND CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * @author Chetan Sarva <chetan@pixelcop.net>
 * @package php_ups_api
 */
 
class UpsAPI_Client {
	
	/**
	 * Access key provided by UPS
	 * 
	 * @access protected
	 * @var string
	 */
	protected $access_key;
	
	/**
	 * Developer key provided by UPS
	 * 
	 * @access protected
	 * @var string
	 */
	protected $developer_key;
	
	/**
	 * UPS Server to send Request to
	 * 
	 * @access protected
	 * @var string
	 */
	protected $server;
	
	/**
	 * Username used to access UPS Systems
	 * 
	 * @access protected
	 * @var string
	 */
	protected $username;
	
	/**
	 * Password used to access UPS Systems
	 * 
	 * @access protected
	 * @var string
	 */
	protected $password;
	
	public function __construct($access_key, $developer_key, $username,
	                            $password, 
	                            $server = "https://www.ups.com") {
	                                
        $this->access_key       = $access_key;
        $this->developer_key    = $developer_key;
        $this->username         = $username;
        $this->password         = $password;
        $this->server           = $server;	    
	}
	
	protected function buildAccessRequestXml() {
	    
	    // create the access request element
		$access_dom = new DOMDocument('1.0');
		$access_element = $access_dom->appendChild(
			new DOMElement('AccessRequest'));
		$access_element->setAttributeNode(new DOMAttr('xml:lang', 'en-US'));
		
		// create the child elements
		$access_element->appendChild(
			new DOMElement('AccessLicenseNumber', $this->access_key));
		$access_element->appendChild(
			new DOMElement('UserId', $this->username));
		$access_element->appendChild(
			new DOMElement('Password', $this->password));
		
		return $access_dom->saveXML();	    
	}
	
	/**
	 * Send a request to the UPS Server using xmlrpc
	 * 
	 * @access public
	 * @param string $request_xml XML request from the child objects
	 * buildRequest() method
	 * @param bool $return_raw_xml whether or not to return the raw XML from
	 * the request
	 * 
	 * @todo remove array creation after switching over to xpath
	 */
	public function sendRequest($api, $return_raw_xml = false) {
		require_once 'XML/Unserializer.php';

		$url = $this->server . $api::ENDPOINT;
		$xml_request = $this->buildAccessRequestXml() . $api->buildRequest();
		
		// build an array of headers to use for our request
		$headers = array(
			'Method: POST',
			'Connection: Keep-Alive',
			'User-Agent: PHP-SOAP-CURL',
			'Content-Type: text/xml; charset=utf-8',
		); // end $headers
		
		// setup the curl resource
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_request);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		
		// TODO: remove array creation after switching over to xpath
		// create an array from the raw XML data
		$unserializer = new XML_Unserializer(array('returnResult' => true));
		$res_array = $unserializer->unserialize($response);
		
		// build the dom objects
		$res = new DOMDocument();
		$res->loadXML($response);
		$xpath = new DOMXPath($res);
		$root_node = $xpath->query('/'.$api->getRootNodeName())->item(0);
			
		$api->setResponse($res, $res_array, $xpath, $root_node);
		
		// check if we should return the raw XML data
		if ($return_raw_xml) {
			return $response;
		} // end if we should return the raw XML
		
		// return the response as an array
		return $res_array;
	} // end function sendRequest()
    
}
 
?>