<?php require trailingslashit(get_template_directory()) . "/template-parts/layouts/job-style/job-informations.php"; ?>

<section class="n-single-job n-single-job-transparent">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
            <div class="job-detail-intro">
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-4">
                <div class="contact-img">
                    <a class="profile-icon-hover" href="<?php echo esc_url(get_author_posts_url($post_author_id)); ?>"><img src="<?php echo esc_url($image_link[0]); ?>" class="img-responsive img-circle" alt="<?php echo esc_attr__('company profile image', 'nokri'); ?>"></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-8 col-sm-6 col-xs-8 right-border-blue">
                <div class="n-single-job-meta">
                    <div class="n-single-title">
                        <h1><?php the_title(); ?></h1>
                        <h4><a href="<?php echo esc_url(get_author_posts_url($post_author_id)); ?>"><?php echo esc_html($company_name); ?></a></h4>
                        <?php if (isset($nokri['allow_job_countries']) && $nokri['allow_job_countries'] != 'hide') { ?>
                        <p><i class="fa fa-map-marker"></i><?php echo $countries_last; ?></p>
                        <?php } ?>
                        <span class="profile-link">
                        <a href="<?php echo esc_url(get_author_posts_url($post_author_id)); ?>"><?php echo esc_html__('View Profile', 'nokri'); ?></a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <aside class="n-single-sidebar">
                    <?php if (!empty($skill_tags)) { ?>
                    <div class="n-skills">
                        <h6 class="title-label bottom-padding">Job Categories</h6>
                        <div class="n-skills-tags">
                            <?php echo "" . ($skill_tags); ?>
                        </div>
                    </div>
                <?php } ?>
                </aside>
            </div>
            </div>
            </div>
        </div>
    </div>
</section>

