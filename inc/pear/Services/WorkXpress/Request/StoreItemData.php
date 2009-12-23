<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Request class for the StoreItemData API function
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
 * Request class for the StoreItemData API function
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @example store_item_data.php Store Item Data Example
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Request_StoreItemData extends Services_WorkXpress_Request
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
	 * The item may contain fields to set/clear and relations to create.
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
		
		// set the reference
		if (!empty($item['reference']))
		{
			$item_element->setAttribute('reference', $item['reference']);
		}
		
		// set the suppress_rules flag
		if (!empty($item['suppress_rules']))
		{
			$item_element->setAttribute('suppress_rules', 1);
		}
		
		// add the fields
		if (!empty($item['fields']))
		{
			$this->attachFields($item_element, $item['fields']);
		}
		
		// add the relations
		if (!empty($item['relations']))
		{
			$this->attachRelations($item_element, $item['relations']);
		}
		
		return true;
	} // end function addItem()
	
	/**
	 * Adds an item search to the request
	 *
	 * The search may contain fields to set/clear and relations to
	 * create/recycle/delete.
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
		
		// add the fields
		if (!empty($search['fields']))
		{
			$this->attachFields($search_element, $search['fields']);
		}
		
		// add the relations
		if (!empty($search['relations']))
		{
			$this->attachRelations($search_element, $search['relations']);
		}
		
		return true;
	} // end function addItemSearch()
	
	/**
	 * Builds and attaches fields to an item-level element (item or search)
	 *
	 * @param  object $element DOMElement for the item or search
	 * @param  array  $fields associative array of fields to attach
	 */
	protected function attachFields(&$element, &$fields)
	{
		// create the fields element
		$fields_element = $this->dom->createElement('fields');
		$element->appendChild($fields_element);
		
		foreach ($fields as $field)
		{
			// create the field element
			$field_element = $this->dom->createElement('field');
			$fields_element->appendChild($field_element);
			
			// set the id
			$field_element->setAttribute('id', $field['id']);
			
			// if the action isn't set
			// then set the action to save
			if (empty($field['action']))
			{
				$field['action'] = Services_WorkXpress::FIELD_ACTION_SAVE;
			}
			
			// set the action
			$field_element->setAttribute('action', $field['action']);
			
			// set the value
			if ($field['action'] == Services_WorkXpress::FIELD_ACTION_SAVE)
			{
				$field_element->setAttribute('value', $field['value']);
			}
		} // end foreach - loop through the fields
		
		return true;
	} // end function attachFields()
	
	/**
	 * Builds and attaches relations to an item-level element (item or search)
	 *
	 * @param  object $element DOMElement for the item or search
	 * @param  array  $relations associative array of relations to attach
	 */
	protected function attachRelations(&$element, &$relations)
	{
		// create the relations element
		$relations_element = $this->dom->createElement('relations');
		$element->appendChild($relations_element);
		
		foreach ($relations as $relation)
		{
			// create the relation element
			$relation_element = $this->dom->createElement('relation');
			$relations_element->appendChild($relation_element);
			
			// if the id isn't set (creating a new relation)
			// and the action isn't set
			// then set the action to create
			if (empty($relation['id']) && empty($relation['action']))
			{
				$relation['action'] = Services_WorkXpress::RELATION_ACTION_CREATE;
			}
			
			// set the action
			$relation_element->setAttribute('action', $relation['action']);
			
			// set the relation type id
			$relation_element->setAttribute('relation_type_id',
				$relation['relation_type_id']);
			
			// set the reference
			if (!empty($relation['reference']))
			{
				$relation_element->setAttribute('reference',
					$relation['reference']);
			}
			
			// set proper attributes depending on if we're creating, recycling,
			// or deleting the relationship
			switch ($relation['action'])
			{
				case Services_WorkXpress::RELATION_ACTION_CREATE:
					
					
					// set the item type id
					if (!empty($relation['item_type_id']))
					{
						$relation_element->setAttribute('item_type_id',
							$relation['item_type_id']);
					}
					
					// set the related item side
					if (!empty($relation['related_item_side']))
					{
						$relation_element->setAttribute('related_item_side',
							$relation['related_item_side']);
					}
					
					// set the related item type id
					if (!empty($relation['related_item_type_id']))
					{
						$relation_element->setAttribute('related_item_type_id',
							$relation['related_item_type_id']);
					}
					
					// set the related item id
					if (!empty($relation['related_item_id']))
					{
						$relation_element->setAttribute('related_item_id',
							$relation['related_item_id']);
					}
					break;
				
				case Services_WorkXpress::RELATION_ACTION_DELETE:
				case Services_WorkXpress::RELATION_ACTION_RECYCLE:
					
					// set the id
					if (!empty($relation['id']))
					{
						$relation_element->setAttribute('id', $relation['id']);
					}
					break;
			} // end switch $relation['action']
		} // end foreach - loop through the relations
		
		return true;
	} // end function attachRelations()
} // end class Services_WorkXpress_Request_StoreItemData

?>
