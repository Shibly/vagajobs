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
                        <button type="submit" class="solidwhitebutton n-btn-flat n-btn-custom btn-block saving_resume" data-cand-id= <?php echo esc_attr($author_id); ?>><?php echo nokri_feilds_label('cand_save_resume', esc_html__('Save Profile', 'nokri')); ?></button>
                        <?php } if ($cand_resume_down && $cand_resume_setting) echo $resume_id; ?>
                        <?php if ($cand_resume_gen && $cand_generate_setting) { ?>

                        <a href="#" class="solidwhitebutton n-btn-custom btn-block"  data-toggle="modal" data-target="#template_modal"><i class="fa fa-download"></i><?php echo nokri_feilds_label('cand_generate_resume', esc_html__('Generate Resume', 'nokri')); ?></a>
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
                    <?php include 'applicant/applicant-intro.php';?>
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
                                    update_user_meta($current_user_id, '_sb_cand_search_value', (int) $remaining_searches - 1);
                                }
                            }
                        }
                    }
                    ?>
                
                    <div class="profile-right-column">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding profile-tab-bar">
                            <?php include 'applicant/applicant-column-1.php';?>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding profile-tab-bar">
                                        
                            <!--------------------------------APPLICANT COLUMN 2 -------------------------------->
                                
                            <?php include 'applicant/applicant-column-2.php';?>
                                        
                                        
                            <!--------------------------------APPLICANT COLUMN 3 -------------------------------->
                                
                            <?php include 'applicant/applicant-column-3.php'; ?>
                        </div>
                    </div>
                
                <?php
                } else {
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="locked-profile alert alert-danger fade in" role="alert">
                            <i class="la la-lock"></i><?php echo "" . ( $user_private_txt ); ?>
                        </div>
                    </div>
                <?php } ?>
                                    
                            
                            
                
                
            </div>
                <?php  get_template_part('template-parts/profiles/cand-rating');  ?>
        </div>       
    </section>
</div>