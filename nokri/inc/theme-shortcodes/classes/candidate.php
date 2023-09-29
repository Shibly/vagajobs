<?php

/* * ********************************* */
/* Ajax handler for Saving Candidate Profile   */
/* * ********************************* */
add_action('wp_ajax_candidate_profile_action', 'nokri_candidate_profile');

function nokri_candidate_profile() {
    global $nokri;
    $taxonomy = 'job_category';
    $user_id = get_current_user_id();
    echo nokri_demo_mode();
    /* Setting profile option */
    $profile_setting_option = isset($nokri['cand_prof_setting']) ? $nokri['cand_prof_setting'] : 'hide';
    // Getting values From Param
    $params = array();
    parse_str(stripslashes($_POST['candidate_data']), $params);
    wp_update_user(array('ID' => $user_id, 'display_name' => $params['cand_name']));
    $cand_phone = $params['cand_phone'];
    $cand_headline = $params['cand_headline'];
    $cand_dob = isset($params['cand_dob']) ? $params['cand_dob'] : "";
    $cand_gender = isset($params['cand_gender']) ? $params['cand_gender'] : "";
    $cand_qualification = $params['cand_qualification'];
    $cand_type = $params['cand_type'];
    $cand_level = $params['cand_level'];
    $cand_experience = $params['cand_experience'];
    $cand_intro = $params['cand_intro'];
    $cand_profile = $params['cand_profile'];
    $cand_salary_type = $params['cand_salary_type'];
    $cand_salary_range = $params['cand_salary'];
    $cand_salary_curren = $params['cand_salary_currency'];
    $cand_skill = isset($params['cand_skills']) ? $params['cand_skills'] : "";
    $cand_skill_values = isset($params['cand_skills_values']) ? $params['cand_skills_values'] : "";
    $cand_video = isset($params['cand_video']) ? $params['cand_video'] : "";
    $cand_fb = isset($params['cand_fb']) ? $params['cand_fb'] : "";
    $cand_twiter = $params['cand_twiter'];
    $cand_linked = $params['cand_linked'];
    $cand_google = $params['cand_google'];
    $cand_address = $params['cand_address'];
    $cand_map_lat = $params['cand_map_lat'];
    $cand_map_long = $params['cand_map_long'];
    $cand_pro_url = isset($params['profile_url']) ? $params['profile_url'] : "";
    $cand_intro_vid = isset($params['cand_intro_video'])  ?  $params['cand_intro_video'] :  "";  
    $cand_skills_new = $params['cand_skills_new'];
    $cand_skills_val = $params['cand_skills_val'];
    
    if( isset( $params['cand_availability'] ) ){
        update_user_meta($user_id, 'cand_availability', $params['cand_availability']);
    }
    if( isset( $params['cand_availability_end'] ) ){
        update_user_meta($user_id, 'cand_availability_end', $params['cand_availability_end']);
    }

    //bussiness hours
    /**
     * 0 = N/A
     * 1 = open 24/7
     * 2 = selective hours
     */
    $listing_is_open = isset($params['hours_type']) ?   ($params['hours_type']) : "";
    $listing_timezone = isset($params['listing_timezome']) ? sanitize_text_field($params['listing_timezome']) : "";
    //   $listing_brandname = sanitize_text_field($params['listing_brandname']);
    /* checkbox for closed/not */
    $is_closed = isset($params['is_closed']) ? $params['is_closed'] : "";
    $start_from = isset($params['to']) ? $params['to'] : "";
    $end_from = isset($params['from']) ? $params['from'] : "";


    if ($listing_is_open == 1) {
        update_user_meta($user_id, 'nokri_is_hours_allow', '1');
        update_user_meta($user_id, 'nokri_business_hours', $listing_is_open);
    }
    // listing business hours
    else if ($listing_is_open == 2) {
        //business hours
        $custom_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        for ($a = 0; $a <= 6; $a++) {
            $to = '';
            $from = '';
            $days = '';
            //get days
            $days = lcfirst($custom_days[$a]);
            if (!in_array($a, $is_closed)) {
                $from = date("H:i:s", strtotime(str_replace(" : ", ":", $end_from[$a])));
                $to = date("H:i:s", strtotime(str_replace(" : ", ":", $start_from[$a])));
                //day status open or not 
                update_user_meta($user_id, '_timingz_' . $days . '_open', '1');

                //day hours from
                update_user_meta($user_id, '_timingz_' . $days . '_from', $from);
                update_user_meta($user_id, '_timingz_' . $days . '_to', $to);
            } else {
                update_user_meta($user_id, '_timingz_' . $days . '_open', '0');
            }
        }
        update_user_meta($user_id, 'nokri_business_hours', 2);
        update_user_meta($user_id, 'nokri_user_timezone', $listing_timezone);
        update_user_meta($user_id, 'nokri_is_hours_allow', '1');
    } else {
        update_user_meta($user_id, 'nokri_is_hours_allow', '0');
        update_user_meta($user_id, 'nokri_business_hours', '0');
    }


    /* Getting Educational Details & Updating Values  */
    $cand_education = ($params['cand_education']);
    $edu = array();
    $arr2 = array();
    $canData = nokri_convert_to_array($cand_education);
    $countNum = ( $canData['count'] == 0 ) ? 0 : $canData['count'] - 1;
    for ($i = 0; $i <= $countNum; $i++) {
        $arr = $canData['arr'];
        if (isset($arr['degree_name'][$i]) && $arr['degree_name'][$i] != "") {
            $arr2['degree_name'] = sanitize_textarea_field($arr['degree_name'][$i]);
            $arr2['degree_institute'] = sanitize_textarea_field($arr['degree_institute'][$i]);
            $arr2['degree_start'] = sanitize_textarea_field($arr['degree_start'][$i]);
            $arr2['degree_end'] = sanitize_textarea_field($arr['degree_end'][$i]);
            $arr2['degree_percent'] = sanitize_textarea_field($arr['degree_percent'][$i]);
            $arr2['degree_grade'] = sanitize_textarea_field($arr['degree_grade'][$i]);
            $arr2['degree_detail'] = wp_kses($arr['degree_detail'][$i], nokri_required_tags());

            $edu[] = $arr2;
        }
    }
    /* Getting Professional Details & Updating Values  */
    $cand_profession = ($params['cand_profession']);
    $arrp = $profession = array();

    $proData = nokri_convert_to_array($cand_profession);

    $countNum = ( $proData['count'] == 0 ) ? 0 : $proData['count'] - 1;
    for ($i = 0; $i <= $countNum; $i++) {
        $arr = $proData['arr'];
        if (isset($arr['project_organization'][$i]) && $arr['project_organization'][$i] != "") {
            $arrp['project_name'] = isset($arr['project_name'][$i]) ? $arr['project_name'][$i] : 1;
            $arrp['project_start'] = isset($arr['project_start'][$i]) ? $arr['project_start'][$i] : '';
            if ($arrp['project_name'] == 1) {
                $arrp['project_end'] = "";
            } else {
                $arrp['project_end'] = isset($arr['project_end'][$i]) ? sanitize_text_field($arr['project_end'][$i]) : '';
            }
            $arrp['project_role'] = isset($arr['project_role'][$i]) ? sanitize_text_field($arr['project_role'][$i]) : '';
            $arrp['project_organization'] = isset($arr['project_organization'][$i]) ? sanitize_text_field($arr['project_organization'][$i]) : '';
            $arrp['project_desc'] = isset($arr['project_desc'][$i]) ? wp_kses($arr['project_desc'][$i], nokri_required_tags()) : '';
            $profession[] = $arrp;
        }
    }
    if (count($profession) > 0) {
        update_user_meta($user_id, '_cand_profession', $profession);
    }
    /* Getting Certifications & Updating Values  */
    $cand_certifications = ($params['cand_certifications']);
    $arrc = $certifications = array();
    $proData = nokri_convert_to_array($cand_certifications);
    $countNum = ( $proData['count'] == 0 ) ? 0 : $proData['count'] - 1;
    for ($i = 0; $i <= $countNum; $i++) {
        $arr = $proData['arr'];
        if (isset($arr['certification_name'][$i]) && $arr['certification_name'][$i] != "") {
            $arrc['certification_name'] = sanitize_text_field($arr['certification_name'][$i]);
            $arrc['certification_start'] = sanitize_text_field($arr['certification_start'][$i]);
            $arrc['certification_end'] = sanitize_text_field($arr['certification_end'][$i]);
            $arrc['certification_duration'] = sanitize_text_field($arr['certification_duration'][$i]);
            $arrc['certification_institute'] = sanitize_text_field($arr['certification_institute'][$i]);
            $arrc['certification_desc'] = wp_kses($arr['certification_desc'][$i], nokri_required_tags());
            $certifications[] = $arrc;
        }
    }
    if ($certifications != '') {
        update_user_meta($user_id, '_cand_certifications', $certifications);
    }
    /* Updating Values In User Meta Of Current Candidate */
    update_user_meta($user_id, '_cand_salary_type', sanitize_text_field($cand_salary_type));
    update_user_meta($user_id, '_cand_salary_range', sanitize_text_field($cand_salary_range));
    update_user_meta($user_id, '_cand_salary_curren', sanitize_text_field($cand_salary_curren));
    update_user_meta($user_id, '_sb_contact', sanitize_text_field($cand_phone));
    update_user_meta($user_id, '_user_headline', sanitize_text_field($cand_headline));
    update_user_meta($user_id, '_cand_dob', sanitize_text_field($cand_dob));
    update_user_meta($user_id, '_cand_gender', sanitize_text_field($cand_gender));
    if ($cand_qualification != '') {
        update_user_meta($user_id, '_cand_qualification', sanitize_text_field($cand_qualification));
    }
    update_user_meta($user_id, '_cand_type', sanitize_text_field($cand_type));
    update_user_meta($user_id, '_cand_level', sanitize_text_field($cand_level));
    update_user_meta($user_id, '_cand_experience', sanitize_text_field($cand_experience));
    if ($edu != '') {
        update_user_meta($user_id, '_cand_education', $edu);
    }
    update_user_meta($user_id, '_cand_intro', wp_kses($cand_intro, nokri_required_tags()));
    /* If allowed */
    if ($profile_setting_option == 'show' || $profile_setting_option == 'required') {
        update_user_meta($user_id, '_user_profile_status', sanitize_text_field($cand_profile));
    } else {
        update_user_meta($user_id, '_user_profile_status', sanitize_text_field('pub'));
    }
    update_user_meta($user_id, '_cand_skills', ($cand_skills_new));
    if (!empty($cand_skills_new)) {
        if (empty($cand_skills_val)) {
            echo 6;
            die();
        }
        $cand_skill_sanatize = array();
        if (isset($cand_skills_val) && !empty($cand_skills_val) && count($cand_skills_val) > 0) {
            foreach ($cand_skills_val as $key) {

                $cand_skill_sanatize[] = sanitize_text_field($key);
            }
        }
        update_user_meta($user_id, '_cand_skills_values', ($cand_skill_sanatize));
    }
    /* Updating Custom feilds */
    if (isset($params['_custom_']) && count($params['_custom_']) > 0) {
        foreach ($params['_custom_'] as $key => $data) {
            if (is_array($data)) {
                $dataArr = array();
                foreach ($data as $k)
                    $dataArr[] = $k;
                $data = stripslashes(json_encode($dataArr, JSON_UNESCAPED_UNICODE));
            }
            update_user_meta($user_id, $key, sanitize_text_field($data));
        }
    }
    update_user_meta($user_id, '_cand_video', ($cand_video));
    update_user_meta($user_id, '_cand_fb', sanitize_text_field($cand_fb));
    update_user_meta($user_id, '_cand_twiter', sanitize_text_field($cand_twiter));
    update_user_meta($user_id, '_cand_linked', sanitize_text_field($cand_linked));
    update_user_meta($user_id, '_cand_google', sanitize_text_field($cand_google));
    if ($cand_address != '') {
        update_user_meta($user_id, '_cand_address', sanitize_text_field($cand_address));
    }
    if ($cand_map_lat != '') {
        update_user_meta($user_id, '_cand_map_lat', sanitize_text_field($cand_map_lat));
    }
    if ($cand_map_long != '') {
        update_user_meta($user_id, '_cand_map_long', sanitize_text_field($cand_map_long));
    }
    /* countries */
    $cand_location = array();
    if ($params['cand_country'] != "") {
        $cand_location[] = $params['cand_country'];
    }
    if ($params['cand_country_states'] != "") {
        $cand_location[] = $params['cand_country_states'];
    }
    if ($params['cand_country_cities'] != "") {
        $cand_location[] = $params['cand_country_cities'];
    }
    if ($params['cand_country_towns'] != "") {
        $cand_location[] = $params['cand_country_towns'];
    }
    update_user_meta($user_id, '_cand_custom_location', ($cand_location));
    /* Validating youtube url */
    $rx = '~
							  ^(?:https?://)?                           # Optional protocol
							   (?:www[.])?                              # Optional sub-domain
							   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
							   ([^&]{11})                               # Video id of 11 characters as capture group 1
								~x';
    $valid = preg_match($rx, $cand_intro_vid, $matches);
    $cand_video = isset($matches[1]) ? $matches[1] : "";
    if ($cand_intro_vid != '') {
        if ($valid) {
            update_user_meta($user_id, '_cand_intro_vid', sanitize_text_field($cand_intro_vid));
        } else {
            echo 5;
            die();
        }
    } else {
        update_user_meta($user_id, '_cand_intro_vid', sanitize_text_field($cand_intro_vid));
    }
    echo 1;
    die();
}

/* * ********************************* */
/* Ajax handler for candidate settings  */
/* * ********************************* */

add_action('wp_ajax_candidate_settings_action', 'nokri_candidate_settings');

function nokri_candidate_settings() {
    global $nokri;
    $user_id = get_current_user_id();
    echo nokri_demo_mode();
    $params = array();
    parse_str(stripslashes($_POST['candidate_data']), $params);
    if (!empty($params)) {
        update_user_meta($user_id, '_cand_settings', serialize($params));
    }
    echo 1;
    die();
}

/* * ********************************* */
/* Ajax handler for Candidate Proifle Picture   */
/* * ********************************* */
add_action('wp_ajax_candidate_dp', 'nokri_candidate_dp');
if (!function_exists('nokri_candidate_dp')) {

    function nokri_candidate_dp() {
        global $nokri;
        $user_id = get_current_user_id();

        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }

        /* img upload */
        $condition_img = 7;
        $img_count = count((array) explode(',', $_POST["image_gallery"]));
        if (!empty($_FILES["candidate_dp"])) {

            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';


            $files = $_FILES["candidate_dp"];
            $attachment_ids = array();
            $attachment_idss = '';

            if ($img_count >= 1) {
                $imgcount = $img_count;
            } else {
                $imgcount = 1;
            }


            $ul_con = '';

            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );

                    $_FILES = array("candidate_dp" => $file);

// Allow certain file formats
                    $imageFileType = strtolower(end(explode('.', $file['name'])));
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        echo '0|' . esc_html__("Sorry, only JPG, JPEG, PNG & GIF files are allowed.", 'nokri');
                        die();
                    }

                    $size_arr = explode('-', $nokri['sb_upload_profile_pic_size']);
                    $display_size = $size_arr[1];
                    $actual_size = $size_arr[0];
                    // Check file size
                    if ($file['size'] > $actual_size) {
                         $mess  =  "Max allowed image size is" . " " . $display_size;

                        echo '0|' . esc_html__($mess, 'nokri');
                        die();
                    }


                    foreach ($_FILES as $file => $array) {

                        if ($imgcount >= $condition_img) {
                            break;
                        }
                        $attach_id = media_handle_upload($file, $post_id);
                        $attachment_ids[] = $attach_id;

                        $image_link = wp_get_attachment_image_src($attach_id, 'nokri-user-profile');
                    }
                    if ($imgcount > $condition_img) {
                        break;
                    }
                    $imgcount++;
                }
            }
        }
        /* img upload */
        $attachment_idss = array_filter($attachment_ids);
        $attachment_idss = implode(',', $attachment_idss);


        $arr = array();
        $arr['attachment_idss'] = $attachment_idss;
        $arr['ul_con'] = $ul_con;

        $uid = $user_id;
        if ($attach_id != '') {
            update_user_meta($uid, '_cand_dp', sanitize_text_field($attach_id));
        }
        echo '1|' . $image_link[0];
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Del Portfolio */
/* * ********************************* */
add_action('wp_ajax_delete_ad_image', 'nokri_delete_ad_imagess');
if (!function_exists('nokri_delete_ad_imagess')) {

    function nokri_delete_ad_imagess() {
        $user_crnt_id = get_current_user_id();
        if ($user_crnt_id == "")
            die();
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $attachmentid = trim($_POST['img']);
        wp_delete_attachment($attachmentid, true);
        if (get_user_meta($user_crnt_id, '_cand_portfolio', true) != "") {
            $ids = get_user_meta($user_crnt_id, '_cand_portfolio', true);
            $res = str_replace($attachmentid, "", $ids);
            $res = str_replace(',,', ",", $res);
            $img_ids = trim($res, ',');
            update_user_meta($user_crnt_id, '_cand_portfolio', sanitize_text_field($img_ids));
        }
        echo "1";
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Adding Portfolio */
/* * ********************************* */
add_action('wp_ajax_nokri_upload_portfolio', 'nokri_upload_portfolio');
if (!function_exists('nokri_upload_portfolio')) {

    function nokri_upload_portfolio() {
        global $nokri;
        $user_id = get_current_user_id();
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '0|' . esc_html__("Edit in demo user not allowed", 'nokri');
            die();
        }
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';

        $size_arr = explode('-', $nokri['sb_upload_size']);
        $display_size = $size_arr[1];
        $actual_size = $size_arr[0];

        // Allow certain file formats
        $imageFileType = strtolower(end(explode('.', $_FILES['my_file_upload']['name'])));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo '0|' . esc_html__("Sorry, only JPG, JPEG, PNG & GIF files are allowed.", 'nokri');
            die();
        }

        // Check file size
        if ($_FILES['my_file_upload']['size'] > $actual_size) {
            echo '0|' . esc_html__("Max allowd image size is", 'nokri') . " " . $display_size;
            die();
        }



        // Check max image limit
        $user_portfolio = get_user_meta($user_id, '_cand_portfolio', true);
        if ($user_portfolio != "") {
            $media = explode(',', $user_portfolio);
            if (count($media) >= $nokri['sb_upload_limit']) {
                echo '0|' . esc_html__("You can not upload more than ", 'nokri') . " " . $nokri['sb_upload_limit'] . " " . esc_html__("images ", 'nokri');
                die();
            }
        }


        $attachment_id = media_handle_upload('my_file_upload', 0);

        if (!is_wp_error($attachment_id)) {

            $user_portfolio = get_user_meta($user_id, '_cand_portfolio', true);
            if ($user_portfolio != "") {
                $updated_portfolio = $user_portfolio . ',' . $attachment_id;
            } else {
                $updated_portfolio = $attachment_id;
            }

            update_user_meta($user_id, '_cand_portfolio', sanitize_text_field($updated_portfolio));
        } else {
            echo '0|' . esc_html__("Some thing went wrong", 'nokri');
            die();
        }

        echo nokri_returnEcho($attachment_id);
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Getting Portfolio */
/* * ********************************* */
add_action('wp_ajax_get_uploaded_portfolio_images', 'nokri_get_uploaded_portfolio_imagess');
if (!function_exists('nokri_get_uploaded_portfolio_imagess')) {

    function nokri_get_uploaded_portfolio_imagess() {
        $ids = get_user_meta(get_current_user_id(), '_cand_portfolio', true);

        if (!$ids)
            return '';

        $ids_array = explode(',', $ids);

        $result = array();
        foreach ($ids_array as $m) {

            $obj = array();
            $obj['name'] = get_the_guid($m);
            $obj['size'] = filesize(get_attached_file($m));
            $obj['id'] = $m;
            $result[] = $obj;
        }
        header('Content-type: text/json');
        header('Content-type: application/json');
        echo json_encode($result);
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Adding Resume */
/* * ********************************* */
add_action('wp_ajax_cand_resume', 'nokri_cand_resume');
if (!function_exists('nokri_cand_resume')) {

    function nokri_cand_resume() {
        global $nokri;
        $user_id = get_current_user_id();
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '0|' . esc_html__("Edit in demo user not allowed", 'nokri');
            die();
        }
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        $size_arr = explode('-', $nokri['sb_upload_resume_size']);
        $display_size = $size_arr[1];
        $actual_size = $size_arr[0];
        // Allow certain file formats
        $imageFileType = strtolower(end(explode('.', $_FILES['my_cv_upload']['name'])));
        if ($imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "pdf") {
            echo '0|' . esc_html__("Sorry, doc, docx &  pdf are allowed.", 'nokri');
            die();
        }
        // Check file size
        if ($_FILES['my_cv_upload']['size'] > $actual_size) {
            echo '0|' . esc_html__("Max allowd image size is", 'nokri') . " " . $display_size;
            die();
        }
        // Check max resume limit
        $user_resume = get_user_meta($user_id, '_cand_resume', true);
        if ($user_resume != "") {
            $media = explode(',', $user_resume);
            if (count($media) >= $nokri['sb_upload_resume_limit']) {
                echo '0|' . esc_html__("You can not upload more than ", 'nokri') . " " . $nokri['sb_upload_resume_limit'] . " " . esc_html__("resumes ", 'nokri');
                die();
            }
        }
        $attachment_id = media_handle_upload('my_cv_upload', 0);
        if (is_wp_error($attachment_id)) {
            echo '0|' . esc_html__("File is empty.", 'nokri');
            die();
        }
        $user_resume = get_user_meta($user_id, '_cand_resume', true);
        if ($user_resume != "") {
            $updated_resume = $user_resume . ',' . $attachment_id;
        } else {
            $updated_resume = $attachment_id;
        }
        if (is_numeric($attachment_id)) {
            update_user_meta($user_id, '_cand_resume', $updated_resume);
        }
        echo nokri_returnEcho($attachment_id);
        die();
    }

}
/* * *************************************** */
/* Candidate upload resume from resume list  */
/* * *************************************** */
add_action('wp_ajax_nopriv_upload_resume_from_tab', 'nokri_upload_resume_from_tab');
add_action('wp_ajax_upload_resume_from_tab', 'nokri_upload_resume_from_tab');
if (!function_exists('nokri_upload_resume_from_tab')) {

    function nokri_upload_resume_from_tab() {
        global $nokri;
        $user_id = get_current_user_id();
        if ($user_id != "" && get_user_meta($user_id, '_sb_reg_type', true) == "1") {
            echo '5|' . esc_html__("Only candidate can perform this", 'nokri');
            die();
        }
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '0|' . esc_html__("Edit in demo user not allowed", 'nokri');
            die();
        }
        $condition_img = 7;
        $image_gallery = isset($_POST["image_gallery"]) ? $_POST["image_gallery"] : "";
        $img_count = count(explode(',', $image_gallery));



        if (!empty($_FILES["upload_resume_tab"])) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            $files = $_FILES["upload_resume_tab"];
            $attachment_ids = array();
            $attachment_idss = '';
            if ($img_count >= 1) {
                $imgcount = $img_count;
            } else {
                $imgcount = 1;
            }
            $ul_con = '';
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                    $_FILES = array("upload_resume_tab" => $file);
                    // Allow certain file formats
                    $imageFileType = strtolower(end(explode('.', $file['name'])));
                    if ($imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx"
                    ) {
                        echo '1|' . __("Sorry, only PDF, DOC, DOCX  files are allowed.", 'nokri');
                        die();
                    }
                    /* Check file size */
                    $size_arr = explode('-', $nokri['sb_upload_resume_size']);


                    $display_size = $size_arr[1];
                    $actual_size = isset($size_arr[0]) ? $size_arr[0] : "";


                    if ($_FILES['upload_resume_tab']['size'] > $actual_size) {
                        echo '2|' . esc_html__("Max allowed resume size is", 'nokri') . " " . $display_size;
                        die();
                    }
                    // Check max resume limit
                    $user_resume = get_user_meta($user_id, '_cand_resume', true);
                    if ($user_resume != "") {
                        $media = explode(',', $user_resume);
                        if (count($media) >= $nokri['sb_upload_resume_limit']) {
                            echo '3|' . esc_html__("You can not upload more than ", 'nokri') . " " . $nokri['sb_upload_resume_limit'] . " " . esc_html__("resumes ", 'nokri');
                            die();
                        }
                    }
                    foreach ($_FILES as $file => $array) {
                        if ($imgcount >= $condition_img) {
                            break;
                        }
                        $attach_id = media_handle_upload($file, $post_id);
                        $attachment_ids[] = $attach_id;
                        $image_link = wp_get_attachment_image_src($attach_id, '');
                    }
                    if ($imgcount > $condition_img) {
                        break;
                    }
                    $imgcount++;
                }
            }
        }
        /* img upload */
        $attachment_idss = array_filter($attachment_ids);
        $attachment_idss = implode(',', $attachment_idss);
        $arr = array();
        $arr['attachment_idss'] = $attachment_idss;
        $arr['ul_con'] = $ul_con;
        $user_resume = get_user_meta($user_id, '_cand_resume', true);
        if ($user_resume != "") {
            $updated_resume = $user_resume . ',' . $attach_id;
        } else {
            $updated_resume = $attach_id;
        }
        if (is_numeric($attach_id)) {
            update_user_meta($user_id, '_cand_resume', $updated_resume);
        }
        echo '4|' . esc_html__("Uploaded Successfully", 'nokri');
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Del Resume */
/* * ********************************* */
add_action('wp_ajax_delete_cand_resume', 'nokri_delete_cand_resume');
if (!function_exists('nokri_delete_cand_resume')) {

    function nokri_delete_cand_resume() {
        $user_crnt_id = get_current_user_id();
        if ($user_crnt_id == "")
            die();
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '0';
            die();
        }
        $attachmentid = trim($_POST['resume']);
        if (get_user_meta($user_crnt_id, '_cand_resume', true) != "") {
            $ids = get_user_meta($user_crnt_id, '_cand_resume', true);
            $res = str_replace($attachmentid, "", $ids);
            $res = str_replace(',,', ",", $res);
            $img_ids = trim($res, ',');
            update_user_meta($user_crnt_id, '_cand_resume', $img_ids);
        }

        wp_delete_attachment($attachmentid, true);
        echo "1";
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Getting Resume */
/* * ********************************* */
add_action('wp_ajax_get_uploaded_cand_resume', 'nokri_get_uploaded_cand_resume');
if (!function_exists('nokri_get_uploaded_cand_resume')) {

    function nokri_get_uploaded_cand_resume() {
        $result = array();
        $ids = get_user_meta(get_current_user_id(), '_cand_resume', true);

        if ($ids != "") {
            $ids_array = explode(',', $ids);
            $cv_icon = '';
            foreach ($ids_array as $m) {
                $obj = array();
                $array = explode('.', get_attached_file($m));
                $extension = end($array);
                if ($extension == 'pdf' && $extension != '') {
                    $cv_icon = trailingslashit(get_template_directory_uri()) . 'images/logo-adobe-pdf.jpg';
                } else if ($extension == 'doc' && $extension != '') {
                    $cv_icon = trailingslashit(get_template_directory_uri()) . 'images/DOC.png';
                } else if ($extension == 'docx' && $extension != '') {
                    $cv_icon = trailingslashit(get_template_directory_uri()) . 'images/docx.png';
                }
                $obj['display_name'] = basename(get_attached_file($m));
                $obj['name'] = $cv_icon;
                $obj['size'] = filesize(get_attached_file($m));
                $obj['id'] = $m;
                $result[] = $obj;
            }
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($result);
        } else {
            header('Content-type: text/json');
            header('Content-type: application/json');
            echo json_encode($result);
        }
        die();
    }

}

function cand_complete_profile( $user_id ){
    if( ! get_user_meta($user_id, '_cand_intro', true) && ! get_user_meta($user_id, '	_cand_profession' ) ){
        return false;
    }else{
        return true;
    }
    
}

/* * ********************************* */
/* Ajax handler for Candidate Aplly Job Athentication */
/* * ********************************* */
add_action('wp_ajax_nopriv_aplly_job', 'nokri_aplly_job');
add_action('wp_ajax_aplly_job', 'nokri_aplly_job');
if (!function_exists('nokri_aplly_job')) {

    function nokri_aplly_job() {
        global $nokri;
        $allow = (isset($nokri['allow_questinares']) && $nokri['allow_questinares'] != "") ? $nokri['allow_questinares'] : false;
        $user_id = get_current_user_id();
        $user_type = get_user_meta($user_id, '_sb_reg_type', true);
        $job_id = ($_POST['apply_job_id']);
        
        /* Is applying job package base */
        $is_apply_pkg_base = ( isset($nokri['job_apply_package_base']) && $nokri['job_apply_package_base'] != "" ) ? $nokri['job_apply_package_base'] : false;
        /* Cand package page */
        $cand_package_page = ( isset($nokri['cand_package_page']) && $nokri['cand_package_page'] != "" ) ? $nokri['cand_package_page'] : '';
        /* Validating candidate job package */
        if ($is_apply_pkg_base == '1' && $user_type != '1') {
            $is_package = nokri_candidate_package_expire_notify();
            if ($is_package == 'ae' || $is_package == 'pe' || $is_package == 'np') {
                echo '6|' . __("Please Purchase Package", 'nokri') . '|' . get_the_permalink($cand_package_page);
                die();
            }
        }




        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '5';
            die();
        }
        $job_questions = get_post_meta($job_id, '_job_questions', true);
        $cover_letter_required = get_post_meta($job_id, '_cover_letter_required', true);
        $questions_html = '';
        $req_mess = esc_html__('This value is required', 'nokri');
        if (isset($job_questions) && !empty($job_questions) && $allow) {
            foreach ($job_questions as $questions) {
                $field_slug = preg_replace('/\s+/', '', $questions);
                if ($questions != '') {
                    $questions_html .= '<div class="form-group">
									<label>' . $questions . '</label>
									<textarea  name="answers[]" class="form-control" cols="30" rows="2" data-parsley-required="true" ' . $req_mess . '></textarea></div>';
                }
            }
        }
        /* Is without login */
        $is_without_login = false;
        //$is_without_login = isset($nokri['apply_without_login']) ? $nokri['apply_without_login'] : false;
        if ($is_without_login && !is_user_logged_in()) {
            $job_id = ($_POST['apply_job_id']);
            $author_id = (isset($_POST['apply_author_id'])) ? $_POST['apply_author_id'] : '';
            echo '<div class="cp-loader"></div>
          <div class="modal fade resume-action-modal" id="myModal-job">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
              <form method="post" id="submit_cv_form1" class="apply-job-modal-popup">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">' . esc_html__("Want to apply for this job?", "nokri") . '</h4>
                </div>
                <div class="modal-body">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
					  <label>' . __('Your Name', 'nokri') . '<span class="color-red">*</span></label>
					  <input placeholder="' . __('Enter your name', 'nokri') . '" class="form-control" type="text" name="sb_reg_name"  data-parsley-required="true" data-parsley-error-message="' . __('Please enter your name', 'nokri') . '">
				   </div>
			   </div>
			  <div class="col-md-6 col-sm-6 col-xs-12">
			   <div class="form-group">
				  <label>' . __('Your Email', 'nokri') . '<span class="color-red">*</span></label>
				  <input placeholder="' . __('Enter your email address', 'nokri') . '" class="form-control" type="email" data-parsley-type="email" data-parsley-required="true"  data-parsley-error-message="' . __('Please enter your valid email', 'nokri') . '" data-parsley-trigger="change" name="sb_reg_email">
			   </div>
		 </div>
				<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
			    <label>' . __('Upload Resume', 'nokri') . '<span class="color-red">*</span></label>
				<input id="input-b2"  name="my_file_upload[]" type="file" class="file sb_files-data-doc" data-show-preview="false" data-show-upload="false" data-parsley-required="true"  data-parsley-error-message="' . __('Upload resume', 'nokri') . '" data-msg-placeholder="' . esc_html__("Click to upload", "nokri") . '"  >
				<input type="hidden" id="_sb_company_doc" value="" name="cand_apply_resume" />
				</div></div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							' . $questions_html . '
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
                	<div class="form-group">
					<label>Write cover letter '. ( $cover_letter_required == 0 ? '(optional)' : '(required)' ) .'</label>
                        <textarea name="cand_cover_letter" rows="6" class="form-control job_textarea" placeholder="' . esc_html__("Write cover letter", "nokri") . '" '.( $cover_letter_required == 0 ? '' : 'data-parsley-required="true" data-parsley-error-message="' . __('Please write your cover letter.', 'nokri') . '"' ).' ></textarea>
                    </div></div>
					<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
					  <button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="submit_cv_form_btn">' . esc_html__('Apply now', 'nokri') . '</button>
					</div> </div>
				<div class="modal-footer">
				 </div>
				<input type="hidden" name="current_job"   id="current_job" value="' . esc_attr($job_id) . '" />
				<input type="hidden" name="current_author" id="current_author" value="' . esc_attr($author_id) . '" />
              </form>
              </div>
              
            </div>
        </div>                  
                         '
            ;
            die();
        }
        /* Dashboard Page */

        $upload_resume_opt = isset($nokri['upload_resume_option']) ? $nokri['upload_resume_option'] : 'req';

        $parsely_req = "true";
        $upload_resume_txt = esc_html__('Upload Resume', 'nokri');
        $select_resume_txt = esc_html__('Select your resume to apply', 'nokri');
        $hide_resume_opt = '';
        if ($upload_resume_opt != 'req') {
            $parsely_req = "false";
            $upload_resume_txt = esc_html__('Upload Resume (optional)', 'nokri');
            $select_resume_txt = esc_html__('Select your resume to apply  (optional)', 'nokri');
        }
        if ($upload_resume_opt == 'no-field') {
            $hide_resume_opt = 'hide-upload-resume';
        }

        $dashboard_id = '';
        if ((isset($nokri['sb_dashboard_page'])) && $nokri['sb_dashboard_page'] != '') {
            $dashboard_id = ($nokri['sb_dashboard_page']);
        }
        /* Signin Page */
        $signin_page = '';
        if ((isset($nokri['sb_sign_in_page'])) && $nokri['sb_sign_in_page'] != '') {
            $signin_page = ($nokri['sb_sign_in_page']);
        }
        $job_id = ($_POST['apply_job_id']);
        $author_id = ($_POST['apply_author_id'] != null) ? $_POST['apply_author_id'] : '';
        $user_id = get_current_user_id();
        if ($user_id == '') {
            echo '2';
            echo nokri_redirect(get_the_permalink($signin_page));
            exit;
        }

        if( ! cand_complete_profile( $user_id ) ){
            echo '2';
            echo nokri_redirect( home_url( '/' ) . 'dashboard/?candidate-page=edit-profile' );
            exit;
        }

        nokri_check_user_activity();
        nokri_check_user_type();
        /* Getting Candidate Resume */
        if (get_user_meta($user_id, '_cand_resume', true) != "") {
            $resume = get_user_meta($user_id, '_cand_resume', true);
            $resumes = explode(',', $resume);
            $resume_options = '';
            foreach ($resumes as $resum) {
                $resume_options .= '<option value="' . $resum . '">' . basename(get_attached_file($resum)) . '</option>  ';
            }
            $select_resumes = '<div class="form-group ' . $hide_resume_opt . '">
							<label>' . $select_resume_txt . '</label>
							<select class="select-generat " data-parsley-required=' . $parsely_req . '  data-parsley-error-message="' . esc_html__('Select your resume to apply', 'nokri') . '" name="cand_apply_resume">
									<option value="">' . esc_html__('Select your resume', 'nokri') . '</option>
									' . $resume_options . '
								</select>
							</div>
							' . $questions_html . '
							<div class="form-group">
							<label>Write cover letter '. ( $cover_letter_required == 0 ? '(optional)' : '(required)' ) .'</label>
                        <textarea name="cand_cover_letter" rows="6" class="form-control job_textarea" placeholder="' . esc_html__("Write cover letter", "nokri") . '" '.( $cover_letter_required == 0 ? '' : 'data-parsley-required="true" data-parsley-error-message="' . __('Please write your cover letter.', 'nokri') . '"' ).' ></textarea>
                    </div>
						</div>
						<div class="modal-footer">
						  <button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="submit_cv_form_btn">' . esc_html__('Apply now', 'nokri') . '</button>
						</div>
						<input type="hidden" name="current_job"   id="current_job" value="' . esc_attr($job_id) . '" />
						<input type="hidden" name="current_author" id="current_author" value="' . esc_attr($author_id) . '" />';
        } else {
            $select_resumes = '<div class="form-group ' . $hide_resume_opt . '">
								<label>' . $upload_resume_txt . '</label>
								<input id="input-b2"  name="candidate_resume[]" type="file" data-parsley-required=' . $parsely_req . ' class="file upload_resume_now" data-show-preview="false" data-show-upload="false"   data-parsley-error-message="' . __('Upload resume', 'nokri') . '" data-msg-placeholder="' . esc_html__("Click to upload", "nokri") . '">
							</div>
							' . $questions_html . '
		                    <div class="form-group">
                            <label>Write cover letter '. ( $cover_letter_required == 0 ? '(optional)' : '(required)' ) .'</label>
                            <textarea name="cand_cover_letter" rows="6" class="form-control job_textarea" placeholder="' . esc_html__("Write cover letter", "nokri") . '" '.( $cover_letter_required == 0 ? '' : 'data-parsley-required="true" data-parsley-error-message="' . __('Please write your cover letter.', 'nokri') . '"' ).' ></textarea>
                        </div>
                
							<div class="modal-footer">
							  <button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="submit_cv_form_btn">' . esc_html__('Apply now', 'nokri') . '</button>
							</div>
							
							<input type="hidden" name="current_job"       id="current_job" value="' . esc_attr($job_id) . '" />
							<input type="hidden" name="cand_apply_resume" id="current_resume" value="" />
							<input type="hidden" name="current_author"    id="current_author" value="' . esc_attr($author_id) . '" />';
        }


        $resume_exist = get_post_meta($job_id, '_job_applied_status_' . $user_id, true);
        if ($resume_exist == '') {
            echo '<div class="cp-loader"></div>
				<div class="modal fade resume-action-modal" id="myModal-job">
				<div class="modal-dialog">
				  <!-- Modal content-->
				  <div class="modal-content">
				  <form method="post" id="submit_cv_form1" class="apply-job-modal-popup">      
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">' . esc_html__("Want to apply for this job?", "nokri") . '</h4>
					</div>
					<div class="modal-body">
						' . $select_resumes . '
				  </form>
				  </div>
				</div>                              
		';
        } else {
            echo '4';
        }
        die();
    }

}

/* * ********************************* */
/* Ajax handler for Candidate Aplly Job with email */
/* * ********************************* */
add_action('wp_ajax_nopriv_aplly_job_with_email', 'nokri_aplly_job_with_email');
add_action('wp_ajax_aplly_job_with_email', 'nokri_aplly_job_with_email');
if (!function_exists('nokri_aplly_job_with_email')) {

    function nokri_aplly_job_with_email() {
        global $nokri;
        $allow = (isset($nokri['allow_questinares']) && $nokri['allow_questinares'] != "") ? $nokri['allow_questinares'] : false;
        $user_id = get_current_user_id();
        $user_type = get_user_meta($user_id, '_sb_reg_type', true);
        $job_id = ($_POST['apply_job_id']);
        $job_email = ($_POST['apply_email']);


        /* Is applying job package base */
        $is_apply_pkg_base = ( isset($nokri['job_apply_package_base']) && $nokri['job_apply_package_base'] != "" ) ? $nokri['job_apply_package_base'] : false;
        /* Cand package page */
        $cand_package_page = ( isset($nokri['cand_package_page']) && $nokri['cand_package_page'] != "" ) ? $nokri['cand_package_page'] : '';
        /* Validating candidate job package */
        if ($is_apply_pkg_base == '1' && $user_type != '1') {
            $is_package = nokri_candidate_package_expire_notify();
            if ($is_package == 'ae' || $is_package == 'pe' || $is_package == 'np') {
                echo '6|' . __("Please Purchase Package", 'nokri') . '|' . get_the_permalink($cand_package_page);
                die();
            }
        }
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '5';
            die();
        }
        $job_questions = get_post_meta($job_id, '_job_questions', true);
        $questions_html = '';
        $req_mess = esc_html__('This value is required', 'nokri');
        if (isset($job_questions) && !empty($job_questions) && $allow) {
            foreach ($job_questions as $questions) {
                $field_slug = preg_replace('/\s+/', '', $questions);
                if ($questions != '') {
                    $questions_html .= '<div class="form-group">
									<label>' . $questions . '</label>
									<textarea  name="answers[]" class="form-control" cols="30" rows="2" data-parsley-required="true" ' . $req_mess . '></textarea></div>';
                }
            }
        }
        /* Is without login */
        $is_without_login = isset($nokri['apply_without_login']) ? $nokri['apply_without_login'] : false;
        if ($is_without_login && !is_user_logged_in()) {
            $job_id = ($_POST['apply_job_id']);
            $author_id = isset($_POST['apply_author_id']) ? $_POST['apply_author_id'] : "";
            echo '<div class="cp-loader"></div>
          <div class="modal fade resume-action-modal" id="myModal-email-job">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
              <form method="post" id="submit_cv_form_email1" class="apply-job-modal-popup">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">' . esc_html__("Want to apply for this job?", "nokri") . '</h4>
                </div>
                <div class="modal-body">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="form-group">
					  <label>' . __('Your Name', 'nokri') . '<span class="color-red">*</span></label>
					  <input placeholder="' . __('Enter your name', 'nokri') . '" class="form-control" type="text" name="sb_reg_name"  data-parsley-required="true" data-parsley-error-message="' . __('Please enter your name', 'nokri') . '">
				   </div>
			   </div>
			  <div class="col-md-6 col-sm-6 col-xs-12">
			   <div class="form-group">
				  <label>' . __('Your Email', 'nokri') . '<span class="color-red">*</span></label>
				  <input placeholder="' . __('Enter your email address', 'nokri') . '" class="form-control" type="email" data-parsley-type="email" data-parsley-required="true"  data-parsley-error-message="' . __('Please enter your valid email', 'nokri') . '" data-parsley-trigger="change" name="sb_reg_email">
			   </div>
		 </div>
				<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="form-group">
			    <label>' . __('Upload Resume', 'nokri') . '<span class="color-red">*</span></label>
				<input id="input-b2"  name="my_file_upload[]" type="file" class="file sb_files-data-doc" data-show-preview="false" data-show-upload="false" data-parsley-required="true"  data-parsley-error-message="' . __('Upload resume', 'nokri') . '" data-msg-placeholder="' . esc_html__("Click to upload", "nokri") . '"  >
				<input type="hidden" id="_sb_company_doc" value="" name="cand_apply_resume" />
				</div></div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="form-group">
							' . $questions_html . '
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12">
                	<div class="form-group">
					<label>' . esc_html__('Write cover letter (optional)', 'nokri') . '</label>
                        <textarea name="cand_cover_letter" rows="6" class="form-control job_textarea" placeholder="' . esc_html__("Write cover letter", "nokri") . '" ></textarea>
                    </div></div>
					<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
					  <button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="submit_cv_form_email_btn">' . esc_html__('Apply now', 'nokri') . '</button>
					</div> </div>
				<div class="modal-footer">
				 </div>
				<input type="hidden" name="current_job"   id="current_job" value="' . esc_attr($job_id) . '" />
				<input type="hidden" name="current_author" id="current_author" value="' . esc_attr($author_id) . '" />
              </form>
              </div>
              
            </div>
        </div>';
            die();
        }
        /* Dashboard Page */
        $dashboard_id = '';
        if ((isset($nokri['sb_dashboard_page'])) && $nokri['sb_dashboard_page'] != '') {
            $dashboard_id = ($nokri['sb_dashboard_page']);
        }
        /* Signin Page */
        $signin_page = '';
        if ((isset($nokri['sb_sign_in_page'])) && $nokri['sb_sign_in_page'] != '') {
            $signin_page = ($nokri['sb_sign_in_page']);
        }
        $job_id = ($_POST['apply_job_id']);
        $author_id = ($_POST['apply_author_id']);
        $user_id = get_current_user_id();
        if ($user_id == '') {
            echo '2';
            echo nokri_redirect(get_the_permalink($signin_page));
            exit;
        }
        nokri_check_user_activity();
        nokri_check_user_type();
        /* Getting Candidate Resume */
        if (get_user_meta($user_id, '_cand_resume', true) != "") {
            $resume = get_user_meta($user_id, '_cand_resume', true);
            $resumes = explode(',', $resume);
            $resume_options = '';
            foreach ($resumes as $resum) {
                $resume_options .= '<option value="' . $resum . '">' . basename(get_attached_file($resum)) . '</option>  ';
            }
            $select_resumes = '<div class="form-group">
							<label>' . esc_html__('Select your resume to apply', 'nokri') . '</label>
							<select class="select-generat" data-allow-clear="true" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Select your resume to apply', 'nokri') . '" name="cand_apply_resume">
									<option value="">' . esc_html__('Select your resume', 'nokri') . '</option>
									' . $resume_options . '
								</select>
							</div>
							' . $questions_html . '
							<div class="form-group">
							<label>' . esc_html__('Write cover letter (optional)', 'nokri') . '</label>
								<textarea name="cand_cover_letter" rows="6" class="form-control job_textarea" placeholder="' . esc_html__("Write cover letter", "nokri") . '" ></textarea>
							</div>
						</div>
						<div class="modal-footer">
						  <button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="submit_cv_form_email_btn">' . esc_html__('Apply now', 'nokri') . '</button>
						</div>
						<input type="hidden" name="current_job"   id="current_job" value="' . esc_attr($job_id) . '" />
						<input type="hidden" name="current_author" id="current_author" value="' . esc_attr($author_id) . '" />';
        } else {
            $select_resumes = '<div class="form-group">
								<label>' . esc_html__('Upload Resume', 'nokri') . '</label>
								<input id="input-b2"  name="candidate_resume[]" type="file" class="file upload_resume_now" data-show-preview="false" data-show-upload="false" data-parsley-required="true"  data-parsley-error-message="' . __('Upload resume', 'nokri') . '" data-msg-placeholder="' . esc_html__("Click to upload", "nokri") . '">
							</div>
							' . $questions_html . '
		                    <div class="form-group">
					          <label>' . esc_html__('Write cover letter (optional)', 'nokri') . '</label>
                              <textarea name="cand_cover_letter " rows="6" class="form-control job_textarea" placeholder="' . esc_html__("Write cover letter", "nokri") . '">
							  </textarea>
                             </div>
                
							<div class="modal-footer">
							  <button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="submit_cv_form_email_btn">' . esc_html__('Apply now', 'nokri') . '</button>
							</div>
							
							<input type="hidden" name="current_job"       id="current_job" value="' . esc_attr($job_id) . '" />
							<input type="hidden" name="cand_apply_resume" id="current_resume" value="" />
							<input type="hidden" name="current_author"    id="current_author" value="' . esc_attr($author_id) . '" />';
        }
        $resume_exist = get_post_meta($job_id, '_job_applied_status_' . $user_id, true);
        if ($resume_exist == '') {
            echo '<div class="cp-loader"></div>
				<div class="modal fade resume-action-modal" id="myModal-email-job">
				<div class="modal-dialog">
				  <!-- Modal content-->
				  <div class="modal-content">
				  <form method="post" id="submit_cv_form_email1" class="apply-job-modal-popup">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h4 class="modal-title">' . esc_html__("Want to apply for this job?", "nokri") . '</h4>
					</div>
					<div class="modal-body">
						' . $select_resumes . '
				  </form>
				  </div>
				</div>
			</div>';
        } else {
            echo '4';
        }
        die();
    }

}
/* * *************************************** */
/* Candidate upload resume at job apply   */
/* * *************************************** */
add_action('wp_ajax_upload_resume_now', 'nokri_upload_resume_now');
if (!function_exists('nokri_upload_resume_now')) {

    function nokri_upload_resume_now() {
        global $nokri;
        $condition_img = 7;
        $img_count = count(explode(',', $_POST["image_gallery"]));
        if (!empty($_FILES["upload_resume_now"])) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            $files = $_FILES["upload_resume_now"];
            $attachment_ids = array();
            $attachment_idss = '';
            if ($img_count >= 1) {
                $imgcount = $img_count;
            } else {
                $imgcount = 1;
            }
            $ul_con = '';
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                    $_FILES = array("upload_resume_now" => $file);
                    // Allow certain file formats
                    $imageFileType = strtolower(end(explode('.', $file['name'])));
                    if ($imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx"
                    ) {
                        echo '0|' . __("Sorry, only PDF, DOC, DOCX  files are allowed.", 'nokri');
                        die();
                    }
                    /* Check file size */
                    $size_arr = explode('-', $nokri['sb_upload_resume_size']);
                    $display_size = $size_arr[1];
                    $actual_size = $size_arr[0];
                    if ($_FILES['upload_resume_now']['size'] > $actual_size) {
                        echo '0|' . esc_html__("Max allowed resume size is", 'nokri') . " " . $display_size;
                        die();
                    }
                    foreach ($_FILES as $file => $array) {
                        if ($imgcount >= $condition_img) {
                            break;
                        }
                        $attach_id = media_handle_upload($file, $post_id);
                        $attachment_ids[] = $attach_id;
                        $image_link = wp_get_attachment_image_src($attach_id, '');
                    }
                    if ($imgcount > $condition_img) {
                        break;
                    }
                    $imgcount++;
                }
            }
        }
        /* img upload */
        $attachment_idss = array_filter($attachment_ids);
        $attachment_idss = implode(',', $attachment_idss);
        $arr = array();
        $arr['attachment_idss'] = $attachment_idss;
        $arr['ul_con'] = $ul_con;
        echo '1|' . $attach_id;
        die();
    }

}
/* * **************************************** */
/* Ajax handler for Candidate resume action status */
/* * *************************************** */
add_action('wp_ajax_nopriv_candidate_resume_status_action', 'candidate_resume_status_action');
add_action('wp_ajax_candidate_resume_status_action', 'candidate_resume_status_action');
if (!function_exists('candidate_resume_status_action')) {

    function candidate_resume_status_action() {

        global $nokri;
        $user_id = get_current_user_id();
        $candidate_id = ($_POST['candidate_id']);
        $candidate_info = get_userdata($candidate_id);
        $job_id = ($_POST['job_id']);

        /* Getting Email Templates */
        $user_id = get_current_user_id();
        $res = nokri_get_resumes_list($user_id);
        $roptions = '';
        if (!empty($roptions)) {
            $roptions .= '<option value="0">' . esc_html__('Select an template', 'nokri') . '</option>';
        }
        foreach ($res as $key => $val) {
            $roptions .= '<option value="' . esc_attr($key) . '">' . esc_html($val['name']) . '</option>';
        }
        /* Getting No email status */
        $cand_status = '';
        $cand_status = nokri_canidate_apply_status();
        $coptions = '';

        foreach ($cand_status as $key => $val) {
            $coptions .= '<option value="' . esc_attr($key) . '">' . esc_html($val) . '</option>';
        }

        echo '<div class="modal fade resume-action-modal" id="myModalaction" role="dialog">
<div class="cp-loader"></div>
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">' . esc_html__('Take action on', 'nokri') . " " . $candidate_info->display_name . " " . esc_html__('application', 'nokri') . '</h4>
                </div>
                <div class="modal-body">
                <form method="post" id="email_template_action" class="job-form" enctype="multipart/form-data">
                  <input type="hidden" value="' . esc_attr($candidate_id) . '"  name="candidiate_id" />
                   <input type="hidden" value="' . esc_attr($job_id) . '"  name="job_stat_id" />
                	<div class="form-group">
                    	<div class="row">
                        	<div class="company-search-toggle">
                                <div class="col-md-9 col-xs-12 col-sm-9">
                                    <label>' . esc_html__('Do you want to send email as well?', 'nokri') . '</label>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-3">
                                    <div class="pull-right ' . esc_attr($rtl_class) . '">
                                      <input id="email_send_toggle"  data-on="' . esc_html__('YES', 'nokri') . '" data-off="' . esc_html__('NO', 'nokri') . '" data-size="small" data-toggle="toggle" type="checkbox">
                                      <input type="hidden" value="" id="is_send_email" name="is_send_email" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group no-email-status">
                        <label class="">' . esc_html__('Select  status', 'nokri') . '</label>
                        <select class="js-example-basic-single form-control stat_cls" name="cand_status_val">
                           ' . ($coptions) . '
                        </select>
                    </div>
                	<div class="form-group email-status">
                        <label class="">' . esc_html__('Select email template', 'nokri') . '</label>
                        <select class="js-example-basic-single form-control template_select"  id="temp_select">
                        <option value="">' . esc_html('Select template', 'nokri') . '</option>                         ' . ($roptions) . '
                        </select>
                    </div>
                    <div id="email_temp_html"></div>
                    </form>
                </div>
                <div class="modal-footer">
                 <button type="submit" name="submit"  class="btn n-btn-flat btn-mid btn-block send_email">
					' . esc_html__('Send', 'nokri') . '
                </button>
                </div>
              </div>
            </div>
        </div>';
        die();
    }

}
/* * **************************************** */
/* Ajax handler for Candidate short details */
/* * *************************************** */
add_action('wp_ajax_nopriv_candidate_short_details', 'candidate_short_details');
add_action('wp_ajax_candidate_short_details', 'candidate_short_details');
if (!function_exists('candidate_short_details')) {

    function candidate_short_details() {
        global $nokri;
        $user_id = get_current_user_id();
        $candidate_id = ($_POST['candidate_id']);
        $job_id = ($_POST['job_id']);
        $attachment_id = ($_POST['attachment_id']);
        $candidate_data = get_userdata($candidate_id);
        $candidate_name = $candidate_data->display_name;
        $candidate_email = $candidate_data->user_email;
        $candidate_phone = get_user_meta($candidate_id, '_sb_contact', true);
        $cand_intro_vid = get_user_meta($candidate_id, '_cand_intro_vid', true);
        $phone_html = '';
        if ($candidate_phone) {
            $phone_html = '<p><i class="la la-phone"></i>' . esc_html($candidate_phone) . '</p>';
        }
        $cand_cover = get_post_meta($job_id, '_job_applied_cover_' . $candidate_id, true);
        $cover_html = '';
        if ($cand_cover) {
            $cover_html = '<div class="n-modal-candidate-cover">
                                            <h5> ' . esc_html__('Cover Letter', 'nokri') . ' </h5>
                                            <p>' . wp_kses_post($cand_cover) . '</p>
                                    </div>';
        }
        /* Getting Questions Answers */
        $allow = (isset($nokri['allow_questinares']) && $nokri['allow_questinares'] != "") ? $nokri['allow_questinares'] : false;
        $qstn_ans_html = '';
        if ($allow) {
            $qstn_ans_html = '<div class="dashboard-questions-box">' . nokri_get_questions_answers($job_id, $candidate_id) . '</div>';
        }
        /* Getting Candidate Dp */
        $image_dp_link[0] = get_template_directory_uri() . '/images/candidate-dp.jpg';
        if (isset($nokri['nokri_user_dp']['url']) && $nokri['nokri_user_dp']['url'] != "") {
            $image_dp_link = array($nokri['nokri_user_dp']['url']);
        }
        if (get_user_meta($candidate_id, '_cand_dp', true) != "") {
            $attach_dp_id = get_user_meta($candidate_id, '_cand_dp', true);
            $image_dp_link = wp_get_attachment_image_src($attach_dp_id, 'nokri_job_hundred');
        }
        /* Getting resume */

        if ($attachment_id == "") {

            $resume_link = '<a href="' . get_author_posts_url($candidate_id) . '" class="btn btn-default">' . esc_html__('View profile', 'nokri') . '</a>';
        } else if (is_numeric($attachment_id)) {
            $link = nokri_set_url_param(get_the_permalink($attachment_id), 'attachment_id', esc_attr($attachment_id));
            $final_url = esc_url(nokri_page_lang_url_callback($link));
            $resume_link = '<a target="_blank" href="' . $final_url . '&download_file=1"" class="btn btn-default">' . esc_html__('Download Resume', 'nokri') . '</a>';
        } else {
            $resume_link = '<a href="' . esc_url($attachment_id) . '" class="btn btn-default">' . esc_html__('View profile', 'nokri') . '</a>';
        }
        echo '<div class="modal fade modal-popup" id="short-detail-modal" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
              	<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">' . esc_html__('Application Details', 'nokri') . ' </h4>
                  </div>
                <div class="modal-body">
                    <form action="">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                                    <div class="n-modal-candidate-avatar">
                                    	<img src="' . esc_url($image_dp_link[0]) . '" alt="' . esc_attr__('image', 'nokri') . '" class="img-responsive img-circle">
                                    </div>
                                    <div class="n-modal-candidate-detail">
                                        <h4>' . esc_html($candidate_name) . '</h4>
                                        <p><i class="la la-envelope-o"></i>' . esc_html($candidate_email) . '</p>
										 ' . $phone_html . '
                                        ' . $resume_link . '
                                    </div>
									' . $qstn_ans_html . '
                                    ' . $cover_html . '
                                  </div>
                            </div>
                        </div>
                        <a href="' . esc_url(get_author_posts_url($candidate_id)) . '" class="btn n-btn-flat btn-mid btn-block"> ' . esc_html__('View Profile', 'nokri') . '</a>
                    </form>
                </div>
              </div>
            </div>
        </div>';
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Candidate submitting url */
/* * ********************************* */
add_action('wp_ajax_nopriv_submiit_linkedin_url', 'submiit_linkedin_url');
add_action('wp_ajax_submiit_linkedin_url', 'submiit_linkedin_url');
if (!function_exists('submiit_linkedin_url')) {

    function submiit_linkedin_url() {
        // Getting values From Param
        $params = array();
        parse_str(stripslashes($_POST['submit_linkedin_url']), $params);
        $profile_url = $params['linkedin_url'];
        $user_id = get_current_user_id();
        $job_id = ($params['apply_job_id']);
        $applied_job_key_val = $user_id . '|' . $profile_url;
        update_post_meta($job_id, '_job_applied_resume_' . $user_id, sanitize_text_field($applied_job_key_val));
        update_user_meta($user_id, '_sb_reg_type', 0);
        echo get_the_permalink($job_id);
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Candidate Aplly Job Athentication */
/* * ********************************* */
add_action('wp_ajax_nopriv_submit_cv_action', 'nokri_submit_cv');
add_action('wp_ajax_submit_cv_action', 'nokri_submit_cv');
if (!function_exists('nokri_submit_cv')) {
    
    function nokri_submit_cv() {
        global $nokri;
        $user_id = get_current_user_id();
        $user_resume = get_user_meta($user_id, '_cand_resume', true);
        // Getting values From Param
        $params = array();
        parse_str(stripslashes($_POST['submit_cv_data']), $params);
        $email = isset($params['sb_reg_email']) ? $params['sb_reg_email'] : '';
        $user_name = isset($params['sb_reg_name']) ? $params['sb_reg_name'] : '';
        $exists = email_exists($email);
        if ($exists) {
            echo "3";
            die();
        }
        /* Is applying job package base */
        $is_apply_pkg_base = ( isset($nokri['job_apply_package_base']) && $nokri['job_apply_package_base'] != "" ) ? $nokri['job_apply_package_base'] : false;
        /* Without login */

        $cand_resume = $params['cand_apply_resume'];
        $cand_cover = $params['cand_cover_letter'];
        $applied_job_id = $params['current_job'];
        $applied_job_author = $params['current_author'];
        $job_answers = $params['answers'];
        $cand_date = date("F j, Y");
        $upload_resume_opt = isset($nokri['upload_resume_option']) ? $nokri['upload_resume_option'] : 'req';

        /* If resume not uploaded */
        if (isset($params['cand_apply_resume']) && $params['cand_apply_resume'] == "" && $upload_resume_opt == 'req') {
            echo "2";
            die();
        }

        if ($user_id == '') {
            $applied_job_id = $params['current_job'];
            $password = nokri_randomString(10);
            $user_id = nokri_do_register_without_login($params['sb_reg_email'], $user_name, $password);
            update_user_meta($user_id, '_sb_reg_type', '0');
            echo nokri_apply_without_login_password($user_id, $password, $applied_job_id);
        }
        
        $applied_job_key_val = $user_id . '|' . $cand_resume;
        /* If resume not uploaded */
        if ($user_resume == "") {
            update_user_meta($user_id, '_cand_resume', $cand_resume);
        }


        /* Email On apply to author */
        if (isset($nokri['sb_send_email_on_apply']) && $nokri['sb_send_email_on_apply'] == '1') {
            nokri_new_candidate_apply($applied_job_id, $user_id);
        }
        
        if(function_exists('nokri_welcome_applier')){          
            nokri_welcome_applier($applied_job_id,$user_id);
        }
        // Updating User Data In Job Meta
        
        update_post_meta($applied_job_id, '_job_applied_resume_' . $user_id, sanitize_text_field($applied_job_key_val));

        if ($cand_cover != "") {
            update_post_meta($applied_job_id, '_job_applied_cover_' . $user_id, $cand_cover);
        }
        update_post_meta($applied_job_id, '_job_applied_status_' . $user_id, 0);
        update_post_meta($applied_job_id, '_job_applied_date_' . $user_id, sanitize_text_field($cand_date));


        /* send notification to employer on mobile */
        if (function_exists('adforestAPI_messages_sent_func')) {
            $emp_id = get_post_field('post_author', $applied_job_id);
            adforestAPI_messages_sent_func('Android', $emp_id, $user_id, $applied_job_id, $cand_date);
        }
        /* Answers */
        
        $answers_sanatize = array();
        if (isset($job_answers) && !empty($job_answers)) {
            foreach ($job_answers as $key) {
                $answers_sanatize[] = sanitize_text_field($key);
            }
        }
        update_user_meta($user_id, '_job_answers_' . $user_id .'_'.$applied_job_id, ($answers_sanatize));
        
        /* Updating candidate job applied package */
        if ($is_apply_pkg_base == '1') {
            $job_applied_rem = get_user_meta($user_id, '_candidate_applied_jobs', true);
            if ($job_applied_rem != '0' && $job_applied_rem != '-1') {
                update_user_meta($user_id, '_candidate_applied_jobs', $job_applied_rem - 1);
            }
        }
        echo "1";
        die();
    }

}

/* * ********************************* */
/* Ajax handler for Candidate Aplly Job email */
/* * ********************************* */
add_action('wp_ajax_nopriv_submit_cv_action_email', 'nokri_send_cv');
add_action('wp_ajax_submit_cv_action_email', 'nokri_send_cv');
if (!function_exists('nokri_send_cv')) {

    function nokri_send_cv() {
        global $nokri;
        $user_id = get_current_user_id();
        $user_resume = get_user_meta($user_id, '_cand_resume', true);
        // Getting values From Param

        $params = array();
        parse_str(stripslashes($_POST['submit_cv_data']), $params);
        $email = $params['sb_reg_email'];
        $user_name = $params['sb_reg_name'];
        $exists = email_exists($email);
        if ($exists) {
            echo "3";
            die();
        }
        /* Is applying job package base */
        $is_apply_pkg_base = ( isset($nokri['job_apply_package_base']) && $nokri['job_apply_package_base'] != "" ) ? $nokri['job_apply_package_base'] : false;
        /* Without login */
        if ($user_id == '') {
            $applied_job_id = $params['current_job'];
            $password = nokri_randomString(10);
            $user_id = nokri_do_register_without_login($params['sb_reg_email'], $user_name, $password);
            update_user_meta($user_id, '_sb_reg_type', '0');
            echo nokri_apply_without_login_password($user_id, $password, $applied_job_id);
        }
        $cand_resume = $params['cand_apply_resume'];
        $cand_cover = $params['cand_cover_letter'];
        $applied_job_id = $params['current_job'];
        $applied_job_author = $params['current_author'];
        $job_answers = $params['answers'];
        $applied_job_key_val = $user_id . '|' . $cand_resume;
        $cand_date = date("F j, Y");
        /* If resume not uploaded */
        if (isset($params['cand_apply_resume']) && $params['cand_apply_resume'] == "") {
            echo "2";
            die();
        }
        /* If resume not uploaded */
        if ($user_resume == "") {
            update_user_meta($user_id, '_cand_resume', $cand_resume);
        }
        /* Email On apply to author */
        if (isset($nokri['sb_send_email_on_apply']) && $nokri['sb_send_email_on_apply'] == '1') {
            nokri_new_candidate_email_apply($applied_job_id, $user_id, $cand_resume, $cand_cover);
        }
        // Updating User Data In Job Meta

         update_post_meta($applied_job_id, '_job_applied_resume_' . $user_id, sanitize_text_field($applied_job_key_val));

        if ($cand_cover != "") {
            update_post_meta($applied_job_id, '_job_applied_cover_' . $user_id, $cand_cover);
        }
        update_post_meta($applied_job_id, '_job_applied_status_' . $user_id, 0);
        update_post_meta($applied_job_id, '_job_applied_date_' . $user_id, sanitize_text_field($cand_date));
        
        /* send notification to employer on mobile */
        if (function_exists('adforestAPI_messages_sent_func')) {
            $emp_id = get_post_field('post_author', $job_id);
            adforestAPI_messages_sent_func('Android', $emp_id, $user_id, $applied_job_id, $cand_date);
        }

        /* Answers */
        $answers_sanatize = array();
        if (isset($job_answers) && !empty($job_answers)) {
            foreach ($job_answers as $key) {
                $answers_sanatize[] = sanitize_text_field($key);
            }
        }
        update_user_meta($user_id, '_job_answers_' . $user_id .'_'.$applied_job_id, ($answers_sanatize));

        /* Updating candidate job applied package */
        $cand_external_jobs = get_user_meta($user_id, '_job_applied_external_' . $applied_job_id, true);
        $external_jobs_array = (explode(",", $cand_external_jobs));
        if (!in_array($applied_job_id, $external_jobs_array)) {
            if ($cand_external_jobs != '') {
                $applied_job_id = $cand_external_jobs . ',' . $applied_job_id;
            }
            update_user_meta($user_id, '_job_applied_external_' . $applied_job_id, ($applied_job_id));
            if ($is_apply_pkg_base == '1') {
                $job_applied_rem = get_user_meta($user_id, '_candidate_applied_jobs', true);
                if ($job_applied_rem != '0' && $job_applied_rem != '-1') {
                    update_user_meta($user_id, '_candidate_applied_jobs', $job_applied_rem - 1);
                }
            }
        }
        echo "1";
        die();
    }

}

/* * ********************************* */
/*  Candidate Aplly with linkedin */
/* * ********************************* */
if (!function_exists('nokri_apply_by_linkedin')) {

    function nokri_apply_by_linkedin($job_id, $user_id, $url = '') {
        $resume_exist = get_post_meta($job_id, '_job_applied_resume_' . $user_id, true);
        $profile_exist = get_post_meta($job_id, '_job_applied_linked_profile' . $user_id, true);
        if ($resume_exist != '' || $profile_exist != '') {
            return false;
        } else {
            $applied_job_key_val = $user_id . '|' . $url;
            // Updating User Data In Job Meta
            if ($job_id != "") {
                update_post_meta($job_id, '_job_applied_resume_' . $user_id, sanitize_text_field($applied_job_key_val));
                /* Email On apply to author */
                nokri_new_candidate_apply($job_id, $user_id);
            }
            $cand_date = date("F j, Y");
            update_post_meta($job_id, '_job_applied_status_' . $user_id, 0);
            update_post_meta($job_id, '_job_applied_date_' . $user_id, $cand_date);
            return true;
        }
    }

}
/* * ********************************* */
/* Package updation after accessing resume */
/* * ********************************* */
add_action('wp_ajax_update_resume_access', 'nokri_resume_access_package_update');
if (!function_exists('nokri_resume_access_package_update')) {

    function nokri_resume_access_package_update() {
        global $nokri;
        $package_base = isset($nokri['cand_search_mode']) ? $nokri['cand_search_mode'] : '1';
        $package_page = isset($nokri['package_page']) ? $nokri['package_page'] : '';
        $candidate_id = ($_POST['candidate_id']);
        $attach_id = ($_POST['attach_id']);
        $user_id = get_current_user_id();
        if (current_user_can('administrator') || $package_base == '1') {
            $link = nokri_set_url_param(get_the_permalink($attach_id), 'attachment_id', esc_attr($attach_id));
            $final_url = esc_url(nokri_page_lang_url_callback($link));
            echo '4|' . $final_url . '&download_file=1';
            die();
        }
        if ($package_base == '2') {
            $remaining_searches = get_user_meta($user_id, '_sb_cand_search_value', true);
            $resumes_viewed = get_user_meta($user_id, '_sb_cand_viewed_resumes', true);
            /* Check package */
            $can_download = nokri_resume_access_package_check();
            if ($can_download == 'ae') {
                echo '1|' . __("Please purchase package", 'nokri') . '|' . get_permalink($package_page);
                die();
            } else if ($can_download == 'pe') {
                echo '2|' . __("Please purchase package", 'nokri') . '|' . get_permalink($package_page);
                die();
            } else if ($can_download == 'np') {
                echo '3|' . __("Please purchase package", 'nokri') . '|' . get_permalink($package_page);
                die();
            } else if ($can_download == 'en') {
                echo '4|' . get_permalink($attach_id) . '?attachment_id=' . $attach_id . '&download_file=1';
                die();
            } else {
                $resumes_viewed_array = (explode(",", $resumes_viewed));
                if (!in_array($candidate_id, $resumes_viewed_array)) {
                    $candidate_id = $candidate_id;
                    if ($resumes_viewed != '') {
                        $candidate_id = $resumes_viewed . ',' . $candidate_id;
                    }
                    update_user_meta($user_id, '_sb_cand_viewed_resumes', $candidate_id);
                    if ($remaining_searches != '0') {
                        update_user_meta($user_id, '_sb_cand_search_value', (int) $remaining_searches - 1);
                    }
                }
                echo '4|' . get_permalink($attach_id) . '?attachment_id=' . $attach_id . '&download_file=1';
                die();
            }
        }
    }

}
/* * ********************************* */
/* Ajax handler for Candidate View Application */
/* * ********************************* */
add_action('wp_ajax_view_application', 'nokri_view_application');
if (!function_exists('nokri_view_application')) {

    function nokri_view_application() {
        global $nokri;
        $job_id = ($_POST['app_job_id']);
        $allow = (isset($nokri['allow_questinares']) && $nokri['allow_questinares'] != "") ? $nokri['allow_questinares'] : false;
        $user_id = get_current_user_id();
        $job_cvr = get_post_meta($job_id, '_job_applied_cover_' . $user_id, true);
        $job_cv = get_post_meta($job_id, '_job_applied_resume_' . $user_id, true);
        $array_data = explode('|', $job_cv);
        $attachment_id = $array_data[1];

        $qstn_ans_html = '';
        if ($allow) {
            $qstn_ans_html = nokri_get_questions_answers($job_id, $user_id);
            $qstn_ans_html = '<div class="dashboard-questions-box">' . $qstn_ans_html . '</div>';
        }
        if (is_numeric($attachment_id)) {
            $link = nokri_set_url_param(get_the_permalink($attachment_id), 'attachment_id', esc_attr($attachment_id));
            $final_url = esc_url(nokri_page_lang_url_callback($link));
            $resume_link = '<a class="btn btn-custom" href="' . $final_url . '&download_file=1"">' . esc_html__('Download', 'nokri') . '</a>';
            $label = esc_html__('You have Applied Against Resume', 'nokri');
        } else {
            $resume_link = '<a href="' . $attachment_id . '">' . esc_html__('View profile', 'nokri') . '</a>';
            $label = esc_html__('You have Applied Against Linkedin Profile', 'nokri');
        }
        if ($attachment_id == '') {
            $resume_link = '<a href="' . esc_url(get_author_posts_url($user_id)) . '">' . esc_html__('View profile', 'nokri') . '</a>';
            $label = esc_html__('You have Applied Against your Profile', 'nokri');
        }
        $filename_only = basename(get_attached_file($attachment_id));
        if ($job_cvr != '') {
            $job_cvr_html = '<div class="form-group">
							<label class="">' . esc_html__('Your Cover Letter', 'nokri') . '</label>
							<textarea class="form-control rich_textarea" rows="10" name="ckeditor" >' . $job_cvr . '</textarea>
						 </div>';
        }
        echo '<div class="modal fade resume-action-modal" id="appmodel" role="dialog">
            <div class="modal-dialog">
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">' . get_the_title($job_id) . '</h4>
                </div>
                
                <div class="modal-body">
                	<div class="form-group">
                    	<div class="row">
                        	<div class="company-search-toggle">
                                <div class="col-md-9 col-xs-12 col-sm-9">
                                    <label>' . $label . '</label>
                                </div>
                                <div class="col-md-3 col-xs-12 col-sm-3">
                                  ' . $resume_link . '
                                </div>
                            </div>
                        </div>
                    </div>
                   
  
					' . $qstn_ans_html . '
                    ' . $job_cvr_html . '
                </div>
                <div class="modal-footer">
                </div>
              </div>
            </div>
        </div>';
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Candidate Saving Job */
/* * ********************************* */
add_action('wp_ajax_save_my_job', 'nokri_save_my_job');
add_action('wp_ajax_nopriv_save_my_job', 'nokri_save_my_job');
if (!function_exists('nokri_save_my_job')) {

    function nokri_save_my_job() {
        global $nokri;
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '4';
            die();
        }
        nokri_check_user_activity();
        nokri_check_user_type();
        $user_id = get_current_user_id();
        $job_id = $_POST['job_id'];
        $saved_job = get_post_meta($job_id, '_job_saved_value_' . $user_id, true);
        $applied_job_key_val = $user_id . '|' . $job_id;


        if ($saved_job != '') {

            echo "5";
            die();
        }

        if ($job_id != "" && $user_id != '') {
            update_post_meta($job_id, '_job_saved_value_' . $user_id, sanitize_text_field($applied_job_key_val));
            echo "1";
            die();
        }
    }

}
/* * ********************************* */
/* Ajax handler for Candidate Deleting Saved Job */
/* * ********************************* */
add_action('wp_ajax_del_saved_job', 'nokri_del_saved_job');
if (!function_exists('nokri_del_saved_job')) {

    function nokri_del_saved_job() {
        global $nokri;
        $user_id = get_current_user_id();
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $job_id = $_POST['cand_job_id'];
        $applied_job_key_val = $user_id . '|' . $job_id;
        if ($job_id != "") {
            delete_post_meta($job_id, '_job_saved_value_' . $user_id, $applied_job_key_val);
        }
        echo "1";
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Candidate Following Company */
/* * ********************************* */
add_action('wp_ajax_nopriv_following_company', 'nokri_following_company');
add_action('wp_ajax_following_company', 'nokri_following_company');
if (!function_exists('nokri_following_company')) {

    function nokri_following_company() {
        global $nokri;
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '4';
            die();
        }
        $user_id = get_current_user_id();
        $company_id = $_POST['company_id'];
        $follow_date = date("F j, Y");

        nokri_check_user_activity();
        nokri_check_user_type();
        if ($company_id != "") {
            update_user_meta($user_id, '_cand_follow_company_' . $company_id, sanitize_text_field($company_id));
            update_user_meta($user_id, '_cand_follow_date', sanitize_text_field($follow_date));
        }
        echo "1";
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Candidate Un Following Company */
/* * ********************************* */
add_action('wp_ajax_un_following_company', 'nokri_un_following_company');
if (!function_exists('nokri_un_following_company')) {

    function nokri_un_following_company() {
        global $nokri;
        $user_id = get_current_user_id();
        $company_id = $_POST['company_id'];
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        if ($company_id != "") {
            if (delete_user_meta($user_id, '_cand_follow_company_' . $company_id)) {
                echo "1";
                die();
            } else {
                echo "0";
                die();
            }
        }
        echo "0";
        die();
    }

}
/* * ********************************* */
/* Return Followed Companies ID's */
/* * ********************************* */
if (!function_exists('nokri_following_company_ids')) {

    function nokri_following_company_ids($user_id) {
        /* Query For Getting All Followed Companies */
        global $wpdb;
        $query = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = '$user_id' AND meta_key like '_cand_follow_company_%'";
        $cand_followings = $wpdb->get_results($query);
        if (count((array) $cand_followings) > 0) {
            $ids = array();
            foreach ($cand_followings as $companies) {
                $ids[] = $companies->meta_value;
            }
            return $ids;
        }
    }

}
/* * ******************* */
/* Return attachment ID */
/* * ******************* */
add_action('wp_ajax_nopriv_sb_upload_user_docs', 'nokri_sb_upload_user_docs');
add_action('wp_ajax_sb_upload_user_docs', 'nokri_sb_upload_user_docs');
if (!function_exists('nokri_sb_upload_user_docs')) {

    function nokri_sb_upload_user_docs() {

        /* img upload */

        $condition_img = 7;
        $img_count = count(explode(',', $_POST["image_gallery"]));


        if (!empty($_FILES["my_file_upload"])) {

            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';


            $files = $_FILES["my_file_upload"];



            $attachment_ids = array();
            $attachment_idss = '';

            if ($img_count >= 1) {
                $imgcount = $img_count;
            } else {
                $imgcount = 1;
            }


            $ul_con = '';

            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );

                    $_FILES = array("my_file_upload" => $file);

// Allow certain file formats
                    $imageFileType = strtolower(end(explode('.', $file['name'])));




                    if ($imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx"
                    ) {
                        echo '0|' . __("Sorry, only PDF, DOC, DOCX  files are allowed.", 'nokri');
                        die();
                    }

                    // Check file size
                    /* if ($file['size'] > 2097152) {
                      echo '0|' . __( "Max allowd image size is 2MB", 'nokri' );
                      die();
                      } */


                    foreach ($_FILES as $file => $array) {

                        if ($imgcount >= $condition_img) {
                            break;
                        }
                        $attach_id = media_handle_upload($file, $post_id);
                        $attachment_ids[] = $attach_id;

                        $image_link = wp_get_attachment_image_src($attach_id, '');
                    }
                    if ($imgcount > $condition_img) {
                        break;
                    }
                    $imgcount++;
                }
            }
        }
        /* img upload */
        $attachment_idss = array_filter($attachment_ids);
        $attachment_idss = implode(',', $attachment_idss);


        $arr = array();
        $arr['attachment_idss'] = $attachment_idss;
        $arr['ul_con'] = $ul_con;

        echo '1|' . $attach_id;
        die();
    }

}
/* * ***************************************** */
/* Ajax handler for job alerts subscription */
/* * **************************************** */
add_action('wp_ajax_nopriv_job_alert_subscription', 'nokri_job_alert_subscription');
add_action('wp_ajax_job_alert_subscription', 'nokri_job_alert_subscription');
if (!function_exists('nokri_job_alert_subscription')) {

    function nokri_job_alert_subscription() {
        global $nokri;
        $user_id = get_current_user_id();
        // Getting values From Param
        $params = array();
        parse_str(stripslashes($_POST['submit_alert_data']), $params);
        $alert_name = $params['alert_name'];
        $alert_email = $params['alert_email'];
        $alert_frequency = $params['alert_frequency'];
        $alert_category = $params['alert_category'];
        $alert_type = $params['alert_type'];
        $alert_experience = $params['alert_experience'];
        $alert_location = $params['alert_location'];
        $user_addres = $params['sb_user_address2'];


        $random_string = nokri_randomString(5);
        $type = get_user_meta($user_id, '_sb_reg_type', true);
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        /* Not login */
        if ($user_id == '') {
            echo "3";
            die();
        }
        /* Not cand */
        if ($type != '0') {
            echo "4";
            die();
        }
        /* countries */
        $cand_alert = array();
        if ($params['alert_name'] != "") {
            $cand_alert[] = $params['alert_name'];
        }
        if ($params['alert_email'] != "") {
            $cand_alert[] = $params['alert_email'];
        }
        if ($params['alert_frequency'] != "") {
            $cand_alert[] = $params['alert_frequency'];
        }
        if ($params['alert_category'] != "") {
            $cand_alert[] = $params['alert_category'];
        }
        if ($params['alert_type'] != "") {
            $cand_alert[] = $params['alert_type'];
        }
        if ($params['alert_experience'] != "") {
            $cand_alert[] = $params['alert_category'];
        }
        if ($params['alert_location'] != "") {
            $cand_alert[] = $params['alert_location'];
        }
        if ($params['alert_start'] != "") {
            $cand_alert[] = $params['alert_start'];
        }
        if ($params['sb_user_address2'] != "") {
            $cand_alert[] = $params['sb_user_address2'];
        }
        $my_alert = json_encode($params);
        update_user_meta($user_id, '_cand_alerts_' . $user_id . $random_string, ($my_alert));

        if (get_user_meta($user_id, '_cand_alerts_en', true) == '') {
            update_user_meta($user_id, '_cand_alerts_en', 1);
        }

        echo "1";
        die();
    }

}
/* * ***************************************** */
/* Ajax handler for job alerts subscription */
/* * **************************************** */
add_action('wp_ajax_job_alert_paid_subscription', 'nokri_job_alert_paid_subscription');
if (!function_exists('nokri_job_alert_paid_subscription')) {

    function nokri_job_alert_paid_subscription() {
        global $nokri;
        $user_id = get_current_user_id();
        $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : "";
        nokri_verify_nonce($nonce, 'ajax-nonce');
        $product_id = isset($nokri['job_alert_package']) ? $nokri['job_alert_package'] : "";


        $params = array();
        parse_str(stripslashes($_POST['submit_alert_data']), $params);

        $my_alert = json_encode($params);
        update_user_meta($user_id, 'temp_test_alert', $my_alert);


        global $woocommerce;
        if ($woocommerce->cart->add_to_cart($product_id, 1)) {
            echo '1|' . __("Added to cart.", 'nokri') . '|' . wc_get_cart_url();
        } else {
            echo '1|' . __("Already in your cart.", 'nokri') . '|' . wc_get_cart_url();
        }
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Candidate del job alert */
/* * ********************************* */
add_action('wp_ajax_del_job_alerts', 'nokri_del_job_alerts');
if (!function_exists('nokri_del_job_alerts')) {

    function nokri_del_job_alerts() {
        global $nokri;
        $user_id = get_current_user_id();
        $alert_id = $_POST['alert_id'];
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        if ($alert_id != "") {
            if (delete_user_meta($user_id, $alert_id)) {
                echo "1";
                die();
            } else {
                echo "0";
                die();
            }
        }
        echo "0";
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Candidate external job package */
/* * ********************************* */
add_action('wp_ajax_nopriv_external_apply_package_base', 'nokri_external_apply_package_base');
add_action('wp_ajax_external_apply_package_base', 'nokri_external_apply_package_base');
if (!function_exists('nokri_external_apply_package_base')) {

    function nokri_external_apply_package_base() {
        global $nokri;
        $user_id = get_current_user_id();
        $user_type = get_user_meta($user_id, '_sb_reg_type', true);
        $apply_job_id = $_POST['apply_job_id'];
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '1|' . __("Demo mode activated", 'nokri');
            die();
        }
        /* Is applying job package base */
        $is_apply_pkg_base = ( isset($nokri['job_apply_package_base']) && $nokri['job_apply_package_base'] != "" ) ? $nokri['job_apply_package_base'] : false;
        /* signin page */
        $sign_in = ( isset($nokri['sb_sign_in_page']) && $nokri['sb_sign_in_page'] != "" ) ? $nokri['sb_sign_in_page'] : '';
        if ($is_apply_pkg_base == '1') {
            /* signin page */
            $sign_in = ( isset($nokri['sb_sign_in_page']) && $nokri['sb_sign_in_page'] != "" ) ? $nokri['sb_sign_in_page'] : '';
            if ($is_apply_pkg_base == '1' && $user_id == '') {
                echo '2|' . __("Please Login To Apply ", 'nokri') . '|' . get_the_permalink($sign_in);
                die();
            }
            /* Cand package page */
            $cand_package_page = ( isset($nokri['cand_package_page']) && $nokri['cand_package_page'] != "" ) ? $nokri['cand_package_page'] : '';
            /* Validating candidate job package */
            if ($is_apply_pkg_base == '1' && $user_type != '1') {
                $is_package = nokri_candidate_package_expire_notify();
                if ($is_package == 'ae' || $is_package == 'pe' || $is_package == 'np') {
                    echo '3|' . __("Please Purchase Package", 'nokri') . '|' . get_the_permalink($cand_package_page);
                    delete_user_meta($user_id, '_job_applied_external_' . $apply_job_id);
                    die();
                }
            }
            /* Cand external jobs */
            $cand_external_jobs = get_user_meta($user_id, '_job_applied_external_' . $apply_job_id, true);



            $external_jobs_array = (explode(",", $cand_external_jobs));


            if (!in_array($apply_job_id, $external_jobs_array)) {


                if ($cand_external_jobs != '') {
                    $apply_job_id = $cand_external_jobs . ',' . $apply_job_id;
                }
                update_user_meta($user_id, '_job_applied_external_' . $apply_job_id, ($apply_job_id));


                if ($is_apply_pkg_base == '1') {
                    $job_applied_rem = get_user_meta($user_id, '_candidate_applied_jobs', true);



                    if ($job_applied_rem != '0' && $job_applied_rem != '-1') {
                        update_user_meta($user_id, '_candidate_applied_jobs', $job_applied_rem - 1);
                    }
                }
                echo '4';
                die();
            } else {
                echo '4';
                die();
            }
        } else {
            /* with login or not */
            $with_log = ( isset($nokri['job_apply_package_base_wl']) && $nokri['job_apply_package_base_wl'] != "" ) ? $nokri['job_apply_package_base_wl'] : '2';
            if ($with_log == '1' && $user_id == '') {
                echo '2|' . __("Please Login To Apply ", 'nokri') . '|' . get_the_permalink($sign_in);
                die();
            } else {
                echo '4';
                die();
            }
        }
    }

}

//send email to employer 
function nokri_new_candidate_email_apply($job_id, $candidate_id, $resume_id, $cand_cover = "") {

    global $nokri;
    if ($nokri['sb_send_email_on_apply'] == '1' && isset($nokri['sb_msg_on_new_apply']) && $nokri['sb_msg_on_new_apply'] != "" && isset($nokri['sb_msg_from_on_new_apply']) && $nokri['sb_msg_from_on_new_apply'] != "") {
        // Auhtor info
        $author_id = get_post_field('post_author', $job_id);
        $author_id = get_userdata($author_id);
        $author_name = $author_id->display_name;
        $author_email = $author_id->user_email;
        $cand_cover = $cand_cover;
        // If job apply is with external link 
        $ext_email = get_post_meta($job_id, '_job_apply_mail', true);
        if ($ext_email != '') {
            $author_email = $ext_email;
        }
        $author_job_title = get_the_title($job_id);
        // Candidate  info
        $candidate_id = get_userdata($candidate_id);
        $candidate_name = $candidate_id->display_name;
        $subject = $nokri['sb_msg_subject_on_new_apply'];
        $from = $nokri['sb_msg_from_on_new_apply'];
        $headers = '';
        $headers .= "MIME-Version: 1.0 \r\n";
        $headers .= "Content-type: text/html; charset=\"UTF-8\" \r\n";
        $headers .= $from;

        $attachemnt = array();
        $attachemnt[] = get_attached_file($resume_id);
        $msg_keywords = array('%site_name%', '%job_title%', '%candidate_name%', '%candidate_cover%');
        $msg_replaces = array(get_bloginfo('name'), $author_job_title, $candidate_name, $cand_cover);
        $body = str_replace($msg_keywords, $msg_replaces, $nokri['sb_msg_on_new_apply']);
        wp_mail($author_email, $subject, $body, $headers, $attachemnt);
    }
}
