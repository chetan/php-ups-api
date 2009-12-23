<?php
/**
 * Get shipping time based on origin and destination address
 * 
 * Copyright (c) 2008, James I. Armes
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
 * @author James I. Armes <jamesiarmes@gmail.com>
 * @package php_ups_api
 */

/**
 * Get shipping time based on origin and destination address
 * 
 * @author James I. Armes <jamesiarmes@gmail.com>
 * @package php_ups_api
 */
class UpsAPI_TimeInTransit extends UpsAPI {
	/**
	 * Node name for the root node
	 * 
	 * @var string
	 */
	const NODE_NAME_ROOT_NODE = '';
	
	/**
	 * Request data
	 * 
	 * @access protected
	 * @param array
	 */
	protected $data;
	
	/**
	 * Destination data
	 * 
	 * @access protected
	 * @param array
	 */
	protected $destination;
	
	/**
	 * Origin data
	 * 
	 * @access protected
	 * @param array
	 */
	protected $origin;
	
	/**
	 * Constructor for the Object
	 * 
	 * @access public
	 * @param array $origin array of origin data
	 * @param array $destination array of destination data
	 * @param array $data array of request data
	 */
	public function __construct($origin, $destination, $data) {
		parent::__construct();
		
		// set object properties
		$this->server      =
			$GLOBALS['ups_api']['server'].'/ups.app/xml/TimeInTransit';
		$this->origin      = $origin;
		$this->destination = $destination;
		$this->data        = $data;
	} // end function __construct()
	
	/**
	 * Builds the XML used to make the request
	 * 
	 * If $customer_context is an array it should be in the format:
	 * $customer_context = array('Element' => 'Value');
	 * 
	 * @access public
	 * @param array|string $cutomer_context customer data
	 * @return string $return_value request XML
	 */
	public function buildRequest($customer_context = null) {
		/** create DOMDocument objects **/
		$transit_dom = new DOMDocument('1.0');
		
		
		/** create the TimeInTransitRequest element **/
		$transit_element = $transit_dom->appendChild(
			new DOMElement('TimeInTransitRequest'));
		$transit_element->setAttributeNode(new DOMAttr('xml:lang', 'en-US'));
		
		// create the child elements
		$request_element = $this->buildRequest_RequestElement(
			$transit_element, 'TimeInTransit', null, $customer_context);
		$transit_from_element = $transit_element->appendChild(
			new DOMElement('TransitFrom'));
		$transit_to_element = $transit_element->appendChild(
			new DOMElement('TransitTo'));
		
		
		/** create the children of the TransitFrom Element **/
		// check if a city was entered
		$from_address_element = $transit_from_element->appendChild(
			new DOMElement('AddressArtifactFormat'));
		$create = (!empty($this->origin['name']))
			? $from_address_element->appendChild(new DOMElement(
				'Consignee', $this->origin['name'])) : false;
		$create = (!empty($this->origin['street_number']))
			? $from_address_element->appendChild(new DOMElement(
				'StreetNumberLow',
					$this->origin['street_number'])) : false;
		$create = (!empty($this->origin['street']))
			? $from_address_element->appendChild(new DOMElement(
				'StreetName', $this->origin['street'])) : false;
		$create = (!empty($this->origin['street_type']))
			? $from_address_element->appendChild(new DOMElement(
				'StreetType',
					$this->origin['street_type'])) : false;
		$create = (!empty($this->origin['city']))
			? $from_address_element->appendChild(new DOMElement(
				'PoliticalDivision2',
					$this->origin['city'])) : false;
		$create = (!empty($this->origin['state']))
			? $from_address_element->appendChild(new DOMElement(
				'PoliticalDivision1',
					$this->origin['state'])) : false;
		$create = (!empty($this->origin['zip_code'])) 
			? $from_address_element->appendChild(new DOMElement(
				'PostcodePrimaryLow',
					$this->origin['zip_code'])) : false;
		$create = (!empty($this->origin['country'])) 
			? $from_address_element->appendChild(new DOMElement(
				'CountryCode',
					$this->origin['country'])) : false;
		unset($create);
		
		
		/** create the children of the TransitTo Element **/
		// check if a city was entered
		$to_address_element = $transit_to_element->appendChild(
			new DOMElement('AddressArtifactFormat'));
		$create = (!empty($this->destination['name']))
			? $to_address_element->appendChild(new DOMElement(
				'Consignee', $this->destination['name'])) : false;
		$create = (!empty($this->destination['street_number']))
			? $to_address_element->appendChild(new DOMElement(
				'StreetNumberLow',
					$this->destination['street_number'])) : false;
		$create = (!empty($this->destination['street']))
			? $to_address_element->appendChild(new DOMElement(
				'StreetName', $this->destination['street'])) : false;
		$create = (!empty($this->destination['street_type']))
			? $to_address_element->appendChild(new DOMElement(
				'StreetType',
					$this->destination['street_type'])) : false;
		$create = (!empty($this->destination['city']))
			? $to_address_element->appendChild(new DOMElement(
				'PoliticalDivision2',
					$this->destination['city'])) : false;
		$create = (!empty($this->destination['state']))
			? $to_address_element->appendChild(new DOMElement(
				'PoliticalDivision1',
					$this->destination['state'])) : false;
		$create = (!empty($this->destination['zip_code'])) 
			? $to_address_element->appendChild(new DOMElement(
				'PostcodePrimaryLow',
					$this->destination['zip_code'])) : false;
		$create = (!empty($this->destination['country'])) 
			? $to_address_element->appendChild(new DOMElement(
				'CountryCode',
					$this->destination['country'])) : false;
		unset($create);
		
		
		/** create the rest of the child elements **/
		// create the PickupDate element
		$transit_element->appendChild(
			new DOMElement('PickupDate',
				$this->data['pickup_date']));
		
		// create the MaximumListSize element if a value was passd in
		if (!empty($this->data['max_list_size'])) {
			$transit_element->appendChild(
				new DOMElement('MaximumListSize',
					$this->data['max_list_size']));
		} // end if a maximum list size was set
		
		// create the InvoiceLineTotal element if a value was passed in
		if (!empty($this->data['invoice'])) {
			$invoice_element = $transit_element->appendChild(
				new DOMElement('InvoiceLineTotal'));
			
			// check if a currency code was passed in
			if (!empty($this->data['invoice']['currency_code'])) {
				$invoice_element->appendChild(
					new DOMElement('CurrencyCode',
						$this->data['invoice']['currency_code']));
			} // end if a currency code was passed in
			
			// check if a monetary value was passed in
			if (!empty($this->data['invoice']['monetary_value'])) {
				$invoice_element->appendChild(
					new DOMElement('MonetaryValue',
						$this->data['invoice']['monetary_value']));
			} // end if a monetary value was passed in
		} // end if invoice values were set
		
		// create the ShipmentWeight element if a value was passed in
		if (!empty($this->data['weight']))
		{
			$weight_element = $transit_element->appendChild(
				new DOMElement('ShipmentWeight'));
			
			// check if unit of measure data was passed in
			if (!empty($this->data['weight']['unit_of_measure'])) {
				$um_element = $weight_element->appendChild(
					new DOMElement('UnitOfMeasurement'));
			} // end if unit of measure was passed in

			// check if a unit of measure code was passed in
			if (!empty($this->data['weight']['unit_of_measure']['code'])) {
				$um_element->appendChild(
					new DOMElement('Code',
						$this->data['weight']['unit_of_measure']['code']));
			} // end if a unit of measure code was passed in
			
			// check if a monetary value was passed in
			if (!empty($this->data['weight']['unit_of_measure'])) {
				$um_element->appendChild(
					new DOMElement('Description',
						$this->data['weight']['unit_of_measure']['code']));
			} // end if a monetary value was passed in
			
			// check if a monetary value was passed in
			if (!empty($this->data['weight']['weight'])) {
				$weight_element->appendChild(
					new DOMElement('Weight',
						$this->data['weight']['weight']));
			} // end if a monetary value was passed in
		} // end if invoice values were set
		
		return parent::buildRequest().$transit_dom->saveXML();
	} // end function buildRequest()
	
