<?php  // <~ don't add me in

add_shortcode ('woo_cart_but', 'woo_cart_but' );
/**
 * Create Shortcode for WooCommerce Cart Menu Item
 */
function woo_cart_but() {
	ob_start();
 
        $cart_count = WC()->cart->cart_contents_count; // Set variable for cart item count
        $cart_url = wc_get_cart_url();  // Set Cart URL
  
        ?>
        <li><a class="menu-item cart-contents" href="<?php echo $cart_url; ?>" title="My Basket">
	    <?php
        if ( $cart_count > 0 ) {
       ?>
            <span class="cart-contents-count"><?php echo $cart_count; ?></span>
        <?php
        }
        ?>
        </a></li>
        <?php
	        
    return ob_get_clean();
 
}