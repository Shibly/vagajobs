<?php
/* ------------------------------------------------ */
/* Blog Posts */
/* ------------------------------------------------ */

function blog_posts_modern_short()
{
	vc_map(array(
		"name" => esc_html__("Blog Posts Modern", 'nokri') ,
		"base" => "blog_posts_base",
		"category" => esc_html__("Theme Shortcodes", 'nokri') ,
		"params" => array(
		array(
		   'group' => esc_html__( 'Shortcode Output', 'nokri' ),  
		   'type' => 'custom_markup',
		   'heading' => esc_html__( 'Shortcode Output', 'nokri' ),
		   'param_name' => 'order_field_key',
		   'description' => nokri_VCImage('nokri_blog_posts_modern.png') . esc_html__( 'Ouput of the shortcode will be look like this.', 'nokri' ),
		  ),
		array(
		"group" => esc_html__("Basic", "nokri"),
		"type" => "dropdown",
		"heading" => esc_html__("Select Posts color", 'nokri') ,
		"param_name" => "blog_posts_clr",
		"admin_label" => true,
		"value" => array( 
		esc_html__('Select Option', 'nokri') => '', 
		esc_html__('White', 'nokri') =>'',
		esc_html__('Gray', 'nokri') =>'light-grey',
		),
		),
		array(
		"group" => esc_html__("Basic", "nokri"),
		"type" => "textfield",
		"holder" => "div",
		"class" => "",
		"heading" => esc_html__( "Section Heading", 'nokri' ),
		"param_name" => "section_title",
		),
		array(
		"group" => esc_html__("Basic", "nokri"),
		"type" => "textarea",
		"holder" => "div",
		"class" => "",
		"heading" => esc_html__( "Section Details", 'nokri' ),
		"param_name" => "section_description",
		),
		array(
		"group" => esc_html__("Post Options", "nokri"),
		"type" => "dropdown",
		"heading" => esc_html__("Number Of Post To Show", 'nokri') ,
		"param_name" => "blog_posts_no",
		"admin_label" => true,
		"value" => range( 1, 50 ),
		),
		array(
		"group" => esc_html__("Post Options", "nokri"),
		"type" => "dropdown",
		"heading" => esc_html__("Number of words in title", 'nokri') ,
		"param_name" => "blog_posts_title_no",
		"admin_label" => true,
		"value" => range( 1, 50 ),
		),
		array(
		"group" => esc_html__("Post Options", "nokri"),
		"type" => "dropdown",
		"heading" => esc_html__("Select Posts Order", 'nokri') ,
		"param_name" => "blog_posts_order",
		"admin_label" => true,
		"value" => array( 
		esc_html__('Select Option', 'nokri') => '', 
		esc_html__('ASCENDING', 'nokri') =>'ASC',
		esc_html__('DESCENDING', 'nokri') =>'DESC',
		),
		),
		array
		(
		'group' => esc_html__( 'Select Categories', 'nokri' ),
		'type' => 'param_group',
		'heading' => esc_html__( 'Add Category', 'nokri' ),
		'param_name' => 'blog_posts',
		'value' => '',
		'params' => array
		(
				 array(
				 "type" => "dropdown",
				 "heading" => esc_html__("Category", 'nokri') ,
				 "param_name" => "categories",
				 "admin_label" => true,
				 "value" => nokri_get_parests('category','yes'),
    			),
   		    )
 			),
	),
	));
}
add_action('vc_before_init', 'blog_posts_modern_short');
function blog_posts_modern_short_base_func($atts, $content = '')
{
	require trailingslashit( get_template_directory () ) . "inc/theme-shortcodes/shortcodes/layouts/header_layout.php";
	extract(shortcode_atts(array(
		'order_field_key'   => '',
		'blog_posts_clr' => '',
		'blog_posts_order' => '',
		'blog_posts' => '',   
		'blog_posts_no' => '', 
		'blog_posts_title_no' => '',
	) , $atts));
	if(isset($atts['blog_posts']) && $atts['blog_posts'] != '')
	{
		$rows = vc_param_group_parse_atts( $atts['blog_posts'] );
		$cats_arr = array();
		if( count((array)  $rows ) > 0) 
		{
		foreach($rows as $row )
			{
				$cats_arr[] = $row['categories'];
			}
		}
	}
        
        $cat_tax = '';
        $all_tax  = isset($cats_arr[0]) ? $cats_arr[0] : '';
       
        if(!empty($cats_arr) && $all_tax != "all"){
            
         $cat_tax =     array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => $cats_arr,
        );
        }
        
       
       