<section class="n-single-job no-padding bottom-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 no-padding top-padding">
<!--                 <h6 class="title-label">Job Status: <span>Posted <?php echo nokri_time_ago(); ?></span></h6> -->
                <span style="display:none;" class="job-status"><?php echo nokri_returnEcho($job_badge_ul); ?></span>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 no-padding">
                <div class="job-action">
                    <div class="n-single-sidebar">
                    <?php
                    /* Author Check */
                    if ($user_id == $post_author_id || current_user_can('administrator')) {
                        $direction = 'pull-right';
                        if (is_rtl()) {
                            $direction = 'pull-left';
                        }
                        $edit_url = esc_url(nokri_set_url_param(get_the_permalink($nokri['sb_post_ad_page']), 'id', esc_attr($job_id)));
                        ?>
                        <a href="<?php echo $edit_url; ?>" class="bluebutton n-btn-flat btn-clear for-author-only <?php echo esc_attr($direction); ?>"><?php echo esc_html__('Edit Job', 'nokri'); ?></a>
                        <?php
                    } else {
                        /* candidate Check */
                        if (get_user_meta($user_id, '_sb_reg_type', true) == '0') {
                            ?> 
                            <div class="apply-buttons">
                            </div>
                            <?php
                            if ($post_apply_status == 'active') {

                                $apply_status = nokri_job_apply_status($job_id);
                                $apply_now_text = esc_html__('Apply now', 'nokri');
                                if ($apply_status != "") {
                                    $apply_now_text = esc_html__('Applied', 'nokri');
                                }
                                ?>
                                <div class="apply-buttons">
                                    <?php if ($job_apply_with == 'exter') { ?>
                                        <a href="#" class="bluebutton n-btn-flat btn-clear external_apply pull-right" data-job-id="<?php echo esc_attr($job_id); ?>" data-job-exter="<?php echo esc_url($job_apply_url); ?>"><?php echo esc_html__('External Apply', 'nokri'); ?></a>
                                    <?php } else if ($job_apply_with == 'mail') { ?>
                                        <input type="hidden" class="external_mail_val" value="<?php echo esc_url($job_apply_mail) ?>"/>
                                        <a href="#" class="bluebutton n-btn-flat btn-clear email_apply pull-right" data-job-id="<?php echo esc_attr($job_id); ?>" data-job-exter="<?php echo ( $job_apply_mail ); ?>"><?php echo esc_html($apply_now_text); ?></a> 
                                    <?php }  
                                    else if ($job_apply_with == 'whatsapp') { ?>
                                        <input type="hidden" class="external_whatsapp_val" value="<?php echo $job_apply_whatsapp?>"/>
                                        <a href="https://api.whatsapp.com/send?phone=<?php echo $job_apply_whatsapp ?>" class="bluebutton n-btn-flat btn-clear whatsapp_apply pull-right" data-job-id="<?php echo esc_attr($job_id); ?>" data-job-exter="<?php echo ( $job_apply_whatsapp ); ?>"><?php echo esc_html($apply_now_text); ?></a> 
                                    <?php 
                                    }
                                   
                                    else { ?>
                                        <a href="javascript:void(0)" class="bluebutton n-btn-flat btn-clear apply_job pull-right" data-job-id="<?php echo esc_attr($job_id); ?>" data-author-id="<?php echo esc_attr($post_author_id); ?>" data-toggle="modal" data-target="#myModal" id="applying_job"><?php echo esc_html($apply_now_text); ?></a>
                                        <?php
                                    }
                                    /* Enable/disable linkedin apply */
                                    if ((isset($nokri['cand_linkedin_apply'])) && $nokri['cand_linkedin_apply'] == 1) {
                                        /* Linkedin key */
                                        $linkedin_api_key = '';
                                        if ((isset($nokri['linkedin_api_key'])) && $nokri['linkedin_api_key'] != '' && (isset($nokri['linkedin_api_secret'])) && $nokri['linkedin_api_secret'] != '' && (isset($nokri['redirect_uri'])) && $nokri['redirect_uri'] != '') {
                                            $linkedin_api_key = ($nokri['linkedin_api_key']);
                                            $linkedin_secret_key = ($nokri['linkedin_api_secret']);
                                            $redirect_uri = ($nokri['redirect_uri']);
                                            $state = 'not_logged_in-' . $job_id;
                                            if (is_user_logged_in()) {
                                                $state = 'logged_in-' . $job_id;
                                            }
                                            echo '<a href="https://www.linkedin.com/oauth/v2/authorization?response_type=code&client_id=' . $linkedin_api_key . '&redirect_uri=' . $redirect_uri . '&state=' . esc_attr($state) . '&scope=r_liteprofile r_emailaddress" class="btn-linkedIn btn-block"><i class="ti-linkedin"></i> <span>' . esc_html__('Apply With LinkedIn', 'nokri') . '</span></a>';
                                        }
                                    }
                                    ?>
                                </div>
                            <?php } else { ?> <a href="javascript:void(0)" class="bluebutton n-btn-flat btn-clear pull-right"><?php echo esc_html__('Job Expired', 'nokri'); ?></a><?php
                            }
                        }
                    }
                    ?>
                        <a class="outlinebluebutton n-btn-flat save_job pull-right" href="javascript:void(0)" data-value=<?php echo esc_attr($job_id); ?>><i class="fa fa-heart"></i></a>
                    <?php if ($is_email_job) { ?>
                            <a class="outlinebluebutton n-btn-flat email_this_job pull-right" href="javascript:void(0)" data-job-id=<?php echo esc_attr($job_id); ?>><i class="fa fa-share"></i></a>
                        <?php                         
                    }               
                    if (current_user_can('manage_options')  && $job_apply_with != "exter" && $job_apply_with != "email" ) {
                        ?>
                            <a class="outlinebluebutton n-btn-flat download_admin_resumes pull-right" href="javascript:void(0)" data-job-id=<?php echo esc_attr($job_id); ?>><i class="fa fa-download"></i></a>
                    <?php } ?>
                
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="n-single-job n-detail-transparent no-padding">
    <div class="container">
        <div class="row">
            <?php if (get_post_status() == 'pending') { ?>
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong><?php echo esc_html__('Information ! ', 'nokri'); ?></strong><?php echo esc_html__('Your job is awaiting for admin approval', 'nokri'); ?> 
                    </div>
                <?php } ?>
                <?php echo ($advert_up); ?>
            
            
            
            
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 job-detail-column">
                <div class="purewhite-background">
                    <div class="job-detail">
                        <i class="fa fa-sun-o"></i>
                        <h3><?php echo nokri_job_post_single_taxonomies('job_type', $job_type); ?>  
                        <?php if (!empty($project)) { ?>
                            <span>-</span> <?php echo $project; ?>
                        <?php } ?>
                        </h3>
                        <p>Seasons For Hire</p>
                    </div>
                </div>
            </div>
            
            
            <?php if (!empty($job_salary_type)) { ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 job-detail-column">
                <div class="purewhite-background">
                    <div class="job-detail">
                        <i class="fa fa-dollar"></i>
                        <h3><?php echo nokri_job_post_single_taxonomies('job_salary_type', $job_salary_type); ?></h3>
                        <p>Payment Schedule</p>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <?php if (!empty($job_deadline)) { ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 job-detail-column">
                <div class="purewhite-background">
                    <div class="job-detail">
                        <i class="fa fa-home"></i>
                        <h3><?php echo nokri_job_post_single_taxonomies('job_salary', $job_salary); ?></h3>
                        <p>Employee Housing</p>
                    </div>
                </div>
            </div>
            <?php } ?>
             
            <?php if (!empty($job_shift)) { ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 job-detail-column">
                <div class="purewhite-background">
                    <div class="job-detail">
                        <i class="fa fa-clock-o"></i>
                        <h3><?php echo nokri_job_post_single_taxonomies('job_shift', $job_shift); ?></h3>
                        <p>Job Type</p>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <?php if (!empty($job_level)) { ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 job-detail-column">
                <div class="purewhite-background">
                    <div class="job-detail">
                        <i class="fa fa-lock"></i>
                        <h3><?php echo nokri_job_post_single_taxonomies('job_level', $job_level); ?></h3>
                        <p>Minimum Age</p>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <?php if (!empty($job_experience)) { ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 job-detail-column">
                <div class="purewhite-background">
                    <div class="job-detail">
                        <i class="fa fa-history"></i>
                        <h3><?php echo nokri_job_post_single_taxonomies('job_experience', $job_experience); ?></h3>
                        <p>Minimum Experience</p>
                    </div>
                </div>
            </div>
            <?php } ?>
                
        </div>
    </div>
</section>
            
<section class="n-single-job n-detail-transparent no-padding">
    <div class="container">
        <div class="row">  
        <div class="job-description-container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 job-detail-column">
                <div class="purewhite-background full-height">
                    <div class="job-description">
                        
                    <span>    
                        <h3><?php echo esc_html__('Job Description', 'nokri'); ?></h3>
                        <?php
                        $format_allow = isset($nokri['formated_text_allow_check']) ? $nokri['formated_text_allow_check'] : true;
                        if (!$format_allow) {
                            echo nokri_get_formated_description(get_the_content());
                        } else {
                            the_content();
                        } ?>
                    </span> 

                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</section>

<section class="n-single-job n-detail-transparent no-top-padding bottom-padding">
    <div class="container">
        <div class="row">  
        <div class="job-description-container">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 job-detail-column">
                <div class="purewhite-background full-height">
                    <div class="job-description">
                        
                        <span>
                        <?php if (!empty($job_vacancy)) { ?>
                                <h3><?php echo esc_html__('Positions to Fill', 'nokri'); ?></h3>
                                <h6><?php echo esc_html($job_vacancy) . " " . ($opening_text); ?></h6>
                        <?php } ?>
                        </span>
                        
                        <span>
                        <?php if (!empty($job_currency)) { ?>
                                <h3><?php echo esc_html__('International Applicants Accepted?', 'nokri'); ?></h3>
                                <h6><?php echo nokri_job_post_single_taxonomies('job_currency', $job_currency); ?></h6>
                        <?php } ?>
                        </span>
                        
                        

                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 job-detail-column">
                <div class="purewhite-background full-height">
                    <div class="job-description">
                        
                        <span>
                        <?php if (!empty($job_qualifications)) { ?>
                                <h3><?php echo nokri_feilds_label('quali_txt', esc_html__('Job Qualifications', 'nokri')); ?></h3>
                                <h6><?php echo nokri_job_post_single_taxonomies('job_qualifications', $job_qualifications); ?></h6>
                        <?php } ?>
                        </span>
                        
                        <span>   
                        <?php if ((isset($nokri['single_job_tags'])) && $nokri['single_job_tags'] == 1 && !empty($tags_html)) { ?>
                            <h3><?php echo esc_html__('Job Tags', 'nokri'); ?></h3>
                            <ul class="job-tag-list">
                                <?php echo "" . ($tags_html); ?>
                            </ul>
                        <?php } ?>
                        </span> 
                        
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>          
                

<div class="modal fade resume-action-modal" id="myModal-linkedin_url">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="submit_linkedin_url" class="apply-job-modal-popup">
                <div class="modal-header">
                    <h4 class="modal-title"><?php echo esc_html__('Want to apply for this job', 'nokri'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label><?php echo esc_html__('Enter your linkedin profile url', 'nokri'); ?><span class="color-red">*</span></label>
                            <input placeholder="<?php echo esc_html__('Enter your linkedin profile url', 'nokri'); ?>" class="form-control" type="text" name="linkedin_url"  data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__('Enter your linkedin profile url', 'nokri'); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit"  class="btn n-btn-flat btn-mid btn-block submit_linkedin_url">
                            <?php echo esc_html__('Apply Now', 'nokri'); ?>
                        </button>
                    </div>
                </div>
                <input type="hidden" value="<?php echo esc_attr($job_id); ?>"  name="apply_job_id" />
            </form>
        </div>
    </div>
</div>
<?php
if (isset($_GET['src']) && $_GET['src'] == 'lkn') {
    echo "<script type='text/javascript'>
	jQuery(window).load(function(){
		jQuery('#myModal-linkedin_url').modal({backdrop: 'static', keyboard: false});
	jQuery('#myModal-linkedin_url').modal('show');
	});
	</script>";
}
if ($single_job_schema) {
    ?>
    <script type="application/ld+json">
        {
        "@context": "https://schema.org/",
        "@type": "JobPosting",
        "title": "<?php the_title(); ?>",
        "description": "<?php echo wp_strip_all_tags(get_the_content()); ?>",
        "hiringOrganization" : {
        "@type": "Organization",
        "name": "<?php echo esc_html($company_name); ?>",
        "sameAs": "<?php echo esc_url($web); ?>"
        },
        "employmentType": "<?php echo nokri_job_post_single_taxonomies('job_type', $job_type); ?>",
        "datePosted": "<?php echo get_the_date('Y-m-d'); ?>",
        "validThrough": "<?php echo esc_html($job_deadline); ?>",
        "jobLocation": {
        "@type": "Place",
        "address": {
        "@type": "PostalAddress",
        "addressCountry": "<?php echo $countries_last; ?>"
        }
        },
        "baseSalary": {
        "@type": "MonetaryAmount",
        "currency": "<?php echo nokri_job_post_single_taxonomies('job_currency', $job_currency); ?>",
        "value": {
        "@type": "QuantitativeValue",
        "value": "<?php echo nokri_job_post_single_taxonomies('job_salary', $job_salary); ?>",
        "unitText": "<?php echo nokri_job_post_single_taxonomies('job_salary_type', $job_salary_type); ?>"
        }
        },
        "qualifications": "<?php echo nokri_job_post_single_taxonomies('job_qualifications', $job_qualifications); ?>",
        "experienceRequirements": "<?php echo nokri_job_post_single_taxonomies('job_experience', $job_experience); ?>"
        }
    </script>
    <?php
}    