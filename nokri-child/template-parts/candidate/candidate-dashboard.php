<?php
/*  Candidate Dashboard */
global $nokri;
$user_info = wp_get_current_user();
$user_crnt_id = $is_candidate = $user_info->ID;
$ad_mapLocation = '';
$ad_mapLocation = get_user_meta($user_crnt_id, '_cand_address', true);
$ad_map_lat = get_user_meta($user_crnt_id, '_cand_map_lat', true);
$ad_map_long = get_user_meta($user_crnt_id, '_cand_map_long', true);
$cand_video = get_user_meta($user_crnt_id, '_cand_video', true);
$job_qualifications = get_user_meta($user_crnt_id, '_cand_last_edu', true);
nokri_load_search_countries(1);
/* Getting Candidate Dp */
/* Updating Profile Percentage */
$top_bar_class = 'no-topbar';
if ((isset($nokri['header_top_bar'])) && $nokri['header_top_bar'] == 1) {
    $top_bar_class = '';
}
echo nokri_updating_candidate_profile_percent();
$image_dp_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
    $image_dp_link = array($nokri['nokri_user_dp']['url']);
}
if (get_user_meta($user_crnt_id, '_cand_dp', true) != "") {
    $attach_dp_id = get_user_meta($user_crnt_id, '_cand_dp', true);
    $image_dp_link = wp_get_attachment_image_src($attach_dp_id, 'nokri_job_hundred');
}
$candidate_page = '';
if (isset($_GET['candidate-page']) && $_GET['candidate-page'] != "") {
    $candidate_page = $_GET['candidate-page'];
} else {
    
}

/* Candidate Job Notifiactions */
$cand_job_notif_en = ( isset($nokri['cand_job_notif']) && $nokri['cand_job_notif'] != "" ) ? $nokri['cand_job_notif'] : '1';
$cand_job_notif = ( isset($nokri['cand_job_notif']) && $nokri['cand_job_notif'] != "" ) ? $nokri['cand_job_notif'] : false;
$get_companies = nokri_following_company_ids($user_crnt_id);



if (!empty($get_companies) && $cand_job_notif == '2') {
    $authors = $get_companies;
    $noti_message = esc_html__('Follow Companies For Job Notifications', 'nokri');
} else if ($cand_job_notif == '1') {
    $authors = 0;
    $noti_message = esc_html__('Set Your Skills For Job Notifications', 'nokri');
} else {
    $authors = 98780;
    $noti_message = esc_html__('Follow Companies For Job Notifications', 'nokri');
}
$query = array(
    'post_type' => 'job_post',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'orderby' => 'date',
    'order' => 'DESC',
    'author__in' => $authors,
    'meta_query' => array(
        array(
            'key' => '_job_status',
            'value' => 'active',
            'compare' => '=',
        ),
    ),
);
$args = nokri_wpml_show_all_posts_callback($query);
$loop = new WP_Query($query);
$notification = '';
while ($loop->have_posts()) {


    $loop->the_post();
    $job_id = get_the_ID();
    $post_author_id = get_post_field('post_author', $job_id);
    $company_name = get_the_author_meta('display_name', $post_author_id);
    $job_skills = wp_get_post_terms($job_id, 'job_skills', array("fields" => "ids"));
    $cand_skills = get_user_meta($user_crnt_id, '_cand_skills', true);
    if (is_array($job_skills) && is_array($cand_skills)) {
        $final_array = array_intersect($job_skills, $cand_skills);
        if (count($final_array) > 0) {
            $notification .= '<li>
								<div class="notif-single">
									<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html($company_name) . " " . '</a>' . esc_html__('Posted', 'nokri') . '<a href="' . get_the_permalink($job_id) . '" class="notif-job-title">' . " " . get_the_title() . '</a>
								</div>
								<span class="notif-timing"><i class="icon-clock"></i>' . nokri_time_ago() . '</span>
							</li>';
        }
    }
}
wp_reset_postdata();
/* Candidate Job Notifiactions End */
if (isset($_GET['candidate-page']) && $_GET['candidate-page'] == "my-profile") {
    $dashboardclass = 'candidate-resume-page';
    $conatainerclass = '';
} else {
    $dashboardclass = 'dashboard-new candidate-dashboard';
    $conatainerclass = '-fluid';
}
/* Transparent header dashboard class */
$transparent_header_class = '';
$stick_right_bar = 'id="dashboard-bar-right"';
if ((isset($nokri['main_header_style'])) && $nokri['main_header_style'] == '2' || $nokri['main_header_style'] == '4') {
    $transparent_header_class = 'dashboard-transparent-header';
    $stick_right_bar = '';
}
/* Cand basic info */
$dob = get_user_meta($user_crnt_id, '_cand_dob', true);
$phone = get_user_meta($user_crnt_id, '_sb_contact', true);
$email = $user_info->user_email;
$address = get_user_meta($user_crnt_id, '_cand_address', true);
/* Cand dashboard text */
$user_profile_dashboard_txt = ( isset($nokri['user_profile_dashboard_txt']) && $nokri['user_profile_dashboard_txt'] != "" ) ? $nokri['user_profile_dashboard_txt'] : "";
/* Low profile txt */
$user_low_profile_txt_btn = ( isset($nokri['user_low_profile_txt_btn']) && $nokri['user_low_profile_txt_btn'] != "" ) ? $nokri['user_low_profile_txt_btn'] : false;
$profile_percent = get_user_meta($user_crnt_id, '_cand_profile_percent', true);
$user_low_profile_txt = ( isset($nokri['user_low_profile_txt']) && $nokri['user_low_profile_txt'] != "" ) ? $nokri['user_low_profile_txt'] : "";
/* Is applying job package base */
$is_apply_pkg_base = ( isset($nokri['job_apply_package_base']) && $nokri['job_apply_package_base'] != "" ) ? $nokri['job_apply_package_base'] : false;

