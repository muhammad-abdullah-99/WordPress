SOME USEFULL SCRIPTS & FUNCTIONS :



// Hide Show Menu according device width


jQuery('.logged-in .menu-item-4007 a span').html('My Account')

if(jQuery(window).width() >= 1024 )
 {
     jQuery('#menu-item-4007, #menu-item-4004').css('display', 'none')
 }




// Click on element and trigger another element

jQuery('.product_type_variable').click(function(e){
    e.preventDefault()
    jQuery(this).parents('.product-grid-item ').find('.quick-view-button').trigger('click')
})



// Change add to cart text on product archives page - PHP HOOK

add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_add_to_cart_button_text_archives' );  
function woocommerce_add_to_cart_button_text_archives() {
    return __( 'SHOP NOW', 'woocommerce' );
}



// Redirect when click on product single page add to cart - PHP HOOK

function redirect_to_product_page() {
    global $woocommerce;
    $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
    $url = get_permalink( $product_id );
    return $url;
}
add_filter( 'add_to_cart_redirect', 'redirect_to_product_page' );




// Show Username when user logged in - PHP HOOK

function show_username() {
	
	global $current_user; wp_get_current_user();

	if ( is_user_logged_in() ) { 
		$user = wp_get_current_user();
// 		echo 'Username: ' . $user->first_name; 
		echo 'Welcome back: ' . '<span>' .$user->first_name." ".$user->last_name . '</span>'; 
	} 
}
add_shortcode( 'show_username', 'show_username');



// Adding extra checkout form fields

function wooc_extra_register_fields() {?>
<p></p>
<p class="form-row form-row-first">
	<label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>
	<input type="text" class="input-text" name="first_name" id="first_name" value="<?php if ( ! empty( $_POST['first_name'] ) ) esc_attr_e( $_POST['first_name'] ); ?>" />
</p>
<p class="form-row form-row-last">
	<label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>
	<input type="text" class="input-text" name="last_name" id="last_name" value="<?php if ( ! empty( $_POST['last_name'] ) ) esc_attr_e( $_POST['last_name'] ); ?>" />
</p>
<p class="form-row form-row-wide">
	<label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
	<input type="text" class="input-text" name="phone" id="phone" value="<?php esc_attr_e( $_POST['phone'] ); ?>" />
</p>
<div class="clear"></div>
<?php
									  }
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );




// Saving all that fields Data to the DATABASE - Customer Meta data Tabel..


add_action( 'woocommerce_created_customer', 'save_extra_fields_to_customer_meta', 10, 3 );
function save_extra_fields_to_customer_meta( $customer_id, $new_customer_data, $password_generated ) {
    if ( isset( $_POST['first_name'] ) ) {
        update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
    }
	
	 if ( isset( $_POST['last_name'] ) ) {
        update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
    }
	
	 if ( isset( $_POST['phone'] ) ) {
        update_user_meta( $customer_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
    }
}



// Hide all Shipping method If Local PickUp Selected

    function hide_shipping_when_free_is_available( $rates, $package ) {
    	$onlyLocal = array();
    	 //Save local pickup if it's present.
    	 foreach ( $rates as $rate_id => $rate ) {
    	 	 if ('local_pickup' === $rate->method_id ) {
    	 	 	 $onlyLocal[ $rate_id ] = $rate;
    	 	 	 break;
    	 	 }
    	 }
    	 
    	 $withOutLocal = $rates;
    	 foreach ( $withOutLocal as $rate_id => $rate ) {
    	     if ('local_pickup' === $rate->method_id ) {
    	 	 	   unset( $withOutLocal[ $rate_id ] );
    	 	 	   break;
    	     }
        }
    	 
		 $finalCost = $onlyLocal;
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
           
            $product = $cart_item['data'];
      
            if( isset($product->attributes['pa_select-delivery-option'])  && $product->attributes['pa_select-delivery-option'] == "delivery"){
                $finalCost = $withOutLocal;
            //     foreach ( $rates as $rate_id => $rate ) {
            // 	 	 if ('local_pickup' === $rate->method_id ) {
            // 	 	 	   unset( $rates[ $rate_id ] );
            // 	 	 	   break;
            // 	 	 }
            // 	 	 $finalCost = $rates;
            //     }
                
            } elseif(!isset($product->attributes['pa_select-delivery-option'])) {
               
            	 $finalCost = $withOutLocal;
            }
        }
    
    	 return $finalCost;
    }

    add_filter( 'woocommerce_package_rates', 'hide_shipping_when_free_is_available', 10, 2 );



//Disable shipping rates if the cart has both Local Pickup & Delivery based product, But shipping charges only applied for delivery products


function filter_woocommerce_cart_shipping_packages( $package ) { 
    
    $new_cart = [];
    global $woocommerce;
    foreach($woocommerce->cart->get_cart() as $cart_item) {
       
      // check for desired shipping method
      // cart items not checking for this property, will not be accounted for shipping costs
   
         if( isset($cart_item['variation']['attribute_pa_select-delivery-option'])  && $cart_item['variation']['attribute_pa_select-delivery-option'] == "delivery"){
            //  print_r( $cart_item['variation']['attribute_pa_select-delivery-option'] ); 
         array_push($new_cart, $cart_item); 
      } 
    }

    if(!empty($new_cart)) $package[0]['contents'] = $new_cart;

    return $package; 
}; 
         
// add the filter 
add_filter( 'woocommerce_cart_shipping_packages', 'filter_woocommerce_cart_shipping_packages', 10, 1 ); 




// Change Add to Cart Text to ADDED with Check Mark when user clicked on Add to Cart


function change_add_to_cart_button_text_ajax() {
    ?>
    <script type="text/javascript">
        jQuery(function($) {
            // Update button text on "added_to_cart" event
            $(document).on('added_to_cart', function(event, fragments, cart_hash, button) {
                var buttonText = '✓ Added to Cart'; // Replace with your desired button text
                
                $(button).text(buttonText);
                localStorage.setItem('add_to_cart_button_text', buttonText);
            });

            // Revert button text on "removed_from_cart" event
            $(document).on('removed_from_cart', function(event, fragments, cart_hash, button) {
                var buttonText = 'Add to Cart'; // Replace with the original button text
                
                $(button).text(buttonText);
                localStorage.setItem('add_to_cart_button_text', buttonText);
            });

            // Retrieve and update button text on page load
            var storedButtonText = localStorage.getItem('add_to_cart_button_text');
            if (storedButtonText) {
                $('.single_add_to_cart_button').text(storedButtonText);
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'change_add_to_cart_button_text_ajax');






