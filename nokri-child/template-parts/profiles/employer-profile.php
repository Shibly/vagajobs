<?php
global $nokri;
$author_id = get_query_var('author');
$author = get_user_by('ID', $author_id);
$current_user_id = get_current_user_id();
$registered = $author->user_registered;
$author_id = get_query_var('author');
$author = get_user_by('ID', $author_id);
global $wpdb;
$query = "SELECT count(umeta_id) FROM $wpdb->usermeta WHERE `meta_key` like '_cand_follow_company_%' AND `meta_value` = '" . $author_id . "'";
$followings = $wpdb->get_var($query);
$comp_followers = (count((array) $followings));
$comp_followers_txt = esc_html__('Follower', 'nokri');
if ($comp_followers > 1) {
    $comp_followers_txt = esc_html__('Followers', 'nokri');
}
$user_post_count = count_user_posts($author_id, 'job_post');
$user_id = get_query_var('author');
$ad_mapLocation = '';
$ad_mapLocation = get_user_meta($author_id, '_emp_map_location', true);
$headline = get_user_meta($author_id, '_user_headline', true);
$ad_map_lat = get_user_meta($author_id, '_emp_map_lat', true);
$ad_map_long = get_user_meta($author_id, '_emp_map_long', true);
/* Getting Profile Photo */
$image_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
    $image_link = array($nokri['nokri_user_dp']['url']);
}
if (get_user_meta($user_id, '_sb_user_pic', true) != "") {
    $attach_id = get_user_meta($user_id, '_sb_user_pic', true);
    if (is_numeric($attach_id)) {
        $image_link = wp_get_attachment_image_src($attach_id, 'nokri_job_post_single');
    } else {
        $image_link[0] = $attach_id;
    }
}
if (empty($image_link[0])) {
    if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
        $image_link = array($nokri['nokri_user_dp']['url']);
    }
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
                $skill_tags .= '<a href="' . esc_url($link) . '" class="skills_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
        }
    }
}

/* Getting Employer Application  */
$emp_application = get_user_meta($user_id, '_emp_application', true);
$application_tags = '';
$employer_search_page = $nokri['employer_search_page'];

