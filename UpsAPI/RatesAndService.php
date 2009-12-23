<?php
/**
 * Handles the sending, receiving, and processing of rates and service data
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
 * Handles the sending, receiving, and processing of rates and service data
 * 
 * @author James I. Armes <jamesiarmes@gmail.com>
 * @package php_ups_api
 */
class UpsAPI_RatesAndService extends UpsAPI {
	/**
	 * Node name for the Monetary Value
	 * 
	 * @var string
	 */
	const NODE_NAME_MONETARY_VALUE = 'MonetaryValue';
	
	/**
	 * Node name for the Rated Shipment Node
	 * 
	 * @var string
	 */
	const NODE_NAME_RATED_SHIPMENT = 'RatedShipment';
	
	/**
	 * Node name for the root node
	 * 
	 * @var string
	 */
	const NODE_NAME_ROOT_NODE = 'RatingServiceSelectionResponse';
	
	/**
	 * Destination (ship to) data
	 * 
	 * Should be in the format:
	 * $destination = array(
	 * 	'name' => '',
	 * 	'attn' => '',
	 * 	'phone' => '1234567890',
	 * 	'address' => array(
	 * 		'street1' => '',
	 * 		'street2' => '',
	 * 		'city' => '',
	 * 		'state' => '**',
	 * 		'zip' => 12345,
	 * 		'country' => '',
	 * 	),
	 * );
	 * 
	 * @access protected
	 * @var array
	 */
	protected $destination = array();
	
	/**
	 * Shipment data
	 * 
	 * @access protected
	 * @var array
	 */
	protected $shipment = array();
	
	/**
	 * Ship from data
	 * 
	 * @access protected
	 * @var array
	 */
	protected $ship_from = array();
	
	/**
	 * Shipper data
	 * 
	 * @access protected
	 * @var array
	 */
	protected $shipper = array();
	
	/**
	 * Constructor for the Object
	 * 
	 * @access public
	 * @param array $shipment array of shipment data
	 * @param array $shipper array of shipper data
	 * @param array $ship_from array of ship from data
	 * @param array $desination array of destination data
	 */
	public function __construct($shipment, $shipper, $ship_from, $desination) {
		parent::__construct();
		
		// set object properties
		$this->server = $GLOBALS['ups_api']['server'].'/ups.app/xml/Rate';
		$this->shipment = $shipment;
		$this->shipper = $shipper;
		$this->ship_from = $ship_from;
		$this->destination = $desination;
	} // end function __construct()
	
	/**
	 * Returns charges for each package
	 * 
	 * @return array
	 */
	public function getPackageCharges() {
		$return_value = array();
		
		// iterate over the packages
		$packages = $this->xpath->query(self::NODE_NAME_RATED_SHIPMENT.
			'/RatedPackage', $this->root_node);
		foreach ($packages as $package) {
			$return_value[] = array(
				'currency_code' => $this->xpath->query(
					'TotalCharges/CurrencyCode',
					$package)->item(0)->nodeValue,
				'transportation' => $this->xpath->query(
					'TransportationCharges/'.self::NODE_NAME_MONETARY_VALUE,
					$package)->item(0)->nodeValue,
				'service_options' => $this->xpath->query(
					'ServiceOptionsCharges/'.self::NODE_NAME_MONETARY_VALUE,
					$package)->item(0)->nodeValue,
				'total' => $this->xpath->query(
					'TotalCharges/'.self::NODE_NAME_MONETARY_VALUE,
					$package)->item(0)->nodeValue,
			); // end $return_value
		} // end for each package
		
		return $return_value;
	} // end function getPackageCharges()
	
	/**
	 * Returns charges for each package
	 * 
	 * @return array
	 */
	public function getPackageWeight() {
		$return_value = array();
		
		// iterate over the packages
		$packages = $this->xpath->query(self::NODE_NAME_RATED_SHIPMENT.
			'/RatedPackage', $this->root_node);
		foreach ($packages as $package) {
			$return_value[] = array(
				'weight' => $this->xpath->query(
					'BillingWeight/Weight', $package)
					->item(0)->nodeValue,
				'units' => $this->xpath->query(
					'BillingWeight/UnitOfMeasurement/Code', $package)
					->item(0)->nodeValue,
			); // end $return_value
		} // end for each package
		
		return $return_value;
	} // end function getPackageCharges()
	
