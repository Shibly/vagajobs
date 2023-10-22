<?php
/* Template Name: Page Employer */
/**
 * The template for displaying Pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#page-search
 *
 * @package nokri
 */
$current_user_id = get_current_user_id();
get_header();
global $nokri;
$title = '';
if (isset($_GET['emp_title']) && $_GET['emp_title'] != "") {
    $title = $_GET['emp_title'];
}


$cands_qry = array(
    'key' => '_sb_reg_type',
    'value' => '1',
    'compare' => '='
);

$taxonomies = array('job-location' => '_emp_custom_location',
    'emp_skills' => '_emp_skills',
    'emp_housing' => '_emp_housing',
    'emp_pets' => '_emp_pets',
    'emp_meal' => '_emp_meal',
    'emp_camping' => '_emp_camping',
    'emp_wifi' => '_emp_wifi',
    'emp_cell' => '_emp_cell',
    'emp_staff' => '_emp_staff',
    'emp_natpark' => '_emp_natpark',
    'emp_states' => '_emp_states',
    'emp_cities' => '_emp_cities',
    'emp_regions' => '_emp_regions',
    '_emp_currency' => '_emp_currency'
);

$order = 'DESC';
$orderby = 'meta_value';
if (isset($_GET['order']) && $_GET['order'] == 'name') {
    $orderby = 'display_name';
    $order = 'ASC';
} elseif (isset($_GET['order']) && $_GET['order'] == 'date') {
    $orderby = 'registered';
    $order = 'DESC';
} elseif (isset($_GET['order']) && $_GET['order'] == 'random') {
    $orderby = 'rand';
    $order = 'ASC';
}


$meta_query = array();
$filter_exists = false;
foreach ($taxonomies as $key => $value) {
    if (isset($_GET[$key]) && $_GET[$key] != "") {
        $filter_exists = true;
        $meta_query[] = array(
            'key' => $value,
            'value' => $_GET[$key],
            'compare' => 'like'
        );
    }
}


$custom_tax = false;
if (!$filter_exists) {
    $meta_query = array();
    foreach ($taxonomies as $tax => $value) {

        if ($tax == 'emp_skills') {
            $tax = 'emp_specialization';
        }
        if ($tax == 'emp_housing') {
            $tax = 'emp_specialization_housing';
        }
        if ($tax == 'emp_pets') {
            $tax = 'emp_specialization_pets';
        }
        if ($tax == 'emp_meal') {
            $tax = 'emp_specialization_meal';
        }
        if ($tax == 'emp_camping') {
            $tax = 'emp_specialization_camping';
        }
        if ($tax == 'emp_wifi') {
            $tax = 'emp_specialization_wifi';
        }
        if ($tax == 'emp_cell') {
            $tax = 'emp_specialization_cell';
        }
        if ($tax == 'emp_staff') {
            $tax = 'emp_specialization_staff';
        }
        if ($tax == 'emp_natpark') {
            $tax = 'emp_specialization_natpark';
        }
        if ($tax == 'emp_states') {
            $tax = 'emp_specialization_states';
        }
        if ($tax == 'emp_cities') {
            $tax = 'emp_specialization_cities';
        }
        if ($tax == 'emp_regions') {
            $tax = 'emp_specialization_regions';
        }
        if ($tax == 'job-location') {
            $tax = 'ad_location';
        }

        $tagterm = term_exists($title, $tax);
        if ($tagterm && !empty($tagterm)) {
            $meta_query[] = array('key' => $value, 'value' => $tagterm['term_id'], 'compare' => 'like');
        }
    }
    $meta_query[] = array('key' => '_emp_intro', 'value' => $title, 'compare' => 'like');
    $meta_query[] = array('key' => 'EmployeeExperience', 'value' => $title, 'compare' => 'like');
    $meta_query[] = array('key' => 'EmployeePerks', 'value' => $title, 'compare' => 'like');
    $meta_query[] = array('key' => 'ThePerfectFitWouldBe...', 'value' => $title, 'compare' => 'like');
    $meta_query[] = array('key' => 'ThingstoDoIntheArea', 'value' => $title, 'compare' => 'like');
    $meta_query[] = array('key' => 'HousingDetails', 'value' => $title, 'compare' => 'like');

    if (!empty($meta_query)) {
        if (count($meta_query) > 1) {
            $meta_query['relation'] = 'OR';
        }
        $custom_tax = true;
    } else {
        $custom_tax = false;
    }
}

