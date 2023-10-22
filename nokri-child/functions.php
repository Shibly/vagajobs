<?php

function vagajobs_menu()
{
    register_nav_menu('my-custom-menu', __('VagaJobs Menu'));
}

add_action('init', 'vagajobs_menu');

function my_custom_scripts()
{
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/main.js', array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'my_custom_scripts');

add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);


//DEFAULT USER ROLE
function my_new_customer_data($new_customer_data)
{
    $new_customer_data['role'] = get_option('default_role');
    return $new_customer_data;
}

add_filter('woocommerce_new_customer_data', 'my_new_customer_data');

add_action('init', 'bt_export_candidate');
function bt_export_candidate()
{

    if (isset($_GET['export_csv_cand']) && $_GET['export_csv_cand'] != '') {
        $user_id = get_current_user_id();

        $args = array(
            'post_type' => 'job_post',
            'orderby' => 'date',
            'order' => 'DESC',
            'author' => $user_id,
            'post_status' => array('publish'),
            'meta_query' => array(
                array(
                    'key' => '_job_status',
                    'value' => 'active',
                    'compare' => '='
                )
            )
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

            }
            wp_reset_postdata();
        }

        if (!empty($job_ids)) {
            global $wpdb;
            $applier = array();
            $applier_jobs = array();
            $extra = " AND meta_key like '_job_applied_resume_%'";
            $query = "SELECT * FROM $wpdb->postmeta WHERE post_id in ( " . implode(',', $job_ids) . " ) $extra";
            $applier_resumes = $wpdb->get_results($query);
            $csv_data = array();
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
                if ($authors) {
                    foreach ($authors as $author) {
                        // get all the user's id's
                        $candidate_id = ($author->ID);
                        $user = get_userdata($candidate_id);
                        $email = $user->user_email;
                        /*

                        */
                        $cand_headline = get_user_meta($candidate_id, '_user_headline', true);


                        $cand_intro_vid = get_user_meta($candidate_id, '_cand_intro_vid', true);
                        $cand_video_resume_switch = isset($nokri['cand_video_resume_switch']) ? $nokri['cand_video_resume_switch'] : false;
                        $cand_video_resumes = get_user_meta($candidate_id, 'cand_video_resumes', true);


                        if ($cand_video_resume_switch && $cand_video_resumes != "") {
                            $video_url = wp_get_attachment_url($cand_video_resumes);
                            $cand_intro_vid = $video_url;
                        }
                        $user_job_key = $candidate_id . '|' . $job_id;


                        /* Getting Candidate Dp */
                        $image_dp_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
                        if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
                            $image_dp_link = array($nokri['nokri_user_dp']['url']);
                        }
                        if (get_user_meta($candidate_id, '_cand_dp', true) != "") {
                            $attach_dp_id = get_user_meta($candidate_id, '_cand_dp', true);
                            $image_dp_link = wp_get_attachment_image_src($attach_dp_id, 'nokri_job_hundred');
                        }
                        /* If video exisst */
                        $video_pop = '';
                        if ($cand_intro_vid != '') {
                            $video_pop = '<li class="tool-tip" title="' . esc_attr__('View Video', 'nokri') . '"><a class="bla-1" href="' . esc_html($cand_intro_vid) . '"><i class="la la-file-video-o"></i></a></li>';
                            echo '<input type="hidden" id="is_intro_vid" value="1" />';
                        }

                        $cjobs = $applier_jobs[$candidate_id];

                        $job_titles = array();
                        foreach ($cjobs as $cjob) {
                            $job_link = get_permalink($cjob);
                            $job_title = get_the_title($cjob);
                            $job_date = get_post_meta($cjob, '_job_applied_date_' . $candidate_id, true);
                            $job_date = esc_html(date_i18n(get_option('date_format'), strtotime($job_date)));
                            $cand_status = get_post_meta($cjob, '_job_applied_status_' . $candidate_id, true);
                            $cand_final = nokri_canidate_apply_status($cand_status);

                            $cand_resume = get_post_meta($cjob, '_job_applied_resume_' . $candidate_id, true);
                            $cand_status = get_post_meta($cjob, '_job_applied_status_' . $candidate_id, true);


                            $cand_final = nokri_canidate_apply_status($cand_status);
                            $job_date = get_post_meta($cjob, '_job_applied_date_' . $candidate_id, true);
                            $cand_cover = get_post_meta($cjob, '_job_applied_cover_' . $candidate_id, true);

                            $array_data = explode('|', $cand_resume);
                            $attachment_id = isset($array_data[1]) ? $array_data[1] : '';
                            /* Resume status colours */
                            $counter_active = '';
                            if ($cand_status == '0') {
                                $label_class = 'default';
                                $counter_active = "counter-active";
                            } elseif ($cand_status == '1') {
                                $label_class = 'info';
                                $counter_active = "counter-active";
                            } elseif ($cand_status == '2') {
                                $label_class = 'danger';
                                $counter_active = "counter-active";
                            } elseif ($cand_status == '3') {
                                $label_class = 'primary';
                                $counter_active = "counter-active";
                            } elseif ($cand_status == '4') {
                                $label_class = 'warning';
                                $counter_active = "counter-active";
                            } elseif ($cand_status == '5') {
                                $label_class = 'success';
                                $counter_active = "counter-active";
                            }

                            if (is_numeric($attachment_id)) {
                                $resume_link = '<a href="' . get_permalink($attachment_id) . '?attachment_id=' . $attachment_id . '&download_file=1"">' . esc_html__('Download', 'nokri') . '</a>';
                            } else {
                                $resume_link = '<a href="' . get_author_posts_url($candidate_id) . '">' . esc_html__('View profile', 'nokri') . '</a>';
                            }


                            $job_titles[] = $job_title;


                            $inner_array = array();
                            $inner_array[0] = $candidate_id;
                            $inner_array[1] = $author->display_name;
                            $inner_array[2] = $job_title;
                            $inner_array[3] = $email;
                            $inner_array[4] = get_user_meta($candidate_id, '_sb_contact', true);

                            $sessonid = get_user_meta($candidate_id, '_cand_type', true);
                            $object_term = get_term_by('id', $sessonid, 'job_type');
                            $inner_array[5] = $object_term->name;
                            $inner_array[6] = get_user_meta($candidate_id, 'cand_availability', true);

                            $sessonid = get_user_meta($candidate_id, '_cand_salary_range', true);
                            $object_term = get_term_by('id', $sessonid, 'job_salary');

                            $inner_array[7] = $object_term->name;


                            $inner_array[8] = str_replace('author', 'job-seekers', get_author_posts_url($candidate_id));

                            if (is_numeric($attachment_id)) {
                                $inner_array[9] = get_permalink($attachment_id) . '?attachment_id=' . $attachment_id . '&download_file=1';
                            } else {


                                $ids_array = get_user_meta($candidate_id, '_cand_resume', true);
                                if (!empty($ids_array)) {
                                    $ids_array = explode(',', $ids_array);
                                    $attach_id = $ids_array[0];
                                    $link = nokri_set_url_param(get_the_permalink($attach_id), 'attachment_id', esc_attr($attach_id));
                                    $final_url = esc_url(nokri_page_lang_url_callback($link));
                                    $inner_array[9] = $final_url;
                                } else {
                                    $inner_array[9] = '';
                                }

                            }

                            array_push($csv_data, $inner_array);

                        }

                    }
                }
            }

            if (!empty($csv_data)) {
                qcld_wpbd_download_send_headers("candidate_lists_" . current_time('Y-m-d') . ".csv");
                $result = qcld_wpbd_array2csv($csv_data);
            }
            die(0);


        }
    }
}