	/**
	 * Returns charges for the entire shipment
	 * 
	 * @return array
	 */
	public function getShipmentCharges() {
		$rated_shipment = $this->xpath->query(
			self::NODE_NAME_RATED_SHIPMENT, $this->root_node)->item(0);
		
		$return_value = array(
			'currency_code' => $this->xpath->query(
				'TotalCharges/CurrencyCode',
				$rated_shipment)->item(0)->nodeValue,
			'transportation' => $this->xpath->query(
				'TransportationCharges/'.self::NODE_NAME_MONETARY_VALUE,
				$rated_shipment)->item(0)->nodeValue,
			'service_options' => $this->xpath->query(
				'ServiceOptionsCharges/'.self::NODE_NAME_MONETARY_VALUE,
				$rated_shipment)->item(0)->nodeValue,
			'total' => $this->xpath->query(
				'TotalCharges/'.self::NODE_NAME_MONETARY_VALUE,
				$rated_shipment)->item(0)->nodeValue,
		); // end $return_value
		
		return $return_value;
	} // end function
	
	/**
	 * Returns billing weight for the entire shipment
	 * 
	 * @return array
	 */
	public function getShipmentWeight() {
		$rated_shipment = $this->xpath->query(
			self::NODE_NAME_RATED_SHIPMENT, $this->root_node)->item(0);
		
		$return_value = array(
			'weight' => $this->xpath->query(
				'BillingWeight/Weight', $rated_shipment)->item(0)->nodeValue,
			'units' => $this->xpath->query(
				'BillingWeight/UnitOfMeasurement/Code', $rated_shipment)
				->item(0)->nodeValue,
		); // end $return_value
		
		return $return_value;
	} // end function getShipmentWeight()
	
	/**
	 * Returns any warnings from the response
	 * 
	 * @return array
	 */
	public function getWarnings() {
		$warnings = $this->xpath->query(self::NODE_NAME_RATED_SHIPMENT.
			'/RatedShipmentWarning', $this->root_node);
		
		// iterate over the warnings
		$return_value = array();
		foreach ($warnings as $warning) {
			$return_value[] = $warning->nodeValue;
		} // end for each warning
		
		return $return_value;
	} // end function getWarnings()
	
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
		// create the new dom document
		$xml = new DOMDocument('1.0', 'utf-8');
		
		
		/** create the AddressValidationRequest element **/
		$rate = $xml->appendChild(
			new DOMElement('RatingServiceSelectionRequest'));
		$rate->setAttributeNode(new DOMAttr('xml:lang', 'en-US'));
		
		// create the child elements
		$requst = $this->buildRequest_RequestElement($rate,
			'Rate', 'Rate', $customer_context);
		
		
		/** build the pickup type node **/
		$pickup_type = $rate->appendChild(new DOMElement('PickupType'));
		$pickup_type->appendChild(new DOMElement('Code',
			$this->shipment['pickup_type']['code']));
		$pickup_type->appendChild(new DOMElement('Description',
			$this->shipment['pickup_type']['description']));
		
		$shipment = $rate->appendChild(new DOMElement('Shipment'));
		
		$this->buildRequest_Shipper($shipment);
		$this->buildRequest_Destination($shipment);
		$this->buildRequest_ShipFrom($shipment);
		$shipment = $this->buildRequest_Shipment($shipment);
		
