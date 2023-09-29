<?php get_header(); 
global $nokri; 
$url 		= home_url();
$notfound 	= '';
$btnText 	= isset($nokri['404-btn-text']) 	? $nokri['404-btn-text'] : esc_html__("Home", "nokri");
$btnText 	= isset($nokri['404-btn-text']) 	? $nokri['404-btn-text'] : esc_html__("Home", "nokri");
$heading 	= isset($nokri['404-heading']) 		? $nokri['404-heading']  : 404;
$desc 		= isset($nokri['404-text-area']) 	? $nokri['404-text-area'] : esc_html__("Sorry, the page you are for is not exit.", "nokri");
$eror_msg 	= isset($nokri['404-text']) 		? $nokri['404-text'] :  esc_html__("0oops Page Not Found", "nokri");
/*Background*/
/* search section bg */ 
$section_bg_url = '';
 if ( isset( $nokri['404_bg_img'] ) )
{
	$section_bg_url = nokri_getBGStyle('404_bg_img');
}
?>

<section class="n-job-pages-section">
  	<div class="container">
    	<div class="row">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="n-job-pages error-page-section">
                    <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-2  col-sm-offset-2">
                     	<div class="error-404">
                        	<h2><?php echo esc_html($heading); ?> </h2>
                            <h4><?php echo esc_html($eror_msg); ?></h4>
                            <p><?php echo esc_html($desc); ?></p>
                            <a style="display: inline-block;" href="<?php echo esc_url($url); ?>" class="bluebutton n-btn-flat"><?php echo esc_html($btnText); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>
<?php get_footer();