function qcld_wpbd_download_send_headers($filename)
{
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function qcld_wpbd_array2csv($array)
{
    if (count($array) == 0) {
        return null;
    }

    $df = fopen("php://output", 'w');
    $titles = array('ID', 'Name', 'Jobs', 'Email', 'Phone', 'Season for Hire', 'Availability', 'Housing Preference', 'Profile Link', 'Resume');
    fputcsv($df, $titles);
    foreach ($array as $row) {
        fputcsv($df, $row);
    }
    fclose($df);

}

add_action('profile_update', 'bt_wp_profile_update', 10, 3);
function bt_wp_profile_update($user_id, $oldUserData, $newUserData)
{
    update_job_expire_date([$user_id]);
}

add_action('init', 'bt_update_job_expire_date');
function bt_update_job_expire_date()
{
    if (isset($_GET['update-job']) && $_GET['update-job'] === 'expire_date') {
        update_job_expire_date();
    }
}

function update_job_expire_date($ids = array())
{


    $args = array(
        'order' => 'ASC',
        'orderby' => array(
            'rand',
            'random',
        ),
        'role__in' => array('parent_account', 'employer', 'pending_employer', 'pending-customer')
    );

    if (!empty($ids)) {
        $args['include'] = $ids;
    }

    $wp_user_query = new WP_User_Query($args);
    $users = $wp_user_query->get_results();

    foreach ($users as $user) {

        $expire_date = '1970-01-01 23:14:59';
        $subscriptions = wcs_get_users_subscriptions($user->ID);

        foreach ($subscriptions as $subscription) {
            if ($subscription->status == 'active') {

                //Take the expiration date from User edit section.
                $expire_date = get_user_meta($user->ID, '_sb_expire_ads', true);

                if ($expire_date == 0 || $expire_date == '') {
                    $expire_date = $subscription->schedule_next_payment;
                }

                //incase if no expire_date and next payment date
                if ($expire_date == 0 || $expire_date == '') {
                    $expire_date = date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))));
                }

            }

        }

        if ($expire_date == '1970-01-01 23:14:59') {
            $expire_date = get_user_meta($user->ID, '_sb_expire_ads', true);
        }

        if ($expire_date != '') {
            $expire_date = date("m/d/Y", strtotime($expire_date));

            $args = array(
                'post_type' => 'job_post',
                'orderby' => 'date',
                'order' => 'ASC',
                'author' => $user->ID,
                'post_status' => array('publish'),
                'posts_per_page' => -1
            );

            if ($expire_date == '1970-01-01 23:14:59' || strtotime(date("m/d/Y")) > strtotime($expire_date)) {
                // Change user role to 'pending-employer'
                $user_obj = new WP_User($user->ID);
                $user_obj->set_role('pending_employer');
                // update_post_meta($jpost->ID, '_job_status', 'inactive');
            } else {
                // update_post_meta($jpost->ID, '_job_status', 'active');

                $user_obj = new WP_User($user->ID);
                $user_obj->set_role('employer');
            }


            $postquery = new WP_Query($args);
            $jposts = $postquery->posts;
            if (!empty($jposts)) {
                foreach ($jposts as $jpost) {
                    update_post_meta($jpost->ID, '_job_date', sanitize_text_field($expire_date));
//                    if ($expire_date == '1970-01-01 23:14:59' || strtotime(date("m/d/Y")) > strtotime($expire_date)) {
//                        // Change user role to 'pending-employer'
//                        $user_obj = new WP_User($user->ID);
//                        $user_obj->set_role('pending_employer');
//                        // update_post_meta($jpost->ID, '_job_status', 'inactive');
//                    } else {
//                        // update_post_meta($jpost->ID, '_job_status', 'active');
//
//                        $user_obj = new WP_User($user->ID);
//                        $user_obj->set_role('employer');
//                    }
                }
            }
        }
    }
}


