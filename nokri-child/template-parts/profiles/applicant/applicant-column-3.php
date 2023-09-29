<?php if (isset($registration_feilds) && $registration_feilds != '' || isset($custom_feilds_cand) && $custom_feilds_cand != '') { ?> 
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 no-padding full-height">
        <aside class="resume-3-sidebar resume-column">
            <div class="n-candidate-info">
                <h4 class="widget-heading"><?php echo nokri_feilds_label('cand_det', esc_html__('Additional Information', 'nokri')); ?></h4>
                    <div class="resume-3-box">
                        <div class="custom-field-box">
                            <div class="n-can-custom-meta">
                                <ul class="n-can-custom-meta-detail">
<!--
                                    <li>
                                        <?php 
                                            $sessonid = get_user_meta($author_id, '_cand_type', true); 
                                            $object_term =  get_term_by( 'id', $sessonid, 'job_type' );
                                        ?>
                                        <small>Season for Hire: </small><p><?php echo $object_term->name; ?></p>
                                    </li>
-->

                                    <li>
                                        <?php 
                                            $sessonid = get_user_meta($author_id, '_cand_salary_curren', true); 
                                            $object_term =  get_term_by( 'id', $sessonid, 'job_currency' );
                                        ?>
                                        <small>Are You An International Applicant? </small><p><?php echo $object_term->name; ?></p>
                                    </li>
                                    
                                    <li>
                                        <?php 
                                            $sessonid = get_user_meta($author_id, '_cand_salary_range', true); 
                                            $object_term =  get_term_by( 'id', $sessonid, 'job_salary' );
                                        ?>
                                        <small>Do You Need Employee Housing? </small><p><?php echo $object_term->name; ?></p>
                                    </li>
                                    <?php echo $registration_feilds . $custom_feilds_cand; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
            </div>
        </aside>
    </div>
<?php } ?>