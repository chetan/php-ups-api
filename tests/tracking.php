<?php
/**
 * Include the configuration file
 */
require_once dirname(__FILE__).'/../inc/config.php';

echo '<img src="ups_logo.gif" /><br />';

// check if the form was submitted
if (!empty($_POST['submit']))
{
	$tracking_number = $_POST['tracking_number'];
	$reference_number  = $_POST['reference_number'];
	$shipper_number  = $_POST['shipper_number'];
	
	// check to see if we have inquiry data
	$inquiry = array();
	if (!empty($reference_number) && !empty($shipper_number))
	{
		$inquiry = array(
			'reference_number' => $reference_number,
			'shipper_number' => $shipper_number,
		); // end $inquiry
	} // end if we have inquiry data
	
	$tracking = new UpsAPI_Tracking($tracking_number, $inquiry);
	$xml = $tracking->buildRequest();
	
	// check the output type
	if ($_POST['output'] == 'array')
	{
		$response = $tracking->sendRequest($xml, false);
		echo 'Response Output:<br />';
		var_dump($response);
	} // end if the output type is an array
	else
	{
		$response = $tracking->sendRequest($xml, true);
		echo 'Response Output:<br />';
		echo '<pre>'.htmlentities($response).'</pre>';
	} // end else the output type is XML
	
	echo 'UpsAPI_Tracking::getNumberOfPackages() Output:<br />';
	var_dump($tracking->getNumberOfPackages());
	echo 'UpsAPI_Tracking::getPackageStatus() Output:<br />';
	var_dump($tracking->getPackageStatus());
	echo 'UpsAPI_Tracking::getShippingAddress() Output:<br />';
	var_dump($tracking->getShippingAddress());
	echo 'UpsAPI_Tracking::getShippingMethod() Output:<br />';
	var_dump($tracking->getShippingMethod());
} // end if the form has been submitted
else
{
?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="text" name="tracking_number" id="tracking_number" size="25"
		value="1Z12345E0291980793" /><br />
	<label for="reference_number">Inquiry Number</label>
	<input type="text" name="reference_number" id="reference_number" size="25" />
	<label for="shipper_number">Sender Shipper Number</label>
	<input type="text" name="shipper_number" id="shipper_number" size="25" />
	<select name="output" id="output">
		<option value="array">Array</option>
		<option value="xml">XML</option>
	</select>
	<input type="hidden" id="submit" name="submit" value="1" />
	<input type="submit" />
</form>
<?php
} // end else the form has not been submitted

?>
