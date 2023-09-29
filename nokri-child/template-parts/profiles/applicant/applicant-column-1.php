<?php if ($cand_introd != '' && nokri_feilds_operat('cand_about_setting', 'show')) { ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding full-height">
        <aside class="resume-3-sidebar resume-column">
            <div class="n-candidate-info">
                <h4 class="widget-heading"><?php echo nokri_feilds_label('cand_det', esc_html__('Background', 'nokri')); ?></h4>
                    <p><?php echo ($cand_introd); ?></p>
            </div>
        </aside>
    </div>
<?php } ?>