<?php $cand_profession = get_user_meta($user_crnt_id, '_cand_profession', true);
    if ($prof_sec && $cand_profession && $cand_profession[0]['project_organization'] != '') { ?>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 no-padding full-height">
                    <aside class="resume-3-sidebar resume-column">
                        <div class="n-candidate-info">
                            <h4 class="widget-heading"><?php echo nokri_feilds_label('cand_det', esc_html__('Work History', 'nokri')); ?></h4>
                                    <div class="work-history">
                                        <div class="resume-timeline">
                                            <?php
                                            foreach ($cand_profession as $profession) {
                                                $project_end = $profession['project_end'];
                                                if ($profession['project_end'] == '') {
                                                    $project_end = esc_html__('Currently working', 'nokri');
                                                }
                                                $project_role = (isset($profession['project_role'])) ? '<h5 class="degree-name">' . esc_html($profession['project_role']) . '</h5>' : '';
                                                $project_org = (isset($profession['project_organization'])) ? '<span class="institute-name">' . $profession['project_organization'] . '</span>' : '';
                                                $project_strt = (isset($profession['project_start'])) ? esc_html($profession['project_start']) : '';
                                                $project_detail = (isset($profession['project_desc'])) ? '<p>' . wp_strip_all_tags($profession['project_desc']) . '</p>' : '';
                                                ?>
                                                <div class="resume-timeline-box">
                                                    <?php echo "" . ($project_org) . ($project_role) . ($project_detail); ?>
                                                    <span class="degree-duration"><?php
                                                        echo esc_html($project_strt) . " ";
                                                        if ($project_end != '') {
                                                            echo '-' . ($project_end);
                                                        } ?></span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>                            
                        
                    </aside>
                </div>