/*
$location_qry = '';
if (isset($_GET['job-location']) && $_GET['job-location'] != "") {
    $location_qry = array(
        'key' => '_emp_custom_location',
        'value' => $_GET['job-location'],
        'compare' => 'like'
    );
}
$skills_qry = '';
if (isset($_GET['emp_skills']) && $_GET['emp_skills'] != "") {
    $skills_qry = array(
        'key' => '_emp_skills',
        'value' => $_GET['emp_skills'],
        'compare' => 'like'
    );
}
$housing_qry = '';
if (isset($_GET['emp_housing']) && $_GET['emp_housing'] != "") {
    $housing_qry = array(
        'key' => '_emp_skills',
        'value' => $_GET['emp_housing'],
        'compare' => 'like'
    );
}
$pets_qry = '';
if (isset($_GET['emp_pets']) && $_GET['emp_pets'] != "") {
    $pets_qry = array(
        'key' => '_emp_pets',
        'value' => $_GET['emp_pets'],
        'compare' => 'like'
    );
}
$meal_qry = '';
if (isset($_GET['emp_meal']) && $_GET['emp_meal'] != "") {
    $meal_qry = array(
        'key' => '_emp_meal',
        'value' => $_GET['emp_meal'],
        'compare' => 'like'
    );
}
$camping_qry = '';
if (isset($_GET['emp_camping']) && $_GET['emp_camping'] != "") {
    $camping_qry = array(
        'key' => '_emp_camping',
        'value' => $_GET['emp_camping'],
        'compare' => 'like'
    );
}
$wifi_qry = '';
if (isset($_GET['emp_wifi']) && $_GET['emp_wifi'] != "") {
    $wifi_qry = array(
        'key' => '_emp_wifi',
        'value' => $_GET['emp_wifi'],
        'compare' => 'like'
    );
}
$cell_qry = '';
if (isset($_GET['emp_cell']) && $_GET['emp_cell'] != "") {
    $cell_qry = array(
        'key' => '_emp_cell',
        'value' => $_GET['emp_cell'],
        'compare' => 'like'
    );
}
$staff_qry = '';
if (isset($_GET['emp_staff']) && $_GET['emp_staff'] != "") {
    $staff_qry = array(
        'key' => '_emp_staff',
        'value' => $_GET['emp_staff'],
        'compare' => 'like'
    );
}
$natpark_qry = '';
if (isset($_GET['emp_natpark']) && $_GET['emp_natpark'] != "") {
    $natpark_qry = array(
        'key' => '_emp_natpark',
        'value' => $_GET['emp_natpark'],
        'compare' => 'like'
    );
}

$states_qry = '';
if (isset($_GET['emp_states']) && $_GET['emp_states'] != "") {
    $states_qry = array(
        'key' => '_emp_states',
        'value' => $_GET['emp_states'],
        'compare' => 'like'
    );
}

$cities_qry = '';
if (isset($_GET['emp_cities']) && $_GET['emp_cities'] != "") {
    $cities_qry = array(
        'key' => '_emp_cities',
        'value' => $_GET['emp_cities'],
        'compare' => 'like'
    );
}

$regions_qry = '';
if (isset($_GET['emp_regions']) && $_GET['emp_regions'] != "") {
    $regions_qry = array(
        'key' => '_emp_regions',
        'value' => $_GET['emp_regions'],
        'compare' => 'like'
    );
}
*/
// total no of User to display
$limit = isset($nokri['user_pagination']) ? $nokri['user_pagination'] : 10;
$page = (get_query_var('paged')) ? get_query_var('paged') : 1;
$offset = ($page * $limit) - $limit;
// Query args
$args = array(
    'order' => $order,
    'orderby' => array(
        $orderby,
        'random',
    ),
    'number' => $limit,
    'offset' => $offset,
    'role__in' => array('parent_account', 'employer'),
    'meta_query' => array(array(
        'relation' => 'OR',
        array(
            'key' => '_emp_feature_profile',
            'compare' => 'NOT EXISTS'
        ),

    ),
        $cands_qry,
        $meta_query
    )
);

