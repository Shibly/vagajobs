<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KB9Q6LR');</script>
<!-- End Google Tag Manager -->
    
<?php
global $nokri;

		//update_post_meta( $pid, '_job_level', $job_level_id);
/* Linkedin response after logged in */
//include( 'template-parts/linkedin-access.php' );
/*Language Switcher*/
$language_switcher = isset($nokri['nokri_lang_switch_frnt']) ? $nokri['nokri_lang_switch_frnt'] : '0';
$custom_social  = isset($nokri['social_media_logo_switch']) ? $nokri['social_media_logo_switch'] : '' ;
$social_logo      = isset($nokri['social_media_share_logo']['url'])  ?   $nokri['social_media_share_logo']['url'] :  '' ;

?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<?php if($custom_social  &&  $social_logo != ''){
    
    echo '<meta property="og:image" content="'.esc_url($social_logo).'">';
    
}?>
    

    
    <?php 
$directoryURI = $_SERVER['REQUEST_URI'];
$path = parse_url($directoryURI, PHP_URL_PATH);
$components = explode('/', $path);
$first_part = $components[1];
?>
    
<?php 
if( isset( $nokri['banners_code'] ) && $nokri['banners_code'] != '')
{
	echo ($nokri['banners_code']);
}
/* Search Page */
$search_page_layout = '';
if((isset($nokri['search_page_layout'])) && $nokri['search_page_layout']  != '' )
{
 	$search_page_layout =  ($nokri['search_page_layout']);
}
?>

    <!--    CUSTOM FILES    -->
<?php include 'cart-shortcode.php';?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!--    END CUSTOM FILES    -->  
    
 <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
 <?php
 if ( ! ( function_exists( 'has_site_icon' ) && has_site_icon() ) ) { ?>
<link rel="shortcut icon" href="
<?php echo get_template_directory_uri(); ?>/images/favicon.ico" type="image/x-icon">
 <?php  } 
 wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KB9Q6LR"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    
<?php
/* header style  */
$header_style = '';
if((isset($nokri['main_header_style'])) && $nokri['main_header_style']  != '' )
{
	 $header_style =  ($nokri['main_header_style']);
}
/*Topbar style*/
$top_bar_style = isset($nokri['header_top_bar_style']) ? $nokri['header_top_bar_style'] : '1';
 if((isset($nokri['loader_img_switch'])) && $nokri['loader_img_switch']  == 1 ) { 
$loader_text = ( isset($nokri['loader_text']) && $nokri['loader_text'] != ""  ) ? $nokri['loader_text'] : '';
/* Profile Pic  */
$preldr_link[0] =  get_template_directory_uri(). '/images/loader.gif';
if( isset( $nokri['loader_img']['url'] ) && $nokri['loader_img']['url'] != "" )
{
	$preldr_link = array($nokri['loader_img']['url']);	
}
?>
<div id="spinner">
    <div class="spinner-img"><img alt="<?php echo esc_html__( 'Preloader', 'nokri' ); ?>" src="<?php echo  esc_url($preldr_link[0]); ?>"/>
      <h2><?php echo ($loader_text ); ?></h2>
    </div>
  </div>
<?php
 }
$is_map = false;
if(wp_basename(get_page_template()) == 'page-search.php' &&  $search_page_layout == '3')
{
	$is_map = true;
}
if(basename(get_page_template()) == 'page-dashboard.php' || $is_map)
{
echo '<div class="navbar-fixed-top">'; 
	if ($header_style == '1' && isset($nokri['header_top_bar']) && $nokri['header_top_bar']  == 1)
	{
		get_template_part( 'template-parts/layouts/top-bars/topbar', $top_bar_style );
	}
} 

 if((isset($nokri['header_top_bar'])) && $nokri['header_top_bar']  == 1  && $header_style != '1')
{
     
	get_template_part( 'template-parts/layouts/top-bars/topbar', $top_bar_style );
}  
if($header_style == '2')
{
	get_template_part( 'template-parts/headers/header', '2' );
}
else
{
	$header = '1';
	get_template_part( 'template-parts/headers/header', $header );
}
if($language_switcher) {
echo nokri_language_switcher();}