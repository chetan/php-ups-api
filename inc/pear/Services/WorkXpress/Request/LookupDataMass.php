<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Request class for the LookupData API function (mass format)
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
 * Request class for the LookupData API function (mass format)
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @example lookup_data-mass.php Lookup Data Mass Example
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Request_LookupDataMass extends Services_WorkXpress_Request
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
	 * @param int $id item id
	 */
	public function addItem($id)
	{
		// create the item element
		$item = $this->dom->createElement('item');
		$this->items->appendChild($item);
		
		// set the id
		$item->setAttribute('id', $id);
		
		return true;
	} // end function addItem()
	
	/**
	 * Adds an item search to the request
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
	 * @param string $format what format to return the field data in
	 * @param bool   $show_alt show alternate data format
	 */
	public function addField($id, $format = null, $show_alt = null)
	{
		// create the field element
		$field = $this->dom->createElement('field');
		$this->fields->appendChild($field);
		
		// set the id
		$field->setAttribute('id', $id);
		
		// if the format isn't set
		// then set the format to stored value
		if (empty($format))
		{
			$format = Services_WorkXpress::FIELD_FORMAT_STORED_VALUE;
		}
		
		// set the format
		$field->setAttribute('format', $format);
		
		// set show alt
		if ($show_alt)
		{
			$field->setAttribute('show_alt', 1);
		}
		
		return true;
	} // end function addField()
	
	/**
	 * Adds a relation to the request
	 *
	 * @param int    $relation_type_id relation type id
	 * @param string $item_side which side the item is on (base|target)
	 * @param int    $item_type_id used to lookup relations for a specific item type
	 */
	public function addRelation($relation_type_id, $item_side, $item_type_id = null)
	{
		// create the relation element
		$relation = $this->dom->createElement('relation');
		$this->relations->appendChild($relation);
		
		// set the relation type id
		$relation->setAttribute('relation_type_id', $relation_type_id);
		
		// set the item side
		$relation->setAttribute('item_side', $item_side);
		
		// set the item type id
		if (!empty($item_type_id))
		{
			$relation->setAttribute('item_type_id', $item_type_id);
		}
		
		return true;
	} // end function addRelation()
} // end class Services_WorkXpress_LookupDataMass

?>