if ((array) $emp_application && $emp_application > 0) {
    $taxonomies = get_terms('emp_specialization_application', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
    if (count((array) $taxonomies) > 0) {
        foreach ($taxonomies as $taxonomy) {
            $link = get_the_permalink($employer_search_page) . "?emp_application=" . $taxonomy->term_id;
            if (in_array($taxonomy->term_id, $emp_application))
                $application_tags .= '<a href="' . esc_url($link) . '" class="category_tags" target="_blank"><i class="fa fa-check"></i>' . esc_html($taxonomy->name) . '</a>';
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
                $housing_tags .= '<a href="' . esc_url($link) . '" class="category_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
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
                $pets_tags .= '<a href="' . esc_url($link) . '" class="category_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
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
                $meal_tags .= '<a href="' . esc_url($link) . '" class="category_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
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
                $camping_tags .= '<a href="' . esc_url($link) . '" class="category_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
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
                $wifi_tags .= '<a href="' . esc_url($link) . '" class="category_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
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
                $cell_tags .= '<a href="' . esc_url($link) . '" class="category_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
        }
    }
}

/* Getting Employer Staff Size  */
$emp_staff = get_user_meta($user_id, '_emp_staff', true);
$staff_tags = '';
$employer_search_page = $nokri['employer_search_page'];

if ((array) $emp_staff && $emp_staff > 0) {
    $taxonomies = get_terms('emp_specialization_staff', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
    if (count((array) $taxonomies) > 0) {
        foreach ($taxonomies as $taxonomy) {
            $link = get_the_permalink($employer_search_page) . "?emp_staff=" . $taxonomy->term_id;
            if (in_array($taxonomy->term_id, $emp_staff))
                $staff_tags .= '<a href="' . esc_url($link) . '" class="category_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
        }
    }
}

/* Getting Employer National Parks  */
$emp_natpark = get_user_meta($user_id, '_emp_natpark', true);
$natpark_tags = '';
$employer_search_page = $nokri['employer_search_page'];

if ((array) $emp_natpark && $emp_natpark > 0) {
    $taxonomies = get_terms('emp_specialization_natpark', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
    if (count((array) $taxonomies) > 0) {
        foreach ($taxonomies as $taxonomy) {
            $link = get_the_permalink($employer_search_page) . "?emp_natpark=" . $taxonomy->term_id; 
            if (in_array($taxonomy->term_id, $emp_natpark))
                $natpark_tags .= '<a href="' . esc_url($link) . '" class="skills_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
        }
    }
}

/* Getting Employer Additional Regions  */
$emp_regions = get_user_meta($user_id, '_emp_regions', true);
$regions_tags = '';
$employer_search_page = $nokri['employer_search_page'];

if ((array) $emp_regions && $emp_regions > 0) {
    $taxonomies = get_terms('emp_specialization_regions', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
    if (count((array) $taxonomies) > 0) {
        foreach ($taxonomies as $taxonomy) {
            $link = get_the_permalink($employer_search_page) . "?emp_regions=" . $taxonomy->term_id; 
            if (in_array($taxonomy->term_id, $emp_regions))
                $regions_tags .= '<a href="' . esc_url($link) . '" class="skills_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
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
                $states_tags .= '<a href="' . esc_url($link) . '" class="skills_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
        }
    }
}

/* Getting Employer Additional Cities  */
$emp_cities = get_user_meta($user_id, '_emp_cities', true);
$cities_tags = '';
$employer_search_page = $nokri['employer_search_page'];

if ((array) $emp_cities && $emp_cities > 0) {
    $taxonomies = get_terms('emp_specialization_cities', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
    if (count((array) $taxonomies) > 0) {
        foreach ($taxonomies as $taxonomy) {
            $link = get_the_permalink($employer_search_page) . "?emp_cities=" . $taxonomy->term_id; 
            if (in_array($taxonomy->term_id, $emp_cities))
                $cities_tags .= '<a href="' . esc_url($link) . '" class="skills_tags" target="_blank">' . esc_html($taxonomy->name) . '</a>';
        }
    }
}

/* Getting Candidate Portfolio */
$portfolio_html = '';
if (get_user_meta($author_id, '_comp_gallery', true) != "") {
    $port = get_user_meta($author_id, '_comp_gallery', true);
    $portfolios = explode(',', $port);
    foreach ($portfolios as $portfolio) {
        $portfolio_image_sm = wp_get_attachment_image_src($portfolio, 'nokri_job_hundred');
        $portfolio_image_lg = wp_get_attachment_image_src($portfolio, 'nokri_cand_large');
        $portfolio_html .= '<li><a class="item portfolio-gallery" data-fancybox="gallery" href="' . esc_url($portfolio_image_lg[0]) . '"><img src="' . esc_url($portfolio_image_lg[0]) . '" alt= "' . esc_attr__('portfolio image', 'nokri') . '"></a></li>';
    }
}

$emp_establish = '';
$emp_establish = get_user_meta($user_id, '_emp_est', true);
$emp_headline = get_user_meta($user_id, '_user_headline', true);
$emp_address = get_user_meta($user_id, '_emp_map_location', true);
$emp_fb = get_user_meta($user_id, '_emp_fb', true);
$emp_google = get_user_meta($user_id, '_emp_google', true);
$emp_twitter = get_user_meta($user_id, '_emp_twitter', true);
$emp_linkedin = get_user_meta($user_id, '_emp_linked', true);
$emp_cntct = get_user_meta($user_id, '_sb_contact', true);
$emp_comp_email = get_user_meta($user_id, '_emp_comp_email', true);
$emp_web = get_user_meta($user_id, '_emp_web', true);
$emp_size = get_user_meta($user_id, '_emp_nos', true);
$emp_video = get_user_meta($user_id, '_emp_video', true);
$emp_profile_status = get_user_meta($author_id, '_user_profile_status', true);

$rtl_class = $bg_url = '';
$cover_pic  =   get_user_meta($user_id,'_sb_user_cover',true);
if($cover_pic != ""){  
    $bg_url    =   nokri_user_cover_bg_url($cover_pic);  
}
else{
    $bg_url = nokri_section_bg_url()    ;
}
$follow_btn_switch = isset($nokri['emp_det_follow_btn']) ? $nokri['emp_det_follow_btn'] : false;



$map_location = isset($nokri['emp_map_switch']) ? $nokri['emp_map_switch'] : false;

if (!$map_location) {

    $emp_address = emp_get_custom_location($author_id);
}






$contact_recaptcha = isset($nokri['user_contact_form_recaptcha']) ? $nokri['user_contact_form_recaptcha'] : false;
/* email/phone hide/show */
$is_public = isset($nokri['user_phone_email']) && $nokri['user_phone_email'] == '1' ? true : false;


/* contact form hide/show */
$is_public_contact = isset($nokri['user_contact_form']) && $nokri['user_contact_form'] == '1' ? true : false;
if ($emp_profile_status == 'priv' & $author_id != $current_user_id) {
    $image_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
};
/* profile private txt */
$user_private_txt = isset($nokri['user_private_txt']) ? $nokri['user_private_txt'] : '';
/* Social links hide/show */
$social_links = isset($nokri['user_contact_social']) ? $nokri['user_contact_social'] : true;
/* Custom registration feilds for candidate */
$custom_feild_id = $registration_feilds = '';
$custom_feild_id = (isset($nokri['custom_registration_feilds'])) ? $nokri['custom_registration_feilds'] : '';
if (isset($custom_feild_id) && $custom_feild_id != '') {
    $registration_feilds = nokri_get_custom_feilds($author_id, 'Registration', $custom_feild_id, false, true);
}
/* Custom feilds for employer about */
$custom_feilds_emp = '';
$custom_feild_emp = (isset($nokri['custom_employer_feilds'])) ? $nokri['custom_employer_feilds'] : '';
if (isset($custom_feild_emp) && $custom_feild_emp != '') {
    $custom_feilds_emp = nokri_get_custom_feilds($author_id, 'Employer', $custom_feild_emp, false, true);
}

/* Custom feilds for employer location */
$custom_feilds_emp_location = '';
$custom_feild_emp_location = (isset($nokri['custom_employer_location_feilds'])) ? $nokri['custom_employer_location_feilds'] : '';
if (isset($custom_feild_emp_location) && $custom_feild_emp_location != '') {
    $custom_feilds_emp_location = nokri_get_custom_feilds($author_id, 'Employer', $custom_feild_emp_location, false, true);
}

$detail_sec = (isset($nokri['emp_spec_switch'])) ? $nokri['emp_spec_switch'] : false;
$soc_sec = (isset($nokri['emp_social_section_switch'])) ? $nokri['emp_social_section_switch'] : false;
$loc_sec = (isset($nokri['emp_loc_switch'])) ? $nokri['emp_loc_switch'] : false;
$cust_sec = (isset($nokri['emp_custom_switch'])) ? $nokri['emp_custom_switch'] : false;
$port_sec = (isset($nokri['emp_port_switch'])) ? $nokri['emp_port_switch'] : false;
$emp_spec_switch = (isset($nokri['emp_spec_switch'])) ? $nokri['emp_spec_switch'] : false;
?> 



<section class="n-breadcrumb-big resume-3-brreadcrumb" <?php echo "" . ($bg_url); ?>>
    <div class="user-headline container">
        <div class="row">
            <div class="hide-on-mobile col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php if ($emp_profile_status == 'pub' || $author_id == $current_user_id) { ?>
                        <div class="n-candidate-meta-box">
                            <?php if ($emp_profile_status == 'pub') { ?>
                                <h4><?php echo the_author_meta('display_name', $user_id); ?></h4>
                            <?php } if ($emp_headline && nokri_feilds_operat('emp_heading_setting', 'show')) { ?>
                                <p><?php echo esc_html($emp_headline); ?></p>
                            <?php } ?>
                        </div>
                    <?php } ?>
            </div>
        </div>
    </div>
</section>


<section class="n-candidate-detail">
    <div class="container full-width">
        <div class="row flex-row mobile">
            <?php if ($emp_profile_status == 'pub' || $author_id == $current_user_id) { ?>
            
                <div class="profile-left-column">
                    <?php include 'employer/employer-sidebar.php';?>
                </div>
<div class="profile-right-column">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding profile-tab-bar">
        <ul id="tabs" class="menu-navigation-tabs profile-tabs">
            <li class="about-tab"><a id="tab1" data-id="about">About</a></li>
            <li class="jobs-tab"><a id="tab2" data-id="jobs">Jobs</a></li>
            <li class="location-tab"><a id="tab3" data-id="location">location</a></li>
        </ul>
        
        <?php if (get_user_meta($current_user_id, '_sb_reg_type', true) == 0 && $follow_btn_switch) {
            $comp_follow = get_user_meta($current_user_id, '_cand_follow_company_' . $user_id, true);
            if ($comp_follow) { ?>
                <div class="employer-follow-button">
                    <a class="followed_company"><?php echo esc_html__('Followed', 'nokri'); ?><span><i class="fa fa-heart"></i></span></a>
                    
                </div>
            <?php } else { ?>
                    <div class="employer-follow-button">
                        <a  data-value="<?php echo esc_attr($author_id); ?>"  class="follow_company"><?php echo " " . esc_html__('Follow', 'nokri'); ?><span><i class="fa fa-heart-o"></i></span></a>
                    </div>
        <?php }} ?>
            
    </div>  
<!--------------------------------EMPLOYER ABOUT-------------------------------->
      
    <div class="container-desktop menu-navigation-container" id="tab1C">
             <?php include 'employer/employer-about.php';?>
    </div>
      
<!--------------------------------EMPLOYER JOBS-------------------------------->
      
    <div class="container-desktop menu-navigation-container" id="tab2C">
              <?php include 'employer/employer-jobs.php';?>
    </div>
      
<!--------------------------------EMPLOYER LOCATION-------------------------------->
      
    <div class="container-desktop menu-navigation-container" id="tab3C">
              <?php include 'employer/employer-location.php';?>
    </div>

            
</div> <!--END profile-right-column-->
            
                <?php
                get_template_part('template-parts/profiles/rating');
            } else {
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="locked-profile alert alert-danger fade in" role="alert">
                        <i class="la la-lock"></i><?php echo "" . ( $user_private_txt ); ?>
                    </div>
                </div>
            <?php } ?>


        </div>
    </div>
</section>