	/**
	 * Returns the number of Services returned by UPS
	 * 
	 * @access public
	 * @return integer
	 */
	public function getNumberOrServices() {
		return count($this->response_array['TransitResponse']
			['ServiceSummary']);
	} // end function getNumberOrServices()
	
	/**
	 * Returns the different services that match the request
	 * 
	 * @access public
	 * @return array $return_value returned services
	 */
	public function getServices() {
		$services = $this->response_array['TransitResponse']
			['ServiceSummary'];
		$return_value = array();
		
		// check to make sure we have services
		if (empty($services)) {
			return $resturn_value;
		} // end if no services were returned
		
		// iterate over each of the services
		foreach ($services as $service) {
			$service_array = array(
				'code' => $service['Service']['Code'],
				'description' => $service['Service']
					['Description'],
			); // end $service
			$estimated_arival = array(
				'days' => $service['EstimatedArrival']
					['BusinessTransitDays'],
				'time' => $service['EstimatedArrival']['Time'],
				'pickup' => $service['EstimatedArrival']
					['PickupDate'],
				'date' => $service['EstimatedArrival']['Date'],
				'day' => $service['EstimatedArrival']
					['DayOfWeek'],
			); // end $estimated_arival
			$return_value[] = array(
				'service' => $service_array,
				'guaranteed' => (strtolower(
					$service['Guaranteed']['Code']) == 'y')
					? 'yes' : 'no',
				'estimated_arival' => $estimated_arival,
			); // end $return_value
		} // end for each service
		
		return $return_value;
	} // end function getServices()
	
	/**
	 * Returns the name of the servies response root node
	 * 
	 * @access protected
	 * @return string
	 * 
	 * @todo remove after phps self scope has been fixed
	 */
	protected function getRootNodeName() {
		return self::NODE_NAME_ROOT_NODE;
	} // end function getRootNodeName()
} // end class UpsAPI_TimeInTransit

?>
