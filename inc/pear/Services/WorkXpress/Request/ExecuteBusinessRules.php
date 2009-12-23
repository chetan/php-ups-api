<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Request class for the ExecuteBusinessRules API function
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
 * Request class for the ExecuteBusinessRules API function
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Doug Warner <dwarner@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @example execute_business_rules.php Execute Business Rules Example
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Request_ExecuteBusinessRules extends Services_WorkXpress_Request
{
	/**
	 * items element in the DOM document
	 */
	protected $items;
	
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
	} // end function __construct()
	
	/**
	 * Adds an item to the request
	 *
	 * The item may contain rules to execute
	 *
	 * @param array $item associative array of item data
	 */
	public function addItem($item)
	{
		// create the item element
		$item_element = $this->dom->createElement('item');
		$this->items->appendChild($item_element);
		
		// set the id
		$item_element->setAttribute('id', $item['id']);
		
		// set the item type id
		$item_element->setAttribute('item_type_id', $item['item_type_id']);
		
		// add the rules
		if (!empty($item['rules']))
		{
			$this->attachRules($item_element, $item['rules']);
		}
		
		return true;
	} // end function addItem()
	
	/**
	 * Adds an item search to the request
	 *
	 * The search may contain rules to execute
	 *
	 * @param array $search associative array of item search data
	 * @example feature-item_search.php How to use the item search
	 */
	public function addItemSearch($search)
	{
		// create the search element
		$search_element = $this->dom->createElement('search');
		$this->items->appendChild($search_element);
		
		// set the item lookup
		$search_element->setAttribute('item_lookup', $search['item_lookup']);
		
		// add the rules
		if (!empty($search['rules']))
		{
			$this->attachRules($search_element, $search['rules']);
		}
		
		return true;
	} // end function addItemSearch()
	
	/**
	 * Builds and attaches rules to an item-level element (item or search)
	 *
	 * @param  object $element DOMElement for the item or search
	 * @param  array  $rules associative array of rules to attach
	 */
	protected function attachRules(&$element, &$rules)
	{
		// create the rules element
		$rules_element = $this->dom->createElement('rules');
		$element->appendChild($rules_element);
		
		// set the log_debug flag
		if (!empty($rules['log_debug']))
		{
			$rules_element->setAttribute('log_debug', $rules['log_debug']);
		}
		
		foreach ($rules as $rule)
		{
			// the element needs to be an array to be a child
			if (!is_array($rule)) { continue; }
			
			// create the rule element
			$rule_element = $this->dom->createElement('rule');
			$rules_element->appendChild($rule_element);
			
			// set the type
			$rule_element->setAttribute('type', $rule['type']);
			
			switch ($rule['type'])
			{
				case Services_WorkXpress::RULE_TYPE_RULE:
					
					// set the id
					$rule_element->setAttribute('id', $rule['id']);
					break;
				
				case Services_WorkXpress::RULE_TYPE_EXECUTION_POINT:
					
					// set the execution point
					$rule_element->setAttribute('point', $rule['point']);
					break;
			} // end switch $rule['type']
		} // end foreach - loop through the rules
		
		return true;
	} // end function attachRules()
	
} // end class Services_WorkXpress_Request_ExecuteBusinessRules

?>
