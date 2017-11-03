
  <form action="<?php echo $action ?>" method="post" id="payu_form" name="payu_form" >
		<input type="hidden" name="key" value="<?php echo $key; ?>" />
		<input type="hidden" name="txnid" value="<?php echo $txnid; ?>" />
		<input type="hidden" name="amount" value="<?php echo $amount; ?>" />
		<?php echo '<input type="hidden" name="productinfo" value="'.htmlspecialchars($productinfo).'"/>'; ?>
		<input type="hidden" name="firstname" value="<?php echo $firstname; ?>" />
		<input type="hidden" name="Lastname" value="<?php echo $Lastname; ?>" />
                
                
                <input type="hidden" name="address" value="<?php echo $address; ?>" />
                <input type="hidden" name="City" value="<?php echo $City; ?>" />
                <input type="hidden" name="State" value="<?php echo $State; ?>" />
                <input type="hidden" name="Country" value="<?php echo $Country; ?>" />
                
		<input type="hidden" name="Zipcode" value="<?php echo $Zipcode; ?>" />
		<input type="hidden" name="email" value="<?php echo $email; ?>" />
                
                
                <input type="hidden" name="ship_name" value="<?php echo $ship_name; ?>" />
                <input type="hidden" name="ship_address" value="<?php echo $ship_address; ?>" />
                <input type="hidden" name="ship_zipcode" value="<?php echo $ship_zipcode; ?>" />
                <input type="hidden" name="ship_city" value="<?php echo $ship_city; ?>" />
                <input type="hidden" name="ship_state" value="<?php echo $ship_state; ?>" />
                <input type="hidden" name="ship_country" value="<?php echo $ship_country; ?>" />
                 <input type="hidden" name="ship_phone" value="<?php echo $ship_phone; ?>" />
                
                
                <input type="hidden" name="website" value="<?php echo $website; ?>" />
		<input type="hidden" name="phone" value="<?php echo $phone; ?>" />
		<input type="hidden" name="surl" value="<?php echo $surl; ?>" />
		<input type="hidden" name="furl" value="<?php echo $furl; ?>" />
		<input type="hidden" name="curl" value="<?php echo $curl; ?>" />
		<input type="hidden" name="hash" value="<?php echo $hash;?>" />
		<input type="hidden" name="Pg" value="<?php echo $pg; ?>" />
		<input type="hidden" name="udf2" value="<?php echo $udf2; ?>" />
		<input type="hidden" name="service_provider" value="payu_paisa" />
		<div class="buttons">
    <div class="right"><a onclick="$('#payu_form').submit();" class="button"><span><?php echo $button_confirm;  ?></span></a></div>
  </div>
	</form>	














    