		return parent::buildRequest().$xml->saveXML();
	} // end function buildRequest()
	
	/**
	 * Builds the destination elements
	 * 
	 * @access protected
	 * @param DOMElement $dom_element
	 * @return DOMElement
	 */
	protected function buildRequest_Destination(&$dom_element) {
		/** build the destination element and its children **/
		$destination = $dom_element->appendChild(new DOMElement('ShipTo'));
		$destination->appendChild(new DOMElement('CompanyName',
			$this->destination['name']));
		$destination->appendChild(new DOMElement('PhoneNumber',
			$this->destination['phone']));
		$address = $destination->appendChild(new DOMElement('Address'));
		
		
		/** build the address elements children **/
		$address->appendChild(new DOMElement('AddressLine1',
			$this->destination['street']));
		
		// check to see if there is a second steet line
		if (isset($this->destination['street2']) &&
			!empty($this->destination['street2'])) {
			$address->appendChild(new DOMElement('AddressLine2',
				$this->destination['street2']));
		} // end if there is a second street line
		
		// check to see if there is a third steet line
		if (isset($this->destination['street3']) &&
			!empty($this->destination['street3'])) {
			$address->appendChild(new DOMElement('AddressLine3',
				$this->destination['street3']));
		} // end if there is a second third line
		
		// build the rest of the address
		$address->appendChild(new DOMElement('City',
			$this->destination['city']));
		$address->appendChild(new DOMElement('StateProvinceCode',
			$this->destination['state']));
		$address->appendChild(new DOMElement('PostalCode',
			$this->destination['zip']));
		$address->appendChild(new DOMElement('CountryCode',
			$this->destination['country']));
		
		return $destination;
	} // end function buildRequest_Destination()
	
	/**
	 * Buildes the package elements
	 * 
	 * @access protected
	 * @param DOMElement $dom_element
	 * @param array $package
	 * @return DOMElement
	 * 
	 * @todo determine if the package description is needed
	 */
	protected function buildRequest_Package(&$dom_element, $package) {
		/** build the package and packaging type **/
		$package_element = $dom_element->appendChild(new DOMElement('Package'));
		$packaging_type = $package_element->appendChild(
			new DOMElement('PackagingType'));
		$packaging_type->appendChild(new DOMElement('Code',
			$package['packaging']['code']));
		$packaging_type->appendChild(new DOMElement('Description',
			$package['packaging']['description']));
		
		// TODO: determine if we need this
		$package_element->appendChild(new DOMElement('Description',
			$package['description']));
		
		
		/** build the package weight **/
		$package_weight = $package_element->appendChild(
			new DOMElement('PackageWeight'));
		$units = $package_weight->appendChild(
			new DOMElement('UnitOfMeasurement'));
		$units->appendChild(new DOMElement('Code', $package['units']));
		$package_weight->appendChild(
			new DOMElement('Weight', $package['weight']));
		
		return $package_element;
	} // end function buildRequest_Package()
	
	/**
	 * Builds the service options node
	 * 
	 * @access protected
	 * @param DOMElement $dom_element
	 * @return boolean|DOMElement
	 */
	protected function buildRequest_ServiceOptions(&$dom_element) {
		// build our elements
		$service_options = $dom_element->appendChild(
			new DOMElement('ShipmentServiceOptions'));
		$on_call_air = $service_options->appendChild(
			new DOMElement('OnCallAir'));
		$schedule = $on_call_air->appendChild(new DOMElement('Schedule'));
		
		// check to see if this is a satruday pickup
		if (isset($this->shipment['saturday']['pickup']) &&
			$this->shipment['saturday']['pickup'] !== false) {
			$service_options->appendChild(new DOMElement('SaturdayPickup'));
		} // end if this is a saturday pickup
		
		// check to see if this is a saturday delivery
		if (isset($this->shipment['saturday']['delivery']) &&
			$this->shipment['saturday']['delivery'] !== false) {
			$service_options->appendChild(new DOMElement('SaturdayDelivery'));
		} // end if this is a saturday delivery
		
		// check to see if we have a pickup day
		if (isset($this->shipment['pickup_day'])) {
			$schedule->appendChild(new DOMElement('PickupDay',
				$this->shipment['pickup_day']));
		} // end if we have a pickup day
		
		// check to see if we have a scheduling method
		if (isset($this->shipment['scheduling_method'])) {
			$schedule->appendChild(new DOMElement('Method',
				$this->shipment['scheduling_method']));
		} // end if we have a scheduling method
		
		// check to see if we have on call air options
		if (!$schedule->hasChildNodes()) {
			$service_options->removeChild($on_call_air);
		} // end if we have on call air options
		
		// check to see if we have service options
		if (!$service_options->hasChildNodes()) {
			$dom_element->removeChild($service_options);
			return false;
		} // end if we do not have service options
		
		return $service_options;
	} // end function buildRequest_ServiceOptions()
	
	/**
	 * Builds the ship from elements
	 * 
	 * @access protected
	 * @param DOMElement $dom_element
	 * @return DOMElement
	 */
	protected function buildRequest_ShipFrom(&$dom_element) {
		/** build the destination element and its children **/
		$ship_from = $dom_element->appendChild(new DOMElement('ShipFrom'));
		$ship_from->appendChild(new DOMElement('CompanyName',
			$this->ship_from['name']));
		$ship_from->appendChild(new DOMElement('PhoneNumber',
			$this->ship_from['phone']));
		$address = $ship_from->appendChild(new DOMElement('Address'));
		
		
		/** build the address elements children **/
		$address->appendChild(new DOMElement('AddressLine1',
			$this->ship_from['street']));
		
		// check to see if there is a second steet line
		if (isset($this->ship_from['street2']) &&
			!empty($this->ship_from['street2'])) {
			$address->appendChild(new DOMElement('AddressLine2',
				$this->ship_from['street2']));
		} // end if there is a second street line
		
		// check to see if there is a third steet line
		if (isset($this->ship_from['street3']) &&
			!empty($this->ship_from['street3'])) {
			$address->appendChild(new DOMElement('AddressLine3',
				$this->ship_from['street3']));
		} // end if there is a second third line
		
		// build the rest of the address
		$address->appendChild(new DOMElement('City',
			$this->ship_from['city']));
		$address->appendChild(new DOMElement('StateProvinceCode',
			$this->ship_from['state']));
		$address->appendChild(new DOMElement('PostalCode',
			$this->ship_from['zip']));
		$address->appendChild(new DOMElement('CountryCode',
			$this->ship_from['country']));
		
		return $ship_from;
	} // end function buildRequest_ShipFrom()
	
	/**
	 * Builds the shipment elements
	 * 
	 * @access protected
	 * @param DOMElement $dom_element
	 * @return DOMElement
	 */
	protected function buildRequest_Shipment(&$shipment) {
		
		/** build the shipment node **/
		$service = $shipment->appendChild(new DOMElement('Service'));
		$service->appendChild(new DOMElement('Code',
			$this->shipment['service']));
		
		// iterate over the pacakges to create the package element
		foreach ($this->shipment['packages'] as $package) {
			$this->buildRequest_Package($shipment, $package);
		} // end for each package
		
		$this->buildRequest_ServiceOptions($shipment);
		
		return $shipment;
	} // end function buildRequest_Shipment()
	
	/**
	 * Builds the shipper elements
	 * 
	 * @access protected
	 * @param DOMElement $dom_element
	 * @return DOMElement
	 */
	protected function buildRequest_Shipper(&$dom_element) {
		/** build the destination element and its children **/
		$shipper = $dom_element->appendChild(new DOMElement('Shipper'));
		$shipper->appendChild(new DOMElement('Name',
			$this->shipper['name']));
		$shipper->appendChild(new DOMElement('PhoneNumber',
			$this->shipper['phone']));
		
		// check to see if we have a shipper number
		if (isset($this->shipper['number']) &&
			!empty($this->shipper['number'])) {
			$shipper->appendChild(new DOMElement('ShipperNumber',
				$this->shipper['number']));
		} // end if we have a shipper number
		
		$address = $shipper->appendChild(new DOMElement('Address'));
		
		
		/** build the address elements children **/
		$address->appendChild(new DOMElement('AddressLine1',
			$this->shipper['street']));
		
		// check to see if there is a second steet line
		if (isset($this->shipper['street2']) &&
			!empty($this->shipper['street2'])) {
			$address->appendChild(new DOMElement('AddressLine2',
				$this->shipper['street2']));
		} // end if there is a second street line
		
		// check to see if there is a third steet line
		if (isset($this->shipper['street3']) &&
			!empty($this->shipper['street3'])) {
			$address->appendChild(new DOMElement('AddressLine3',
				$this->shipper['street3']));
		} // end if there is a second third line
		
		// build the rest of the address
		$address->appendChild(new DOMElement('City',
			$this->shipper['city']));
		$address->appendChild(new DOMElement('StateProvinceCode',
			$this->shipper['state']));
		$address->appendChild(new DOMElement('PostalCode',
			$this->shipper['zip']));
		$address->appendChild(new DOMElement('CountryCode',
			$this->shipper['country']));
		
		return $shipper;
	} // end function buildRequest_Shipper()
	
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
} // end class UpsAPI_RatesAndService

?>
