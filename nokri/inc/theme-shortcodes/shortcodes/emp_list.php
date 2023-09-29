<?php
/* -------------- */
/* Employer List */
/* ------------*/
if ( !function_exists ( 'emp_list_short' ) ) {
function emp_list_short()
{
	vc_map(array(
		"name" => __("Employer List", 'nokri') ,
		"base" => "emp_list_short_base",
		"category" => __("Theme Shortcodes", 'nokri') ,
		"params" => array(
		array(
		   'group' => __( 'Shortcode Output', 'nokri' ),  
		   'type' => 'custom_markup',
		   'heading' => __( 'Shortcode Output', 'nokri' ),
		   'param_name' => 'order_field_key',
		   'description' => nokri_VCImage('nokri_premium_users.png') . __( 'Ouput of the shortcode will be look like this.', 'nokri' ),
		  ),
		 array(
		"group" => esc_html__("Basic", "nokri"),
		"type" => "dropdown",
		"heading" => esc_html__("Select BG Color", 'nokri') ,
		"param_name" => "employer_bg_clr",
		"admin_label" => true,
		"value" => array( 
		esc_html__('Select Option', 'nokri') => '', 
		esc_html__('Sky BLue', 'nokri') =>'light-grey',
		esc_html__('White', 'nokri') =>'',
		),
		),
		array(
			"group" => esc_html__("Basic", "nokri"),
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__( "Section Title", 'nokri' ),
			"param_name" => "section_title",
		),	
		array(
		'group' => esc_html__( 'Basic', 'nokri' ),
		"type" => "vc_link",
		"heading" => esc_html__( "Link", 'nokri' ),
		"param_name" => "link",
		),
		array
		(
			"group" => esc_html__("Select Employers", "nokri"),
			'type' => 'param_group',
			'heading' => esc_html__( 'Select Employers', 'nokri' ),
			'param_name' => 'employers',
			'value' => '',
			'params' => array
			(
				array(
				"group" => esc_html__(" Select employers", "nokri"),
				"type" => "dropdown",
				"heading" => esc_html__("Select Employers To Show", 'nokri') ,
				"param_name" => "employer",
				"admin_label" => true,
				"value" => nokri_top_employers_lists_shortcodes(),
				),

			)
		),
		
		
		),
	));
}
}
add_action('vc_before_init', 'emp_list_short');
if ( !function_exists ( 'emp_list_short_base_func' ) )
{
function emp_list_short_base_func($atts, $content = '')
{	
extract(shortcode_atts(array( 
		'employer_bg_clr' => '',   
		'section_title' => '',
		'link' => '',
		'employers' => '',
		'order_by' => '',  
	) , $atts));

if(isset($atts['employers']) && $atts['employers'] != '')
{	
	$rows = vc_param_group_parse_atts( $atts['employers'] );
	$stories_html = '';
	$current_user_id 	  = get_current_user_id();
	if( (array)count( $rows ) > 0 )
	{
		foreach($rows as $row ) 
		{
			$employers_array[] = (isset($row['employer']) && $row['employer'] != "") ? $row['employer'] : array();
		}
	}
}
		global $nokri;	
        
	if( count((array)  $employers_array ) > 0 && $employers_array != "" )
		{
			foreach( $employers_array as $key => $value )
			{
				$employers_array[]	=	$value;
			}
		}

        $args2 = array(
            'order' => 'ASC',
             'orderby' => array( 
               'rand',
                'random',
            ) ,
            'role__in' => array('parent_account', 'employer'),
            'meta_query' => array( array(
                    'relation' => 'OR',        
                    array(
                        'key' => '_emp_feature_profile',
                        'compare' => 'EXISTS'
                    )
                ),
            )
        );
        $wp_user_query2 = new WP_User_Query($args2);
        $users2 = $wp_user_query2->get_results();
        $featured_list = array();
        if( ! empty( $users2 ) ) {
            shuffle( $users2 );
            foreach( $users2 as $user2 ) {
                $featured_list[] = $user2->ID;
            }
        }
        
        if( ! empty( $employers_array ) && ! empty( $featured_list ) ) {
            $employers_array = array_unique ( array_values( array_merge ( $employers_array, $featured_list )));
        }

        if( empty( $employers_array ) && ! empty( $featured_list ) ) {
            $employers_array = $featured_list;
        }
        
        shuffle( $employers_array );

		
	/* WP User Query */
		$args 			= 	array (
		'order' 		=> 	'ASC',
		'include'       => $employers_array,
	);
	$user_query = new WP_User_Query($args);	
	$authors = $user_query->get_results();
    shuffle( $authors );
	$required_user_html = '';
	if (!empty($authors))
	{
		$fb_link = $twitter_link = $google_link = $linkedin_link =  $follow_btn = '';
		foreach ($authors as $author)
		{
			$emp_fb    =  $emp_twitter = $emp_google = $emp_linkedin = '';
			$user_id   = $author->ID;
			$user_name = $author->display_name;
			/* Profile Pic  */
			$image_dp_link[0] =  get_template_directory_uri(). '/images/candidate-dp.jpg';
			if( isset( $nokri['nokri_user_dp']['url'] ) && $nokri['nokri_user_dp']['url'] != "" )
			{
				$image_dp_link = array($nokri['nokri_user_dp']['url']);	
			}
			if(get_user_meta($user_id, '_sb_user_pic', true ) != '')
			{
				$attach_dp_id     =  get_user_meta($user_id, '_sb_user_pic', true );
				$image_dp_link    =  wp_get_attachment_image_src( $attach_dp_id, '' );
			}
			$user_post_count = count_user_posts( $user_id , 'job_post' );
			$user_post_count_html = '<span class="job-openings">'.$user_post_count." ".esc_html__( 'Openings', 'nokri' ).'</span>';
			$emp_address   = get_user_meta($user_id, '_emp_map_location', true);
				
            $rtl_class = $bg_url = '';
                $cover_pic  =   get_user_meta($user_id,'_sb_user_cover',true);
                if($cover_pic != ""){  
                $bg_url    =   nokri_user_cover_bg_url($cover_pic);  
                } else {
                $bg_url = nokri_section_bg_url()    ;
                }
            
            /* Getting Employer Skills  */
                                                    $emp_skills = get_user_meta($user_id, '_emp_skills', true);
                                                    $skill_tags = '';
                                                    $employer_search_page = $nokri['employer_search_page'];

                                                    if ((array) $emp_skills && $emp_skills > 0) {
                                                        $taxonomies = get_terms('emp_specialization', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                        if (count((array) $taxonomies) > 0) {
                                                            foreach ($taxonomies as $taxonomy) {
                                                                $link = get_the_permalink($employer_search_page) . "?emp_skills=" . $taxonomy->term_id;
                                                                if (in_array($taxonomy->term_id, $emp_skills))
                                                                    $skill_tags .= '<a href="' . esc_url($link) . '" class="skills_tags">' . esc_html($taxonomy->name) . '</a>';
                                                            }
                                                        }
                                                    }
                                                    /* Getting Employer Housing  */
                                                    $emp_housing = get_user_meta($user_id, '_emp_housing', true);
                                                    $housing_tags = '';
                                                    $employer_search_page = $nokri['employer_search_page'];

                                                    if ((array) $emp_housing && $emp_housing > 0) {
                                                        $taxonomies = get_terms('emp_specialization_housing', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                        if (count((array) $taxonomies) > 0) {
                                                            foreach ($taxonomies as $taxonomy) {
                                                                $link = get_the_permalink($employer_search_page) . "?emp_housing=" . $taxonomy->term_id;
                                                                if (in_array($taxonomy->term_id, $emp_housing))
                                                                    $housing_tags .= '<a href="' . esc_url($link) . '" class="category_tags">' . esc_html($taxonomy->name) . '</a>';
                                                            }
                                                        }
                                                    }
                                                    
                                                    /* Getting Employer Pets Allowed  */
                                                    $emp_pets = get_user_meta($user_id, '_emp_pets', true);
                                                    $pets_tags = '';
                                                    $employer_search_page = $nokri['employer_search_page'];

                                                    if ((array) $emp_pets && $emp_pets > 0) {
                                                        $taxonomies = get_terms('emp_specialization_pets', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                        if (count((array) $taxonomies) > 0) {
                                                            foreach ($taxonomies as $taxonomy) {
                                                                $link = get_the_permalink($employer_search_page) . "?emp_pets=" . $taxonomy->term_id;
                                                                if (in_array($taxonomy->term_id, $emp_pets))
                                                                    $pets_tags .= '<a href="' . esc_url($link) . '" class="category_tags">' . esc_html($taxonomy->name) . '</a>';
                                                            }
                                                        }
                                                    }

                                                    /* Getting Employer Meal Plan  */
                                                    $emp_meal = get_user_meta($user_id, '_emp_meal', true);
                                                    $meal_tags = '';
                                                    $employer_search_page = $nokri['employer_search_page'];

                                                    if ((array) $emp_meal && $emp_meal > 0) {
                                                        $taxonomies = get_terms('emp_specialization_meal', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                        if (count((array) $taxonomies) > 0) {
                                                            foreach ($taxonomies as $taxonomy) {
                                                                $link = get_the_permalink($employer_search_page) . "?emp_meal=" . $taxonomy->term_id;
                                                                if (in_array($taxonomy->term_id, $emp_meal))
                                                                    $meal_tags .= '<a href="' . esc_url($link) . '" class="category_tags">' . esc_html($taxonomy->name) . '</a>';
                                                            }
                                                        }
                                                    }

                                                    /* Getting Employer Camping Available  */
                                                    $emp_camping = get_user_meta($user_id, '_emp_camping', true);
                                                    $camping_tags = '';
                                                    $employer_search_page = $nokri['employer_search_page'];

                                                    if ((array) $emp_camping && $emp_camping > 0) {
                                                        $taxonomies = get_terms('emp_specialization_camping', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                        if (count((array) $taxonomies) > 0) {
                                                            foreach ($taxonomies as $taxonomy) {
                                                                $link = get_the_permalink($employer_search_page) . "?emp_camping=" . $taxonomy->term_id;
                                                                if (in_array($taxonomy->term_id, $emp_camping))
                                                                    $camping_tags .= '<a href="' . esc_url($link) . '" class="category_tags">' . esc_html($taxonomy->name) . '</a>';
                                                            }
                                                        }
                                                    }

                                                    /* Getting Employer Wifi Available  */
                                                    $emp_wifi = get_user_meta($user_id, '_emp_wifi', true);
                                                    $wifi_tags = '';
                                                    $employer_search_page = $nokri['employer_search_page'];

                                                    if ((array) $emp_wifi && $emp_wifi > 0) {
                                                        $taxonomies = get_terms('emp_specialization_wifi', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                        if (count((array) $taxonomies) > 0) {
                                                            foreach ($taxonomies as $taxonomy) {
                                                                $link = get_the_permalink($employer_search_page) . "?emp_wifi=" . $taxonomy->term_id;
                                                                if (in_array($taxonomy->term_id, $emp_wifi))
                                                                    $wifi_tags .= '<a href="' . esc_url($link) . '" class="category_tags">' . esc_html($taxonomy->name) . '</a>';
                                                            }
                                                        }
                                                    }

                                                    /* Getting Employer Cell Service  */
                                                    $emp_cell = get_user_meta($user_id, '_emp_cell', true);
                                                    $cell_tags = '';
                                                    $employer_search_page = $nokri['employer_search_page'];

                                                    if ((array) $emp_cell && $emp_cell > 0) {
                                                        $taxonomies = get_terms('emp_specialization_cell', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                        if (count((array) $taxonomies) > 0) {
                                                            foreach ($taxonomies as $taxonomy) {
                                                                $link = get_the_permalink($employer_search_page) . "?emp_cell=" . $taxonomy->term_id;
                                                                if (in_array($taxonomy->term_id, $emp_cell))
                                                                    $cell_tags .= '<a href="' . esc_url($link) . '" class="category_tags">' . esc_html($taxonomy->name) . '</a>';
                                                            }
                                                        }
                                                    }
                                                    
                                                    /* Getting Employer Additional States  */
                                                    $emp_states = get_user_meta($user_id, '_emp_states', true);
                                                    $states_tags = '';
                                                    $employer_search_page = $nokri['employer_search_page'];

                                                    if ((array) $emp_states && $emp_states > 0) {
                                                        $taxonomies = get_terms('emp_specialization_states', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                        if (count((array) $taxonomies) > 0) {
                                                            foreach ($taxonomies as $taxonomy) {
                                                                $link = get_the_permalink($employer_search_page) . "?emp_states=" . $taxonomy->term_id; 
                                                                if (in_array($taxonomy->term_id, $emp_states))
                                                                    $states_tags .= '<a href="' . esc_url($link) . '" class="states_tags">' . esc_html($taxonomy->name) . '</a>';
                                                            }
                                                        }
                                                    }
            
            /* Social links */
				$emp_fb        = get_user_meta($user_id, '_emp_fb', true);
				$emp_twitter    = get_user_meta($user_id, '_emp_twiter', true);
				$emp_google    = get_user_meta($user_id, '_emp_google', true);
				$emp_linkedin    = get_user_meta($user_id, '_emp_linked', true);
				 if($emp_fb)
				 {
					 $fb_link = '<li><a target="_blank" href="'. esc_url($emp_fb).'"><i class="fa fa-facebook"></i></a></li>';
				 }
				 if($emp_twitter)
				 {
					 $twitter_link = '<li><a target="_blank" href="'. esc_url($emp_twitter).'"><i class="fa fa-twitter"></i></a></li>';
				 }
				  if($emp_google)
				 {
					 $google_link = '<li><a target="_blank" href="'. esc_url($emp_google).'"><i class="fa fa-instagram"></i></a></li>';
				 }
				 if($emp_linkedin)
				 {
					 $linkedin_link = '<li><a target="_blank" href="'. esc_url($emp_linkedin).'"><i class="fa fa-linkedin"></i></a></li>';
				 }
				/* Social links */
				$adress_html = '';
				if($emp_address)
				{
					$adress_html = '<p><i class="fa fa-map-marker"></i>'.$emp_address.'</p>';
				}
				    /* follow company */
				    if(get_user_meta($current_user_id, '_sb_reg_type', true) == 0)
					 { 
						$comp_follow = get_user_meta( $current_user_id, '_cand_follow_company_'.$user_id,true);
					 	if ( $comp_follow ) 
						{  
							$follow_btn = '<a   class="solidwhitebutton n-btn-flat">'.esc_html__('Followed','nokri').'</a>';
					    } 
					 else
					  { 
							$follow_btn = '<a  data-value="'.esc_attr( $user_id ).'"  class="solidwhitebutton n-btn-flat follow_company"><i class="fa fa-send-o"></i>'. " ".esc_html__('Follow','nokri').'</a>';
					  }
					 }
                                         
                                         
                                         
                                             $featured_date = get_user_meta($user_id, '_emp_feature_profile', true);
                                                    $is_featured = false;
                                                    $today = date("Y-m-d");
                                                    $expiry_date_string = strtotime($featured_date);
                                                    $today_string = strtotime($today);
                                                    if ($today_string > $expiry_date_string) {
                                                        delete_user_meta($user_id, '_emp_feature_profile');
                                                        delete_user_meta($user_id, '_is_emp_featured');
                                                    } else {
                                                        $is_featured = true;
                                                    }
                                                    $featured = "";
                                                    if (isset($is_featured) && $is_featured) {
                                                        $featured = '<div class="features-star"><i class="fa fa-star"></i></div>';
                                                    };

                                                    
				
				
			$required_user_html .= '<div class="homepage-employers col-lg-12 col-md-12 col-sm-12 col-xs-12 item">
                                             <div class="n-company-grid-single">
                                              '.$featured.'
                                              <a href="'.esc_url(get_author_posts_url($user_id)).'"><div class="employer-cover-thumb" '. "" . ($bg_url).'></div></a>
                                                <div class="n-company-grid-img">
                                                   <div class="n-company-logo">
                                                      <img src="'.esc_url($image_dp_link[0]).'" class="img-responsive" alt="'.esc_attr__('image','nokri').'">
                                                   </div>
                                                   <div class="n-company-title">
                                                      <h3><a href="'.esc_url(get_author_posts_url($user_id)).'">'.$user_name.'</a></h3>
                                                   </div>
                                                   <div class="employer-states">
                                                      <i class="fa fa-map-marker"></i>
                                                    '.$states_tags.'
                                                   </div>
                                                   <div class="employer-industries">
                                                      '.$skill_tags.'
                                                   </div>
                                                   <div class="employer-details">
                                                   <span class="tooltip"><i class="fa fa-home"></i>
                                                   <span class="tooltiptext"><p>Employee Housing</p>'.$housing_tags.'</span>
                                                   </span>
                                                   <span class="tooltip"><i class="fa fa-paw"></i>
                                                   <span class="tooltiptext"><p>Pets</p>'.$pets_tags.'</span>
                                                   </span>
                                                   <span class="tooltip"><i class="fa fa-cutlery"></i>
                                                   <span class="tooltiptext"><p>Meal Plan</p>'.$meal_tags.'</span>
                                                   </span>
                                                   <span class="tooltip"><i class="fa fa-truck"></i>
                                                   <span class="tooltiptext"><p>RV/CamperVan</p>'.$camping_tags.'</span>
                                                   </span>
                                                   <span class="tooltip"><i class="fa fa-wifi"></i>
                                                   <span class="tooltiptext"><p>Wifi Access</p>'.$wifi_tags.'</span>
                                                   </span>
                                                   <span class="tooltip"><i class="fa fa-mobile"></i>
                                                   <span class="tooltiptext"><p>Cell Service</p>'.$cell_tags.'</span>
                                                   </span>
                                                   </div>
                                                </div>
                                                <div class="n-company-bottom">
                                                   <ul class="social-links list-inline">
                                                      '.$fb_link.'
                                                     '.$twitter_link.'
                                                      '.$google_link.'
                                                      '.$linkedin_link.'
                                                   </ul>
                                                </div>
                                             </div>
                                          </div>';
		}
	}

/*Section clr*/
$section_clr = (isset($employer_bg_clr) && $employer_bg_clr != "") ? $employer_bg_clr : "";
/*Section title*/
$section_title = (isset($section_title) && $section_title != "") ? '<h2>'.$section_title.'</h2>' : "";
/*View All  Link */
$read_more = '';
if( isset( $link) )
{
	$read_more = '<span class="view-more">'.nokri_ThemeBtn($link, 'btn n-btn-rounded',false).'</span>';	
}
return ' <section class="'.esc_attr($section_clr).'">
         <div class="container">
            <div class="row">
               </div>
               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="row">
                     <div class="n-company-grids employer-slider owl-carousel owl-theme">
					 '.$required_user_html.'
					  </div>
                  </div>
               </div>
            </div>
         </div>
      </section>';
}
}
if (function_exists('nokri_add_code'))
{
	nokri_add_code('emp_list_short_base', 'emp_list_short_base_func');
}