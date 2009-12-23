<?php
/**
 * Include the configuration file
 */
require_once dirname(__FILE__).'/../inc/config.php';

echo '<img src="ups_logo.gif" /><br />';

// check if the form was submitted
if (!empty($_POST['submit']))
{
	$address['city'] = $_POST['city'];
	$address['state'] = $_POST['state'];
	$address['zip_code'] = $_POST['zip_code'];
	
	$validation = new UpsAPI_USAddressValidation($address);
	$xml = $validation->buildRequest();
	
	// check the output type
	if ($_POST['output'] == 'array')
	{
		$response = $validation->sendRequest($xml, false);
		echo 'Response Output:<br />';
		var_dump($response);
	} // end if the output type is an array
	else
	{
		$response = $validation->sendRequest($xml, true);
		echo 'Response Output:<br />';
		echo '<pre>'.htmlentities($response).'</pre>';
	} // end else the output type is XML
	
	echo 'UpsAPI_USAddressValidation::getMatchType() Output:<br />';
	var_dump($validation->getMatchType());
	echo 'UpsAPI_USAddressValidation::getMatches() Output:<br />';
	var_dump($validation->getMatches());
} // end if the form has been submitted
else
{
?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<input type="text" name="city" id="city" size="25"
		value="Camp Hill" /><br />
	<input type="text" name="state" id="state" size="2" maxlength="2"
		value="PA" />
	<input type="text" name="zip_code" id="zip_code" size="5" maxlength="5"
		value="17011" /><br />
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
