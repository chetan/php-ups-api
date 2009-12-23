<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Request class for the AddNewItem API function (mass format)
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
 * Request class for the AddNewItem API function (mass format)
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @example add_new_item-mass.php Add New Item Mass Example
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Request_AddNewItemMass extends Services_WorkXpress_Request
{
	/**
	 * items element in the DOM document
	 */
	protected $items;
	
	/**
	 * fields element in the DOM document
	 */
	protected $fields;
	
	/**
	 * relations element in the DOM document
	 */
	protected $relations;
	
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
		
		// create a fields element in the DOM document
		$this->fields = $this->dom->createElement('fields');
		$this->request->appendChild($this->fields);
		
		// create a relations element in the DOM document
		$this->relations = $this->dom->createElement('relations');
		$this->request->appendChild($this->relations);
	} // end function __construct()
	
	/**
	 * Adds an item to the request
	 *
	 * @param int    $item_type_id the item type id for the new item
	 * @param string $reference item reference
	 * @param bool   $suppress_rules suppress the business rules from running
	 */
	public function addItem($item_type_id, $reference = null, $suppress_rules = null)
	{
		// create the item element
		$item = $this->dom->createElement('item');
		$this->items->appendChild($item);
		
		// set the item type id
		$item->setAttribute('item_type_id', $item_type_id);
		
		// set the reference
		if (!empty($reference))
		{
			$item->setAttribute('reference', $reference);
		}
		
		// suppress the business rules
		if (!empty($suppress_rules))
		{
			$this->setAttribute('suppress_rules', 1);
		}
		
		return true;
	} // end function addItem()
	
	/**
	 * Adds a field to the request
	 *
	 * @param int    $id field id
	 * @param string $value data to store in this field
	 */
	public function addField($id, $value = null)
	{
		// create the field element
		$field_element = $this->dom->createElement('field');
		$this->fields->appendChild($field_element);
		
		// set the id
		$field_element->setAttribute('id', $id);
		
		// set the value
		$field_element->setAttribute('value', $value);
		
		return true;
	} // end function addField()
	
	/**
	 * Adds a relation to the request
	 * 
	 * @param integer $relation_type_id relation type id
	 * @param string  $related_item_side base|target
	 * @param integer $related_item_type_id id of the related item type (on the
	 * other side of the relationship)
	 * @param integer $related_item_id id of the related item (on the other
	 * side of the relationship)
	 * @param string  $reference reference name
	 */
	public function addRelation($relation_type_id, $related_item_side, 
		$related_item_type_id, $related_item_id, $reference = null)
	{
		// create the relation element
		$relation_element = $this->dom->createElement('relation');
		$this->relations->appendChild($relation_element);
		
		// set the relation type id
		$relation_element->setAttribute('relation_type_id', $relation_type_id);
		
		// set the related item side
		$relation_element->setAttribute('related_item_side', $related_item_side);
		
		// set the related item type id
		$relation_element->setAttribute('related_item_type_id', $related_item_type_id);
		
		// set the related item id
		$relation_element->setAttribute('related_item_id', $related_item_id);
		
		// set the reference
		if (!empty($reference))
		{
			$relation_element->setAttribute('reference', $reference);
		}
		
		return true;
	} // end function addRelation()
} // end class Services_WorkXpress_AddNewItemMass

?>
