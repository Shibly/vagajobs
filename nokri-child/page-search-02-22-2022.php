<?php
get_header();
/* Template Name: Page Search */
global $nokri;
/* Getting Title From Query String */
$title = '';
if (isset($_GET['job-title']) && $_GET['job-title'] != "") {
    $title = $_GET['job-title'];
}
/* RTL check */
$rtl_class = '';
if (is_rtl()) {
    $rtl_class = "flip";
}


/* search section bg */
$search_bg_url = '';
if (isset($nokri['search_bg_img'])) {
    $search_bg_url = nokri_getBGStyle('search_bg_img');
}

/* Getting All Taxonomy From Query String */
$taxonomies = array('ad_location', 'job_type', 'ad_title', 'cat_id', 'job_category', 'job_qualifications', 'job_level', 'job_salary', 'job_currency', 'job_skills', 'job_experience', 'job_currency', 'job_shift' , 'job_class');
$tax_query = array();
$tax_exists = false;
$taken_tax = array();
foreach ($taxonomies as $tax) {
    if (isset($_GET[$tax]) && $_GET[$tax] != "") {
        $tax_exists = true;
        $taken_tax[] = $tax;
        $tax_query[] = array(
            array('taxonomy' => $tax, 'field' => 'term_id', 'terms' => $_GET[$tax]),
        );
    }
}
$custom_tax = false;
$is_search = false;
$tax_query1 = array();
foreach ($taxonomies as $tax) {
    if( ! in_array( $tax, $taken_tax ) ) {
        $tagterm = term_exists( $title, $tax );
        if( $tagterm && !empty( $tagterm ) ){
            $is_search = true;
            $tax_query1[] = array(
                array('taxonomy' => $tax, 'field' => 'term_id', 'terms' => $tagterm['term_id']),
            );
        }
    }
    
}

if( !empty( $tax_query1 ) ){
    $tax_query1['relation'] = 'AND';
    
    $custom_tax = true;
    $tax_query = array_merge( $tax_query, $tax_query1 );
}else{
    $custom_tax = false;
}



$category = '';
if (isset($_GET['cat-id']) && $_GET['cat-id'] != "") {
    $category = array(
        array(
            'taxonomy' => 'job_category',
            'field' => 'term_id',
            'terms' => $_GET['cat-id'],
        ),
    );
}

$location = '';
/*
if (isset($_GET['job-location']) && $_GET['job-location'] != "") {
    $tax_query[] = array(
        array(
            'taxonomy' => 'ad_location',
            'field' => 'term_id',
            'terms' => $_GET['job-location'],
        ),
    );
}
*/
if( isset($_GET['job-location-city']) && $_GET['job-location-city'] != "" ) {
    $tax_query[] = array(
        array(
            'taxonomy' => 'ad_location',
            'field' => 'term_id',
            'terms' => $_GET['job-location-city'],
        ),
    );
}elseif( isset($_GET['job-location-state']) && $_GET['job-location-state'] != "" ){
    $tax_query[] = array(
        array(
            'taxonomy' => 'ad_location',
            'field' => 'term_id',
            'terms' => $_GET['job-location-state'],
        ),
    );
}


$location_keyword = '';
if (isset($_GET['loc_keyword']) && $_GET['loc_keyword'] != "") {
    $tax_query[] = array(
        array(
            'taxonomy' => 'ad_location',
            'field' => 'name',
            'terms' => $_GET['loc_keyword'],
            'operator' => 'LIKE'
        ),
    );
}

/* Custom feilds search satrts */
$custom_search = array();
if (isset($_GET['custom'])) {
    foreach ($_GET['custom'] as $key => $val) {

        $val = stripslashes_deep($val);
        $metaKey = '_nokri_tpl_field_' . $key;
        $custom_search[] = array(
            'key' => $metaKey,
            'value' => $val,
            'compare' => 'LIKE',
        );
    }
}
/* Custom feilds search ends */

