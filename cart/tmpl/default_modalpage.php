<?php
/**
** Parts of this code is written by Joomlaproffs.se Copyright (c) 2012, 2015 All Right Reserved.
** Many part of this code is from VirtueMart Team Copyright (c) 2004 - 2015. All rights reserved.
** Some parts might even be Joomla and is Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved. 
** http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
** This source is free software. This version may have been modified pursuant
** to the GNU General Public License, and as distributed it includes or
** is derivative of works licensed under the GNU General Public License or
** other free or open source software licenses.
**
** THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
** KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
** IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
** PARTICULAR PURPOSE.

** <author>Joomlaproffs / Virtuemart team</author>
** <email>info@joomlaproffs.se</email>
** <date>2015</date>
*/
defined('_JEXEC') or die('Restricted access');

$plugin=JPluginHelper::getPlugin('system','vmuikit_onepage');
$params=new JRegistry($plugin->params);

if(VmConfig::get('oncheckout_show_legal_info',1))
{
  ?>
	  <div id="full-tos" class="opg-modal">
		  <div class="opg-modal-dialog opg-text-left">
	        <a class="opg-modal-close opg-close"></a>
				<strong><?php echo JText::_('COM_VIRTUEMART_CART_TOS'); ?></strong>
			<?php echo $this->cart->vendor->vendor_terms_of_service;?>
		  </div>
	 </div>
  <?php
}
?>

