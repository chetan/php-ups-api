<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Request class for the StoreItemData API function (mass format)
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
 * Request class for the StoreItemData API function (mass format)
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Doug Warner <dwarner@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @example store_item_data-mass.php Store Item Data Mass Example
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Request_StoreItemDataMass extends Services_WorkXpress_Request
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
	 * @param int     $id item id
	 * @param string  $reference item reference
	 * @param boolean $suppress_rules whether to keep the business rules
	 * attached to this item from executing
	 */
	public function addItem($id, $reference = null, $suppress_rules = false)
	{
		// create the item element
		$item = $this->dom->createElement('item');
		$this->items->appendChild($item);
		
		// set the id
		$item->setAttribute('id', $id);
		
		// set the reference
		if (!empty($reference))
		{
			$item->setAttribute('reference', $reference);
		}
		
		// set the suppress_rules flag
		if (!empty($suppress_rules))
		{
			$item->setAttribute('suppress_rules', ($suppress_rules ? '1' : '0'));
		}
		
		return true;
	} // end function addItem()
	
	/**
	 * Adds an item search to the request
	 * 
	 * The search may contain fields to set/clear and relations to
	 * create/recycle/delete.
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
	 * Adds a field to the request
	 *
	 * @param int    $id field id
	 * @param string $action save|clear
	 * @param string $value data to store in this field
	 */
	public function addField($id, $action = null, $value = null)
	{
		// create the field element
		$field_element = $this->dom->createElement('field');
		$this->fields->appendChild($field_element);
		
		// set the id
		$field_element->setAttribute('id', $id);
		
		// if the action isn't set
		// then set the action to save
		if (empty($action))
		{
			$action = Services_WorkXpress::FIELD_ACTION_SAVE;
		}
		
		// set the action
		$field_element->setAttribute('action', $action);
		
		// set the value
		if ($action == Services_WorkXpress::FIELD_ACTION_SAVE)
		{
			$field_element->setAttribute('value', $value);
		}
		
		return true;
	} // end function addField()
	
	/**
	 * Adds a relation to the request
	 * 
	 * @param integer $relation_type_id relation type id
	 * @param integer $item_type_id item type id
	 * @param string  $related_item_side base|target
	 * @param integer $related_item_type_id id of the related item type (on the
	 * other side of the relationship)
	 * @param integer $related_item_id id of the related item (on the other
	 * side of the relationship)
	 * @param string  $reference reference name
	 */
	public function addRelation($relation_type_id, $item_type_id, $related_item_side, 
		$related_item_type_id, $related_item_id, $reference = null)
	{
		// create the relation element
		$relation_element = $this->dom->createElement('relation');
		$this->relations->appendChild($relation_element);
		
		// we're forcing the action to alwas be create
		// delete and recycle are only valid on single-item updates
		$action = Services_WorkXpress::RELATION_ACTION_CREATE;
		
		// set the action
		$relation_element->setAttribute('action', $action);
		
		// set the relation type id
		$relation_element->setAttribute('relation_type_id', $relation_type_id);
		
		// set the item type id
		$relation_element->setAttribute('item_type_id', $item_type_id);
		
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
} // end class Services_WorkXpress_StoreItemDataMass

?>
