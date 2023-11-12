<?php
/* Employer Dashboard */
global $nokri;
$user_id = get_current_user_id();
$post_found = '';
$args = array(
    'post_type' => 'job_post',
    'orderby' => 'date',
    'order' => 'DESC',
    'author' => $user_id,
    'post_status' => array('publish', 'draft'),

);


$job_ids = array();

$query = new WP_Query($args);
$job_html = '';
if ($query->have_posts()) {
    $post_found = $query->found_posts;
    while ($query->have_posts()) {
        $query->the_post();
        $job_id = get_the_ID();
        $job_ids[] = get_the_ID();
        $resume_counts = nokri_get_resume_count($job_id);
        $post_status = get_post_status($job_id);
        $class = 'warning';
        if ($post_status == 'publish') {
            $post_status = esc_html__('active', 'nokri');
            $class = 'success';
        }
        // check for plugin post-views-counter
        $job_views = '';
        if (class_exists('Post_Views_Counter')) {
            $job_views = pvc_post_views($post_id = $job_id, '');
        }
        $job_html .= '<tr>
						<td><a href="' . get_the_permalink() . '">' . get_the_title($job_id) . '</a></td>
						<td><span class="label label-' . esc_attr($class) . '">' . $post_status . '</span></td>
						<td>' . $resume_counts . '</td>
						<td>' . $job_views . '</td>
                    </tr>';
    }
    wp_reset_postdata();
}

global $wp;
$export_url = home_url($wp->request) . '?export_csv_cand=' . get_current_user_id();
?>
<div class="main-body dashboard-overview">
    <h4 style="float: left;">Applied Candidates</h4>
    <a class="bt_export_cand_btn" href="<?php echo $export_url; ?>"> Export To CSV </a>
    <div style="clear:both"></div>
    <?php

    if (!empty($job_ids)):
        global $wpdb;
        $applier = array();
        $applier_jobs = array();
        $extra = " AND meta_key like '_job_applied_resume_%'";
        $query = "SELECT * FROM $wpdb->postmeta WHERE post_id in ( " . implode(',', $job_ids) . " ) $extra";
        $applier_resumes = $wpdb->get_results($query);
        if (!empty($applier_resumes)) {
            foreach ($applier_resumes as $resumes) {
                $array_data = explode('|', $resumes->meta_value);
                $applier[] = $array_data[0];
                $applier_jobs[$array_data[0]][] = $resumes->post_id;
            }
            $args = array(
                'include' => $applier,
                'order ' => 'ASC',
            );
            $user_query = new WP_User_Query($args);
            $authors = $user_query->get_results();
            $resume_table = $resume_link = '';
            // ...

// Initialize an array to hold the rows with applied date as keys
            $resume_rows = array();

            if ($authors) {
                foreach ($authors as $author) {
                    // get all the user's id's
                    $candidate_id = ($author->ID);

                    $cand_headline = get_user_meta($candidate_id, '_user_headline', true);

                    // Image fetching logic
                    $image_dp_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
                    if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
                        $image_dp_link = array($nokri['nokri_user_dp']['url']);
                    }
                    if (get_user_meta($candidate_id, '_cand_dp', true) != "") {
                        $attach_dp_id = get_user_meta($candidate_id, '_cand_dp', true);
                        $image_dp_link = wp_get_attachment_image_src($attach_dp_id, 'nokri_job_hundred');
                    }

                    // ... (rest of the code for fetching user details remains the same)

                    $latest_applied_date = 0;

                    $cjobs = $applier_jobs[$candidate_id];
                    $job_html = '<ul>';
                    foreach ($cjobs as $cjob) {
                        $job_link = get_permalink($cjob);
                        $job_title = get_the_title($cjob);
                        $job_date_str = get_post_meta($cjob, '_job_applied_date_' . $candidate_id, true);
                        $job_date_timestamp = strtotime($job_date_str);
                        $job_date = esc_html(date_i18n(get_option('date_format'), $job_date_timestamp));
                        $cand_status = get_post_meta($cjob, '_job_applied_status_' . $candidate_id, true);
                        $cand_final = nokri_canidate_apply_status($cand_status);

                        if ($job_date_timestamp > $latest_applied_date) {
                            $latest_applied_date = $job_date_timestamp;
                        }

                        $job_html .= '<li>';
                        $job_html .= '<a href="' . $job_link . '">' . $job_title . '</a>';
                        $job_html .= '<p>Applied On: ' . $job_date . '</p>';
                        $job_html .= '<p>Status: ' . $cand_final . '</p>';
                        $job_html .= '</li>';
                    }
                    $job_html .= '</ul>';

                    // Add the row to the array with the latest applied date as the key
                    $resume_rows[$latest_applied_date] = '<tr class="job-applicant-info job-applicant-info-custom">
                <td>' . esc_html($candidate_id) . '</td>
                <td>
                    <div class="posted-job-title-img gt">
                        <a href="' . get_author_posts_url($candidate_id) . '"><img src="' . $image_dp_link[0] . '" class="img-responsive img-circle" alt="' . esc_html__('Candidate Image', 'nokri') . '"></a>
                    </div> 
                    <div class="posted-job-title-meta">
                        <a  href="' . get_author_posts_url($candidate_id) . '" target="_blank"  class="cand-view-prof" data-cand_status="' . esc_attr($cand_status) . '"  data-cand_id = "' . esc_attr($candidate_id) . '" data-job_id = "' . esc_attr($job_id) . '">' . esc_html($author->display_name) . '</a>
                        <p>' . esc_html($cand_headline) . '</p>
                    </div>
                </td>
                <td>' . $job_html . '</td>
              </tr> ';
                }
            }


// Sort the array by keys (applied date) in descending order
            krsort($resume_rows);

// Implode the array to get the final table string
            $resume_table = implode("", $resume_rows);

// ...

        }

        ?>


        <div class="dashboard-posted-jobs">
            <div class="table-responsive">
                <table class="table dashboard-table table-fit">
                    <thead>
                    <tr class="posted-job-list resume-on-jobs header-title">
                        <th> <?php echo esc_html__('Id', 'nokri') ?></th>
                        <th> <?php echo esc_html__('Candidate Name', 'nokri') ?></th>
                        <th> <?php echo esc_html__('Job Title', 'nokri') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php echo "" . ($resume_table); ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php endif; ?>
</div>
</div>