<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
            <?php if ($cand_save_setting) { ?>
                            <div class="resume-action">
                                <button type="submit" class="outlinebluebutton n-btn-flat btn n-btn-custom btn-block saving_resume" data-cand-id= <?php echo esc_attr($author_id); ?>><i class="fa fa-heart"></i><?php echo nokri_feilds_label('cand_save_resume', esc_html__('Save Resume', 'nokri')); ?></button>
                            <?php } if ($cand_resume_down && $cand_resume_setting) echo $resume_id; ?>
                            <?php if ($cand_resume_gen && $cand_generate_setting) { ?>

                                <a href="#" class="btn n-btn-custom btn-block"  data-toggle="modal" data-target="#template_modal"><i class="fa fa-download"></i><?php echo nokri_feilds_label('cand_generate_resume', esc_html__('Generate Resume', 'nokri')); ?></a>
                            <?php } ?>
                            </div>
            </div>