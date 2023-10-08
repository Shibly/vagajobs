<style>
    .btn-resume {
        border-radius: 0px !important;
        margin: 3px !important;
        background: transparent !important;
        color: #09283C !important;
        border: 1px solid #09283C !important;
        padding: 7px 30px !important;
        font-size: 12px !important;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: bold;
    }

    .btn-resume:hover {
        background-color: #242424 !important;
        color: white !important;
    }


</style>
<?php require trailingslashit(get_template_directory()) . "/template-parts/profiles/candidate-meta.php"; ?>
<section class="n-breadcrumb-big resume-3-brreadcrumb"<?php echo "" . ($bg_url); ?>>
    <div class="user-headline container">
        <div class="row">
            <div class="hide-on-mobile col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="n-candidate-meta-box">
                    <?php if ($cand_headline && nokri_feilds_operat('cand_profession_setting', 'show')) { ?>
                        <h4><?php echo esc_html($cand_headline); ?></h4>
                    <?php } ?>
                </div>

                <?php if ($cand_save_setting) { ?>
                <div class="resume-action">
                    <button type="submit" class="solidwhitebutton n-btn-flat n-btn-custom btn-block saving_resume"
                            data-cand-id= <?php echo esc_attr($author_id); ?>><?php echo nokri_feilds_label('cand_save_resume', esc_html__('Save Profile', 'nokri')); ?></button>
                    <?php }
                    if ($cand_resume_down && $cand_resume_setting) echo $resume_id; ?>
                    <?php if ($cand_resume_gen && $cand_generate_setting) { ?>

                        <a href="#" class="solidwhitebutton n-btn-custom btn-block" data-toggle="modal"
                           data-target="#template_modal"><i
                                    class="fa fa-download"></i><?php echo nokri_feilds_label('cand_generate_resume', esc_html__('Generate Resume', 'nokri')); ?>
                        </a>
                    <?php } ?>
                </div>

            </div>


        </div>
    </div>
</section>

<div id="applicant-profile">

    <!-------------------------------- APPLICANT INTRO -------------------------------->


    <section class="n-candidate-detail">
        <div class="container full-width">
            <div class="row flex-row mobile">

                <div class="profile-left-column">
                    <?php include 'applicant/applicant-intro.php'; ?>
                </div>


                <?php
                if ($cand_profile_status == 'pub' || $author_id == $current_user_id || current_user_can('administrator') || $is_search) {
                    $resumes_viewed = get_user_meta($current_user_id, '_sb_cand_viewed_resumes', true);
                    if (isset($nokri['cand_search_mode']) && $nokri['cand_search_mode'] == "2") {
                        $remaining_searches = get_user_meta($current_user_id, '_sb_cand_search_value', true);
                        $unlimited_searches = false;
                        if ($remaining_searches == '-1') {
                            $unlimited_searches = true;
                        }
                        if (!$is_applied && !$unlimited_searches && !current_user_can('administrator') && $author_id != $current_user_id) {
                            $resumes_viewed_array = (explode(",", $resumes_viewed));
                            if (!in_array($author_id, $resumes_viewed_array)) {
                                $author_id = $author_id;
                                if ($resumes_viewed != '') {
                                    $author_id = $resumes_viewed . ',' . $author_id;
                                }
                                update_user_meta($current_user_id, '_sb_cand_viewed_resumes', $author_id);
                                if ($remaining_searches != '0') {
                                    update_user_meta($current_user_id, '_sb_cand_search_value', (int)$remaining_searches - 1);
                                }
                            }
                        }
                    }
                    ?>

                    <div class="profile-right-column">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding profile-tab-bar">
                            <?php include 'applicant/applicant-column-1.php'; ?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding profile-tab-bar">

                            <div class="resume-3-sidebar resume-column">

                                <div class="n-candidate-info">

                                    <?php
                                    $user_id = $_GET['candidate_id']; // replace with your user ID
                                    $user_info = get_userdata($user_id);
                                    $user_name = '';
                                    if ($user_info) {
                                        $user_name = $user_info->display_name;
                                    }
                                    ?>

                                    <h4 class="widget-heading"> Resume for <?php echo $user_name; ?></h4>
                                    <br>
                                    <?php
                                    $job_id = $_GET['job_id'];
                                    $candidate_id = $_GET['candidate_id'];
                                    $cand_status = get_post_meta($job_id, '_job_applied_status_' . $candidate_id, true);
                                    $candidate_resume = get_post_meta($job_id, '_job_applied_resume_' . $candidate_id, true);
                                    $array_data = explode('|', $candidate_resume);
                                    $attachment_id = $array_data[1] ?? '';
                                    $label_class = '';
                                    $counter_active = '';

                                    switch ($cand_status) {
                                        case '0':
                                            $label_class = 'default';
                                            $counter_active = "counter-active";
                                            break;
                                        case '1':
                                            $label_class = 'info';
                                            $counter_active = "counter-active";
                                            break;
                                        case '2':
                                            $label_class = 'danger';
                                            $counter_active = "counter-active";
                                            break;
                                        case '3':
                                            $label_class = 'primary';
                                            $counter_active = "counter-active";
                                            break;
                                        case '4':
                                            $label_class = 'warning';
                                            $counter_active = "counter-active";
                                            break;
                                        case '5':
                                            $label_class = 'success';
                                            $counter_active = "counter-active";
                                            break;
                                    }
                                    ?>

                                    <?php if (is_numeric($attachment_id)): ?>
                                        <!-- Link to view the resume in the browser -->
                                        <a class="btn btn-resume" target="_blank"
                                           href="<?php echo get_permalink($attachment_id) . '?attachment_id=' . $attachment_id; ?>">
                                            <?php echo esc_html__('View Resume', 'nokri'); ?>
                                        </a>

                                        <!-- Link to force download the resume -->
                                        <a class="btn btn-resume"
                                           href="<?php echo get_permalink($attachment_id) . '?attachment_id=' . $attachment_id . '&download_file=1'; ?>"
                                           download>
                                            <?php echo esc_html__('Download Resume', 'nokri'); ?>
                                        </a>
                                    <?php else: ?>
                                        <p class="no-resume-message">Resume not available to download.</p>
                                    <?php endif; ?>


                                </div>


                            </div>
                            <div class="resume-3-sidebar resume-column">

                                <div class="n-candidate-info">
                                    <h4 class="widget-heading">Cover Letter</h4>
                                    <?php
                                    $candidate_cover_letter = get_post_meta($job_id, '_job_applied_cover_' . $candidate_id, true);
                                    if (empty($candidate_cover_letter)) {
                                        echo "This candidate does not have any cover letter";
                                    } else {
                                        echo $candidate_cover_letter;
                                    }
                                    ?>
                                </div>

                            </div>


                            <!--------------------------------APPLICANT COLUMN 2 -------------------------------->

                            <?php include 'applicant/applicant-column-2.php'; ?>


                            <!--------------------------------APPLICANT COLUMN 3 -------------------------------->

                            <?php include 'applicant/applicant-column-3.php'; ?>


                        </div>
                    </div>

                    <?php
                } else {
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="locked-profile alert alert-danger fade in" role="alert">
                            <i class="la la-lock"></i><?php echo "" . ($user_private_txt); ?>
                        </div>
                    </div>
                <?php } ?>


            </div>
            <?php get_template_part('template-parts/profiles/cand-rating'); ?>
        </div>
    </section>
</div>