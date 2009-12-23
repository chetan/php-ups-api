<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Request class for the ExecuteBusinessRulesMass API function (mass format)
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
 * @author Doug Warner <dwarner@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link PACKAGE_URL
 */

/**
 * Request class for the ExecuteBusinessRulesMass API function (mass format)
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Doug Warner <dwarner@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @example execute_business_rules-mass.php Execute Business Rules Mass Example
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Request_ExecuteBusinessRulesMass extends Services_WorkXpress_Request
{
	/**
	 * items element in the DOM document
	 */
	protected $items;
	
	/**
	 * rules element in the DOM document
	 */
	protected $rules;
	
	/**
	 * Constructor
	 *
	 * @param array $props WorkXpress API properties
	 */
	public function __construct($props)
	{
		// let the base class handle most of the setup
		parent::__construct($props);
		
		// create an items element in the DOM document
		$this->items = $this->dom->createElement('items');
		$this->request->appendChild($this->items);
		
		// create a rules element in the DOM document
		$this->rules = $this->dom->createElement('rules');
		$this->request->appendChild($this->rules);
	} // end function __construct()
	
	/**
	 * Adds an item to the request
	 *
	 * @param int $id item id
	 * @param int $item_type_id item type id
	 */
	public function addItem($id, $item_type_id)
	{
		// create the item element
		$item = $this->dom->createElement('item');
		$this->items->appendChild($item);
		
		// set the id
		$item->setAttribute('id', $id);
		
		// set the item type id
		$item->setAttribute('item_type_id', $item_type_id);
		
		return true;
	} // end function addItem()
	
	/**
	 * Adds an item search to the request
	 * 
	 * The search may contain rules to execute
	 * 
	 * @param string $item_lookup item lookup
	 */
	public function addItemSearch($item_lookup)
	{
		// create the search element
		$search = $this->dom->createElement('search');
		$this->items->appendChild($search);
		
		// set the item lookup
		$search->setAttribute('item_lookup', $item_lookup);
		
		return true;
	} // end function addItemSearch()
	
	/**
	 * sets whether we want to log debugging information about business rule
	 * execution
	 * 
	 * @param boolean $log_debug
	 */
	public function setLogDebug($log_debug = false)
	{
		$this->rules->setAttribute('log_debug', ($log_debug ? '1' : '0'));
	} // end function setLogDebug()
	
	/**
	 * Adds a rule to the request
	 *
	 * @param integer $id rule id
	 */
	public function addRule($id)
	{
		// create the rule element
		$rule_element = $this->dom->createElement('rule');
		$this->rules->appendChild($rule_element);
		
		// set the type
		$rule_element->setAttribute('type', Services_WorkXpress::RULE_TYPE_RULE);
		
		// set the id
		$rule_element->setAttribute('id', $id);
		
		return true;
	} // end function addRule()
	
	/**
	 * Adds an execution point to the request
	 *
	 * @param string $point execution point name
	 */
	public function addExecutionPoint($point)
	{
		// create the rule element
		$rule_element = $this->dom->createElement('rule');
		$this->rules->appendChild($rule_element);
		
		// set the type
		$rule_element->setAttribute('type', Services_WorkXpress::RULE_TYPE_EXECUTION_POINT);
		
		// set the point
		$rule_element->setAttribute('point', $point);
		
		return true;
	} // end function addExecutionPoint()
} // end class Services_WorkXpress_ExecuteBusinessRulesMass

?>
