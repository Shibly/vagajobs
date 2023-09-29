<aside class="profile-sidebar" style="margin-top:unset !important">
    <div class="n-candidate-info justify-row-center">
        <div class="n-candidate-img-box">
            <img src="<?php echo esc_url($image_link); ?>" class="img-responsive" alt="<?php echo esc_attr__('image', 'nokri'); ?>">
        </div>
    </div>

    <div class="n-candidate-info">
        
        <div class="n-candidate-meta-box" style="margin-left: 0px">
            <?php if ($cand_profile_status == 'pub') { ?>
                <h4><?php echo esc_html($author->display_name); ?></h4>
            <?php
            } 
            if (($is_public && $cand_email_setting ) || $author_id == $current_user_id) { ?>
                <div class="resume-detail-meta"><p><?php echo esc_html($author->user_email); ?></p></div>
            <?php 
            } if (($is_public && $cand_phone != "" && $cand_phone_setting)) {
                ?>
                    <div class="resume-detail-meta"><p><?php echo esc_html($cand_phone); ?></p></div>
                <?php
            } 
            if ($soc_sec && $social_links && $cand_profile_status == 'pub' || $author_id == $current_user_id) {
                ?>
                <ul class="social-links list-inline">
                    <?php if ($cand_fb) { ?>
                        <li> <a href="<?php echo esc_url($cand_fb); ?>"><i class="fa fa-facebook"></i></a></li>
                    <?php } if ($cand_google) { ?>
                        <li> <a href="<?php echo esc_url($cand_google); ?>"><i class="fa fa-instagram"></i></a></li>
                    <?php } if ($cand_twiter) { ?>
                        <li> <a href="<?php echo esc_url($cand_twiter); ?>"><i class="fa fa-twitter"></i></a></li>
                    <?php } if ($cand_linked) { ?>
                        <li> <a href="<?php echo esc_url($cand_linked); ?>"><i class="fa fa-linkedin"></i></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <br>
            <h4>Availability</h4>
            <div class="resume-detail-meta">
                <p> <?php echo bt_get_month_year( get_user_meta($author_id, 'cand_availability', true) ); ?></p>
            </div>
            <br>
            <?php $cand_education = get_user_meta($user_crnt_id, '_cand_education', true);
            if ($edu_sec && $cand_education && $cand_education[0]['degree_name'] != '') {
            ?>
                <h4><?php echo nokri_feilds_label('cand_det', esc_html__('Education', 'nokri')); ?></h4>

                <?php foreach ($cand_education as $edu) {
                    $degre_name = (isset($edu['degree_name'])) ? '<p style="font-size:12px !important;margin-bottom:5px !important">' . esc_html($edu['degree_name']) . '</p>' : '';
                    $degre_insti = (isset($edu['degree_institute'])) ? '<small>' . esc_html($edu['degree_institute']) . '</small>' : '';
                    ?>
                    <div class="resume-detail-meta">
                        <?php echo $degre_name; ?>
                        <?php echo $degre_insti; ?>
                    </div>
                <?php } ?>

            <?php } ?>
            <br>
            <?php if ($skill_sec && !empty($skills_bar)) { ?>
            <div class="bt_cand_skills">
                <h4 style="margin-bottom:10px !important"><?php echo nokri_feilds_label('cand_skills_label', esc_html__('Industry Skills', 'nokri')); ?></h4>
                <?php echo "" . ($skills_bar); ?>
            </div>
            <?php } ?>
        </div>

        
    </div>

</aside>