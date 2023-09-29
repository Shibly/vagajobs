<?php
                        $args = array(
                            'post_type' => 'job_post',
                            'orderby' => 'id',
                            'order' => 'ASC',
                            'author' => $author_id,
                            'post_status' => 'publish',
                            'meta_query' => array(array('key' => '_job_status', 'value' => 'active', 'compare' => '=',
                                ),
                            ),
                        );
                        $results = new WP_Query($args);
                        ?>
                        <div class="n-related-jobs">
                            <?php if ($results->have_posts()) { ?>
                                <div class="n-search-listing n-featured-jobs">
                                    <div class="n-featured-job-boxes">
                                        <?php
                                        while ($results->have_posts()) {
                                            $results->the_post();
                                            $rel_post_id = get_the_id();
                                            $rel_post_author_id = get_post_field('post_author', $rel_post_id);
                                            /* Getting Company  Profile Photo */
                                            $comp_img_html = '';
                                            $rel_image_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
                                            if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
                                                $rel_image_link = array($nokri['nokri_user_dp']['url']);
                                            }
                                            if (get_user_meta($rel_post_author_id, '_sb_user_pic', true) != "") {
                                                $attach_id = get_user_meta($rel_post_author_id, '_sb_user_pic', true);
                                                $rel_image_link = wp_get_attachment_image_src($attach_id, 'nokri_job_post_single');
                                            }

                                            if ($rel_image_link[0] != '') {
                                                $comp_img_html = '<div class="n-job-img"><img src="' . esc_url($rel_image_link[0]) . '" alt="' . esc_attr__('logo', 'nokri') . '" class="img-responsive img-circle"></div>';
                                            }
                                            $project = nokri_job_categories_with_chlid_no_href($rel_post_id,'job_category');
                                            $job_deadline_n = get_post_meta($rel_post_id, '_job_date', true);
                                            $job_deadline = date_i18n(get_option('date_format'), strtotime($job_deadline_n));
                                            $job_salary = wp_get_post_terms($rel_post_id, 'job_salary', array("fields" => "ids"));
                                            $job_salary = isset($job_salary[0]) ? $job_salary[0] : '';
                                            $job_salary_type = wp_get_post_terms($rel_post_id, 'job_salary_type', array("fields" => "ids"));
                                            $job_salary_type = isset($job_salary_type[0]) ? $job_salary_type[0] : '';
                                            $job_experience = wp_get_post_terms($rel_post_id, 'job_experience', array("fields" => "ids"));
                                            $job_experience = isset($job_experience[0]) ? $job_experience[0] : '';
                                            $job_currency = wp_get_post_terms($rel_post_id, 'job_currency', array("fields" => "ids"));
                                            $job_currency = isset($job_currency[0]) ? $job_currency[0] : '';
                                            $job_type = wp_get_post_terms($rel_post_id, 'job_type', array("fields" => "ids"));
                                            $job_type = isset($job_type[0]) ? $job_type[0] : '';
                                            $job_shift = wp_get_post_terms($rel_post_id, 'job_shift', array("fields" => "ids"));
                                            $job_shift = isset($job_shift[0]) ? $job_shift[0] : '';
                                            $job_level = wp_get_post_terms($rel_post_id, 'job_level', array("fields" => "ids"));
                                            $job_level = isset($job_level[0]) ? $job_level[0] : '';
                                            /* Calling Funtion Job Class For Badges */
                                            $single_job_badges = nokri_job_class_badg($rel_post_id);
                                            $job_badge_text = '';
                                            if (count((array) $single_job_badges) > 0) {
                                                foreach ($single_job_badges as $job_badge => $val) {
                                                    $term_vals = get_term_meta($val);
                                                    $bg_color = get_term_meta($val, '_job_class_term_color_bg', true);
                                                    $color = get_term_meta($val, '_job_class_term_color', true);

                                                    $style_color = '';
                                                    if ($color != "") {
                                                        $style_color = 'style=" background-color: transparent !important; color: ' . $color . ' !important;"';
                                                    }
                                                    $job_badge_text .= '<li><a href="' . get_the_permalink($nokri['sb_search_page']) . '?job_class=' . $val . '" class="job-class-tags-anchor" ' . $style_color . '><span>' . esc_html(ucfirst($job_badge)) . '</span></a></li>';
                                                }
                                            }
                                            if (is_user_logged_in()) {
                                                $user_id = get_current_user_id();
                                            } else {
                                                $user_id = '';
                                            }
                                            $job_bookmark = get_post_meta($rel_post_id, '_job_saved_value_' . $user_id, true);
                                            if ($job_bookmark == '') {
                                                $save_job = '<a class="n-job-saved save_job" href="javascript:void(0)" data-value = "' . $rel_post_id . '"><i class="ti-heart"></i></a> ';
                                            } else {
                                                $save_job = '<a class="n-job-saved saved" href="javascript:void(0)"><i class="fa fa-heart"></i></a>';
                                            }

                                            /* Getting Last country value */
                                            $job_locations = array();
                                            $last_location = '';
                                            $job_locations = wp_get_object_terms($rel_post_id, array('ad_location'), array('orderby' => 'term_group'));

                                            if (!empty($job_locations)) {
                                                $location_html = '';
                                                foreach ($job_locations as $term) {
                                                    if( $term->parent == 264 ){
                                                        $link = nokri_set_url_param(get_the_permalink($nokri['sb_search_page']), 'job-location', esc_attr($term->term_id));
                                                        $final_url = esc_url(nokri_page_lang_url_callback($link));
                                                        $location_html .= '<a href="' . $final_url . '">' . $term->name . '</a>, ';
                                                        
                                                    }
                                                }
                                                $location_html = trim( $location_html );
                                                $last_location = rtrim( $location_html, ',' );
                                            }

                                            ?>
                                            <div class="n-job-single job-search-loop">
                                                <div class="n-job-detail">
                                                    <ul class="list-inline">
                                                        <li class="n-job-title-box">
                                                            <h4><a href="<?php echo the_permalink($rel_post_id); ?>" class="job-title"><?php echo the_title(); ?></a></h4>
                                                            <p><i class="fa fa-map-marker"></i><?php echo " " . $last_location; ?></p>
                                                        </li>
                                                        <li class="n-job-short">
                                                            
                                                            <?php if (!empty($job_type)) { ?>
                                                            <span class="tooltip">
                                                                <i class="fa fa-sun-o"></i>
                                                                <span class="tooltiptext"><p>Season for Hire</p><?php echo nokri_job_post_single_taxonomies('job_type', $job_type); ?></span>
                                                            </span>
                                                            <?php } if (!empty($job_deadline)) { ?>
                                                            <span class="tooltip">
                                                                <i class="fa fa-calendar"></i>
                                                                <span class="tooltiptext"><p>Deadline to Apply</p><?php echo " " . ($job_deadline); ?></span>
                                                            </span>
                                                            <?php } if (!empty($job_shift)) { ?>
                                                            <span class="tooltip">
                                                                <i class="fa fa-clock-o"></i>
                                                                <span class="tooltiptext"><p>Job Type</p><?php echo nokri_job_post_single_taxonomies('job_shift', $job_shift); ?></span>
                                                            </span>
                                                            <?php } if (!empty($job_level)) { ?>
                                                            <span class="tooltip">
                                                                <i class="fa fa-lock"></i>
                                                                <span class="tooltiptext"><p>Minimum Age</p><?php echo nokri_job_post_single_taxonomies('job_level', $job_level); ?></span>
                                                            </span>
                                                            <?php } if (!empty($job_experience)) { ?>
                                                            <span class="tooltip">
                                                                <i class="fa fa-thermometer-half"></i>
                                                                <span class="tooltiptext"><p>Minimum Experience</p><?php echo nokri_job_post_single_taxonomies('job_experience', $job_experience); ?></span>
                                                            </span>
                                                            <?php } ?>
                                                        </li>
                                                        <li class="n-job-divider"></li>
                                                        <li class="n-job-btns">
                                                            <a href="<?php echo the_permalink($rel_post_id); ?>" class="view-job-link"><?php echo esc_html__('View Job', 'nokri'); ?></a>
                                                        </li>
                                                        <li style="display: none;" class="n-job-btns">
                                                            <a href="javascript:void(0)" class="apply-link apply_job" data-job-id="<?php echo esc_attr($rel_post_id); ?>" data-toggle="modal" data-target="#myModal"><?php echo esc_html__('Apply now', 'nokri'); ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="heading-title left">
                                    <h4><?php echo esc_html__('No open positions', 'nokri'); ?></h4>
                                </div>
                            <?php } ?>
                        </div>