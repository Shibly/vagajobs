<div class="container full-width no-padding about-content">
    <div class="row width-moz flex-row tablet">
                                    
        <div class="gallery-column col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                
        <?php if ($port_sec && $portfolio_html) { ?>
            <div class="n-candidate-info">
                <h4 style="display:none;" class="widget-heading"><?php echo nokri_feilds_label('emp_gall_lab', esc_html__('Employer Gallery:', 'nokri')); ?> </h4>
                    <ul class="emp-gallery owl-carousel owl-theme"><?php echo ($portfolio_html); ?></ul>
                            </div>
                        <?php } if (!empty($emp_video)) { ?>
                            <div class="n-candidate-info">
                                <h4 style="display:none;" class="widget-heading"><?php echo nokri_feilds_label('emp_vid_lab', esc_html__('Employer Video:', 'nokri')); ?> </h4>
                                <?php
                                $rx = '~
							  ^(?:https?://)?                           # Optional protocol
							   (?:www[.])?                              # Optional sub-domain
							   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
							   ([^&]{11})                               # Video id of 11 characters as capture group 1
								~x';
                                $valid = preg_match($rx, $emp_video, $matches);
                                $emp_video = $matches[1];
                                ?>
                                <div class="videoWrapper">
                                <iframe width="320" height="300" src="https://www.youtube.com/embed/<?php echo "" . ($emp_video); ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                            
        <div class="detail-column col-lg-7 col-md-12 col-sm-12 col-xs-12">
                                
         <?php if (get_user_meta($user_id, '_emp_intro', true) != '' && nokri_feilds_operat('emp_about_setting', 'show')) { ?>
            <div class="n-candidate-info">
                <h4 class="widget-heading"><?php echo nokri_feilds_label('emp_about_label', esc_html__('About Company:', 'nokri')); ?></h4>
                                        <?php
                                        $intro = get_user_meta($user_id, '_emp_intro', true);
                                        if (!preg_match('%(<p[^>]*>.*?</p>)%i', $intro, $regs)) {
                                            echo '<p>' . get_user_meta($user_id, '_emp_intro', true) . '</p>';
                                        } else {
                                            echo get_user_meta($user_id, '_emp_intro', true);
                                        }
                                        ?>
                                    </div>
                                
                        <div class="container full-width">
                            <div class="row flex-row mobile">
                                <?php } if ($housing_tags != "" && $emp_spec_switch) { ?>
                                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 about-detail-column">
                                      <div class="blue-background">
                                        <div class="about-detail">
                                            <i class="fa fa-home"></i>
                                            <h3><?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $housing_tags . '</ul></div></div>'; ?></h3>
                                            <p>Employee Housing</p>
                                          </div>
                                        </div>
                                    </div>
                                <?php } if ($meal_tags != "" && $emp_spec_switch) { ?>
                                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 about-detail-column">
                                      <div class="blue-background">
                                        <div class="about-detail">
                                            <i class="fa fa-cutlery"></i>
                                            <h3><?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $meal_tags . '</ul></div></div>'; ?></h3>
                                            <p>Meal Plan</p>
                                          </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                                <?php if ($skill_tags != "" && $emp_spec_switch) { ?>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 about-detail-column">
                                      <div class="purewhite-background">
                                        <div class="n-candidate-info">
                                            <h4 class="widget-heading"><?php echo nokri_feilds_label('emp_spec_label', esc_html__('Employer Specialization', 'nokri')); ?></h4>
                                            <?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $skill_tags . '</ul></div></div>'; ?>
                                          </div>
                                        </div>
                                    </div>
                                <?php } if (isset($registration_feilds) && $registration_feilds != '' || isset($custom_feilds_emp) && $custom_feilds_emp != '') { ?>
                                    <div class="n-camp-custom-fields">
                                        <?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail widget-heading-ul">' . $registration_feilds . $custom_feilds_emp . '</ul></div></div>'; ?>
                                    </div>
                                <?php } ?>
            </div>
    </div>
</div>