$args2 = array(
    'order' => $order,
    'orderby' => array(
        $orderby,
        'random',
    ),
    'number' => $limit,
    'offset' => $offset,
    'role__in' => array('parent_account', 'employer'),
    'meta_query' => array(array(
        'relation' => 'OR',
        array(
            'key' => '_emp_feature_profile',
            'compare' => 'EXISTS'
        )
    ),
        $cands_qry,
        $meta_query
    )
);

if (!$custom_tax) {
    $args['search'] = "*" . esc_attr($title) . "*";
    $args2['search'] = "*" . esc_attr($title) . "*";
}

// Create the WP_User_Query object
$wp_user_query = new WP_User_Query($args);
$wp_user_query2 = new WP_User_Query($args2);
// Get the results
$users = $wp_user_query->get_results();

shuffle($users);

$users2 = $wp_user_query2->get_results();
shuffle($users2);


$total_users = $wp_user_query->get_total();
$total_users2 = $wp_user_query2->get_total();

$pages_number = ceil($total_users / $limit);
if ($total_users > 0) {
    $users_found = esc_html__("Employers", 'nokri') . " " . '(' . ($total_users + $total_users2) . ')';
} else {
    $users_found = esc_html__("No Employer found", 'nokri');
}
/* search section bg */
$list_bg_url = '';
if (isset($nokri['candidate_list_bg_img'])) {
    $list_bg_url = nokri_getBGStyle('candidate_list_bg_img');
}
?>
    <section class="n-search-page n-user-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">

                        <!--Search/Sort-->
                        <div style="visibility: hidden; position: absolute;"
                             class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="heading-area">
                                <div class="row">

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>
                                            <?php echo esc_html__("Seach by Name", "nokri"); ?>
                                        </label>
                                        <div id="search-widget">
                                            <form role="search" method="get"
                                                  action="<?php echo get_the_permalink($nokri['employer_search_page']); ?>">
                                                <div class="form-group">
                                                    <input type="text" class="form-control"
                                                           value="<?php echo esc_html($title); ?>" name="emp_title"
                                                           placeholder="<?php echo esc_html__('Search Here', 'nokri') ?>">
                                                </div>
                                                <div class="form-group form-action">
                                                    <button type="submit" class="btn"><i class="fa fa-search"></i>
                                                    </button>
                                                </div>
                                                <?php echo nokri_search_params('emp_title'); ?>
                                                <?php echo nokri_form_lang_field_callback(true) ?>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>
                                            <?php echo esc_html__("Sort By", "nokri"); ?>
                                        </label>
                                        <form method="GET" id="candiate_order">
                                            <select class="js-example-basic-single form-control candidates_orders"
                                                    data-allow-clear="true"
                                                    data-placeholder="<?php echo esc_html__("Select option", 'nokri'); ?>"
                                                    style="width: 100%" name="order">
                                                <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
                                                <option value="date" <?php if (isset($_GET['order']) && $_GET['order'] == 'date') echo "selected=selected"; ?>><?php echo esc_html__("New Employers", 'nokri'); ?></option>
                                                <option value="name" <?php if (isset($_GET['order']) && $_GET['order'] == 'name') echo "selected=selected"; ?>><?php echo esc_html__("Sort by Name", 'nokri'); ?></option>
                                            </select>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!--Refine Search-->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search-refine">
                            <aside class="new-sidebar">
                                <div class="heading">
                                    <h4> <?php echo esc_html__("Refine Search", "nokri"); ?></h4>
                                    <p><?php echo esc_html($users_found); ?></p>
                                    <a class="clear-button"
                                       href="<?php echo get_the_permalink(); ?>"><?php echo esc_html__("Clear All", "nokri"); ?></a>
                                    <a role="button" class="collapsed" data-toggle="collapse" href="#accordion"
                                       aria-expanded="false" id="panel_acordian"></a>
                                </div>
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"
                                     aria-expanded="false">
                                    <?php echo get_sidebar('employers'); ?>
                                </div>
                            </aside>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="n-search-main">

                                <div class="n-search-listing n-featured-jobs">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
                                        <div class="row">
                                            <div class="n-company-grids mansi">
                                                <?php

                                                //featured users query
                                                if (!empty($users2)) {
                                                    // Loop through results
                                                    foreach ($users2 as $user) {
                                                        $user_id = $user->ID;
                                                        $user_info = get_userdata($user_id);

                                                        // Assuming the user has only one role
                                                        $role = !empty($user_info->roles) ? array_shift($user_info->roles) : null;

                                                        if ($role === 'employer') {

                                                            $user_name = $user->display_name;
                                                            /* Profile Pic  */
                                                            $image_dp_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
                                                            if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
                                                                $image_dp_link = array($nokri['nokri_user_dp']['url']);
                                                            }
                                                            if (get_user_meta($user_id, '_sb_user_pic', true) != '') {
                                                                $attach_dp_id = get_user_meta($user_id, '_sb_user_pic', true);
                                                                $image_dp_link = wp_get_attachment_image_src($attach_dp_id, '');
                                                            }
                                                            if (empty($image_dp_link[0])) {
                                                                $image_dp_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
                                                            }
                                                            $rtl_class = $bg_url = '';
                                                            $cover_pic = get_user_meta($user_id, '_sb_user_cover', true);
                                                            if ($cover_pic != "") {
                                                                $bg_url = nokri_user_cover_bg_url($cover_pic);
                                                            } else {
                                                                $bg_url = nokri_section_bg_url();
                                                            }
                                                            /* Getting Employer Skills  */
                                                            $emp_skills = get_user_meta($user_id, '_emp_skills', true);
                                                            $skill_tags = '';
                                                            $employer_search_page = $nokri['employer_search_page'];

                                                            if ((array)$emp_skills && $emp_skills > 0) {
                                                                $taxonomies = get_terms('emp_specialization', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_housing && $emp_housing > 0) {
                                                                $taxonomies = get_terms('emp_specialization_housing', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_pets && $emp_pets > 0) {
                                                                $taxonomies = get_terms('emp_specialization_pets', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_meal && $emp_meal > 0) {
                                                                $taxonomies = get_terms('emp_specialization_meal', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_camping && $emp_camping > 0) {
                                                                $taxonomies = get_terms('emp_specialization_camping', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_wifi && $emp_wifi > 0) {
                                                                $taxonomies = get_terms('emp_specialization_wifi', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_cell && $emp_cell > 0) {
                                                                $taxonomies = get_terms('emp_specialization_cell', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_states && $emp_states > 0) {
                                                                $taxonomies = get_terms('emp_specialization_states', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
                                                                    foreach ($taxonomies as $taxonomy) {
                                                                        $link = get_the_permalink($employer_search_page) . "?emp_states=" . $taxonomy->term_id;
                                                                        if (in_array($taxonomy->term_id, $emp_states))
                                                                            $states_tags .= '<a href="' . esc_url($link) . '" class="states_tags">' . esc_html($taxonomy->name) . '</a>';
                                                                    }
                                                                }
                                                            }

                                                            $user_post_count = count_user_posts($user_id, 'job_post');
                                                            $user_post_count_html = '<span class="job-openings">' . $user_post_count . " " . esc_html__('Openings', 'nokri') . '</span>';
                                                            $emp_address = get_user_meta($user_id, '_emp_map_location', true);
                                                            /* Social links */
                                                            $fb_link = $twitter_link = $google_link = $linkedin_link = '';
                                                            $emp_fb = get_user_meta($user_id, '_emp_fb', true);
                                                            $emp_twitter = get_user_meta($user_id, '_emp_twitter', true);
                                                            $emp_google = get_user_meta($user_id, '_emp_google', true);
                                                            $emp_linkedin = get_user_meta($user_id, '_emp_linked', true);
                                                            if ($emp_fb) {
                                                                $fb_link = '<li><a target="_blank" href="' . esc_url($emp_fb) . '"><i class="fa fa-facebook"></i></a></li>';
                                                            }
                                                            if ($emp_twitter) {
                                                                $twitter_link = '<li><a target="_blank" href="' . esc_url($emp_twitter) . '"><i class="fa fa-twitter"></i></a></li>';
                                                            }
                                                            if ($emp_google) {
                                                                $google_link = '<li><a target="_blank" href="' . esc_url($emp_google) . '"><i class="fa fa-instagram"></i></a></li>';
                                                            }
                                                            if ($emp_linkedin) {
                                                                $linkedin_link = '<li><a target="_blank" href="' . esc_url($emp_linkedin) . '"><i class="fa fa-linkedin"></i></a></li>';
                                                            }
                                                            /* Social links */
                                                            $adress_html = '';
                                                            if ($emp_address) {
                                                                $adress_html = '<p class="location"><i class="la la-map-marker"></i>' . " " . $emp_address . '</p>';
                                                            }
                                                            $social_icons = '';
                                                            if ($emp_fb || $emp_twitter || $emp_google || $emp_linkedin) {
                                                                $social_icons = '<div class="n-company-bottom"><ul class="social-links list-inline">' . "" . $fb_link . $twitter_link . $google_link . $linkedin_link . '</ul></div>';
                                                            }

                                                            /* follow company */
                                                            $follow_btn = '';
                                                            $follow_switch = isset($nokri['emp_det_follow_btn']) ? $nokri['emp_det_follow_btn'] : false;
                                                            if (get_user_meta($current_user_id, '_sb_reg_type', true) == 0 && $follow_switch) {
                                                                $comp_follow = get_user_meta($current_user_id, '_cand_follow_company_' . $user_id, true);
                                                                if ($comp_follow) {
                                                                    $follow_btn = '<a   class="solidwhitebutton n-btn-flat">' . esc_html__('Followed', 'nokri') . '</a>';
                                                                } else {
                                                                    $follow_btn = '<a  data-value="' . esc_attr($user_id) . '"  class="whitebutton n-btn-flat  follow_company"><i class="fa fa-send-o"></i>' . " " . esc_html__('Follow', 'nokri') . '</a>';
                                                                }
                                                            }
                                                            $company_tot_jobs = (count_user_posts($user_id, 'job_post'));
                                                            $open_positions_txt = esc_html__('Open postion', 'nokri');
                                                            $postion_html = '';
                                                            if ($company_tot_jobs < 1) {
                                                                $postion_html = '<span>' . esc_html__('No open postion', 'nokri') . '</span>';
                                                            }
                                                            if ($company_tot_jobs > 1) {
                                                                $open_positions_txt = esc_html__('Open postions', 'nokri');
                                                            }
                                                            if ($company_tot_jobs) {
                                                                $postion_html = '<span>' . $company_tot_jobs . " " . $open_positions_txt . '</span>';
                                                            }
                                                            $intro_html = '';
                                                            $emp_intro = get_user_meta($user_id, '_emp_intro', true);
                                                            if ($emp_intro) {
                                                                $intro_html = '<p>' . wp_trim_words($emp_intro, 20, '…') . '</p>';
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

                                                            ?>
                                                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="n-company-grid-single">
                                                                    <?php echo $featured; ?>
                                                                    <a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>">
                                                                        <div class="employer-cover-thumb" <?php echo "" . ($bg_url); ?>></div>
                                                                    </a>
                                                                    <div class="n-company-grid-img">
                                                                        <div class="n-company-logo">
                                                                            <a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>">
                                                                                <div style="background: url(<?php echo esc_url($image_dp_link[0]); ?>);"
                                                                                     class="logo-responsive img-responsive"
                                                                                     alt="<?php echo esc_attr__('image', 'nokri'); ?>"></div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="n-company-title">
                                                                            <h3>
                                                                                <a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>"><?php echo $user_name; ?></a>
                                                                            </h3>
                                                                        </div>
                                                                        <div class="employer-states">
                                                                            <?php if (!empty($states_tags)) { ?>
                                                                                <i class="fa fa-map-marker"></i><?php echo $states_tags; ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                        <div class="employer-industries">
                                                                            <?php if (!empty($skill_tags)) { ?>
                                                                                <?php echo $skill_tags; ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                        <div class="employer-details">
                                                                            <?php if (!empty($housing_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-home"></i>
                                                                                <span class="tooltiptext"><p>Employee Housing</p><?php echo $housing_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($pets_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-paw"></i>
                                                                                <span class="tooltiptext"><p>Pets</p><?php echo $pets_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($meal_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-cutlery"></i>
                                                                                <span class="tooltiptext"><p>Meal Plan</p><?php echo $meal_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($camping_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-truck"></i>
                                                                                <span class="tooltiptext"><p>RV/CamperVan</p><?php echo $camping_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($wifi_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-wifi"></i>
                                                                                <span class="tooltiptext"><p>Wifi Access</p><?php echo $wifi_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($cell_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-mobile"></i>
                                                                                <span class="tooltiptext"><p>Cell Service</p><?php echo $cell_tags; ?></span>
                                                                            </span>
                                                                            <?php } ?>
                                                                        </div>

                                                                        <div class="n-company-follow">
                                                                            <?php echo "" . ($follow_btn); ?>
                                                                        </div>

                                                                    </div>

                                                                    <?php echo "" . ($social_icons); ?>


                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                }

                                                /* Query User results */
                                                if (!empty($users)) {
                                                    // Loop through results
                                                    foreach ($users as $user) {
                                                        $user_id = $user->ID;

                                                        $user_info = get_userdata($user_id);

                                                        // Assuming the user has only one role
                                                        $role = !empty($user_info->roles) ? array_shift($user_info->roles) : null;

                                                        if ($role === 'employer') {
                                                            $user_name = $user->display_name;
                                                            /* Profile Pic  */
                                                            $image_dp_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
                                                            if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
                                                                $image_dp_link = array($nokri['nokri_user_dp']['url']);
                                                            }
                                                            if (get_user_meta($user_id, '_sb_user_pic', true) != '') {
                                                                $attach_dp_id = get_user_meta($user_id, '_sb_user_pic', true);
                                                                $image_dp_link = wp_get_attachment_image_src($attach_dp_id, '');
                                                            }
                                                            if (empty($image_dp_link[0])) {
                                                                $image_dp_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
                                                            }
                                                            $rtl_class = $bg_url = '';
                                                            $cover_pic = get_user_meta($user_id, '_sb_user_cover', true);
                                                            if ($cover_pic != "") {
                                                                $bg_url = nokri_user_cover_bg_url($cover_pic);
                                                            } else {
                                                                $bg_url = nokri_section_bg_url();
                                                            }
                                                            /* Getting Employer Skills  */
                                                            $emp_skills = get_user_meta($user_id, '_emp_skills', true);
                                                            $skill_tags = '';
                                                            $employer_search_page = $nokri['employer_search_page'];

                                                            if ((array)$emp_skills && $emp_skills > 0) {
                                                                $taxonomies = get_terms('emp_specialization', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_housing && $emp_housing > 0) {
                                                                $taxonomies = get_terms('emp_specialization_housing', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_pets && $emp_pets > 0) {
                                                                $taxonomies = get_terms('emp_specialization_pets', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_meal && $emp_meal > 0) {
                                                                $taxonomies = get_terms('emp_specialization_meal', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_camping && $emp_camping > 0) {
                                                                $taxonomies = get_terms('emp_specialization_camping', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_wifi && $emp_wifi > 0) {
                                                                $taxonomies = get_terms('emp_specialization_wifi', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_cell && $emp_cell > 0) {
                                                                $taxonomies = get_terms('emp_specialization_cell', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
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

                                                            if ((array)$emp_states && $emp_states > 0) {
                                                                $taxonomies = get_terms('emp_specialization_states', array('hide_empty' => false, 'orderby' => 'id', 'order' => 'ASC', 'parent' => 0));
                                                                if (count((array)$taxonomies) > 0) {
                                                                    foreach ($taxonomies as $taxonomy) {
                                                                        $link = get_the_permalink($employer_search_page) . "?emp_states=" . $taxonomy->term_id;
                                                                        if (in_array($taxonomy->term_id, $emp_states))
                                                                            $states_tags .= '<a href="' . esc_url($link) . '" class="states_tags">' . esc_html($taxonomy->name) . '</a>';
                                                                    }
                                                                }
                                                            }

                                                            $user_post_count = count_user_posts($user_id, 'job_post');
                                                            $user_post_count_html = '<span class="job-openings">' . $user_post_count . " " . esc_html__('Openings', 'nokri') . '</span>';
                                                            $emp_address = get_user_meta($user_id, '_emp_map_location', true);
                                                            /* Social links */
                                                            $fb_link = $twitter_link = $google_link = $linkedin_link = '';
                                                            $emp_fb = get_user_meta($user_id, '_emp_fb', true);
                                                            $emp_twitter = get_user_meta($user_id, '_emp_twitter', true);
                                                            $emp_google = get_user_meta($user_id, '_emp_google', true);
                                                            $emp_linkedin = get_user_meta($user_id, '_emp_linked', true);
                                                            if ($emp_fb) {
                                                                $fb_link = '<li><a target="_blank" href="' . esc_url($emp_fb) . '"><i class="fa fa-facebook"></i></a></li>';
                                                            }
                                                            if ($emp_twitter) {
                                                                $twitter_link = '<li><a target="_blank" href="' . esc_url($emp_twitter) . '"><i class="fa fa-twitter"></i></a></li>';
                                                            }
                                                            if ($emp_google) {
                                                                $google_link = '<li><a target="_blank" href="' . esc_url($emp_google) . '"><i class="fa fa-instagram"></i></a></li>';
                                                            }
                                                            if ($emp_linkedin) {
                                                                $linkedin_link = '<li><a target="_blank" href="' . esc_url($emp_linkedin) . '"><i class="fa fa-linkedin"></i></a></li>';
                                                            }
                                                            /* Social links */
                                                            $adress_html = '';
                                                            if ($emp_address) {
                                                                $adress_html = '<p class="location"><i class="la la-map-marker"></i>' . " " . $emp_address . '</p>';
                                                            }
                                                            $social_icons = '';
                                                            if ($emp_fb || $emp_twitter || $emp_google || $emp_linkedin) {
                                                                $social_icons = '<div class="n-company-bottom"><ul class="social-links list-inline">' . "" . $fb_link . $twitter_link . $google_link . $linkedin_link . '</ul></div>';
                                                            }

                                                            /* follow company */
                                                            $follow_btn = '';
                                                            $follow_switch = isset($nokri['emp_det_follow_btn']) ? $nokri['emp_det_follow_btn'] : false;
                                                            if (get_user_meta($current_user_id, '_sb_reg_type', true) == 0 && $follow_switch) {
                                                                $comp_follow = get_user_meta($current_user_id, '_cand_follow_company_' . $user_id, true);
                                                                if ($comp_follow) {
                                                                    $follow_btn = '<a   class="solidwhitebutton n-btn-flat">' . esc_html__('Followed', 'nokri') . '</a>';
                                                                } else {
                                                                    $follow_btn = '<a  data-value="' . esc_attr($user_id) . '"  class="whitebutton n-btn-flat  follow_company"><i class="fa fa-send-o"></i>' . " " . esc_html__('Follow', 'nokri') . '</a>';
                                                                }
                                                            }
                                                            $company_tot_jobs = (count_user_posts($user_id, 'job_post'));
                                                            $open_positions_txt = esc_html__('Open postion', 'nokri');
                                                            $postion_html = '';
                                                            if ($company_tot_jobs < 1) {
                                                                $postion_html = '<span>' . esc_html__('No open postion', 'nokri') . '</span>';
                                                            }
                                                            if ($company_tot_jobs > 1) {
                                                                $open_positions_txt = esc_html__('Open postions', 'nokri');
                                                            }
                                                            if ($company_tot_jobs) {
                                                                $postion_html = '<span>' . $company_tot_jobs . " " . $open_positions_txt . '</span>';
                                                            }
                                                            $intro_html = '';
                                                            $emp_intro = get_user_meta($user_id, '_emp_intro', true);
                                                            if ($emp_intro) {
                                                                $intro_html = '<p>' . wp_trim_words($emp_intro, 20, '…') . '</p>';
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

                                                            ?>
                                                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="n-company-grid-single">
                                                                    <?php echo $featured; ?>
                                                                    <a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>">
                                                                        <div class="employer-cover-thumb" <?php echo "" . ($bg_url); ?>></div>
                                                                    </a>
                                                                    <div class="n-company-grid-img">
                                                                        <div class="n-company-logo">
                                                                            <a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>">
                                                                                <div style="background: url(<?php echo esc_url($image_dp_link[0]); ?>);"
                                                                                     class="logo-responsive img-responsive"
                                                                                     alt="<?php echo esc_attr__('image', 'nokri'); ?>"></div>
                                                                            </a>
                                                                        </div>
                                                                        <div class="n-company-title">
                                                                            <h3>
                                                                                <a href="<?php echo esc_url(get_author_posts_url($user_id)); ?>"><?php echo $user_name; ?></a>
                                                                            </h3>
                                                                        </div>
                                                                        <div class="employer-states">
                                                                            <?php if (!empty($states_tags)) { ?>
                                                                                <i class="fa fa-map-marker"></i><?php echo $states_tags; ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                        <div class="employer-industries">
                                                                            <?php if (!empty($skill_tags)) { ?>
                                                                                <?php echo $skill_tags; ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                        <div class="employer-details">
                                                                            <?php if (!empty($housing_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-home"></i>
                                                                                <span class="tooltiptext"><p>Employee Housing</p><?php echo $housing_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($pets_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-paw"></i>
                                                                                <span class="tooltiptext"><p>Pets</p><?php echo $pets_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($meal_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-cutlery"></i>
                                                                                <span class="tooltiptext"><p>Meal Plan</p><?php echo $meal_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($camping_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-truck"></i>
                                                                                <span class="tooltiptext"><p>RV/CamperVan</p><?php echo $camping_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($wifi_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-wifi"></i>
                                                                                <span class="tooltiptext"><p>Wifi Access</p><?php echo $wifi_tags; ?></span>
                                                                            </span>
                                                                            <?php }
                                                                            if (!empty($cell_tags)) { ?>
                                                                                <span class="tooltip">
                                                                                <i class="fa fa-mobile"></i>
                                                                                <span class="tooltiptext"><p>Cell Service</p><?php echo $cell_tags; ?></span>
                                                                            </span>
                                                                            <?php } ?>
                                                                        </div>

                                                                        <div class="n-company-follow">
                                                                            <?php echo "" . ($follow_btn); ?>
                                                                        </div>

                                                                    </div>

                                                                    <?php echo "" . ($social_icons); ?>


                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nopadding">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        <?php echo nokri_user_pagination($pages_number, $page); ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php

get_footer();
