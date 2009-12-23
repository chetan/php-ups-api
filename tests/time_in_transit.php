<?php
/**
 * Include the configuration file
 */
require_once dirname(__FILE__).'/../inc/config.php';

echo '<img src="ups_logo.gif" /><br />';

// check if the form was submitted
if (!empty($_POST['submit']))
{
	$origin = array(
		'name' => $_POST['origin_name'],
		'street_number' => $_POST['origin_street_number'],
		'street' => $_POST['origin_street'],
		'street_type' => $_POST['origin_street_type'],
		'city' => $_POST['origin_city'],
		'state' => $_POST['origin_state'],
		'zip_code' => $_POST['origin_zip_code'],
		'country' => $_POST['origin_country'],
	); // end $origin
	$destination = array(
		'name' => $_POST['destination_name'],
		'street_number' => $_POST['destination_street_number'],
		'street' => $_POST['destination_street'],
		'street_type' => $_POST['destination_street_type'],
		'city' => $_POST['destination_city'],
		'state' => $_POST['destination_state'],
		'zip_code' => $_POST['destination_zip_code'],
		'country' => $_POST['destination_country'],
	); // end $destination
	$data = array(
		'pickup_date' => $_POST['pickup_date'],
		'max_list_size' => $_POST['max_list_size'],
		'invoice' => array(
			'currency_code' => $_POST['currency_code'],
			'monetary_value' => $_POST['monetary_value'],
		), // end pickup_date
		'weight' => array(
			'unit_of_measure' => array(
				'code' => $_POST['weight_um'],
				'desc' => $_POST['weight_desc'],
			), // end unit_of_measure
			'weight' => $_POST['weight'],
		), // end weight
	); // end $data
	
	$time_in_transit = new UpsAPI_TimeInTransit($origin, $destination,
		$data);
	$xml = $time_in_transit->buildRequest();
	
	// check the output type
	if ($_POST['output'] == 'array')
	{
		$response = $time_in_transit->sendRequest($xml, false);
		echo 'Response Output:<br />';
		var_dump($response);
	} // end if the output type is an array
	else
	{
		$response = $time_in_transit->sendRequest($xml, true);
		echo 'Response Output:<br />';
		echo '<pre>'.htmlentities($response).'</pre>';
	} // end else the output type is XML
	
	echo 'UpsAPI_TimeInTransit::getservices() Output:<br />';
	var_dump($time_in_transit->getServices());
	echo 'UpsAPI_TimeInTransit::getNumberOrServices() Output:<br />';
	var_dump($time_in_transit->getNumberOrServices());
} // end if the form has been submitted
else
{
?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table border="0">
<tr>
	<td>&nbsp;</td>
	<td style="text-align: center; font-weight: bold;">Origin</td>
	<td style="text-align: center; font-weight: bold;">Destination</td>
</tr>
<tr>
	<td>
		<label for="origin_name">Name: </label>
	</td>
	<td>
		<input type="text" name="origin_name" id="origin_name" size="25"
			value="Joe Schmoe" />
	</td>
	<td>
		<input type="text" name="destination_name"
			id="destination_name" size="25" value="Jane Doe" />
	</td>
</tr>
<tr>
	<td>
		<label for="origin_street_number">Street Number: </label>
	</td>
	<td>
		<input type="text" name="origin_street_number"
			id="origin_street_number" size="25" value="463" />
	</td>
	<td>
		<input type="text" name="destination_street_number"
			id="destination_street_number" size="25" value="8400" />
	</td>
</tr>
<tr>
	<td>
		<label for="origin_street">Street 1: </label>
	</td>
	<td>
		<input type="text" name="origin_street" id="origin_street"
			size="25" value="North Enola" />
	</td>
	<td>
		<input type="text" name="destination_street"
			id="destination_street1" size="25" value="Edgewater" />
	</td>
</tr>
<tr>
	<td>
		<label for="origin_street_type">Street 2: </label>
	</td>
	<td>
		<input type="text" name="origin_street_type" 
			id="origin_street_type" size="25" value="Road" />
	</td>
	<td>
		<input type="text" name="destination_street_type"
			id="destination_street_type" size="25" value="Drive" />
	</td>
</tr>
<tr>
	<td>
		<label for="origin_city">City: </label>
	</td>
	<td>
		<input type="text" name="origin_city" id="origin_city" size="25"
			value="Enola" />
	</td>
	<td>
		<input type="text" name="destination_city" id="destination_city"
			size="25" value="Oakland" />
	</td>
</tr>
<tr>
	<td>
		<label for="origin_state">State/Zip Code: </label>
	</td>
	<td>
		<input type="text" name="origin_state" id="origin_state"
			size="2" maxlength="2" value="PA" /> , 
		<input type="text" name="origin_zip_code" id="origin_zip_code"
			size="5" maxlength="5" value="17025" />
	</td>
	<td>
		<input type="text" name="destination_state"
			id="destination_state" size="2" maxlength="2"
			value="CA" />
		<input type="text" name="destination_zip_code"
			id="destination_zip_code" size="5" maxlength="5"
			value="94621" />
	</td>
</tr>
<tr>
	<td>
		<label for="origin_country">Country: </label>
	</td>
	<td>
		<input type="text" name="origin_country" id="origin_country"
			size="2" maxlength="2" value="US" />
	</td>
	<td>
		<input type="text" name="destination_country"
			id="destination_country" size="2" maxlength="2"
			value="US" />
	</td>
</tr>
<tr>
	<td>
		<label for="pickup_date">Pickup Date: </label>
	</td>
	<td colspan="2">
<?php
	$pickup_date = date('Y').date('m').date('d');
?>
		<input type="text" name="pickup_date" id="pickup_date" size="8"
			maxlength="8" value="<?php echo $pickup_date; ?>" />
	</td>
</tr>
<tr>
	<td>
		<label for="max_list_size">Maximum List Size (1-50): </label>
	</td>
	<td colspan="2">
		<input type="text" name="max_list_size" id="max_list_size"
			size="2" maxlength="2" value="35" />
	</td>
</tr>
<tr>
	<td>
		<label for="currency_code">Currency/Amount: </label>
	</td>
	<td>
		<input type="text" name="currency_code" id="currency_code"
			size="3" maxlength="3" value="USD" />
	</td>
	<td>
		<input type="text" name="monetary_value" id="monetary_value"
			size="11" maxlength="11" value="500.00" />
	</td>
</tr>
<tr>
	<td>
		<label for="weight_um">Weight UM Appr./Desc.: </label>
	</td>
	<td>
		<input type="text" name="weight_um" id="weight_um" size="3"
			maxlength="3" value="LBS" />
	</td>
	<td>
		<input type="text" name="weight_desc" id="weight_desc"
			size="25" maxlength="255" value="Pounds" />
	</td>
</tr>
<tr>
	<td>
		<label for="weight">Weight: </label>
	</td>
	<td colspan="2">
		<input type="text" name="weight" id="weight" size="3"
			maxlength="3" value="23" />
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>
		<select name="output" id="output">
			<option value="array">Array</option>
			<option value="xml">XML</option>
		</select>
	</td>
	<td>
		<input type="hidden" id="submit" name="submit" value="1" />
		<input type="submit" />
	</td>
</tr>
</table>
</form>
<?php
} // end else the form has not been submitted

?>
