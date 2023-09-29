<aside class="profile-sidebar">
                        <div class="n-candidate-info justify-row-center">
                            <div class="n-candidate-img-box">
                                <div style="background: url(<?php echo esc_url($image_link[0]); ?>);" class="logo-responsive profile img-responsive" alt="<?php echo esc_attr__('company profile image', 'nokri'); ?>"></div>
                            </div>
                        </div>
                        
                            <div class="n-candidate-meta-box hide-on-desktop">
                                <?php if ($emp_profile_status == 'pub') { ?>
                                    <h4><?php echo the_author_meta('display_name', $user_id); ?></h4>
                                <?php } if ($emp_headline && nokri_feilds_operat('emp_heading_setting', 'show')) { ?>
                                    <p><?php echo esc_html($emp_headline); ?></p>
                                <?php } ?>
                            </div>
                        <div class="n-candidate-info hide-on-mobile">
                            <ul>
                        <?php if ($emp_profile_status == 'pub') { ?>
                            <li>
                                <div class="resume-detail-meta"><small><?php echo the_author_meta('display_name', $user_id); ?></small></div>
                            </li>
                        <?php } ?>
                            </ul>
                        </div>

                        <div class="n-candidate-info">
                            <h4 class="widget-heading"><?php echo nokri_feilds_label('emp_det', esc_html__('Contact Info', 'nokri')); ?></h4>
                            <ul>
                                <?php if ($emp_size) { ?>
                                    <li>
                                        <div class="resume-detail-meta"><a href="mailto:<?php echo esc_attr($emp_size); ?>"><small><?php echo nokri_feilds_label('emp_no_emp_label', esc_html__('Company Email', 'nokri')); ?></small><p><?php echo esc_html($emp_size); ?></p></a></div>
                                    </li>
                                <?php } if ($emp_web && nokri_feilds_operat('emp_web_setting', 'show')) { ?>
                                    <li> 
                                        <div class="resume-detail-meta"><a href="<?php echo esc_url($emp_web); ?>" target="_blank"><small><?php echo nokri_feilds_label('emp_web_label', esc_html__('Website URL:', 'nokri')); ?></small><p>View Site</p></a></div>
                                    </li>
                                <?php } if ($is_public && $author_id == $current_user_id) { ?>
                                    <li>
                                        <div class="resume-detail-meta"><a href="tel:<?php echo esc_attr($emp_cntct); ?>"> <small><?php echo nokri_feilds_label('emp_phone_label', esc_html__('Contact Number:', 'nokri')); ?></small><p><?php echo esc_html($emp_cntct); ?></p></a></div>
                                    </li>
                                <?php } if ($soc_sec && $social_links || $author_id == $current_user_id) { ?>
                                <ul class="social-links list-inline">
                                    <?php if ($emp_fb) { ?>
                                        <li> <a href="<?php echo esc_url($emp_fb); ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <?php } if ($emp_google) { ?>
                                        <li> <a href="<?php echo esc_url($emp_google); ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                    <?php } if ($emp_twitter) { ?>
                                        <li> <a href="<?php echo esc_url($emp_twitter); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <?php } if ($emp_linkedin) { ?>
                                        <li> <a href="<?php echo esc_url($emp_linkedin); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                    <?php } ?>
                                </ul>
                            <?php } if ($application_tags != "" && $emp_spec_switch) { ?>
                                    <div class="application-tag">
                                        <h4 class="widget-heading">Application</h4>
                                        <?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $application_tags . '</ul></div></div>'; ?>       
                                    </div>
                            <?php } ?>
                            </ul>
                        </div>

                        <?php if ($is_public_contact) { ?>
                            <div class="widget">
                                <h4 class="widget-heading"><?php
                                    echo nokri_feilds_label('emp_cont_lab', esc_html__('Contact ', 'nokri')) . " ";
                                    echo the_author_meta('display_name', $user_id);
                                    ?></h4>
                                <form id="contact_form_email" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <input type="text" name="contact_name" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__('Please enter name', 'nokri'); ?>" class="form-control" placeholder="<?php echo esc_html__('Full name', 'nokri'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="contact_email" class="form-control" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__('Please enter email', 'nokri'); ?>"  placeholder="<?php echo esc_html__('Email address', 'nokri'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__('Please enter subject', 'nokri'); ?>" name="contact_subject"   placeholder=" <?php echo esc_html__('Subject', 'nokri'); ?>">
                                    </div>
                                    <input type="hidden" name="receiver_id" value="<?php echo esc_attr($author_id); ?>" />
                                    <div class="form-group">
                                        <textarea name="contact_message" class="form-control"  rows="5"></textarea>
                                    </div>
                                    <?php if ($nokri['google_recaptcha_key'] != "" && $contact_recaptcha) { ?>

                                        <div class="g-recaptcha form-group" name="contact-recaptcha" data-sitekey="<?php echo esc_attr($nokri['google_recaptcha_key']); ?>">
                                        </div>             
                                    <?php } ?>
                                    <button type="submit" class="btn n-btn-flat btn-mid btn-block contact_me"><?php echo esc_html__('Message', 'nokri'); ?></button>
                                </form>
                            </div>

                        <?php } ?>
                    </aside>