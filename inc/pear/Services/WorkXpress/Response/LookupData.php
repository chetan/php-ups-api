<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Response class for the LookupData API function
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
 * Response class for the LookupData API function
 *
 * PHP version 5
 *
 * @package Services_WorkXpress
 * @author Scott Gonzalez <sgonzalez@expressdynamics.com>
 * @copyright 2005-2006 Express Dynamics
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link PACKAGE_URL
 */
class Services_WorkXpress_Response_LookupData extends Services_WorkXpress_Response
{
	/**
	 * Constructor
	 *
	 * @param string $xml XML response from the WorkXpress API call
	 */
	public function __construct($xml)
	{
		parent::__construct($xml);
	} // end function __construct()
	
	/**
	 * Processes the unserialized array into the proper format
	 *
	 * @param  int   $format format of the response data array
	 * @return array response data
	 */
	protected function processData($format)
	{
		// get the unserialized array
		$data = $this->unserializeResponse();
		
		$processed_data = array();
		
		// determine how to format the processed array
		switch ($format)
		{
			// not collapsed
			case Services_WorkXpress::DATA_ARRAY_FORMAT_NOT_COLLAPSED:
			
				if (!empty($data['items']))
				{
					foreach ($data['items']['item'] as $item)
					{
						$field_array = array();
						if (!empty($item['fields']))
						{
							$field_array = $item['fields']['field'];
						}
						
						$relation_array = array();
						if (!empty($item['relations']))
						{
							$relation_array = $item['relations']['relation'];
						}
						
						$processed_data['items'][] = array(
							'id'        => $item['id'],
							'fields'    => $field_array,
							'relations' => $relation_array,
						);
					} // end foreach - loop through items
				} // end if we have items
				break; // end case not collapsed
				
			// partially collapsed
			case Services_WorkXpress::DATA_ARRAY_FORMAT_PARTIALLY_COLLAPSED:
			
				if (!empty($data['items']['item']))
				{
					foreach ($data['items']['item'] as $item)
					{
						$processed_data[$item['id']] = array();
						
						if (!empty($item['fields']))
						{
							foreach ($item['fields']['field'] as $field)
							{
								$processed_data[$item['id']]['fields'][$field['id']][$field['format']] = $field['value'];
							}
						} // end if we have fields
						
						if (!empty($item['relations']))
						{
							foreach ($item['relations']['relation'] as $relation)
							{
								$processed_data[$item['id']]['relations'][$relation['id']] = $relation;
							}
						} // end if we have relations
					} // end foreach - loop through items
				} // end if we have items
				break; // end case partially collapsed
				
			// fully collapsed
			case Services_WorkXpress::DATA_ARRAY_FORMAT_FULLY_COLLAPSED:
			
				if (!empty($data['items']['item']))
				{
					foreach ($data['items']['item'] as $item)
					{
						$processed_data[$item['id']] = array();
						
						if (!empty($item['fields']))
						{
							foreach ($item['fields']['field'] as $field)
							{
								$processed_data[$item['id']]['fields'][$field['id']] = $field['value'];
							}
						} // end if we have fields
						
						if (!empty($item['relations']))
						{
							foreach ($item['relations']['relation'] as $relation)
							{
								$processed_data[$item['id']]['relations'][$relation['id']] = array(
									'relation_type_id'    => $relation['relation_type_id'],
									'base_item_type_id'   => $relation['base_item_type_id'],
									'base_item_id'        => $relation['base_item_id'],
									'target_item_type_id' => $relation['target_item_type_id'],
									'target_item_id'      => $relation['target_item_id'],
									'active'              => $relation['active'],
								);
							} // end foreach - loop through relations
						} // end if we have relations
					} // end foreach - loop through items
				} // end if we have items
				break; // end case fully collapsed
		} // end switch data array format
		
		// return the processed array
		return $processed_data;
	} // end function processData()
} // end class Services_WorkXpress_Reponse_LookupData

?>