// Schedule an action for update job expire date
if (!wp_next_scheduled('update_job_expire_date_cron')) {

    wp_schedule_event(time(), 'twicedaily', 'update_job_expire_date_cron');
}
// Hook into that action that'll fire twicedaily
add_action('update_job_expire_date_cron', 'update_job_expire_date');

/**
 * Update jobs after job post.
 *
 * @param int $user_id
 * @return void
 */
function bt_run_job_updater($user_id)
{

    //Bail if no user id;
    if (empty($user_id)) {
        return;
    }

    $user = get_user_by('id', $user_id);

    $expire_date = '1970-01-01 23:14:59';
    $subscriptions = wcs_get_users_subscriptions($user->ID);

    foreach ($subscriptions as $subscription) {
        if ($subscription->status == 'active') {
            $expire_date = get_user_meta($user->ID, '_sb_expire_ads', true);

            if ($expire_date == 0 || $expire_date == '') {
                $expire_date = $subscription->schedule_next_payment;
            }

            //incase if no expire_date and next payment date
            if ($expire_date == 0 || $expire_date == '') {
                $expire_date = date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))));
            }

        }

    }

    if ($expire_date == '1970-01-01 23:14:59') {
        $expire_date = get_user_meta($user->ID, '_sb_expire_ads', true);
    }

    if ($expire_date != '') {
        $expire_date = date("m/d/Y", strtotime($expire_date));

        $args = array(
            'post_type' => 'job_post',
            'orderby' => 'date',
            'order' => 'ASC',
            'author' => $user->ID,
            'post_status' => array('publish'),
            'posts_per_page' => -1
        );

        $postquery = new WP_Query($args);
        $jposts = $postquery->posts;

        if (!empty($jposts)) {
            foreach ($jposts as $jpost) {
                update_post_meta($jpost->ID, '_job_date', sanitize_text_field($expire_date));
                if ($expire_date == '1970-01-01 23:14:59' || strtotime(date("m/d/Y")) > strtotime($expire_date)) {
                    update_post_meta($jpost->ID, '_job_status', 'inactive');
                } else {
                    update_post_meta($jpost->ID, '_job_status', 'active');
                }
            }
        }
    }

}