/* Radius search starts */
$lat_lng_meta_query = array();
if (isset($_GET['radius_lat']) && isset($_GET['radius_long'])) {
    $latitude = $_GET['radius_lat'];
    $longitude = $_GET['radius_long'];
}
if (!empty($latitude) && !empty($longitude)) {
    $distance = '30';
    if (!empty($_GET['distance']) && !empty($_GET['distance'])) {
        $distance = $_GET['distance'];
    }

    $data_array = array("latitude" => $latitude, "longitude" => $longitude, "distance" => $distance);
    $type_lat = "'DECIMAL'";
    $type_lon = "'DECIMAL'";
    $lats_longs = nokri_radius_search_theme($data_array, false);

    if (!empty($lats_longs) && count((array) $lats_longs) > 0) {
        if ($latitude > 0) {
            $lat_lng_meta_query[] = array(
                'key' => '_job_lat',
                'value' => array($lats_longs['lat']['min'], $lats_longs['lat']['max']),
                'compare' => 'BETWEEN',
            );
        } else {
            $lat_lng_meta_query[] = array(
                'key' => '_job_lat',
                'value' => array($lats_longs['lat']['max'], $lats_longs['lat']['min']),
                'compare' => 'BETWEEN',
            );
        }
        if ($longitude > 0) {
            $lat_lng_meta_query[] = array(
                'key' => '_job_long',
                'value' => array($lats_longs['long']['min'], $lats_longs['long']['max']),
                'compare' => 'BETWEEN',
            );
        } else {
            $lat_lng_meta_query[] = array(
                'key' => '_job_long',
                'value' => array($lats_longs['long']['max'], $lats_longs['long']['min']),
                'compare' => 'BETWEEN',
            );
        }
    }
}
/* Radius search ends */

/* Passing Query String Results To Arguments */
$order = 'DESC';
if (isset($_GET['order_job'])) {
    if (isset($_GET['order_job']) && $_GET['order_job'] != "") {
        $order = $_GET['order_job'];
    }
}
$featur_excluded = '';

if (isset($nokri['premium_jobs_class_switch']) && $nokri['premium_jobs_class_switch'] ) {
    if (isset($nokri['premium_jobs_class']) && $nokri['premium_jobs_class'] != '') {

        $featur_excluded = array(
            'taxonomy' => 'job_class',
            'field' => 'term_id',
            'terms' => $nokri['premium_jobs_class'],
            'operator' => 'NOT IN',
        );
    }
}
if (get_query_var('paged')) {
    $paged = get_query_var('paged');
} else if (get_query_var('page')) {
    /* This will occur if on front page. */
    $paged = get_query_var('page');
} else {
    $paged = 1;
}
$args = array(
    'tax_query' => $tax_query,
    //'tax_query' => $taxquery,
    'posts_per_page' => get_option('posts_per_page'),
    'post_type' => 'job_post',
    'post_status' => 'publish',
    'order' => $order,
    'orderby' => 'date',
    'paged' => $paged,
    'meta_query' => array(
        array(
            'key' => '_job_status',
            'value' => 'active',
            'compare' => '=',
        ),
        $custom_search,
        $lat_lng_meta_query,
    ),
    
);

if( $title != '' && ! $is_search ) {
    $args['s'] = $title;
}



if ( ! isset( $_GET['order_job'] ) ) {
    $args['orderby'] = 'rand';
}


$args = nokri_wpml_show_all_posts_callback($args);
$results = new WP_Query($args);

if ($results->found_posts > 0) {
    $message = __('Available Jobs', 'nokri');
} else {
    $message = __('No Jobs Matched', 'nokri');
}

