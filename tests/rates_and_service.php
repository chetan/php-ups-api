<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>PHP UPS API Tesing -> Rates & Service</title>
	<style type="text/css">
		h1 {
			text-align: center;
		}
		table.rate_form {
			width: 1em;
		}
		
		table.rate_form tbody tr td {
			vertical-align: top;
			white-space: nowrap;
		}
	</style>
</head>
<body>
	<h1><img src="ups_logo.gif" />Rates &amp; Service</h1>
<?php
/**
 * Include the configuration file
 */
require_once dirname(__FILE__).'/../inc/config.php';

$pickup_codes = array(
	'01' => 'Daily Pickup',
	'03' => 'Customer Counter',
	'06' => 'One Time Pickup',
	'07' => 'On Call Air',
	'11' => 'Suggested Retail Rates',
	'19' => 'Letter Center',
	'20' => 'Air Service Center',
); // end $pickup_codes

// check if the form was submitted
if (!empty($_POST['submit']))
{
	$shipment = array(
		'pickup_type' => array(
			'code' => $_POST['pickup_type'],
			'description' => $pickup_codes[$_POST['pickup_type']],
		),
		'service' => $_POST['service'],
		'packages' => array(
			array(
				'packaging' => array(
					'code' => 21,
					'description' => 'Express Box',
				),
				'description' => 'Package from customer',
				'units' => 'LBS',
				'weight' => 100.0,
			),
			array(
				'packaging' => array(
					'code' => '02',
					'description' => 'Package',
				),
				'description' => 'Package from customer',
				'units' => 'LBS',
				'weight' => 23.6,
			),
		),
		'saturday' => array(
			'pickup' => true,
			'deliver' => false,
		),
		'pickup_day' => '02',
		'scheduling_method' => '02',
	); // end $shipment
	
	$shipper = array(
		'name' => 'Shipper Name',
		'phone' => '1234567890',
		'number' => null,
		'street' => 'Address Line1',
		'street2' => 'Address Line2',
		'city' => 'West Chester',
		'state' => 'PA',
		'zip' => '19380',
		'country' => 'US',
	); // end $shipper
	
	$ship_from = array(
		'name' => 'Ship F. Name',
		'phone' => '1234567890',
		'street' => 'Address Line1',
		'street2' => 'Address Line2',
		'street3' => 'Address Line3',
		'city' => 'Carlisle',
		'state' => 'PA',
		'zip' => '17013',
		'country' => 'US',
	); // end $ship_from
	
	$destination = array(
		'name' => 'Recipients Name',
		'phone' => '1234567890',
		'street' => 'Address Line',
		'city' => 'Duncannon',
		'state' => 'PA',
		'zip' => '17020',
		'country' => 'US',
	); // end $destination
	
	$rate = new UpsAPI_RatesAndService($shipment, $shipper, $ship_from,
		$destination);
	$xml = $rate->buildRequest();
	
	// check the output type
	if ($_POST['output'] == 'array')
	{
		$response = $rate->sendRequest($xml, false);
		echo 'Response Output:<br />';
		var_dump($response);
	} // end if the output type is an array
	else
	{
		$response = $rate->sendRequest($xml, true);
		echo 'Response Output:<br />';
		echo '<pre>'.htmlentities($response).'</pre>';
	} // end else the output type is XML
	
	var_dump($rate->isError());
	var_dump($rate->getError());
	var_dump($rate->getWarnings());
	var_dump($rate->getPackageCharges());
	var_dump($rate->getShipmentCharges());
	var_dump($rate->getPackageWeight());
	var_dump($rate->getShipmentWeight());
} // end if the form has been submitted
else
{
	$packaging_codes = array(
		'00' => 'UNKNOWN',
		'01' => 'UPS Letter',
		'02' => 'Package',
		'03' => 'Tube',
		'04' => 'Pak',
		'21' => 'Express Box',
		'24' => '25KG Box',
		'25' => '10KG Box',
		'30' => 'Pallet',
		'2a' => 'Small Express Box',
		'2b' => 'Medium Express Box',
		'2c' => 'Large Express Box',
	); // end $packaging_codes
	
	$service_codes = array(
		'01' => 'UPS Express',
		'02' => 'UPS Expedited',
		'03' => 'UPS Ground',
		'07' => 'UPS Express',
		'08' => 'UPS Expedited',
		'11' => 'UPS Standard',
		'12' => 'UPS Three-Day Select',
		'13' => 'UPS Saver',
		'14' => 'UPS Express Early A.M.',
		'54' => 'UPS Worldwide Express Plus',
		'59' => 'UPS Second Day Air A.M.',
		'65' => 'UPS Saver',
		'82' => 'UPS Today Standard',
		'83' => 'UPS Today Dedicated Courrier',
		'84' => 'UPS Today Intercity',
		'85' => 'UPS Today Express',
		'86' => 'UPS Today Express Saver',
		'308' => 'UPS Freight LTL',
		'309' => 'UPS Freight LTL Guaranteed',
		'310' => 'UPS Freight LTL Urgent',
		'TDCB' => 'Trade Direct Cross Border',
		'TDA' => 'Trade Direct Air',
		'TDO' => 'Trade Direct Ocean',
	); // end $service_codes
	
	$weight_um = array(
		'LBS' => 'Pounds',
		'KGS' => 'Kilograms',
	); // end $weight_um
?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	
	<table border="0" cellpadding="0" cellspacing="2px" class="rate_form">
	<tbody>
		<tr>
			<td>
				<label for="pickup_type">Pickup Type:</label>
			</td>
			<td>
				<select id="pickup_type" name="pickup_type">
					<option value="" selected="selected">Select</option>
<?php
	// iterate over the pickup codes
	asort($pickup_codes);
	foreach ($pickup_codes as $code => $title) {
		echo "\t\t\t\t\t".
			'<option value="'.$code.'">'.$title.'</option>'."\n";
	} // end for each pickup code
?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="packaging">Packaging Type:</label>
			</td>
			<td>
				<select id="packaging" name="packaging">
					<option value="" selected="selected">Select</option>
<?php
	// iterate over the packaging codes
	asort($packaging_codes);
	foreach ($packaging_codes as $code => $title) {
		echo "\t\t\t\t\t".
			'<option value="'.$code.'">'.$title.'</option>'."\n";
	} // end for each packaging code
?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="packaging_desc">Packaging Description:</label>
			</td>
			<td>
				<input type="text" id="packaging_desc" name="packaging_desc"
					size="40" />
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="service">Service:</label>
			</td>
			<td>
				<select id="service" name="service">
					<option value="" selected="selected">Select</option>
<?php
	// iterate over the service codes
	asort($service_codes);
	foreach ($service_codes as $code => $title) {
		echo "\t\t\t\t\t".
			'<option value="'.$code.'">'.$title.'</option>'."\n";
	} // end for each service code
?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="weight">Weight:</label>
			</td>
			<td>
				<input type="text" id="weight" name="weight" size="5" />
				<select id="weight_um" name="weight_um">
					<option value="" selected="selected">Select</option>
<?php
	// iterate over the units of measurement
	asort($weight_um);
	foreach ($weight_um as $code => $title) {
		echo "\t\t\t\t\t".
			'<option value="'.$code.'">'.$title.'</option>'."\n";
	} // end for each unit of measurement
?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="pickup">Pickup Day:</label>
			</td>
			<td>
				<select id="pickup" name="pickup">
					<option value="" selected="selected">Select</option>
					<option value="01">Same Day</option>
					<option value="02">Future Day</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="sched_method">Scheduling Method:</label>
			</td>
			<td>
				<select id="sched_method" name="sched_method">
					<option value="" selected="selected">Select</option>
					<option value="01">Internet</option>
					<option value="02">Phone</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<td colspan="2"><strong>Shipper Information</strong></td>
		</tr>
		
		<tr>
			<td>
				<label for="name">Name:</label>
			</td>
			<td>
				<input type="text" id="name" name="name" size="40" />
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="area_code">Phone: </label>
			</td>
			<td>
				(<input type="text" id="area_code" name="area_code"
					maxlength="3" size="3" />)
				<input type="text" id="phone_prefix" name="phone_prefix"
					maxlength="3" size="3" /> -
				<input type="text" id="phone_line" name="phone_line"
					maxlength="4" size="4" />
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="shipper">Shipper Number:</label>
			</td>
			<td>
				<input type="text" id="shipper" name="shipper" size="40" />
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="street1">Address:</label>
			</td>
			<td>
				<input type="text" id="street1" name="street1"
					size="40" /><br />
				<input type="text" id="street2" name="street2"
					size="40" /><br />
				<input type="text" id="city" name="city" size="21" />,
				<input type="text" id="state" name="state"
					maxlength="2" size="2" />
				<input type="text" id="zip" name="zip" maxlength="5" size="5" />
			</td>
		</tr>
		
		<tr>
			<td colspan="2"><strong>Ship To Information</strong></td>
		</tr>
		
		<tr>
			<td>
				<label for="name">Name:</label>
			</td>
			<td>
				<input type="text" id="shipto_name" name="shipto_name"
					size="40" />
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="shipper">Attention:</label>
			</td>
			<td>
				<input type="text" id="shipto_attention" name="shipto_attention"
					size="40" />
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="area_code">Phone: </label>
			</td>
			<td>
				(<input type="text" id="shipto_area_code" name="shipto_area_code"
					maxlength="3" size="3" />)
				<input type="text" id="shipto_prefix" name="shipto_prefix"
					maxlength="3" size="3" /> -
				<input type="text" id="shipto_line" name="shipto_line"
					maxlength="4" size="4" />
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="street1">Address:</label>
			</td>
			<td>
				<input type="text" id="shipto_street1" name="shipto_street1"
					size="40" /><br />
				<input type="text" id="shipto_street2" name="shipto_street2"
					size="40" /><br />
				<input type="text" id="shipto_city" name="shipto_city"
					size="21" />,
				<input type="text" id="shipto_state" name="shipto_state"
					maxlength="2" size="2" />
				<input type="text" id="shipto_zip" name="shipto_zip"
					maxlength="5" size="5" />
			</td>
		</tr>
		
		<tr>
			<td>
				<label for="output">Output:</label>
			</td>
			<td>
				<select id="output" name="output">
					<option value="array">Array</option>
					<option value="xml">XML</option>
				</select>
			</td>
		</tr>
	</tbody>
	</table>
	
	<input type="hidden" id="submit" name="submit" value="1" />
	<input type="submit" />
	</form>
<?php
} // end else the form has not been submitted

?>
</body>
</html>