/**
 * Return only month name and year from date
 *
 * @param string $date //Date
 *
 * @return string | null
 */
function bt_get_month_year($date)
{
    if ($date !== '') {

        list($start_date, $end_date) = explode('-', $date);

        if ($start_date != '') {
            $start_dateValue = strtotime($start_date);
            $start_date = date("F Y", $start_dateValue);
        }

        if ($end_date != '') {
            $end_dateValue = strtotime($end_date);
            $end_date = date("F Y", $end_dateValue);
        }

        if ($start_date != '' && $end_date != '') {
            return $start_date . ' - ' . $end_date;
        } elseif ($start_date != '') {
            return $start_date;
        } else {
            return $end_date;
        }

    }
    return $date;
}

function bt_change_date_format($date)
{

    if ($date !== '') {

        list($start_date, $end_date) = explode('-', $date);

        if ($start_date != '') {
            $start_dateValue = strtotime($start_date);
            $start_date = date("m/d/Y", $start_dateValue);
        }

        if ($end_date != '') {
            $end_dateValue = strtotime($end_date);
            $end_date = date("m/d/Y", $end_dateValue);
        }

        if ($start_date != '' && $end_date != '') {
            return $start_date . ' - ' . $end_date;
        } elseif ($start_date != '') {
            return $start_date . ' - ' . $start_date;
        } else {
            return $end_date . ' - ' . $end_date;
        }

    }
    return $date;
}


/**
 * BT Latest code
 */

add_action('init', function () {
    if (!is_admin() && class_exists('BT_Modify_Query')) {
        $o = new BT_Modify_Query;
        $o->activate();
    }
});


class BT_Modify_Query
{
    private $search = '';

    public function activate()
    {
        add_action('pre_get_posts', array($this, 'pre_get_posts'));
    }

    public function pre_get_posts(WP_Query $q)
    {
        if (filter_var(
                $q->get('bt_search_or_tax_query'),
                FILTER_VALIDATE_BOOLEAN
            )
            && $q->get('tax_query')
            && $q->get('s')
        ) {
            add_filter('posts_clauses', array($this, 'posts_clauses'), 10, 2);
            add_filter('posts_search', array($this, 'posts_search'), 10, 2);
        }
    }

    public function posts_clauses($clauses, \WP_Query $q)
    {
        remove_filter(current_filter(), array($this, __FUNCTION__));

        // Generate the tax query:
        $tq = new WP_Tax_Query($q->query_vars['tax_query']);

        // Get the generated taxonomy clauses:
        global $wpdb;
        $tc = $tq->get_sql($wpdb->posts, 'ID');

        // Remove the search part:
        $clauses['where'] = str_ireplace(
            $this->search,
            ' ',
            $clauses['where']
        );

        // Remove the taxonomy part:
        $clauses['where'] = str_ireplace(
            $tc['where'],
            ' ',
            $clauses['where']
        );


        // Add the search OR taxonomy part:
        $clauses['where'] .= sprintf(
            " AND ( ( 1=1 %s ) OR ( 1=1 %s ) ) ",
            $tc['where'],
            $this->search
        );

        return $clauses;
    }

    public function posts_search($search, \WP_Query $q)
    {
        remove_filter(current_filter(), array($this, __FUNCTION__));
        $this->search = $search;
        return $search;
    }

} // end class

add_action('init', 'bt_change_dashbaord_url', 100);

function bt_change_dashbaord_url()
{

    $url_part = explode('/', $_SERVER['REQUEST_URI']);
    $url_part = array_filter($url_part);

    $account = end($url_part);
    if ($account == 'account' && is_user_logged_in()) {
        wp_safe_redirect(home_url("/dashboard"));
        exit;
    }

}


add_action('wp_head', 'bt_my_custom_nofollow_fnc', 1);
function bt_my_custom_nofollow_fnc()
{
    global $wp_query;
    if (isset($wp_query->query) && isset($wp_query->query['author_level']) && $wp_query->query['author_level'] == 'job-seekers') {
        echo '<meta name="robots" content="noindex" />';
    }
}