$percaentage_switch = isset($nokri['cand_per_switch']) ? $nokri['cand_per_switch'] : false;

$is_allow_crop = isset($nokri['user_crop_dp_switch']) ? $nokri['user_crop_dp_switch'] : false;
?>
<section class="dashboard-new candidate-dashboard <?php echo esc_attr($top_bar_class); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                <?php
                if (isset($_GET['candidate-page']) && $_GET['candidate-page'] == "my-profile") {
                    get_template_part('template-parts/candidate/candidate', $candidate_page);
                } else {
                    ?>
                    
                        <div class="dashboard-sidebar nopadding">
                            <div class="profile-menu">
                            <div class="menu-avtr-box">
                                <div class="user-img">
                                    <img src="<?php echo esc_url($image_dp_link[0]); ?>" id="candidate_dp" class="img-responsive"   alt="<?php echo esc_html__('candidate profile image', 'nokri'); ?>"> 
                                </div>
                                <div class="user-text">
                                    <h4><?php echo esc_html__($user_profile_dashboard_txt); ?></h4>
                                    <p><?php echo the_author_meta( 'display_name', $user_crnt_id ); ?></p>
                                </div>
                            </div>
                            <a href="javascript:void(0)" class="menu-dashboard"> <i class="ti-menu-alt"></i></a>
                            <ul id="accordion" class="accordion">
                                <?php echo nokri_candidate_menu_sorter($user_crnt_id); ?>
                            </ul>
                        </div>
                    </div>
                
                    <div class="dashboard-content">
                        <?php
                        if ($candidate_page != "") {
                            get_template_part('template-parts/candidate/candidate', $candidate_page);
                        } else {
                            get_template_part('template-parts/candidate/index', $candidate_page);
                        }
                        ?>
                    </div>
                
                <?php } ?>
            </div>
        </div>
    </div>
    <input type="hidden" id="is_accordion" value="1">
</section>

<!------ Resumes template model's ---------->
<div class="container text-center">
    <div class="modal fade" tabindex="-1"  aria-hidden="true" id="template_modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div id="template-crousel" class="carousel slide" data-ride="carousel">
                    <!-- Crousel Container -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <a href="javascript:void(0)" data-id="1" class="generate_resume"  data-cand-id="<?php echo esc_attr($user_crnt_id) ?>"> <img src="<?php echo get_template_directory_uri() . '/template-parts/cv-templates/images/cv-1.png'; ?>" class="" ></a>    
                        </div>
                        <div class="item">
                            <a href="javascript:void(0)" data-id="2" class="generate_resume"  data-cand-id="<?php echo esc_attr($user_crnt_id) ?>">  <img src="<?php echo get_template_directory_uri() . '/template-parts/cv-templates/images/cv-2.png'; ?>" class="" ></a>     
                        </div>
                        <div class="item">
                            <a href="javascript:void(0)" data-id="3" class="generate_resume" data-cand-id="<?php echo esc_attr($user_crnt_id) ?>"> <img src="<?php echo get_template_directory_uri() . '/template-parts/cv-templates/images/cv-3.png'; ?>" class="" ></a>

                        </div>
                        <div class="item">
                            <a href="javascript:void(0)" data-id="4" class="generate_resume" data-cand-id="<?php echo esc_attr($user_crnt_id) ?>"> <img src="<?php echo get_template_directory_uri() . '/template-parts/cv-templates/images/cv-4.png'; ?>" class="" ></a>

                        </div>
                    </div>
                    <a class="left carousel-control" href="#template-crousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control" href="#template-crousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade resume-action-modal" id="edit-profile-modal">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">         
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title"><?php echo esc_html__('Crop Profile Image', 'nokri') ?></h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">

                        <label class=""><?php echo nokri_feilds_label('cand_dp_label', esc_html__('Profile Image', 'nokri')); ?></label>
                        <input id="browse-cand-dp" name="candidate_dp[]" type="file" class="file form-control" data-show-preview="false" data-allowed-file-extensions='["jpg", "png", "jpeg"]' data-show-upload="false" >

                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                    <div class="cropper-img">                                                              
                        <img  src="<?php echo esc_url($image_dp_link[0]); ?>">
                    </div>                          
                    <button class="btn n-btn-flat"  id="image_rotator" data-deg="-90"><?php echo esc_html__('Rotate', 'nokri') ?></button>
                </div>                                                                                                           
                <div class="modal-footer">
                    <button type="submit" name="crop_image" class="btn n-btn-flat btn-mid btn-block" id="crop_image_submit">
                        <?php echo esc_html__('Crop', 'nokri') ?>                  </button>
                </div>
            </div>
        </div>
    </div>             
</div>
