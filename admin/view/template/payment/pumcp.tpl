<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (!empty($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
        <tr>
        	<td colspan="2"><h1><img src="view/image/payment/payulogo.png" alt="PayU Money" height="40" /></h1></td>
        </tr>
        <tr>
            <td><span data-toggle="tooltip" title="<?php echo $help_merchant; ?>"><?php echo $entry_merchant; ?></span></td>
            <td><input type="text" name="pumcp_payu_merchant" value="<?php echo $pumcp_payu_merchant; ?>" placeholder="<?php echo $entry_merchant; ?>" id="pumcp_payu_merchant" />
              <?php if (!empty($error_merchant)) { ?>
              <span class="error"><?php echo $error_merchant; ?></span>
              <?php } ?></td>
          </tr>
        
        <tr>
            <td><span data-toggle="tooltip" title="<?php echo $help_salt; ?>"><?php echo $entry_salt; ?></span></td>
            <td><input type="text" name="pumcp_payu_salt" value="<?php echo $pumcp_payu_salt; ?>" placeholder="<?php echo $entry_salt; ?>" id="pumcp_payu_salt" />
              <?php if (!empty($error_salt)) { ?>
              <span class="error"><?php echo $error_salt; ?></span>
              <?php } ?></td>
          </tr>
        
        <tr>
        	<td colspan="2"><h2><img src="view/image/payment/citrus.png" alt="Citruspay" height="40" />&nbsp;Citrus Pay</h2></td>
        </tr>
        
        <tr>
            <td><span data-toggle="tooltip" title="<?php echo $help_vanityurl; ?>"><?php echo $entry_vanityurl; ?></span></td>
            <td><input type="text" name="pumcp_citrus_vanityurl" value="<?php echo $pumcp_citrus_vanityurl; ?>" id="pumcp_citrus_vanityurl" placeholder="<?php echo $entry_vanityurl; ?>" />
              <?php if (!empty($error_citrus_vanityurl)) { ?>
              <span class="error"><?php echo $error_citrus_vanityurl; ?></span>
              <?php } ?></td>
          </tr>
          
          <tr>
            <td><span data-toggle="tooltip" title="<?php echo $help_accesskey; ?>"><?php echo $entry_access_key; ?></span></td>
            <td><input type="text" name="pumcp_citrus_access_key" value="<?php echo $pumcp_citrus_access_key; ?>" id="pumcp_citrus_access_key"  placeholder="<?php echo $entry_access_key; ?>" />
              <?php if (!empty($error_citrus_access_key)) { ?>
              <span class="error"><?php echo $error_citrus_access_key; ?></span>
              <?php } ?></td>
          </tr>
          
          <tr>
            <td><span data-toggle="tooltip" title="<?php echo $help_secretkey; ?>"><?php echo $entry_secret_key; ?></span></td>
            <td><input type="text" name="pumcp_citrus_secret_key" value="<?php echo $pumcp_citrus_secret_key; ?>" id="pumcp_citrus_secret_key" placeholder="<?php echo $entry_secret_key; ?>" />
              <?php if (!empty($error_citrus_secret_key)) { ?>
              <span class="error"><?php echo $error_citrus_secret_key; ?></span>
              <?php } ?></td>
          </tr>
          
          <tr>
        	<td colspan="2"><h3>General Settings</h3></td>
         </tr>
          
         <tr>
            <td><span data-toggle="tooltip" title="<?php echo $help_route; ?>"><?php echo $entry_route; ?></span></td>
            <td>
            	<table>
                	<tr>
                    	<td><?php echo $entry_route_payu; ?></td>
                        <td><input type="text" name="pumcp_route_payu" value="<?php echo $pumcp_route_payu; ?>" placeholder="<?php echo $entry_route; ?>" id="pumcp_route_payu"  size="3" maxlength="3" /></td>
                    </tr>
                    <tr>
                    	<td><?php echo $entry_route_citrus; ?></td>
                        <td><input type="text" name="pumcp_route_citrus" value="<?php echo $pumcp_route_citrus; ?>" placeholder="<?php echo $entry_route; ?>" id="pumcp_route_citrus"  size="3" maxlength="3" /></td>
                    </tr>                        
                </table>
                <?php if (!empty($error_route)) { ?>
              <span class="error"><?php echo $error_route; ?></span>
              <?php } ?>               
            </td>
          </tr> 
          
          
		 <tr>
            <td><?php echo $entry_module; ?></td>
            <td>
            <select name="pumcp_module">
              <?php $cm=explode('|',$entry_module_id);foreach($cm as $m){?>
                <?php if ($pumcp_module == $m) { ?>
                <option value="<?php echo $m; ?>" selected="selected"><?php echo $m; ?></option>
                <?php } else { ?>
                <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                <?php }} ?>
              </select>
              <?php if (!empty($error_module)) { ?>
              <span class="error"><?php echo $error_module; ?></span>
              <?php } ?>    
              </td>
          </tr>
		  
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td>
            <select name="pumcp_geo_zone_id" id="pumcp_geo_zone_id" >
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $pumcp_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              </td>
          </tr>
          
          <tr>
            <td><span data-toggle="tooltip" title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></td>
            <td>
            <input type="text" name="pumcp_currency" value="<?php echo $pumcp_currency; ?>" placeholder="<?php echo $entry_currency; ?>" id="pumcp_currency" size="8" maxlength="3" />
              <?php if (!empty($error_currency)) { ?>
                  <span class="error"><?php echo $error_currency; ?></span>
              <?php } ?>
              </td>
          </tr>
          
          <tr>
            <td><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></td>
            <td>
            <input type="text" name="pumcp_total" value="<?php echo $pumcp_total; ?>" placeholder="<?php echo $entry_total; ?>" id="pumcp_total" />
              </td>
          </tr>
                     
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="pumcp_order_status_id" id="pumcp_order_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pumcp_order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select></td>
          </tr>
          
          <tr>
            <td><?php echo $entry_order_fail_status; ?></td>
            <td><select name="pumcp_order_fail_status_id" id="input-status" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $pumcp_order_fail_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select></td>
          </tr>
          
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="pumcp_status" id="input-status" class="form-control">
                      <?php if ($pumcp_status) { ?>
                         <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                         <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                         <option value="1"><?php echo $text_enabled; ?></option>
                         <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                   </select>
                   <?php if (!empty($error_status)) { ?>
                	<span class="error"><?php echo $error_status; ?></span>
            	 	<?php } ?>
                    
                    </td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="pumcp_sort_order" value="<?php echo $pumcp_sort_order; ?>"  id="pumcp_sort_order" size="1" /></td>
          </tr>
          
        </table>
      </form>
      <script type="text/javascript">
function routeen()
{
	if( $('input[name=pumcp_payu_merchant]').val()==="" || $('input[name=pumcp_payu_salt]').val()==="" || $('input[name=pumcp_citrus_vanityurl]').val()==="" || $('input[name=pumcp_citrus_access_key]').val()==="" || $('input[name=pumcp_citrus_secret_key]').val()==="")
	   {		   
		   $('input[name=pumcp_route_citrus]').attr("readonly", true);
		   $('input[name=pumcp_route_payu]').attr("readonly", true);
	   }
	   else {		
	   	   $('input[name=pumcp_route_citrus]').removeAttr("readonly");
		   $('input[name=pumcp_route_payu]').removeAttr("readonly");
	   }
}

$(document).ready(function() { routeen(); });
$('#form-pumcp').change(function() { routeen();  });
 
$('#pumcp_route_payu').bind('change',function() {
	var val = parseInt(this.value,10);	
	if(val > 100)
	{
		$('input[name=pumcp_route_payu]').val(100);
		$('input[name=pumcp_route_citrus]').val(0);
	}
	else if(val < 0)
	{
		$('input[name=pumcp_route_payu]').val(0);
		$('input[name=pumcp_route_citrus]').val(100);
	}
	else {
		$('input[name=pumcp_route_citrus]').val(Math.abs(100 - val));	
	}
	
});

$('#pumcp_route_citrus').bind('change',function() {
	var val = parseInt(this.value,10);	
	if(val > 100)
	{
		$('input[name=pumcp_route_citrus]').val(100);
		$('input[name=pumcp_route_payu]').val(0);		
	}
	else if(val < 0)
	{
		$('input[name=pumcp_route_citrus]').val(0);
		$('input[name=pumcp_route_payu]').val(100);
		
	}
	else {
		$('input[name=pumcp_route_payu]').val(Math.abs(100 - val));	
	}
});

</script>
    </div>
  </div>
</div>
<?php echo $footer; ?>