$side_bar_emp_title = ( isset($nokri['multi_company_select_title']) && $nokri['multi_company_select_title'] != "" ) ? '<div class="widget-heading"><span class="title">' . $nokri['multi_company_select_title'] . '</span></div>' : "";
/* Premium Jobs Top Query */
$premium_jobs_class_num = ( isset($nokri['premium_jobs_class_number']) && $nokri['premium_jobs_class_number'] != "" ) ? $nokri['premium_jobs_class_number'] : "";
$args_premium = array();
if (isset($nokri['premium_jobs_class']) && $nokri['premium_jobs_class'] != '') {
    $job_classes = '';
    $job_classes = array(
        array(
            'taxonomy' => 'job_class',
            'field' => 'term_id',
            'terms' => $nokri['premium_jobs_class'],
            'operator' => 'IN',
        ),
    );

    $args_premium = array(
        'tax_query' => array($job_classes, $category, $location),
        's' => $title,
        'posts_per_page' => $premium_jobs_class_num,
        'post_type' => 'job_post',
        'post_status' => 'publish',
        'orderby' => 'rand',
        'order' => $order,
        'orderby' => 'date',
        'meta_query' => array(
            array(
                'key' => '_job_status',
                'value' => 'active',
                'compare' => '=',
            ),
        ),
    );
}
/* Advertisement Module */
$advert_up = $advert_down = '';
if (isset($nokri['search_job_advert_switch']) && $nokri['search_job_advert_switch'] == "1") {
    /* Above joba */
    if (isset($nokri['search_job_advert_up']) && $nokri['search_job_advert_up'] != "") {
        $advert_up = $nokri['search_job_advert_up'];
    }
    /* Below jobs */
    if (isset($nokri['search_job_advert_down']) && $nokri['search_job_advert_down'] != "") {
        $advert_down = $nokri['search_job_advert_down'];
    }
}
/* Search page lay out */
$search_page_layout = ( isset($nokri['search_page_layout']) && $nokri['search_page_layout'] != "" ) ? $nokri['search_page_layout'] : "";
/* Is job alerts */
$job_alerts = ( isset($nokri['job_alerts_switch']) && $nokri['job_alerts_switch'] != "" ) ? $nokri['job_alerts_switch'] : false;
/* Job alert title */
$job_alerts_title = ( isset($nokri['job_alerts_title']) && $nokri['job_alerts_title'] != "" ) ? $nokri['job_alerts_title'] : '';
/* Job alert tagline */
$job_alerts_tagline = ( isset($nokri['job_alerts_tagline']) && $nokri['job_alerts_tagline'] != "" ) ? $nokri['job_alerts_tagline'] : '';
/* Job alert btn */
$job_alerts_btn = ( isset($nokri['job_alerts_btn']) && $nokri['job_alerts_btn'] != "" ) ? $nokri['job_alerts_btn'] : '';

$multi_searach = ( isset($nokri['multi_job_search_form']) && $nokri['multi_job_search_form'] != "" ) ? $nokri['multi_job_search_form'] : false;

$loader_image = get_template_directory_uri() . '/images/loader-img.png';
$current_layout = $nokri['search_layout'];

$is_mobile = wp_is_mobile() ? "collapse" : "collapse in";
$pull_class = "";
$push_class = "";

$horizontal_class  =   "";

$horizontal_searh = ( isset($nokri['search_page_widget_style']) && $nokri['search_page_widget_style'] != "" ) ? $nokri['search_page_widget_style'] : 2;


$show_mob_filter = isset($nokri['search_mobile_filter']) ? $nokri['search_mobile_filter'] : false;



if ($horizontal_searh == "1" && $search_page_layout != "3") {
   
    $horizontal_class   =  "horizontal";
    ?>
    <section class="padding horizontal_search <?php echo nokri_returnEcho($show_mob_filter) ?  'mobile-filters' : ''; ?>">
      <a class="btn n-btn-flat filter-close-btn" href="javascript:void(0);"><i class="fa fa-close"></i></a>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <div class="jobs-form">
                        <form method="get" action = "<?php echo get_the_permalink($nokri['sb_search_page']); ?>" id="jobs_searh_form">
                            <ul>
                                <?php  nokri_return_horizontal_search_bar();   ?>
                                      
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
 <?php if ($show_mob_filter) { ?>
                                    <div class="mobile-filters-btn">
                                        <a class="btn n-btn-flat" href="javascript:void(0);"><?php esc_html_e("Filters", "nokri"); ?><i class="fa fa-filter"></i></a>
                                    </div>
                                <?php } ?>
    <?php
}