<div id="shiptopopup" class="opg-modal"><!-- Shipto Modal Started -->
	 <div class="opg-modal-dialog"><!-- Shipto Modal Started -->
		<a class="opg-modal-close opg-close"></a>
    	   <div class="opg-modal-header"><strong><?php echo JText::_('PLG_SYSTEM_VMUIKIT_CHANGE_SHIP_ADDRESS_HEADING'); ?></strong></div>
      <label class="opg-text-small opg-hidden">
	  <?php 
	    $samebt = "";
		if($this->cart->STsameAsBT == 0)
		{
			$samebt = '';
			$shiptodisplay = "";
			
		}
	    else if($params->get('check_shipto_address') == 1)
		{
			$samebt = 'checked="checked"';
			$shiptodisplay = "";
		}
		else
		{
		   $samebt = '';
		   $shiptodisplay = "";
		}
	  ?> 
      <input class="inputbox opg-hidden" type="checkbox" name="STsameAsBT" checked="checked" id="STsameAsBT" value="1"/>
	  
	  <?php
		if(!empty($this->cart->STaddress['fields'])){
			if(!class_exists('VmHtml'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'html.php');
				echo JText::_('COM_VIRTUEMART_USER_FORM_ST_SAME_AS_BT');
		?>
		</label>
      <?php
		}
 		?>

    <?php if(!isset($this->cart->lists['current_id'])) $this->cart->lists['current_id'] = 0; ?>
    <?php
		echo '	<div class="adminform" id="shipto_fields_div" style="'.$shiptodisplay.'">';
		foreach($this->cart->STaddress["fields"] as $singlefield) {
		 echo '<div class="opg-width-1-1 opg-margin-small">';
	     if($singlefield['type'] == "select")
	      {		
		    echo '<label class="' . $singlefield['name'] . '" for="' . $singlefield['name'] . '_field">';
		    echo $singlefield['title'] . ($singlefield['required'] ? ' *' : '');
		    echo '</label><br/>';
		  }
		  else
		  {
		    $singlefield['formcode']=str_replace('<input','<input placeholder="'.$singlefield['title'].'"' ,$singlefield['formcode']);
		  }
	    if($singlefield['name']=='shipto_zip') {
			  $replacetext = 'input onchange="javascript:updateaddress(3);"';
			  $singlefield['formcode']=str_replace('input', $replacetext ,$singlefield['formcode']);
	    } 
		else if($singlefield['name']=='customer_note') {
		}
		else if($singlefield['name']=='shipto_virtuemart_country_id') {
		    	$singlefield['formcode']=str_replace('<select','<select onchange="javascript:updateaddress(1);"',$singlefield['formcode']);
		    	$singlefield['formcode']=str_replace('class="virtuemart_country_id','class="shipto_virtuemart_country_id',$singlefield['formcode']);
				$singlefield['formcode']=str_replace('vm-chzn-select','',$singlefield['formcode']);

    	}else if($singlefield['name']=='shipto_virtuemart_state_id') {
	    	$singlefield['formcode']=str_replace('id="virtuemart_state_id"','id="shipto_virtuemart_state_id"',$singlefield['formcode']);
	        $replacetext = '<select onchange="javascript:updateaddress(2);"';
	    	$singlefield['formcode']=str_replace('<select',$replacetext,$singlefield['formcode']);
			if($singlefield['required'])
			{
				  $singlefield['formcode']=str_replace('vm-chzn-select','required',$singlefield['formcode']);
			}
			else
			{
			   $singlefield['formcode']=str_replace('vm-chzn-select','',$singlefield['formcode']);
			} 
	    }
	    echo $singlefield['formcode'];
		echo '</div>';
	}	
    echo '</div>';
	?>
	  <div class="opg-modal-footer">
	  	 <a class="opg-button opg-button-primary" href="Javascript:void(0);" onclick="validateshipto();"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_SUBMIT"); ?></a>
		 <a id="shiptoclose" class="opg-modal-close opg-button"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CANCEL"); ?></a>
		 
		 <a id="shiptoclose" onclick="removeshipto();" class="opg-modal-close opg-margin-left opg-button opg-button-danger"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_REMOVE_SHIPTO"); ?></a>
	  </div>
    </div> <!-- Shipto Modal ended -->
</div><!-- Shipto Modal ended -->

<?php
 foreach($this->cart->BTaddress["fields"] as $singlefield) 
  {
     if($singlefield['name']=='customer_note') 
	 {
	 ?>

 <div id="commentpopup" class="opg-modal"><!-- Comment Modal Started -->
	 <div class="opg-modal-dialog"><!-- Comment Modal Started -->
		<a class="opg-modal-close opg-close"></a>
    	   <div class="opg-modal-header"><strong><?php echo JText::_('COM_VIRTUEMART_COMMENT_CART'); ?></strong></div>
		   <div id="extracomments" class="customer-comments">
		   <?php
			   if($singlefield['required'])
			   {
			     $tmptext = "";
				$tmptext = str_replace("<textarea", '<textarea onblur="javascript:updatecustomernote(this);" ', $singlefield['formcode']);
				 $tmptext = str_replace("<textarea", '<textarea class="required"', $tmptext);
				 echo $tmptext;

			   }
			   else
			   {
			    	echo str_replace("<textarea", '<textarea onblur="javascript:updatecart();" ', $singlefield['formcode']);
			   }
			   ?>
		   </div>
		   <div class="opg-modal-footer">
	  			 <a class="opg-button opg-button-primary" href="Javascript:void(0);" onclick="validatecomment();">Submit</a>
				 <a id="commentclose" class="opg-modal-close opg-button">Cancel</a>
		   </div>
    </div> <!-- comments Modal ended -->
	</div><!-- comments Modal ended -->
<?php
   }
}
?>
<!-- SHIPMENT SELECT MODAL START -->
<?php
				  echo '<div id="shipmentdiv" class="opg-modal">';
				   echo '<div class="opg-modal-dialog">';
				    echo '<a class="opg-modal-close opg-close"></a>';
				     echo '<div class="opg-modal-header">Select Shipment Method</div>';
				      echo "<fieldset id='shipment_selection'>";					
					   echo '<ul class="opg-list" id="shipment_ul">';
						foreach($this->shipments_shipment_rates as $rates) 
						{
						     if(strpos($rates, "checked") !== false)
							 {
							   $actclass = "liselcted";
							 }
							 else
							 {
							   $actclass = "";
							 }
						     echo '<li class="'.$actclass.'">';
							 echo '<label class="opg-width-1-1">'.$rates.'</label>';
							 echo '</li><hr class="opg-margin-small-bottom opg-margin-small-top" />';
						}
					echo "</ul>";
					echo "</fieldset>";
					
			
				?>
				<div class="opg-modal-footer">
				<a class="opg-button opg-button-primary" id="shipmentset"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_SUBMIT"); ?></a>
				<a id="shipmentclose" class="opg-modal-close opg-button"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CANCEL"); ?></a>
				</div>
				<?php
				echo '</div>';
				echo '</div>';
				?>
<!-- SHHIPMENT SELECT MODAL ENDS -->
<!-- PAYMENT SELECT MODAL STARTS -->
<?php
			 
				 echo '<div id="paymentdiv" class="opg-modal">';
				   echo '<div class="opg-modal-dialog">';
				    echo '<a class="opg-modal-close opg-close"></a>';
				      echo '<div class="opg-modal-header">Select Payment Method</div>';
				  	  $paymentsarr = $this->paymentplugins_paymentsnew;
					   echo '<div id="paymentsdiv">';
						echo '<ul class="opg-list" id="payment_ul">';
							foreach($paymentsarr as $pay)
							{
							  echo '<li>'.$pay.'<hr class="opg-margin-small-bottom opg-margin-small-top" /></li>';
							}
						echo '</ul>';
					  echo '</div>';

					
				?>
				
				<div class="opg-modal-footer">
				<a class="opg-button opg-button-primary" id="paymentset"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_SUBMIT"); ?></a>
				<a id="paymentclose" class="opg-modal-close opg-button"><?php echo JText::_("PLG_SYSTEM_VMUIKIT_ONEPAGE_CANCEL"); ?></a>
				</div>
				<?php
				echo '</div>';
				echo '</div>';
   ?>
 <!-- PAYMENT SELECT MODAL ENDS -->