/*Post Numbers*/
$section_post_no = (isset($blog_posts_no) && $blog_posts_no != "") ? $blog_posts_no : "6";	
/*Post Orders */
$section_post_ordr = (isset($blog_posts_order) && $blog_posts_order != "") ? $blog_posts_order : "ASC";	
	$args = array(
	'posts_per_page' => $section_post_no, 
    'post_type' => 'post',
	'order' => $section_post_ordr,
    'tax_query' => array(
      $cat_tax
    ),
);
$the_query = new WP_Query( $args ); 
$blogs_html = '';
 if ( $the_query->have_posts() ) 
 {
  $num = 1;
  while ($the_query->have_posts())
   { 
		$the_query->the_post();
		$pid       = get_the_ID();
		$author_id = get_post_field( 'post_author', $pid );
		/* Post Title Limit */
		$blog_posts_title_limit= "3";
		if(isset($blog_posts_title_no) && $blog_posts_title_no  != "")
		{
			$blog_posts_title_limit = $blog_posts_title_no;
		}
		$thumb_html = '';
		if ( has_post_thumbnail() ) 
		{
			$thumb_html = '<div class="nth-latest-product">
									<a href="'. esc_url(get_the_permalink($pid)).'"> '. get_the_post_thumbnail($pid,'nokri_post_standard',array('class'=>'img-responsive') ).' </a>
								</div>';
		}
						$blogs_html .= '<div class="col-lg-4 col-sm-6 col-md-4 col-xs-12">
											<div class="nth-latest-content">
												'.$thumb_html.'
												<div class="nth-latest-box">
													<div class="nth-latest-cb">
														<div class="nth-latest-details">
															<a href="'. esc_url(get_the_permalink($pid)).'">
																<h4>'.get_the_title($pid).'</h4>
															</a>
														</div>
														<div class="nth-latest-in">
															<ul>
																<li><i class="fa fa-comments"></i><span>'.get_comments_number($pid).'</span>
																</li>
																<li><i class="fa fa-calendar"></i><span>'. get_the_time(get_option( 'date_format' )).'</span>
																</li>
															</ul>
														</div>
													</div>
													<div class="nth-latest-blog">
														<div class="nth-latest-profile">
														'.get_avatar( $the_query->post_author, $size = '45', $default = '', $alt= '', array( 'class' => array( 'img-responsive' ) )).' <span class="nth-style-0">'.get_the_author_meta('display_name', $author_id).'</span> 
														</div>
														<div class="nth-latest-jobs"> <i class="fa fa-long-arrow-right" aria-hidden="true"></i> 
														</div>
													</div>
												</div>
											</div>
										</div>';
								 //if($num % 3 == 0){$blogs_html .= '<div class="clearfix"></div>';}
								 $num++;
	}
 			wp_reset_postdata(); 
 }	
 /*Section Color */
$section_clr = (isset($blog_posts_clr) && $blog_posts_clr != "") ? $blog_posts_clr : "";
return '<section class="nth-latest-update nth-latest2 nth-latest3" '.esc_attr($section_clr).'>
			<div class="container">
			<div class="row">
			     '.$header.'
				 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
					 <div class="row">
						'.$blogs_html.'
					</div>
				</div>
				</div>
  			</div>
		</section>';
}
if (function_exists('nokri_add_code'))
{
	nokri_add_code('blog_posts_base', 'blog_posts_modern_short_base_func');
}