if ($search_page_layout == 1) {
    ?>
    <div class="cp-loader"></div>
    <section class="n-search-page" >
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">

                       
                            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">                      

                                <aside class="new-sidebar <?php echo nokri_returnEcho($show_mob_filter && $horizontal_searh == "2") ?  'mobile-filters' : ''; ?>">

                                    <div class="heading">
                                        <h4> <?php echo esc_html__("Search Filters", "nokri"); ?></h4>
                                        <a href="<?php echo get_the_permalink($nokri['sb_search_page']); ?>"><?php echo esc_html__("Clear All", "nokri"); ?></a>                  
                                        <a role="button" class="" data-toggle="collapse" href="#accordion" aria-expanded="true" id="panel_acordian"></a>                           
                                    </div>  
                                    <a class="btn filter-close-btn" href="javascript:void(0);"><i class="fa fa-close"></i></a>

                                    <?php if ($multi_searach && $horizontal_searh !="1") { ?>
                                        <form method="get" id="all_search_form">
                                            <div class = "panel-group" id = "accordion" role = "tablist" aria-multiselectable = "true">
                                                <button type = "submit" class = "submit-all-form btn n-btn-flat btn-block" ><?php echo esc_html__('Search', 'nokri')
                                        ?></button>    
                                                <?php get_sidebar('widget'); ?>
                                                <button type = "submit" class = "submit-all-form btn n-btn-flat btn-block" ><?php echo esc_html__('Search', 'nokri')
                                                ?></button>    
                                            </div>
                                        </form>

                                    <?php } else { ?>

                                        <div class = "panel-group  <?php echo esc_attr($is_mobile) ?>" id = "accordion" role = "tablist" aria-multiselectable = "true">                                                                  
                                            <?php get_sidebar('widget'); ?>                                 
                                        </div>
                                    <?php } ?>
                                </aside>       

                            </div>
                            <?php if ($show_mob_filter && $horizontal_searh != "1") { ?>
                                    <div class="mobile-filters-btn">
                                        <a class="btn n-btn-flat" href="javascript:void(0);"><?php esc_html_e("Filters", "nokri"); ?><i class="fa fa-filter"></i></a>
                                    </div>
                                <?php } ?>
                            <?php
                      
                        /* Premium Job Top Query */
                        if (isset($nokri['premium_jobs_class_switch']) && $nokri['premium_jobs_class_switch'] == '1') {

                            $results_premium = new WP_Query($args_premium);
                            if ($results_premium->have_posts()) {
                               
                                    $pull_class = "col-lg-pull-3";
                                    $push_class = "col-lg-push-6";
                                

                                /* Section Title */
                                $section_title = (isset($nokri['premium_jobs_class_title']) && $nokri['premium_jobs_class_title'] != "") ? '<div class="heading"><h4>' . $nokri['premium_jobs_class_title'] . '</h4></div>' : "";
                                ?>
                                <div class="col-lg-3 col-md-8 col-sm-12 col-xs-12 <?php echo esc_attr($push_class) ?> ">
                                    <aside class="new-sidebar">
                                        <?php echo ($section_title); ?>
                                        <div class="vertical-job-slider verticalCarousel">
                                            <ul class="slider-1">
                                                <?php
                                                $layouts = array('list_1', 'list_2', 'list_3');
                                                if (in_array($current_layout, $layouts)) {
                                                    require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-premium-list.php";
                                                    echo($out);
                                                } else {
                                                    require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-premium-grid.php";
                                                    echo($out);
                                                }
                                                wp_reset_postdata();
                                                ?>
                                            </ul>
                                        </div>
                                    </aside>
                                </div>
                                <?php
                            }
                        }
                        ?>

                        <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12 <?php echo $pull_class ?>">
                            <div class="n-search-main">
                                <div class="n-bread-crumb">
                                    <ol class="breadcrumb">
                                        <li> <a href=""><?php echo esc_html__("Home", "nokri"); ?></a></li>
                                        <li class="active"><a href="javascript:void(0);" class="active"><?php echo esc_html__("Search Page", "nokri"); ?></a></li>
                                    </ol>
                                </div>
                                <div class="heading-area" <?php echo ($search_bg_url); ?>>
                                    <div class="row">
                                        <div class="col-md-8 col-sm-8 col-xs-12">
                                            <h4><?php echo esc_html($message) . " " . '(' . esc_html($results->found_posts) . ')'; ?></h4>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <form method="GET" id="emp_active_job">
                                                <select class="js-example-basic-single form-control  emp_active_job" data-allow-clear="true" data-placeholder="<?php echo esc_html__("Select Option", "nokri"); ?>" style="width: 100%" name="order_job" id="order_job">
                                                    <option value="" ><?php echo esc_html__("Select Option", "nokri"); ?></option>
                                                    <option value="ASC" <?php
                                                    if ($order == 'ASC') {
                                                        echo "selected";
                                                    };
                                                    ?>><?php echo esc_html__("Ascending", "nokri"); ?></option>
                                                    <option value="DESC" <?php
                                                    if ($order == 'DESC') {
                                                        echo "selected";
                                                    };
                                                    ?>><?php echo esc_html__("Descending ", "nokri"); ?></option>
                                                </select>
                                                <?php if(!$multi_searach){ echo nokri_search_params('order_job');} ?>
                                                <?php echo nokri_form_lang_field_callback(true); ?> 
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($job_alerts) { ?>
                                    <div class="jobs-alert-box">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                                                <span><?php echo esc_html($job_alerts_title); ?></span>
                                                <p><?php echo esc_html($job_alerts_tagline); ?></p>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                <a href="javascript:void(0)" class="btn n-btn-flat job_alert"><?php echo esc_html($job_alerts_btn); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                                <div class="n-search-listing n-featured-jobs-two">
                                    <div class="row">
                                        <?php echo nokri_returnEcho($advert_up); ?>
                                        <div class="n-features-job-two-box" id="">
                                            <?php
                                            /* Regular Search Query */
                                            if ($results->have_posts()) {

                                                $layouts = array('list_1', 'list_2', 'list_3');
                                                if (in_array($current_layout, $layouts)) {
                                                    require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-list.php";
                                                    echo($out);
                                                } else {
                                                    require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-grid.php";
                                                    echo($out);
                                                }
                                                /* Restore original Post Data */
                                                wp_reset_postdata();
                                            }
                                            ?>

                                            <div class="clearfix"></div>
                                            <div class="n-features-job-two-box" id="jobs_container"></div>
                                            <?php echo ($advert_down); ?>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                <?php if (isset($nokri['job_search_loader']) && $nokri['job_search_loader'] && $results->have_posts()) { ?>
                                                    <div  class = "loader_container" style="display: none"> 
                                                        <img class="loader_img" src="<?php echo esc_url($loader_image) ?>" >
                                                        <img class="loader_img" src="<?php echo esc_url($loader_image) ?>">
                                                        <img class="loader_img" src="<?php echo esc_url($loader_image) ?>" >
                                                    </div>
                                                    <center>
                                                        <button class="btn n-btn-flat" id="more_jobs"><?php echo esc_html__('More Jobs', 'nokri') ?><span class="fa fa-spinner"></span></button>
                                                    </center>
                                                    <input type="hidden" id="page_number" value="1" />
                                                    <input type="hidden" id="more_loading" value="1" />
                                                    <input type="hidden" id="page_url" value ="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" />

                                                <?php } else { ?>
                                                    <nav aria-label="Page navigation">
                                                        <?php echo nokri_job_pagination($results); ?>
                                                    </nav>                                         
                                                <?php } ?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } else if ($search_page_layout == 2) { ?>
    <section class="n-search-page n-user-page  <?php   echo esc_html($horizontal_class)  ?>" >
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        
                        <!--Search/Sort-->
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                            <div class="heading-area">
                                <div class="row">
                                    
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>
                                            <?php echo esc_html__("Search by Keyword", "nokri"); ?>
                                        </label>
                                        <div id="search-widget">
                                            <form role="search" method="get" action = "<?php echo get_the_permalink($nokri['sb_search_page']); ?>">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="<?php echo esc_attr($title); ?>" name="job-title" placeholder="<?php echo esc_html__('Search Here', 'nokri') ?>">
                                                    </div>
                                                    <div class="form-group form-action">
                                                        <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                                                    </div>
                                                    <?php echo nokri_search_params('job-title'); ?>
                                                    <?php echo nokri_form_lang_field_callback(true); ?>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>
                                            <?php echo esc_html__("Sort By", "nokri"); ?>
                                        </label>
                                        <form method="GET" id="emp_active_job">
                                            <select class="js-example-basic-single form-control emp_active_job" data-allow-clear="true" data-placeholder="<?php echo esc_html__("Select Option", "nokri"); ?>" style="width: 100%" name="order_job" id="order_job">
                                                <option value="" ><?php echo esc_html__("Select Option", "nokri"); ?></option>
                                                <option value="ASC" <?php
                                                    if ($order == 'ASC') {
                                                        echo "selected";
                                                    };
                                                    ?>><?php echo esc_html__("Old Jobs", "nokri"); ?></option>
                                                <option value="DESC" <?php
                                                    if ($order == 'DESC') {
                                                        echo "selected";
                                                    };
                                                    ?>><?php echo esc_html__("New Jobs", "nokri"); ?></option>
                                            </select>
                                            <?php echo nokri_form_lang_field_callback(true); ?>
                                            <?php if(!$multi_searach){ echo nokri_search_params('order_job');} ?>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">      
                                <aside class="new-sidebar  <?php echo nokri_returnEcho($show_mob_filter && $horizontal_searh == "2") ?  'mobile-filters' : ''; ?>">                       
                                    <div class="heading">
                                        <h4> <?php echo esc_html__("Refine Search", "nokri"); ?></h4>
                                        <p><?php echo esc_html($message) . " " . '(' . esc_html($results->found_posts) . ')'; ?></p>
                                        <a class="clear-button" href="<?php echo get_the_permalink($nokri['sb_search_page']); ?>"><?php echo esc_html__("Clear All", "nokri"); ?></a>

                                        <a role="button" class="collapsed" data-toggle="collapse" href="#accordion" aria-expanded="false" id="panel_acordian"></a>                                 
                                    </div> 
                                    
                                    

                                    <?php if ($multi_searach  && $horizontal_searh !="1" ) { ?>
                                        <form method="get" id="all_search_form">
                                            <div class = "panel-group" id = "accordion" role = "tablist" aria-multiselectable = "true">
                                                <button type = "submit" class = "submit-all-form btn n-btn-flat btn-block" ><?php echo esc_html__('Search', 'nokri')
                                        ?></button>    

                                                <?php get_sidebar('widget'); ?>


                                                <button type = "submit" class = "submit-all-form btn n-btn-flat btn-block" ><?php echo esc_html__('Search', 'nokri')
                                                ?></button>    
                                            </div>
                                        </form>

                                    <?php } else { ?>

                                        <div class = "panel-group collapse" id = "accordion" role = "tablist" aria-multiselectable = "true" aria-expanded="false">                                                                  
                                            <?php get_sidebar('widget'); ?>                                 
                                        </div>
                                    <?php } ?> 

                                </aside>   
                                
                            </div>
                         <?php if ($show_mob_filter && $horizontal_searh != "1") { ?>
                                    <div class="mobile-filters-btn">
                                        <a class="btn n-btn-flat" href="javascript:void(0);"><?php esc_html_e("Filters", "nokri"); ?><i class="fa fa-filter"></i></a>
                                    </div>
                                <?php } ?>
                        
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="n-search-main">
                            
                                <?php if ($job_alerts) { ?>
                                    <div class="jobs-alert-box">
                                        <div class="row">
                                            <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
                                                <span><?php echo esc_html($job_alerts_title); ?></span>
                                                <p><?php echo esc_html($job_alerts_tagline); ?></p>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                                <a href="javascript:void(0)" class="btn n-btn-flat job_alert"><?php echo esc_html($job_alerts_btn); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } echo ($advert_up); ?>
                                <?php
                                if (isset($nokri['premium_jobs_class_switch']) && $nokri['premium_jobs_class_switch'] == '1') {
                                    $section_title = (isset($nokri['premium_jobs_class_title']) && $nokri['premium_jobs_class_title'] != "") ? '<h3>' . $nokri['premium_jobs_class_title'] . '</h3>' : "";
                                    echo "<div class='n-search-listing n-featured-jobs featured'>";
                                    /* Premium jobs in list style */
                                    $results_premium = new WP_Query($args_premium);
                                    $current_layout = $nokri['search_layout'];
                                    $layouts = array('list_1', 'list_2', 'list_3');
                                    if ($results_premium->have_posts()) {
                                        echo $section_title;
                                        echo "<div class='n-featured-job-boxes'>";
                                        if (in_array($current_layout, $layouts)) {
                                            require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-premium-list2.php";
                                            echo($out);
                                        }
                                        wp_reset_postdata();
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                }
                                ?>
                                <div class="n-search-listing n-featured-jobs"  >
                                    
                                    <div class="n-featured-job-boxes" >

                                        <?php
                                        /* Regular Search Query */
                                        if ($results->have_posts()) {
                                            $current_layout = $nokri['search_layout'];
                                            $layouts = array('list_1', 'list_2', 'list_3');
                                            if ($results->have_posts()) {
                                                if (in_array($current_layout, $layouts)) {
                                                    require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-full.php";
                                                    echo($out);
                                                } else {
                                                    require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-grid.php";
                                                    echo($out);
                                                }
                                                /* Restore original Post Data */
                                                wp_reset_postdata();
                                            }
                                        }
                                        ?>
                                        <div class="n-featured-job-boxes" id="jobs_container"></div>
                                        <div class="clearfix"></div>
                                        <?php echo ($advert_down); ?> 
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                            <?php if (isset($nokri['job_search_loader']) && $nokri['job_search_loader'] && $results->have_posts()) { ?>
                                                <div  class = "loader_container" style="display: none"> 
                                                    <img class="loader_img" src="<?php echo esc_url($loader_image) ?>" >
                                                    <img class="loader_img" src="<?php echo esc_url($loader_image) ?>">
                                                    <img class="loader_img" src="<?php echo esc_url($loader_image) ?>" >
                                                </div>
                                                <center>
                                                    <button class="btn n-btn-flat" id="more_jobs"><?php echo esc_html__('More jobs', 'nokri') ?><span class="fa fa-spinner"></span></button>
                                                </center>
                                                <input type="hidden" id="page_number" value="1" />
                                                <input type="hidden" id="more_loading" value="1" />
                                                <input type="hidden" id="page_url" value ="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" />

                                            <?php } else { ?>
                                                <nav aria-label="Page navigation">
                                                    <?php echo nokri_job_pagination($results); ?>
                                                </nav>                                         
                                            <?php } ?>
                                        </div>
                                    </div>                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--footer section-->
    <?php
} else {
    /* Getting map layout */
    get_template_part('template-parts/layouts/job-style/search', 'map');
}


  
get_footer();
