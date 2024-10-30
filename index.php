<?php if ( ! defined( 'ABSPATH' ) ) exit; 
/*
	Plugin Name: Instant Search Using Ajax For XT Woo Floating Cart
	Plugin URI:  https://profiles.wordpress.org/fahadmahmood/#content-plugins
	Description: Ajax based add to cart button for the combination of Quick Links - WooCommerce Search Plugin by Instant Search + XT Floating Cart for WooCommerce.
	Version:     1.0.1
	Author:      Fahad Mahmood
	Author URI:  https://profiles.wordpress.org/fahadmahmood/#content-about
	License:     GPL2
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
	Text Domain: instant-search-ajax-for-floating-cart
	Domain Path: /languages
*/
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}else{
		 
	}
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	global $isaffc_data, $isaffc_pro;
	
	$isaffc_premium_copy = 'https://shop.androidbubbles.com/product/instant-search-using-ajax-for-xt-woo-floating-cart';
	$isaffc_data = get_plugin_data(__FILE__);
	
	
	define( 'ISAFFC_PLUGIN_DIR', dirname( __FILE__ ) );
	
	$isaffc_pro_file = ISAFFC_PLUGIN_DIR . '/pro/functions-pro.php';
	$isaffc_pro =  file_exists($isaffc_pro_file);
	
	if(function_exists('isaffc_wp_admin_links')){
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'isaffc_wp_admin_links' );	
	}	
	
	add_action('wp_head', 'isaffc_wp_quick_links_search_bar_extended');
	
	function isaffc_wp_quick_links_search_bar_extended(){
?>		
<style type="text/css">
	.isp_add_to_cart_form {
		position: relative;
		border: 0;
		font-size: 12px;
		transform: translateY(-50%);
		margin: 0 auto;
		display: block;
		float: none;
		text-align: center;
		z-index: 100;
		width: 100%;
		background-color: transparent;
		top: 16px;
	}
	.isp_add_to_cart_btn{ 
		font-size: 14px;
		line-height: 34px;
		border-color: #002c40;
		color: #ededed;
		text-align: center !important;
		margin: 0 auto !important;
		display: block !important;
		width: 50% !important;
		position: relative !important;
		-moz-transition: background-color 0.175s ease-in-out, border-color 0.175s ease-in-out, color 0.175s ease-in-out;
		-o-transition: background-color 0.175s ease-in-out, border-color 0.175s ease-in-out, color 0.175s ease-in-out;
		-webkit-transition: background-color 0.175s ease-in-out, border-color 0.175s ease-in-out, color 0.175s ease-in-out;
		transition: background-color 0.175s ease-in-out, border-color 0.175s ease-in-out, color 0.175s ease-in-out;
		background-color: #002c40;
		padding: 6px 19px;
		cursor: pointer;
		text-decoration: none;
		border-width: 1px;
		transition: all .3s !important;
		-webkit-border-radius: 5px 5px 5px 5px;
		-moz-border-radius: 5px 5px 5px 5px;
		-ms-border-radius: 5px 5px 5px 5px;
		-o-border-radius: 5px 5px 5px 5px;
		border-radius: 5px 5px 5px 5px;
		padding: 0; 
		width: 131px; 
		height: 38px; 
		background-size: 25px; 
		background-position: center; 
		background-repeat: no-repeat;
	}
	.isp_add_to_cart_btn:hover{ 
		background-color: #002c40e8;
	}
	ul.ui-autocomplete li.ui-menu-item{
		position:relative;
		text-align:center;
	}
	
</style>
<script type="text/javascript" language="javascript">
	function isaffc_wp_add_to_cart(event, product_id) {
		event.preventDefault();
		jQuery.blockUI({message:''});
		jQuery('.isp_search_box_input').val('');

		
		jQuery.ajax({
			url: "?wc-ajax=add_to_cart",
			type: "POST",
			data: jQuery('form#form_'+product_id).serialize(),
			beforeSend: function(result) {
				jQuery("#btn_" + product_id).css("background-image", "url(<?php echo ISAFFC_PLUGIN_DIR; ?>/images/progress.gif)");
				jQuery("#btn_" + product_id).attr("value", "");
			},
			success: function(result) {
				jQuery("#btn_" + product_id).css("background-image", "none");
				jQuery("#btn_" + product_id).attr("value", "<?php _e("Add to cart", "instant-search-ajax-for-floating-cart"); ?>");
				
				xt_woofc_refresh_cart();		
				setTimeout(function(){	
					jQuery.unblockUI();
					if(jQuery('ul.ui-autocomplete.neutralized').length>0)
					jQuery('ul.ui-autocomplete.neutralized').show();		
				}, 1000);
				
						
				
			},
			error: function(result) {
			$.unblockUI();
			
			
		}});
	   
		
		event.stopPropagation();
		event.stopImmediatePropagation();
	}
	var __isp_options = {
		isp_dropdown_callback: function(jquery_li_element, item) {
			var product_id = jquery_li_element.attr('isp_id');
			var product_sku = item.sku;
		   
			if (!item.id || item.category || !item.product_url) { return jquery_li_element; }
			var addtocart_html = '<div class="isp_add_to_cart_form"><span class="isp_add_to_cart_btn" data-id="'+product_id+'"><?php _e("Add to cart", "instant-search-ajax-for-floating-cart"); ?></span></div>';		 
			
			
			
			addtocart_html += "<form style='display: none;'" +
			"name='form_" + product_id + "' id='form_" + product_id + "' class='isp_add_to_cart_form  button product_type_simple ajax_add_to_cart' enctype='multipart/form data' method='post'>";
		   
			addtocart_html += "<input name='product_id' type='hidden' value=" + product_id + ">";
			addtocart_html += "<input name='action' type='hidden' value='add'>";
			addtocart_html += "<input name='product_sku' type='hidden' value=" + product_sku + ">";
			addtocart_html += "<input type='hidden' id='quantity_" + product_id + "' name='qty[]' value='1' min='1' autocomplete='off' type='number'>";
			addtocart_html += "<input id='btn_" + product_id+"' onclick='isaffc_wp_add_to_cart(event, "+product_id+")' type='submit' value='<?php _e("Add to cart", "instant-search-ajax-for-floating-cart"); ?>' class='isp_add_to_cart_btn' />";
			addtocart_html += "</form>";
			
			return jquery_li_element.append(addtocart_html);
		},
		isp_dropdown_select_callback: function(event, ui){
			return false;
		},
		isp_dropdown_select_callback: function(event, $jquery_element){
			return false;
		}
	}
	jQuery(document).ready(function($){
		$('body').on('mouseover', 'ul.ui-autocomplete:not(.neutralized) .ui-menu-item[isp_product]', function(event){
			var ul = $(this).parents().closest('ul');
			var prev = $(this).parents().closest('ul').prev();
			$(this).parents().closest('ul').remove();
			$(ul).insertAfter(prev).addClass('neutralized');		
		});
		$('body').on('click', 'span.isp_add_to_cart_btn', function(event){
			event.preventDefault();
			var product_id = $(this).data('id');
			$('#btn_'+product_id).click();
		});
		
	});
</script>

<?php
	}	