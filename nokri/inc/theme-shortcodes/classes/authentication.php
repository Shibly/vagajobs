<?php

// sign up form
if (!class_exists('authentication')) {

    class authentication
    {

        function nokri_sign_up_form($string = "", $terms = array(), $section_user_name = '', $section_user_email = '', $section_user_password = '', $section_term = '', $section_user_btn = '', $section_user_phone = '', $key_code = '', $section_term_link = '', $section_emp_btn = '', $section_cand_btn = '', $section_already_txt = '', $login_txt = '', $default_btn = "", $show_pasword = "", $show_pass_confirm = "")
        {
            global $nokri;
            $custom_feild_id = (isset($nokri['custom_registration_feilds'])) ? $nokri['custom_registration_feilds'] : '';
            $rtl_class = '';
            if (is_rtl()) {
                $rtl_class = "flip";
            }
            /* Only admin post job */
            $regsiter_buttons = "";
            if ((isset($nokri['job_post_for_admin'])) && $nokri['job_post_for_admin'] != '1') {
                $employer = $candidate = $active_emp = $active_cand = '';
                if ($default_btn == '1') {
                    $employer = 'checked="checked"';
                    $active_emp = 'active';
                } else {
                    $candidate = 'checked="checked"';
                    $active_cand = 'active';
                }
                $regsiter_buttons = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="btn-group" id="status" data-toggle="buttons">
					   <label class="btn btn-default btn-md ' . $active_emp . '">
					 <input type="radio" value="1" name="sb_reg_type" ' . $employer . '>' . $section_emp_btn . '</label>
					   </label>
					   <label class="btn btn-default btn-md ' . $active_cand . '">
					    <input type="radio" value="0" name="sb_reg_type" ' . $candidate . '>' . $section_cand_btn . '</label>
					   </label>
					</div>
				 </div>';
            }

            /* phone number verification fielkd */

            $phone_field = "";
            $verification_modal = "";
            $var_num = isset($nokri['user_phone_verification']) ? $nokri['user_phone_verification'] : false;
            if ($var_num) {
                $signin_page = (isset($nokri['sb_sign_in_page']) && $nokri['sb_sign_in_page'] != '') ? $nokri['sb_sign_in_page'] : '';
                $phone_field = '  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
					 <input placeholder="' . esc_html__('Phone Number', 'nokri') . '" class="form-control" type="text" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please enter Valid number with country code.', 'nokri') . '" name="sb_reg_contact" id="sb_reg_contact">
					</div>
				 </div>';
                $verification_modal .= '<div class="custom-modal">
                            <div id="verification_modal" class="resume-action-modal modal fade" role="dialog">
                               <div class="modal-dialog">
                                  <!-- Modal content-->
                                  <div class="modal-content">
                                     <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal">×</button>
                                        <h4 class="modal-title">' . __('Verify phone number', 'nokri') . '</h4>
                                     </div>
                                     <form id="sb-ph-verification">
                                        <div class="modal-body">

                                           <div class="form-group sb_ver_ph_code_div no-display">
                                           <div>
                                           <label id="verification_label"> </label>
                                           </div>
                                             <label>' . __('Enter code', 'nokri') . '</label>
                                             <input class="form-control" type="text" name="sb_ph_number_code" id="sb_ph_number_code">
                                               <small class="pull-right">' . __('Did not get code?', 'nokri') . ' <a href="javascript:void(0);" class="small_text" id="resend_now">' . __('Resend now', 'nokri') . '</a></small>
                                           </div>
                                        </div>
                                        <div class="modal-footer">                                     
                                              <button class="btn n-btn-flat no-display" type="button" id="sb_verification_processing">' . __('Processing ...', 'nokri') . '</button>
                                              <button class="btn  n-btn-flat no-display" type="button" id="sb_verification_ph_code">' . __('Verify now', 'nokri') . '</button>
                                              <input type="hidden" value=""  id="user_id">
                                              <input type="hidden" value=""  id="user_phone_number">
                                              <input type="hidden"  value="' . esc_url(get_the_permalink($signin_page)) . '"  id="sign_in_page">
                                        </div>
                                 </form>
                                  </div>
                               </div>
                            </div>
                       </div>';
            }

            /* Custom feilds for registration */
            $custom_feilds_html = '';
            if ($custom_feild_id) {
                $custom_feilds_html = nokri_get_custom_feilds('', 'Registration', $custom_feild_id, '', '');
            }
            $show_pasword_div = "";
            $show_pass_confirm_div = "";
            if ($show_pasword == "yes") {

                $show_pasword_div = ' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="remember_area">
                                        <div class="form-group pull-right">
						   <input type="checkbox" name="show_password" id="show_password" class="input-icheck-others" >
						  <p> ' . esc_html__('Show password', 'nokri') . '</p>
			          </div>
                                   </div>
				 </div>';
            }
            if ($show_pass_confirm == "yes") {

                $show_pass_confirm_div = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
					   <input placeholder="' . esc_html__('Confirm Password', 'nokri') . '" class="form-control" type="password" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please confirm your password.', 'nokri') . '" name="sb_reg_conf_password" id="sb_reg_conf_password">
					</div>
				 </div>';
            }
            $recaptcha = "";
            $contact_recaptcha = isset($nokri['signin_form_recaptcha_switch']) ? $nokri['signin_form_recaptcha_switch'] : false;
            if ($nokri['google_recaptcha_key'] != "" && $contact_recaptcha) {
                $recaptcha = ' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="g-recaptcha form-group" name="contact-recaptcha" data-sitekey="' . esc_attr($nokri['google_recaptcha_key']) . '">
                                        </div></div> ';
            }


            $subscribe_check = "";

            $allow_subscribe = isset($nokri['subscribe_on_user_register']) ? $nokri['subscribe_on_user_register'] : false;
            $allow_check = isset($nokri['subscriber_checkbox_on_register']) ? $nokri['subscriber_checkbox_on_register'] : false;
            $subscribe_text = isset($nokri['subscriber_checkbox_on_register_text']) ? $nokri['subscriber_checkbox_on_register_text'] : false;

            if ($allow_subscribe && $allow_check) {
                $subscribe_check = '<div>
                     <input type="checkbox" name="subscribe_now" class="input-icheck-others"  >
                     <p>' . esc_html($subscribe_text) . '</p>
                     </div>';
            }

            $validator_switch = isset($nokri['password_validator_switch']) ? $nokri['password_validator_switch'] : false;
            $validators = "";
            if ($validator_switch) {
                $validators = "data-parsley-lowercase='1'    data-parsley-uppercase='1' data-parsley-number='1' data-parsley-customlimit='8' data-parsley-trigger='keyup'  data-parsley-trigger='change'";
            }
            return '<form id="sb-signup-form" method="post" >
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
					    <input placeholder="' . esc_html($section_user_name) . '" class="form-control" type="text" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please enter your name.', 'nokri') . '" name="sb_reg_name" >
					</div>
				 </div>
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
					  <input placeholder="' . esc_html($section_user_email) . '" class="form-control" type="email" data-parsley-type="email" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please enter your valid email.', 'nokri') . '" data-parsley-trigger="change" name="sb_reg_email">
					</div>
				 </div>                                 
                                 ' . $phone_field . '                               		
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group  custom-parsely">
					   <input placeholder="' . esc_html($section_user_password) . '" class="form-control" type="password" data-parsley-required="true" data name="sb_reg_password" id="sb_reg_password" 
                                                 ' . $validators . '>
					</div>
				 </div>
                                 ' . $show_pass_confirm_div . '                                
                                 ' . $show_pasword_div . '                                
				 <div style="visibility:hidden; position: absolute;">				
				 ' . $regsiter_buttons . '				
				 ' . $custom_feilds_html . '
                 </div> 
                                 ' . $recaptcha . '
				
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                
					<div class="buttons-area">
                                            ' . $subscribe_check . '
					   <div class="form-group">
						  <input type="checkbox" name="icheck_box" class="input-icheck-others" data-parsley-required="true" data-parsley-error-message="' . __('Please accept terms and conditions.', 'nokri') . '" >
						  <p> ' . esc_html__('I Agree To', 'nokri') . ' <a href="' . $section_term_link . '" target="_blank">' . $section_term . '</a></p>
					   </div>
                                            

					   <button class="bluebutton n-btn-flat btn-mid pull-right ' . esc_attr($rtl_class) . '" type="submit" id="sb_register_submit">' . $section_user_btn . '</button>
					   <button class="bluebutton n-btn-flat btn-mid pull-right ' . esc_attr($rtl_class) . ' no-display disabled" type="button" id="sb_register_msg">' . esc_html__('Processing...', 'nokri') . '</button>
					   <button class="bluebutton n-btn-flat btn-mid pull-right ' . esc_attr($rtl_class) . ' no-display disabled" type="button" id="sb_register_redirect">' . esc_html__('Redirecting...', 'nokri') . '</button>
					</div>
				 </div>
		
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="signup-area">
					   ' . $section_already_txt . ' <a href="' . get_the_permalink($nokri['sb_sign_in_page']) . '">' . $login_txt . '</a>
					</div>
				 </div>
		   <input type="hidden" class="get_action" value="register"/>
		   <input type="hidden" id="verify_account_msg" value="' . esc_attr__('Verification email has been sent to your email.', 'nokri') . '" />
		  <input type="hidden" id="verify_msg" value="' . esc_attr__('Verification code has been sent.', 'nokri') . '" />
                   <input type="hidden" id="nonce" value="' . $key_code . '" />
		</form> ' . $verification_modal . '';
        }

        // sign In form
        function nokri_sign_in_form($key_code = '', $already_acount = '', $sign_up = '', $submit_button = '', $forgot_password = '', $user_email_plc = "", $user_password_plc = "", $remember_me = "", $show_pasword = "")
        {
            global $nokri;
            $rtl_class = '';
            if (is_rtl()) {
                $rtl_class = "flip";
            }

            $remember_div = "";
            $show_pasword_div = "";

            if ($show_pasword == "yes") {

                $show_pasword_div = '<div class="form-group pull-right">
						   <input type="checkbox" name="show_password" id="show_password" class="input-icheck-others" >
						  <p> ' . esc_html__('Show password', 'nokri') . '</p>
			          </div>';
            }
            if ($remember_me == "yes") {

                $remember_div = '<div class="form-group">
						  <input type="checkbox" name="remember_me" id="remember_me" class="input-icheck-others" >
						  <p> ' . esc_html__('Remember me', 'nokri') . '</p>
			        </div>';
            }

            $ext_div = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="remember_area">
					   ' . $remember_div . '
                                           ' . $show_pasword_div . '					  		   
					</div>
				 </div>';
            $recaptcha = "";
            $contact_recaptcha = isset($nokri['signin_form_recaptcha_switch']) ? $nokri['signin_form_recaptcha_switch'] : false;
            if ($nokri['google_recaptcha_key'] != "" && $contact_recaptcha) {
                $recaptcha = ' <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="g-recaptcha form-group" name="contact-recaptcha" data-sitekey="' . esc_attr($nokri['google_recaptcha_key']) . '">
                                        </div></div> ';
            }

            $verification_modal = "";
            $var_num = isset($nokri['user_phone_verification']) ? $nokri['user_phone_verification'] : false;
            if ($var_num) {
                $signin_page = (isset($nokri['sb_sign_in_page']) && $nokri['sb_sign_in_page'] != '') ? $nokri['sb_sign_in_page'] : '';
                $verification_modal .= '<div class="custom-modal">
                            <div id="sign_verification_modal" class="resume-action-modal modal fade" role="dialog">
                               <div class="modal-dialog">
                                  <!-- Modal content-->
                                  <div class="modal-content">
                                     <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal">×</button>
                                        <h4 class="modal-title">' . __('Verify phone number', 'nokri') . '</h4>
                                     </div>
                                     <form id="sb_login_verification_form">
                                        <div class="modal-body">
                                        
                                        <div class="form-group">
                                         <label>' . __('Enter your registered email', 'nokri') . '</label>
					 <input placeholder="' . esc_html__('Your Email', 'nokri') . '" class="form-control" type="email" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please enter Valid email.', 'nokri') . '" name="user_email" id="user_email" readonly>
					</div>                            
                                       <div class="form-group">
                                         <label>' . __('Enter your registered phone number e.g +923311212122', 'nokri') . '</label>
					 <input placeholder="' . esc_html__('Phone Number', 'nokri') . '" class="form-control" type="text" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please enter Valid number with country code.', 'nokri') . '" name="user_phone_number" id="user_phone_number">
					</div>
                                           <div class="form-group sb_ver_ph_code_div no-display">
                                           <div>
                                           <label id="verification_label"> </label>
                                           </div>
                                             <label>' . __('Enter code', 'nokri') . '</label>
                                             <input class="form-control" type="text" name="sb_ph_number_code" id="sb_ph_number_code"   data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please enter Valid code', 'nokri') . '">
                                               <small class="pull-right">' . __('Did not get code?', 'nokri') . ' <a href="javascript:void(0);" class="small_text" id="resend_now">' . __('Resend now', 'nokri') . '</a></small>
                                           </div>
                                           
                                        </div>
                                        <div class="modal-footer">                                     
                                              <button class="btn n-btn-flat no-display" type="button" id="sb_verification_processing">' . __('Processing ...', 'nokri') . '</button>
                                              <button class="btn  n-btn-flat no-display" type="submit" id="verify_acc">' . __('Verify now', 'nokri') . '</button>                                              
                                              <input type="hidden"  value="' . esc_url(get_the_permalink($signin_page)) . '"  id="sign_in_page">
                                        </div>
                                 </form>
                                  </div>
                               </div>
                            </div>
                       </div>';
            }


            return '<form id="sb-login-form-data"  >
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
					   <input placeholder="' . esc_html($user_email_plc) . '" class="form-control" type="email" data-parsley-type="email" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please enter your valid email.', 'nokri') . '" data-parsley-trigger="change" name="sb_reg_email" id="sb_reg_email" autocomplete="off">
					</div>
				 </div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
					   <input placeholder="' . esc_html($user_password_plc) . '" class="form-control" type="password" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please enter your password.', 'nokri') . '" name="sb_reg_password" id="sb_reg_password" autocomplete="off">
					</div>
				 </div>
                                 ' . $recaptcha . '
                               ' . $ext_div . '
                                 
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
					   <a href="#" class="pull-left ' . esc_attr($rtl_class) . '" data-target="#myModal" data-toggle="modal">' . ($forgot_password) . '</a>
					   <button class="bluebutton n-btn-flat btn-mid pull-right ' . esc_attr($rtl_class) . '" type="submit" id="sb_login_submit">' . ($submit_button) . '</button>
		                           <button class="bluebutton n-btn-flat btn-mid pull-right ' . esc_attr($rtl_class) . ' no-display" type="button" id="sb_login_msg" disabled>' . esc_html__('Processing...', 'nokri') . '</button>
		                           <button class="bluebutton n-btn-flat btn-mid pull-right ' . esc_attr($rtl_class) . ' no-display" type="button" id="sb_login_redirect" disabled>' . esc_html__('Redirecting...', 'nokri') . '</button>
					</div>
				 </div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="signup-area">
					   ' . ($already_acount) . '<a href="' . get_the_permalink($nokri['sb_sign_up_page']) . '">' . " " . ($sign_up) . '</a>
					</div>
				 </div>
		   <input type="hidden" id="nonce" value="' . $key_code . '" />
		   <input type="hidden" class="get_action" value="login" />
                    <input type="hidden" id="acc_not_verified" value="' . esc_html__("Your account is not verified yet.", "nokri") . '" />
		</form>' . $verification_modal . '';
        }

        // Forgot Password Form
        function nokri_forgot_password_form()
        {
            return '
			<form id="sb-forgot-form">
				 <div class="modal-body">
					<div class="form-group">
					  <label>' . esc_html__('Email', 'nokri') . '</label>
					  <input placeholder="' . esc_html__('Your Email Where We Send You New Password', 'nokri') . '" class="form-control" type="email" data-parsley-type="email" data-parsley-required="true" data-parsley-error-message="' . esc_html__('Please enter valid email.', 'nokri') . '" data-parsley-trigger="change" name="sb_forgot_email" id="sb_forgot_email">
					</div>
				 </div>
				 <div class="modal-footer">
					   <button class="btn n-btn-flat btn-mid btn-block" type="submit" id="sb_forgot_submit">' . esc_html__('Reset My Account', 'nokri') . '</button>
					   <button class="btn btn-default" type="button" id="sb_forgot_msg">' . esc_html__('Processing...', 'nokri') . '</button>
				 </div>
		  </form>
		';
        }

    }

}
// Ajax handler for Login User
add_action('wp_ajax_sb_login_user', 'nokri_login_user');
add_action('wp_ajax_nopriv_sb_login_user', 'nokri_login_user');
// Login User
if (!function_exists('nokri_login_user')) {

    function nokri_login_user()
    {
        global $nokri;
        // Getting values

        $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : "";
        nokri_verify_nonce($nonce, 'ajax-nonce');

        $params = array();
        parse_str($_POST['sb_login_data'], $params);
        $remember = false;
        $remember_me = isset($params['remember_me']) ? $params['remember_me'] : false;
        if ($remember_me == "on") {
            $remember = true;
        }
        if (isset($params['g-recaptcha-response'])) {
            if (nokri_recaptcha_verify($nokri['google_recaptcha_secret_key'], $params['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'], '')) {
                $user = wp_authenticate($params['sb_reg_email'], $params['sb_reg_password']);
                if (!is_wp_error($user)) {
                    if (count((array)$user->roles) == 0) {
                        echo __('Your account is not verified yet.', 'nokri');
                        die();
                    } else {
                        $res = nokri_auto_login($params['sb_reg_email'], $params['sb_reg_password'], $remember);
                        if ($res == 1) {
                            echo "1";
                        }
                    }
                } else {
                    echo esc_html__('Invalid email or password.', 'nokri');
                }
                die();
            } else {
                echo esc_html__("Please verify captcha.", 'nokri');
                die();
            }
        } else {
            $user = wp_authenticate($params['sb_reg_email'], $params['sb_reg_password']);
            if (!is_wp_error($user)) {
                if (count((array)$user->roles) == 0) {
                    echo "2";
                    die();
                } else {
                    $res = nokri_auto_login($params['sb_reg_email'], $params['sb_reg_password'], $remember);
                    if ($res == 1) {
                        echo "1";
                    }
                }
            } else {
                echo esc_html__('Invalid email or password.', 'nokri');
            }
            die();
        }
    }

}
// Ajax handler for Register User
add_action('wp_ajax_sb_register_user', 'nokri_register_user');
add_action('wp_ajax_nopriv_sb_register_user', 'nokri_register_user');
if (!function_exists('nokri_register_user')) {

// Register User
    function nokri_register_user()
    {

        global $nokri;
        $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : "";

        nokri_verify_nonce($nonce, 'ajax-nonce');

        $params = array();
        parse_str($_POST['sb_signup_data'], $params);

        if (isset($params['g-recaptcha-response'])) {
            if (!nokri_recaptcha_verify($nokri['google_recaptcha_secret_key'], $params['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'], '')) {
                echo "6|" . esc_html__("Please verify captcha.", 'nokri');
                die();
            }
        }
        /* verify phone number format */
        $var_num = isset($nokri['user_phone_verification']) ? $nokri['user_phone_verification'] : false;
        $phone = "";

        if ($var_num) {
            $phone = isset($params['sb_reg_contact']) ? sanitize_text_field($params['sb_reg_contact']) : "";
            if (!preg_match("/\+[0-9]+$/", $phone)) {
                echo "6|" . __('Please add valid phone number +CountrycodePhonenumber', 'nokri');
                die();
            }
        }

        if (email_exists($params['sb_reg_email']) == false) {
            /* demo check */
            $is_demo = nokri_demo_mode();
            if ($is_demo) {
                echo '3|' . "";
                die();
            }
            if (isset($params['sb_reg_conf_password']) && $params['sb_reg_conf_password'] != "") {
                if ($params['sb_reg_conf_password'] != $params['sb_reg_password']) {
                    echo '4|' . "";
                    die();
                }
            }

            $user_name = explode('@', $params['sb_reg_email']);
            $u_name = nokri_check_user_name($user_name[0]);
            $uid = wp_create_user($u_name, $params['sb_reg_password'], sanitize_email($params['sb_reg_email']));
            /* Updating Custom feilds */
            if (isset($params['_custom_']) && count($params['_custom_']) > 0) {
                foreach ($params['_custom_'] as $key => $data) {
                    if (is_array($data)) {
                        $dataArr = array();
                        foreach ($data as $k)
                            $dataArr[] = $k;
                        $data = stripslashes(json_encode($dataArr, JSON_UNESCAPED_UNICODE));
                    }
                    update_user_meta($uid, $key, sanitize_text_field($data));
                }
            }
            /* Updating Custom feilds ends */
            wp_update_user(array('ID' => $uid, 'display_name' => sanitize_text_field($params['sb_reg_name'])));

            $profile_status = isset($nokri['default_profile_option']) ? $nokri['default_profile_option'] : 'pub';

            update_user_meta($uid, '_sb_contact', $phone);
            update_user_meta($uid, '_sb_reg_type', sanitize_text_field($params['sb_reg_type']));
            update_user_meta($uid, '_user_profile_status', sanitize_text_field($profile_status));
            // Email for new user
            if (function_exists('nokri_email_on_new_user')) {
                nokri_email_on_new_user($uid, '');
            }
            /* Assign package to employer */
            $product_id = nokri_assign_free_package();
            if (isset($product_id) && $product_id != '') {
                if (isset($nokri['user_assign_pkg']) && $nokri['user_assign_pkg'] == '1' && $params['sb_reg_type'] == '1') {
                    $is_pkg_free = get_post_meta($product_id, 'op_pkg_typ', true);
                    if ($is_pkg_free == 1) {
                        nokri_free_package($product_id, $uid);
                    }
                }
            }
            /* Assign package to candidate */
            $product_cand_id = nokri_candidate_assign_free_package();
            if (isset($product_cand_id) && $product_cand_id != '') {
                if (isset($nokri['cand_assign_pkg']) && $nokri['cand_assign_pkg'] == '1' && $params['sb_reg_type'] == '0') {
                    $is_pkg_free = get_post_meta($product_cand_id, 'op_pkg_typ', true);
                    if ($is_pkg_free == 1) {
                        nokri_free_package_for_candidate($product_cand_id, $uid);
                    }
                }
            }


            $subscribe_now = isset($params['subscribe_now']) ? $params['subscribe_now'] : "off";
            if ($subscribe_now == "on") {
                if (function_exists('nokri_subscribe_user_on_registration')) {
                    nokri_subscribe_user_on_registration($uid);
                }
            }

            if ($var_num && $phone != "") {
                $code = mt_rand(100000, 500000);
                $code = $uid . "-" . $code;
                $res = nokri_send_sms($phone, $code);

                $gateway = nokri_verify_sms_gateway();
                $sms_sent = false;
                if ($gateway == "iletimerkezi-sms" && $res == true) {
                    $sms_sent = true;
                }
                if ($gateway == "twilio" && $res->sid) {
                    $sms_sent = true;
                }

                if ($sms_sent) {
                    //if( true )
                    update_user_meta($uid, 'user_ph_code_', $code);
                    update_user_meta($uid, '_sb_is_ph_verified', '0');
                    update_user_meta($uid, '_ph_code_date_', date('Y-m-d H:i:s'));
                    $user = new WP_User($uid);
                    // Remove all user roles after registration
                    foreach ($user->roles as $role) {
                        $user->remove_role($role);
                    }
                    echo "5|" . $uid;
                    die();
                } else {
                    echo "6|" . __("Server not responding.", 'nokri');
                    update_user_meta($uid, '_sb_is_ph_verified', '0');
                    die();
                }
            }

            if (isset($nokri['sb_new_user_email_verification']) && $nokri['sb_new_user_email_verification']) {
                $user = new WP_User($uid);
                // Remove all user roles after registration
                foreach ($user->roles as $role) {
                    $user->remove_role($role);
                }
                echo "2|" . "";
                die();
            } else {
                nokri_auto_login($params['sb_reg_email'], $params['sb_reg_password'], true);
                echo "1|" . "";
                die();
            }
        } else {
            echo "6|" . esc_html__('Email already exist, please try other one.', 'nokri');
        }
        die();
    }

}

// Verify user verification code  on registration
add_action('wp_ajax_nokri_verification_system', 'nokri_verification_system_fun');
add_action('wp_ajax_nopriv_nokri_verification_system', 'nokri_verification_system_fun');
if (!function_exists('nokri_verification_system_fun')) {

    function nokri_verification_system_fun()
    {
        $code = isset($_POST['code_entered']) ? $_POST['code_entered'] : "";
        $user_id = "";
        $code_arr = explode('-', $code);
        if (empty($code_arr) || count($code_arr) < 2) {
            echo "1|" . esc_html__('you have entered wrong otp 1', 'nokri');
            die();
        } else {
            $user_id = $code_arr[0];
            $userdata = get_userdata($user_id);
            if ($userdata) {
                $saved_code = get_user_meta($user_id, 'user_ph_code_', true);
                if ($saved_code == $code) {
                    $user = new WP_User($user_id);
                    $user->set_role('subscriber');
                    echo "2|" . esc_html__("Your account has been verified.", 'nokri');
                    die();
                } else {
                    echo "1|" . esc_html__('you have entered wrong otp', 'nokri');
                    die();
                }
            } else {
                echo "1|" . esc_html__('you have entered wrong otp ', 'nokri');
                die();
            }
        }
    }

}

// Verify user verification code  on login
add_action('wp_ajax_nokri_login_verification_system', 'nokri_login_verification_system_fun');
add_action('wp_ajax_nopriv_nokri_login_verification_system', 'nokri_login_verification_system_fun');
if (!function_exists('nokri_login_verification_system_fun')) {

    function nokri_login_verification_system_fun()
    {

        $params = array();
        parse_str($_POST['form_data'], $params);

        $code = isset($params['sb_ph_number_code']) ? $params['sb_ph_number_code'] : "";
        $email = isset($params['user_email']) ? $params['user_email'] : "";
        $phone_number = isset($params['user_phone_number']) ? $params['user_phone_number'] : "";
        $user = get_user_by('email', $email);

        if ($user) {
            $user_id = $user->ID;
            $Saved_code = get_user_meta($user_id, 'user_ph_code_', ture);
            if ($code == $Saved_code) {
                $user_user = new WP_User($user_id);
                $user->set_role('subscriber');
                echo "1|" . esc_html__("Your account has been verified.", 'nokri');
                die();
            } else {
                echo "2|" . esc_html__("Your have entered wrong otp.", 'nokri');
                die();
            }
        } else {
            echo "2|" . esc_html__("No user found for this email", 'nokri');
            die();
        }
    }

}


/* resend verification code */
add_action('wp_ajax_nokri_resend_verification_code', 'nokri_resend_verification_code_fun');
add_action('wp_ajax_nopriv_nokri_resend_verification_code', 'nokri_resend_verification_code_fun');

if (!function_exists('nokri_resend_verification_code_fun')) {

    function nokri_resend_verification_code_fun()
    {

        $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : "";
        $uid = isset($_POST['user_id']) ? $_POST['user_id'] : "";
        $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : "";

        if ($uid == "" && $user_email != "") {
            $user = get_user_by('email', $user_email);
            if ($user) {

                $uid = $user->ID;
            }
        }
        if ($phone_number != "" && $uid != "") {
            $code = mt_rand(100000, 500000);
            $code = $uid . "-" . $code;
            $res = nokri_send_sms($phone_number, $code);

            $gateway = nokri_verify_sms_gateway();
            $sms_sent = false;
            if ($gateway == "iletimerkezi-sms" && $res == true) {
                $sms_sent = true;
            }
            if ($gateway == "twilio" && $res->sid) {
                $sms_sent = true;
            }

            if ($sms_sent) {
                update_user_meta($uid, 'user_ph_code_', $code);
                echo "1|" . esc_html__('Verification sms has been sent', 'nokri');
                die();
            } else {
                echo "2|" . esc_html__('Something went wrong', 'nokri');
                die();
            }
        } else {
            echo "2|" . esc_html__('Something went wrong', 'nokri');
            die();
        }
    }

}

if (!function_exists('nokri_auto_login')) {

    function nokri_auto_login($username, $password, $remember)
    {
        $creds = array();
        $creds['user_login'] = $username;
        $creds['user_password'] = $password;
        $creds['remember'] = $remember;

        $user = wp_signon($creds, false);
        if (is_wp_error($user)) {
            return false;
        } else {
            if (count((array)$user->roles) > 0) {
                return true;
            } else {
                return 2;
            }
        }
    }

}
add_action('wp_ajax_sb_social_login', 'nokri_check_social_user');
add_action('wp_ajax_nopriv_sb_social_login', 'nokri_check_social_user');
if (!function_exists('nokri_check_social_user')) {

    function nokri_check_social_user($keycode = '', $email = '', $name = '', $headline = '', $profile_url = '')
    {


        $network = (isset($_POST['sb_network'])) ? $_POST['sb_network'] : '';


        $response_response = false;
        $user_name = "";
        if ($network == 'facebook') {
            $access_token = (isset($_POST['access_token'])) ? $_POST['access_token'] : '';
            $token_verify = wp_remote_get("https://graph.facebook.com/me?fields=name,email&access_token=$access_token");
            if (isset($token_verify['response']['code']) && $token_verify['response']['code'] == '200') {
                $info = (json_decode($token_verify['body']));
                if (isset($_POST['email']) && isset($token_verify['body'])) {
                    if (isset($info->email) && $info->email == $_POST['email']) {
                        $user_name = $info->email;
                        $response_response = true;
                    }
                }
            }
        } else if ($network == 'google') {
            $access_token = (isset($_POST['access_token'])) ? $_POST['access_token'] : '';
            $token_verify = wp_remote_get("https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=$access_token");
            if (isset($token_verify['response']['code']) && $token_verify['response']['code'] == '200') {
                $info = (json_decode($token_verify['body']));
                if (isset($_POST['email']) && isset($token_verify['body'])) {
                    if (isset($info->email) && $info->email == $_POST['email']) {
                        $user_name = $info->email;
                        $response_response = true;
                    }
                }
            }
        }

        if ($response_response == false) {
            echo '0|error|Invalid request|Authentication failed';
            die();
        }


        //if( $_SESSION['sb_nonce'] == $_POST['key_code'] )
        global $nokri;

        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '3|' . __("Edit in demo user not allowed", 'nokri');
            die();
        }
        if ($keycode != "" || $_POST['key_code'] != "") {
            unset($_SESSION['sb_nonce']);
            $_SESSION['sb_nonce'] = time();
            if (email_exists($user_name) == true) {
                $user = get_user_by('email', $user_name);
                $user_id = $user->ID;
                /* if only admin can post */
                if ((isset($nokri['job_post_for_admin'])) && $nokri['job_post_for_admin'] == '1') {
                    //update_user_meta($user_id, '_sb_reg_type', 1);
                }
                if ($user) {
                    wp_set_current_user($user_id, $user->user_login);
                    wp_set_auth_cookie($user_id);
                    //do_action( 'wp_login', $user->user_login );

                    if ($keycode == "") {
                        echo '1|' . $_SESSION['sb_nonce'] . '|1|' . __("You're logged in successfully.", 'nokri');
                    } else {
                        return 1;
                    }
                }
            } else {
                // Here we need to register user.
                $password = mt_rand(1000, 10000);
                $uid = nokri_do_register($user_name, $password);
                global $nokri;
                if (function_exists('nokri_email_on_new_user')) {
                    nokri_email_on_new_user($uid, $password);
                }
                if ($keycode == "") {
                    echo '1|' . $_SESSION['sb_nonce'] . '|1|' . __("You're registered and logged in successfully.", 'nokri');
                } else {
                    $user = get_user_by('email', $user_name);
                    $user_id = $user->ID;
                    if ($user) {
                        wp_set_current_user($user_id, $user->user_login);
                        wp_set_auth_cookie($user_id);
                        // need to store user meta values
                    }
                    return 2;
                }
            }
        } else {
            echo '0|error|Invalid request|Diret Access not allowed';
        }
        die();
    }

}

function nokri_check_social_user_linkedin($keycode = '', $email = '', $name = '', $headline = '', $profile_url = '')
{

    global $nokri;

    /* demo check */
    $is_demo = nokri_demo_mode();
    if ($is_demo) {
        echo '3|' . __("Edit in demo user not allowed", 'nokri');
        die();
    }
    if ($keycode != "" || $_POST['key_code'] != "") {
        if ($email == "") {
            $user_name = $_POST['email'];
        } else {
            $user_name = $email;
        }
        unset($_SESSION['sb_nonce']);
        $_SESSION['sb_nonce'] = time();
        if (email_exists($user_name) == true) {
            $user = get_user_by('email', $user_name);
            $user_id = $user->ID;
            /* if only admin can post */
            if ((isset($nokri['job_post_for_admin'])) && $nokri['job_post_for_admin'] == '1') {
                //update_user_meta($user_id, '_sb_reg_type', 1);
            }
            if ($user) {
                wp_set_current_user($user_id, $user->user_login);
                wp_set_auth_cookie($user_id);
                //do_action( 'wp_login', $user->user_login );

                if ($keycode == "") {
                    echo '1|' . $_SESSION['sb_nonce'] . '|1|' . __("You're logged in successfull.", 'nokri');
                } else {
                    return 1;
                }
            }
        } else {
            // Here we need to register user.
            $password = mt_rand(1000, 10000);
            $uid = nokri_do_register($user_name, $password);
            global $nokri;
            if (function_exists('nokri_email_on_new_user')) {
                nokri_email_on_new_user($uid, $password);
            }
            if ($keycode == "") {
                echo '1|' . $_SESSION['sb_nonce'] . '|1|' . __("You're registered and logged in successfully.", 'nokri');
            } else {
                $user = get_user_by('email', $user_name);
                $user_id = $user->ID;
                if ($user) {
                    wp_set_current_user($user_id, $user->user_login);
                    wp_set_auth_cookie($user_id);
                    // need to store user meta values
                }
                return 2;
            }
        }
    } else {
        echo '0|error|Invalid request|Diret Access not allowed';
    }
    die();
}

if (!function_exists('nokri_user_not_logged_in')) {

    function nokri_user_not_logged_in()
    {
        global $nokri;
        if (get_current_user_id() == "") {
            echo nokri_redirect(home_url('/'));
        }
    }

}
if (!function_exists('nokri_user_logged_in')) {

    function nokri_user_logged_in()
    {
        if (get_current_user_id() != "") {
            echo nokri_redirect(home_url('/'));
        }
    }

}
if (!function_exists('nokri_check_user_name')) {

    function nokri_check_user_name($username = '')
    {
        if (username_exists($username)) {
            $random = rand();
            $username = $username . '-' . $random;
            nokri_check_user_name($username);
        }
        return $username;
    }

}
add_action('wp_ajax_sb_reset_password', 'nokri_reset_password');
add_action('wp_ajax_nopriv_sb_reset_password', 'nokri_reset_password');
// Reset Password
if (!function_exists('nokri_reset_password')) {

    function nokri_reset_password()
    {
        global $nokri;
        // Getting values
        $params = array();
        parse_str($_POST['sb_data'], $params);
        $token = $params['token'];
        $token_arr = explode('-sb-uid-', $token);
        $key = $token_arr[0];
        $uid = $token_arr[1];
        $token_db = get_user_meta($uid, 'sb_password_forget_token', true);
        if ($token_db != $key) {
            echo '0|' . esc_html__("Invalid security token.", 'nokri');
        } else {
            $new_password = $params['sb_new_password'];
            wp_set_password($new_password, $uid);
            update_user_meta($uid, 'sb_password_forget_token', '');
            echo '1|' . esc_html__("Password Changed successfully.", 'nokri');
        }
        die();
    }

}

/* After Social Login Acount Check */
add_action('wp_ajax_after_social_login', 'nokri_after_social_login');
if (!function_exists('nokri_after_social_login')) {

    function nokri_after_social_login()
    {

        global $nokri;
        $user_id = get_current_user_id();
        $acount_type = array();
        parse_str($_POST['social_login_data'], $acount_type);
        $params = $acount_type;
        $acount_type = $acount_type['sb_reg_type'];
        update_user_meta($user_id, '_sb_reg_type', $acount_type);

        if ($acount_type == 0) {


            /* Getting Professional Details & Updating Values  */
            $cand_profession = ($params['cand_profession']);


            $proData = nokri_convert_to_array($cand_profession);

            if (isset($proData['arr'])) {
                $arr = $proData['arr'];
                $profession = [];

                // Clean the keys first to determine the number of projects
                $cleaned_keys_arr = [];
                foreach ($arr as $key => $value) {
                    $cleaned_key = stripslashes($key);
                    $cleaned_keys_arr[$cleaned_key] = $value;
                }

                // Determine the number of projects based on one of the cleaned keys.
                $numProjects = count($cleaned_keys_arr['project_organization']);

                for ($i = 0; $i < $numProjects; $i++) {
                    $cleaned_arr = [];

                    // Assign values for the current project using cleaned keys
                    foreach ($cleaned_keys_arr as $cleaned_key => $value) {
                        $cleaned_arr[$cleaned_key] = $value[$i];
                    }

                    // Fetch and sanitize project_name
                    $project_name = isset($cleaned_arr['project_name']) ? $cleaned_arr['project_name'] : '1';

                    // Fetch and sanitize project_start
                    $project_start = isset($cleaned_arr['project_start']) ? sanitize_text_field($cleaned_arr['project_start']) : '';

                    // Logic for project_end based on project_name
                    $project_end = ($project_name == '1') ? '' : (isset($cleaned_arr['project_end']) ? sanitize_text_field($cleaned_arr['project_end']) : '');

                    // Fetch and sanitize project_role
                    $project_role = isset($cleaned_arr['project_role']) ? sanitize_text_field($cleaned_arr['project_role']) : '';

                    // Fetch and sanitize project_organization
                    $project_organization = isset($cleaned_arr['project_organization']) ? sanitize_text_field($cleaned_arr['project_organization']) : '';

                    // Fetch and sanitize project_desc
                    $project_desc = isset($cleaned_arr['project_desc']) ? wp_kses($cleaned_arr['project_desc'], nokri_required_tags()) : '';

                    // Consolidate the project data
                    $project = [
                        'project_name' => $project_name,
                        'project_start' => $project_start,
                        'project_end' => $project_end,
                        'project_role' => $project_role,
                        'project_organization' => $project_organization,
                        'project_desc' => $project_desc
                    ];

                    // Add to the $profession array
                    $profession[] = $project;
                }


                // Check if $profession has data and then update user meta
                if (!empty($profession)) {
                    update_user_meta($user_id, '_cand_profession', $profession);
                }
            }


            $cand_name = $params['cand_name'];
            $cand_phone = $params['cand_phone'];
            $cand_intro = $params['cand_intro'];
            $cand_skills_new = $params['cand_skills_new'];
            $cand_skills_val = $params['cand_skills_val'];

            $wp_user_object = new WP_User($user_id);
            $wp_user_object->set_role('job_seeker');

            if ($cand_name != '') {
                wp_update_user(array('ID' => $user_id, 'display_name' => sanitize_text_field($params['cand_name'])));
            }
            if ($cand_phone != '') {
                update_user_meta($user_id, '_sb_contact', sanitize_text_field($cand_phone));
            }

            if ($cand_intro != '') {
                update_user_meta($user_id, '_cand_intro', wp_kses($cand_intro, nokri_required_tags()));
            }
            update_user_meta($user_id, '_cand_skills', ($cand_skills_new));
            if (!empty($cand_skills_new)) {

                $cand_skill_sanatize = array();
                if (isset($cand_skills_val) && !empty($cand_skills_val) && count($cand_skills_val) > 0) {
                    foreach ($cand_skills_val as $key) {

                        $cand_skill_sanatize[] = sanitize_text_field($key);
                    }
                }
                update_user_meta($user_id, '_cand_skills_values', ($cand_skill_sanatize));
            }

        }

        if ($acount_type == 1) {
            $emp_name = $params['emp_name'];
            $emp_phone = $params['sb_reg_contact'];
            $emp_cat = $params['emp_cat'];

            $wp_user_object = new WP_User($user_id);
            $wp_user_object->set_role('pending_employer');

            if ($emp_name != '') {
                wp_update_user(array('ID' => $user_id, 'display_name' => sanitize_text_field($params['emp_name'])));
            }
            if ($emp_phone != '') {
                update_user_meta($user_id, '_sb_contact', sanitize_text_field($emp_phone));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_skills', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_application', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_housing', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_pets', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_meal', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_camping', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_wifi', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_cell', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_staff', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_natpark', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_regions', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_states', ($emp_cat));
            }
            if ($emp_cat != '') {
                update_user_meta($user_id, '_emp_cities', ($emp_cat));
            }
        }


        $product_id = nokri_assign_free_package();
        if (isset($product_id) && $product_id != '') {
            if (isset($nokri['user_assign_pkg']) && $nokri['user_assign_pkg'] == '1' && $acount_type == '1') {
                $is_pkg_free = get_post_meta($product_id, 'op_pkg_typ', true);
                if ($is_pkg_free == 1) {
                    nokri_free_package($product_id, $user_id);
                }
            }
        }
        /* Assign package to candidate */
        $product_cand_id = nokri_candidate_assign_free_package();
        if (isset($product_cand_id) && $product_cand_id != '') {
            if (isset($nokri['cand_assign_pkg']) && $nokri['cand_assign_pkg'] == '1' && $acount_type == '0') {
                $is_pkg_free = get_post_meta($product_cand_id, 'op_pkg_typ', true);
                if ($is_pkg_free == 1) {
                    nokri_free_package_for_candidate($product_cand_id, $user_id);
                }
            }
        }
        echo 1;
        die();
    }

}
if (!function_exists('nokri_do_register')) {

    function nokri_do_register($email = '', $password = '')
    {
        global $nokri;
        $user_name = explode('@', $email);
        $u_name = nokri_check_user_name($user_name[0]);
        $uid = wp_create_user($u_name, $password, $email);
        wp_update_user(array('ID' => $uid, 'display_name' => $u_name));
        update_user_meta($uid, '_user_profile_status', 'pub');
        nokri_auto_login($email, $password, true);


        return $uid;
    }

}
if (!function_exists('nokri_do_register_without_login')) {

    function nokri_do_register_without_login($email = '', $user_name = '', $password = '')
    {
        $emailarray = explode('@', $email);
        $emailSuffix = $emailarray[0];

        $user_name = sanitize_user($user_name);
        $uid = wp_create_user($emailSuffix, $password, $email);
        wp_update_user(array('ID' => $uid, 'display_name' => $user_name));
        update_user_meta($uid, '_user_profile_status', 'pub');
        nokri_auto_login($email, $password, true);
        return $uid;
    }

}
/* * ********************************* */
/* Ajax handler for Saving Empoyer Profile   */
/* * ********************************* */
add_action('wp_ajax_emp_profiles', 'nokri_emp_profiles');
add_action('wp_ajax_nopriv_emp_profiles', 'nokri_emp_profiles');

function nokri_emp_profiles()
{
    global $nokri;
    /* Setting profile option */
    $profile_setting_option = isset($nokri['emp_prof_setting']) ? $nokri['emp_prof_setting'] : false;
    $taxonomy = 'job_category';
    $user_id = get_current_user_id();
    /* demo check */
    $is_demo = nokri_demo_mode();
    if ($is_demo) {
        echo '2';
        die();
    }


    // Getting values From Param
    $params = array();
    parse_str($_POST['sb_data'], $params);

    $emp_name = $params['emp_name'];
    $emp_headline = $params['emp_headline'];
    $emp_web = $params['emp_web'];
    $emp_est = $params['emp_est'];
    $emp_search = $params['is_in_search'];
    $emp_intro = $params['emp_intro'];
    $emp_phone = $params['sb_reg_contact'];
    $emp_comp_email = $params['emp_comp_email'];
    $emp_entity = $params['emp_entity'];
    $emp_nos = $params['emp_nos'];
    $emp_dp = $params['emp_dp'];
    $emp_profile = isset($params['emp_profile']) ? $params['emp_profile'] : "";
    $emp_lat = $params['ad_map_lat'];
    $emp_long = $params['ad_map_long'];
    $emp_adress = $params['emp_adress'];
    $emp_fb = $params['emp_fb'];
    $emp_twitter = $params['emp_twitter'];
    $emp_linked = $params['emp_linked'];
    $emp_google = $params['emp_google'];
    $emp_map_location = $params['sb_user_address'];
    $emp_postal_address = $params['sb_user_postal'];
    $emp_cat = $params['emp_cat'];
    $emp_video = $params['emp_video'];
    $emp_currency = $params['_emp_currency'];
    /* Updating Values In User Meta Of Current User */
    if ($emp_name != '') {
        wp_update_user(array('ID' => $user_id, 'display_name' => sanitize_text_field($params['emp_name'])));
    }
    update_user_meta($user_id, '_user_headline', sanitize_text_field($emp_headline));
    update_user_meta($user_id, '_emp_web', sanitize_text_field($emp_web));
    if ($emp_est != '') {
        update_user_meta($user_id, '_emp_est', sanitize_text_field($emp_est));
    }
    if ($emp_search != '') {
        update_user_meta($user_id, '_emp_search', sanitize_text_field($emp_search));
    }
    update_user_meta($user_id, '_sb_contact', sanitize_text_field($emp_phone));
    update_user_meta($user_id, '_emp_comp_email', sanitize_text_field($emp_comp_email));
    if ($emp_entity != '') {
        update_user_meta($user_id, '_emp_entity', sanitize_text_field($emp_entity));
    }
    update_user_meta($user_id, '_emp_nos', sanitize_text_field($emp_nos));
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_skills', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_application', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_housing', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_pets', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_meal', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_camping', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_wifi', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_cell', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_staff', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_natpark', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_regions', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_states', ($emp_cat));
    }
    if ($emp_cat != '') {
        update_user_meta($user_id, '_emp_cities', ($emp_cat));
    }
    if ($emp_currency != '') {
        update_user_meta($user_id, '_emp_currency', ($emp_currency));
    }
    update_user_meta($user_id, '_emp_video', sanitize_text_field($emp_video));
    /* If allowed */
    if ($profile_setting_option == "show") {
        update_user_meta($user_id, '_user_profile_status', sanitize_text_field($emp_profile));
    } else {
        update_user_meta($user_id, '_user_profile_status', 'pub');
    }
    update_user_meta($user_id, '_emp_intro', wp_kses($emp_intro, nokri_required_tags()));
    update_user_meta($user_id, '_emp_fb', sanitize_text_field($emp_fb));
    update_user_meta($user_id, '_emp_twitter', sanitize_text_field($emp_twitter));
    update_user_meta($user_id, '_emp_linked', sanitize_text_field($emp_linked));
    update_user_meta($user_id, '_emp_google', sanitize_text_field($emp_google));
    if ($emp_map_location != '') {
        update_user_meta($user_id, '_emp_map_location', sanitize_text_field($emp_map_location));
    }
    if ($emp_lat != '') {
        update_user_meta($user_id, '_emp_map_lat', sanitize_text_field($emp_lat));
    }
    if ($emp_long != '') {
        update_user_meta($user_id, '_emp_map_long', sanitize_text_field($emp_long));
    }
    if ($emp_postal_address != '') {
        update_user_meta($user_id, '_emp_postal_address', sanitize_text_field($emp_postal_address));
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
    update_user_meta($user_id, '_emp_custom_location', ($cand_location));
    echo "1";
    die();
}

/* * ********************************* */
/* Ajax handler for Proifle Picture   */
/* * ********************************* */
add_action('wp_ajax_upload_user_pic', 'nokri_user_profile_pic');
if (!function_exists('nokri_user_profile_pic')) {

    function nokri_user_profile_pic()
    {
        global $nokri;
        $user_id = get_current_user_id();
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2|' . esc_html__("Demo User Not Allowed", 'nokri');
            die();
        }
        /* img upload */
        $condition_img = 7;
        $img_count = count((array)explode(',', $_POST["image_gallery"]));

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

                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        echo '0|' . esc_html__("Sorry, only JPG, JPEG, PNG & GIF files are allowed.", 'nokri');
                        die();
                    }

                    $size_arr = explode('-', $nokri['sb_upload_profile_pic_size']);
                    $display_size = $size_arr[1];
                    $actual_size = $size_arr[0];
                    // Check file size
                    if ($file['size'] > $nokri['sb_upload_profile_pic_size']) {

                        $mess = "Max allowed image size is" . " " . $display_size;

                        echo '0|' . esc_html__($mess, 'nokri');

                        die();
                    }


                    foreach ($_FILES as $file => $array) {

                        if ($imgcount >= $condition_img) {
                            break;
                        }
                        $attach_id = media_handle_upload($file, "");
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
        update_user_meta($uid, '_sb_user_pic', sanitize_text_field($attach_id));
        echo '1|' . $image_link[0];
        die();
    }

}


/* * ********************************* */
/* Ajax handler for user cover    */
/* * ********************************* */
add_action('wp_ajax_upload_user_cover', 'nokri_user_cover_pic');
if (!function_exists('nokri_user_cover_pic')) {

    function nokri_user_cover_pic()
    {
        global $nokri;
        $user_id = get_current_user_id();
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2|' . esc_html__("Demo User Not Allowed", 'nokri');
            die();
        }
        /* img upload */
        $condition_img = 7;
        $img_count = count((array)explode(',', $_POST["image_gallery"]));

        if (!empty($_FILES["my_cover_upload"])) {

            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';


            $files = $_FILES["my_cover_upload"];


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

                    $_FILES = array("my_cover_upload" => $file);

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
                    if ($file['size'] > $nokri['sb_upload_profile_pic_size']) {
                        $mess = "Max allowed image size is" . " " . $display_size;

                        echo '0|' . esc_html__($mess, 'nokri');
                        die();
                    }


                    foreach ($_FILES as $file => $array) {

                        if ($imgcount >= $condition_img) {
                            break;
                        }
                        $attach_id = media_handle_upload($file, "");
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
        update_user_meta($uid, '_sb_user_cover', sanitize_text_field($attach_id));
        echo '1|' . $image_link[0];
        die();
    }

}
/* * ********************************* */
// Ajax handler for add to cart    //
/* * ********************************* */
add_action('wp_ajax_sb_add_cart', 'nokri_add_to_cart');
add_action('wp_ajax_nopriv_sb_add_cart', 'nokri_add_to_cart');
if (!function_exists('nokri_add_to_cart')) {

    function nokri_add_to_cart()
    {
        global $nokri;
        $user_id = get_current_user_id();
        $user_type = get_user_meta($user_id, '_sb_reg_type', true);
        $signin_page = (isset($nokri['sb_sign_in_page']) && $nokri['sb_sign_in_page'] != '') ? $nokri['sb_sign_in_page'] : '';
        if ($user_id == "") {
            echo '0|' . __("You must need to logged in", 'nokri') . '|' . get_the_permalink($signin_page);
            die();
        }
        $product_id = $_POST['product_id'];
        $product_id = nokri_get_origional_product_id($product_id);
        $is_avail = get_user_meta($user_id, 'avail_free_package', true);
        $is_pkg_free = get_post_meta($product_id, 'op_pkg_typ', true);
        $is_pkg_for = get_post_meta($product_id, 'op_pkg_for', true);
        if ($user_type == '0' && $is_pkg_for == '1') {
            echo '5|' . __("This is employer package", 'nokri');
            die();
        }
        if ($is_avail == 1 && $_POST['is_free'] == 1) {
            echo '4|' . __("You have already availed free package", 'nokri') . '|' . get_the_permalink($nokri['package_page']);
            die();
        }
        if ($_POST['is_free'] == 1 && $is_pkg_free == 1) {
            nokri_free_package($product_id);
            update_user_meta(get_current_user_id(), 'avail_free_package', (int)'1');
            echo '3|' . __("Success", 'nokri') . '|' . get_the_permalink($nokri['sb_post_ad_page']);
            die();
        }
        $qty = $_POST['qty'];
        global $woocommerce;

        if ($woocommerce->cart->add_to_cart($product_id, $qty)) {
            echo '1|' . __("Added to cart.", 'nokri') . '|' . $woocommerce->cart->get_cart_url();
        } else {
            echo '1|' . __("Already in your cart.", 'nokri') . '|' . $woocommerce->cart->get_cart_url();
        }
        die();
    }

}
/* * ********************************************* */
// Ajax handler for add to cart for candidate    //
/* * ******************************************* */
add_action('wp_ajax_sb_add_cart_cand', 'nokri_add_to_cart_cand');
add_action('wp_ajax_nopriv_sb_add_cart_cand', 'nokri_add_to_cart_cand');
if (!function_exists('nokri_add_to_cart_cand')) {

    function nokri_add_to_cart_cand()
    {
        global $nokri;
        $user_id = get_current_user_id();
        $user_type = get_user_meta($user_id, '_sb_reg_type', true);
        $signin_page = (isset($nokri['sb_sign_in_page']) && $nokri['sb_sign_in_page'] != '') ? $nokri['sb_sign_in_page'] : '';

        if ($user_id == "") {
            echo '0|' . __("You must need to logged in", 'nokri') . '|' . get_the_permalink($signin_page);
            die();
        }
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '6|' . __("Editing not allowed in demo mode", 'nokri') . '|' . get_the_permalink();
            die();
        }

        $product_id = $_POST['product_id'];
        $is_avail = get_user_meta($user_id, 'avail_free_package', true);
        $is_pkg_free = get_post_meta($product_id, 'op_pkg_typ', true);
        $is_pkg_for = get_post_meta($product_id, 'op_pkg_for', true);

        if ($user_type == 1 && $is_pkg_for == 0) {
            echo '7|' . __("This is candidate package", 'nokri');
            die();
        }

        if ($is_avail == 1 && $_POST['is_free'] == 1) {
            echo '4|' . __("You have already availed free package", 'nokri') . '|' . get_the_permalink($nokri['package_page']);
            die();
        }

        if ($_POST['is_free'] == 1 && $is_pkg_free == 1) {
            nokri_free_package_for_candidate($product_id);
            update_user_meta(get_current_user_id(), 'avail_free_package', (int)'1');
            echo '3|' . __("Success", 'nokri') . '|' . get_the_permalink($nokri['sb_post_ad_page']);
            die();
        }
        $qty = $_POST['qty'];
        global $woocommerce;
        if ($woocommerce->cart->add_to_cart($product_id, $qty)) {
            echo '1|' . __("Added to cart.", 'nokri') . '|' . $woocommerce->cart->get_cart_url();
        } else {
            echo '1|' . __("Already in your cart.", 'nokri') . '|' . $woocommerce->cart->get_cart_url();
        }
        die();
    }

}
/* * ********************************* */
// Ajax handler for Job Posting. //
/* * ********************************* */
add_action('wp_ajax_sb_ad_posting', 'nokri_ad_posting');
if (!function_exists('nokri_ad_posting')) {

    function nokri_ad_posting()
    {
        global $nokri;
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $is_only_admin = isset($nokri['job_post_for_admin']) ? $nokri['job_post_for_admin'] : false;
        if (!is_super_admin()) {
            if ($is_only_admin) {
                echo '3';
                die();
            }
        }
        $job_params = array();
        parse_str($_POST['sb_data'], $job_params);
        // Getting values
        $job_date = $job_params['job_date'];
        $cover_letter_required = $job_params['cover_letter_required'];
        $job_description = $job_params['job_description'];
        $job_category = isset($job_params['job_category']) ? $job_params['job_category'] : '';
        $job_type = $job_params['job_type'];
        $job_level = $job_params['job_level'];
        $job_shift = $job_params['job_shift'];
        $job_experience = $job_params['job_experience'];
        $job_skills = isset($job_params['job_skills']) ? $job_params['job_skills'] : "";
        $job_seasons = isset($job_params['job_seasons']) ? $job_params['job_seasons'] : "";
        $job_certifications = isset($job_params['job_certifications']) ? $job_params['job_certifications'] : "";
        $job_country = isset($job_params['job_country']) ? $job_params['job_country'] : "";
        $job_state = isset($job_params['job_state']) ? $job_params['job_state'] : "";
        $job_city = isset($job_params['job_city']) ? $job_params['job_city'] : "";
        $job_salary = $job_params['job_salary'];
        $job_qualifications = $job_params['job_qualifications'];
        $job_currency = $job_params['job_currency'];
        $job_salary_type = $job_params['job_salary_type'];
        $job_address = $job_params['sb_user_address'];
        $job_lat = $job_params['ad_map_lat'];
        $job_long = $job_params['ad_map_long'];
        $job_phone = isset($job_params['job_phone']) ? $job_params['job_phone'] : "";
        $job_posts = $job_params['job_posts'];
        $job_apply_with = $job_params['job_apply_with'];
        $job_apply_url = $job_params['job_external_url'];
        $job_apply_whatsapp = $job_params['job_external_whatsapp'];
        $job_video = isset($job_params['job_video']) ? $job_params['job_video'] : "";
        $job_questions = $job_params['job_qstns'];
        $job_class = array();
        $job_class_checked = isset($job_params['class_type_value']) ? $job_params['class_type_value'] : array();
        if (get_current_user_id() == "") {
            echo "0";
            die();
        }
        $ad_status = 'publish';
        if ($_POST['is_update'] != "") {
            if ($nokri['sb_update_approval'] == 'manual') {
                $ad_status = 'pending';
            }
            $pid = $_POST['is_update'];
        } else {
            if ($nokri['sb_ad_approval'] == 'manual') {
                $ad_status = 'pending';
            }
            $pid = get_user_meta(get_current_user_id(), 'ad_in_progress', true);
            $media = get_attached_media('image', $pid);
            /* Now user can post new Job */
            delete_user_meta(get_current_user_id(), 'ad_in_progress');
            /* Getting User Simple Jobs Informations */
            $simple_job_term_id = nokri_simple_jobs();
            $meta_name = 'package_job_class_' . $simple_job_term_id;
            $simple_ads = get_user_meta(get_current_user_id(), $meta_name, true);
            if ($simple_ads > 0 && !is_super_admin(get_current_user_id())) {
                $simple_ads = $simple_ads - 1;
                update_user_meta(get_current_user_id(), $meta_name, $simple_ads);
            }
            update_post_meta($pid, '_nokri_ad_status_', 'active');
        }

        if ($_POST['is_update'] == "") {
            nokri_get_notify_on_ad_post($pid);
        }
        // nokri_job_alerts_function();

        /* Getting Other Jobs Informations */
        foreach ($job_class_checked as $job_class) {
            $no_of_jobs = get_user_meta(get_current_user_id(), 'package_job_class_' . $job_class, true);

            if (is_super_admin()) {
                wp_set_post_terms($pid, $job_class_checked, 'job_class');
                update_post_meta($pid, 'package_job_class_' . $job_class, $job_class);
            } else {
                if ($no_of_jobs > 0) {
                    $no_of_jobs = $no_of_jobs - 1;
                    update_user_meta(get_current_user_id(), 'package_job_class_' . $job_class, $no_of_jobs);
                    update_post_meta($pid, 'package_job_class_' . $job_class, $job_class);
                    wp_set_post_terms($pid, $job_class_checked, 'job_class');
                }
            }
        }
        /* Bad words filteration */
        $words = explode(',', $nokri['bad_words_filter']);
        $replace = $nokri['bad_words_replace'];
        $desc = nokri_badwords_filter($words, $job_params['job_description'], $replace);
        $title = nokri_badwords_filter($words, $job_params['job_title'], $replace);
        $my_post = array(
            'ID' => $pid,
            'post_title' => $title,
            'post_status' => $ad_status,
            'post_content' => $desc,
            'post_type' => 'job_post',
            'post_name' => $title
        );
        wp_update_post($my_post);
        /* Categories Level */
        $categories = array();
        if ($job_params['job_cat'] != "") {
            $categories[] = $job_params['job_cat'];
        }
        if ($job_params['job_cat_second'] != "") {
            $categories[] = $job_params['job_cat_second'];
        }
        if ($job_params['job_cat_third'] != "") {
            $categories[] = $job_params['job_cat_third'];
        }
        if ($job_params['job_cat_forth'] != "") {
            $categories[] = $job_params['job_cat_forth'];
        }
        wp_set_post_terms($pid, $categories, 'job_category');
        /* countries */

        $countries = array();
        /*
        if ($job_params['ad_country'] != "") {
            $countries[] = $job_params['ad_country'];
        }
        if ($job_params['ad_country_states'] != "") {
            $countries[] = $job_params['ad_country_states'];
        }
        if ($job_params['ad_country_cities'] != "") {
            $countries[] = $job_params['ad_country_cities'];
        }
        if ($job_params['ad_country_towns'] != "") {
            $countries[] = $job_params['ad_country_towns'];
        }
        */

        if (isset($job_params['ad_location_cities']) && is_array($job_params['ad_location_cities']) && !empty($job_params['ad_location_cities'])) {

            $ad_location_cities = $job_params['ad_location_cities'];

            foreach ($ad_location_cities as $city) {

                if (strpos($city, '_all') !== false) {

                    $state = (int)str_replace('_all', '', $city);
                    $ad_states = nokri_get_cats('ad_location', $state);
                    if (!empty($ad_states)) {
                        foreach ($ad_states as $cities_obj) {

                            if ($cities_obj->parent != 264 && $cities_obj->parent != 0 && !in_array($cities_obj->term_id, $countries)) {
                                $countries[] = $cities_obj->parent;
                                $countries[] = $cities_obj->term_id;
                            }

                        }
                    }


                } else {
                    $cities_obj = get_terms(array(
                        'taxonomy' => 'ad_location',
                        'include' => $city,
                        'hide_empty' => false,
                    ));

                    if ($cities_obj[0]->parent != 264 && $cities_obj[0]->parent != 0 && !in_array($city, $countries)) {
                        $countries[] = $cities_obj[0]->parent;
                        $countries[] = $city;
                    }
                }


            }

        }

        wp_set_post_terms($pid, $countries, 'ad_location');
        /*         * **************************** */
        /* setting taxonomoies Post Meta */
        /*         * **************************** */

        update_post_meta($pid, '_cover_letter_required', sanitize_text_field($cover_letter_required));
        if ($job_date != '') {
            update_post_meta($pid, '_job_date', sanitize_text_field($job_date));
        }
        if ($job_params['job_type'] != "") {
            wp_set_post_terms($pid, $job_type, 'job_type');
        } else {
            wp_set_post_terms($pid, '', 'job_type');
        }
        if ($job_params['job_level'] != "") {
            wp_set_post_terms($pid, $job_level, 'job_level');
        } else {
            wp_set_post_terms($pid, '', 'job_level');
        }
        if ($job_params['job_shift'] != "") {
            wp_set_post_terms($pid, $job_shift, 'job_shift');
        } else {
            wp_set_post_terms($pid, '', 'job_shift');
        }
        if ($job_params['job_experience'] != "") {
            wp_set_post_terms($pid, $job_experience, 'job_experience');
        } else {
            wp_set_post_terms($pid, '', 'job_experience');
        }
        if (isset($job_params['job_skills']) && $job_params['job_skills'] != "") {
            wp_set_post_terms($pid, $job_skills, 'job_skills');
        } else {
            wp_set_post_terms($pid, '', 'job_skills');
        }
        if (isset($job_params['job_seasons']) && $job_params['job_seasons'] != "") {
            wp_set_post_terms($pid, $job_seasons, 'job_seasons');
        } else {
            wp_set_post_terms($pid, '', 'job_seasons');
        }
        if (isset($job_params['job_certifications']) && $job_params['job_certifications'] != "") {
            wp_set_post_terms($pid, $job_certifications, 'job_certifications');
        } else {
            wp_set_post_terms($pid, '', 'job_certifications');
        }
        if (isset($job_params['job_country']) && $job_params['job_country'] != "") {
            wp_set_post_terms($pid, $job_country, 'job_country');
        } else {
            wp_set_post_terms($pid, '', 'job_country');
        }
        if (isset($job_params['job_state']) && $job_params['job_state'] != "") {
            wp_set_post_terms($pid, $job_state, 'job_state');
        } else {
            wp_set_post_terms($pid, '', 'job_state');
        }
        if (isset($job_params['job_city']) && $job_params['job_city'] != "") {
            wp_set_post_terms($pid, $job_city, 'job_city');
        } else {
            wp_set_post_terms($pid, '', 'job_city');
        }
        if ($job_params['job_salary'] != "") {
            wp_set_post_terms($pid, $job_salary, 'job_salary');
        } else {
            wp_set_post_terms($pid, '', 'job_salary');
        }
        if ($job_params['job_salary_type'] != "") {
            wp_set_post_terms($pid, $job_salary_type, 'job_salary_type');
        } else {
            wp_set_post_terms($pid, '', 'job_salary_type');
        }
        if ($job_params['job_qualifications'] != "") {
            wp_set_post_terms($pid, $job_qualifications, 'job_qualifications');
        } else {
            wp_set_post_terms($pid, '', 'job_qualifications');
        }
        if ($job_params['job_currency'] != "") {
            wp_set_post_terms($pid, $job_currency, 'job_currency');
        } else {
            wp_set_post_terms($pid, '', 'job_currency');
        }
        if ($job_posts != "") {
            update_post_meta($pid, '_job_posts', sanitize_text_field($job_posts));
        }
        /* Setting Tags */
        $tags = explode(',', $job_params['job_tags']);
        wp_set_object_terms($pid, $tags, 'job_tags');
        if ($job_address != '') {
            update_post_meta($pid, '_job_address', sanitize_text_field($job_address));
        }
        if ($job_lat != '') {
            update_post_meta($pid, '_job_lat', sanitize_text_field($job_lat));
        }
        if ($job_long != '') {
            update_post_meta($pid, '_job_long', sanitize_text_field($job_long));
        }


        update_post_meta($pid, '_job_apply_with', sanitize_text_field($job_apply_with));
        if ($job_apply_with == 'mail') {
            update_post_meta($pid, '_job_apply_mail', sanitize_text_field($job_params['job_external_mail']));
        } else if ($job_apply_with == 'exter') {
            update_post_meta($pid, '_job_apply_url', sanitize_text_field($job_apply_url));
        } else if ($job_apply_with == 'whatsapp') {
            update_post_meta($pid, '_job_apply_whatsapp', sanitize_text_field($job_apply_whatsapp));
        }
        if ($job_video != '') {
            update_post_meta($pid, '_job_video', ($job_video));
        }
        //Add Dynamic Fields in custom category template
        if (isset($job_params['cat_template_field']) && count($job_params['cat_template_field']) > 0) {


            foreach ($job_params['cat_template_field'] as $key => $data) {
                if (is_array($data)) {
                    $dataArr = array();
                    foreach ($data as $k)
                        $dataArr[] = $k;
                    $data = stripslashes(json_encode($dataArr, JSON_UNESCAPED_UNICODE));
                }
                update_post_meta($pid, $key, sanitize_text_field($data));
            }
        }
        /* Questionares */
        $questions_sanatize = array();
        if (isset($job_questions) && !empty($job_questions)) {
            foreach ($job_questions as $key) {
                if (!empty($key)) {
                    $questions_sanatize[] = sanitize_text_field($key);
                }
            }
            update_post_meta($pid, '_job_questions', ($questions_sanatize));
        }
        /* Jobs Status */
        update_post_meta($pid, '_job_status', sanitize_text_field('active'));
        if ($_POST['is_update'] == "") {
            /* wpml duplication function */
            nokri_duplicate_posts_lang_callback($pid);
        }

        bt_run_job_updater(get_current_user_id());

        echo get_the_permalink($pid);
        die();
    }

}
/* Create Post By Title */
if (!function_exists('nokri_check_author')) {

    function nokri_check_author($ad_id)
    {
        if (get_post_field('post_author', $ad_id) != get_current_user_id()) {
            return false;
        } else {
            return true;
        }
    }

}
add_action('wp_ajax_post_ad', 'nokri_post_ad_process');
if (!function_exists('nokri_post_ad_process')) {

    function nokri_post_ad_process()
    {


        if ($_POST['is_update'] != "") {
            die();
        }


        $title = $_POST['title'];
        if (get_current_user_id() == "")
            die();

        if (!isset($title))
            die();


        $ad_id = get_user_meta(get_current_user_id(), 'ad_in_progress', true);
        if (get_post_status($ad_id) && $ad_id != "") {
            $my_post = array(
                'ID' => $ad_id,
                'post_title' => $title
            );
            wp_update_post($my_post);
            die();
        }


        // Gather post data.
        $my_post = array(
            'post_title' => $title,
            'post_status' => 'pending',
            'post_author' => get_current_user_id(),
            'post_type' => 'job_post'
        );

// Insert the post into the database.
        $id = wp_insert_post($my_post);
        if ($id) {
            update_user_meta(get_current_user_id(), 'ad_in_progress', sanitize_text_field($id));
        }

        die();
    }

}
// Get States
add_action('wp_ajax_sb_get_sub_states', 'nokri_get_sub_states');
add_action('wp_ajax_nopriv_sb_get_sub_states_search', 'nokri_get_sub_states_search');
if (!function_exists('nokri_get_sub_states')) {

    function nokri_get_sub_states()
    {
        $cat_id = $_POST['cat_id'];
        $ad_cats = nokri_get_cats('ad_location', $cat_id);
        if (count((array)$ad_cats) > 0) {
            $cats_html = '<select class="category form-control" id="ad_cat_sub" name="ad_cat_sub">';
            $cats_html .= '<option label="' . esc_html__('Select Option', 'nokri') . '"></option>';
            foreach ($ad_cats as $ad_cat) {
                $cats_html .= '<option value="' . $ad_cat->term_id . '">' . $ad_cat->name . '</option>';
            }
            $cats_html .= '</select>';
            echo nokri_returnEcho($cats_html);
            die();
        } else {
            echo "";
            die();
        }
    }

}
/* Employer Action Request */
add_action('wp_ajax_job_action', 'nokri_job_action');
if (!function_exists('nokri_job_action')) {

    function nokri_job_action()
    {
        $user_id = get_current_user_id();
        $cv_action = $_POST['cv_action'];
        $job_id = $_POST['job_id'];
        $cand_id = $_POST['cand_id'];
        if ($cv_action != "") {
            update_post_meta($job_id, '_job_applied_status_' . $cand_id, sanitize_text_field($cv_action));
        }
        echo nokri_canidate_apply_status($cv_action);
        die();
    }

}
/* Employer Email Tempalte Action */
add_action('wp_ajax_create_email_action', 'nokri_create_email_templates');
if (!function_exists('nokri_create_email_templates')) {

    function nokri_create_email_templates()
    {
        $user_id = get_current_user_id();
        $email_params = array();
        parse_str($_POST['temp_data'], $email_params);
        $temp_name = $email_params['email_temp_name'];
        $temp_subject = $email_params['email_temp_subject'];
        $temp_description = $email_params['email_temp_details'];
        $template_id = $email_params['template_id'];
        $template_for = $email_params['email_temp_for'];
        $template_date = date("F j, Y");
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }

        if ($template_id != "") {
            $template_meta_key = $template_id;
        } else {
            $template_meta_key = '_email_temp_name_' . $user_id . '_' . time();
        }
        //header("Content-Type: application/json; charset=UTF-8");
        $template['name'] = $temp_name;
        $template['subject'] = $temp_subject;
        $template['body'] = $temp_description;
        $template['for'] = $template_for;
        $template['date'] = $template_date;
        $templateData = json_encode($template);
        $templateData = nokri_base64Encode($templateData);
        update_user_meta($user_id, $template_meta_key, $templateData);
        echo 1;
        die();
    }

}
/* Employer Deleting Email Template */
add_action('wp_ajax_del_email_temp', 'nokri_del_email_temp');
if (!function_exists('nokri_del_email_temp')) {

    function nokri_del_email_temp()
    {
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $user_id = get_current_user_id();
        $temp_id = $_POST['temp_id'];
        delete_user_meta($user_id, $temp_id);
        echo 1;
        die();
    }

}
/* Employer Select Email Template */
add_action('wp_ajax_template_select_action', 'nokri_template_select');
if (!function_exists('nokri_template_select')) {

    function nokri_template_select()
    {
        /* Getting Email Templates */
        $user_id = get_current_user_id();
        $res = nokri_get_resumes_list($user_id);
        $meta_key = $_POST['temp_val'];
        if ($meta_key != "") {
            $meta_data = get_user_meta($user_id, $meta_key, true);
            $meta_data = nokri_base64Decode($meta_data);
            $val = json_decode($meta_data, true);
            $template_name = $val['name'];
            $template_subject = $val['subject'];
            $template_body = $val['body'];
            $template_for = $val['for'];
        }

        $html = '<input type="hidden" value="' . $template_for . '"  name="cand_status_val" />
								<div class="form-group no-email-subject">
									<input  type="text" class="form-control" name="email_sub" value="' . esc_html($template_subject) . '" placeholder="' . esc_html__('Subject', 'nokri') . '" required>
								</div>
							<div class="form-group no-email-body">
                        <label class="">' . __("Email template.", 'nokri') . '</label>
                        <textarea name="email_body" rows="6" class="form-control rich_textarea" placeholder="' . esc_html__('Cover Letter', 'nokri') . '" required>' . esc_html($template_body) . '</textarea>
                    </div>';

        echo '' . $html;
        die();
    }

}
/* Employer Sending Email */
add_action('wp_ajax_sending_email', 'nokri_sending_email');
if (!function_exists('nokri_sending_email')) {

    function nokri_sending_email()
    {

        global $nokri;
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $user_id = get_current_user_id();
        $send_email_params = array();
        parse_str($_POST['email_data'], $send_email_params);
        $candidate_id = $send_email_params['candidiate_id'];
        $job_id = $send_email_params['job_stat_id'];
        $cand_status = $send_email_params['cand_status_val'];
        $is_send_mail = $send_email_params['is_send_email'];
        if ($candidate_id != "") {
            update_post_meta($job_id, '_job_applied_status_' . $candidate_id, $cand_status);
        }
        if ($is_send_mail == 'true') {
            $subject = $send_email_params['email_sub'];
            $body = $send_email_params['email_body'];
            nokri_employer_status_email($job_id, $candidate_id, $subject, $body);
        }
        echo 1;
        die();
    }

}
/* Employer Activating/Inactivating His Job */
add_action('wp_ajax_inactive_job', 'nokri_inactive_job');
if (!function_exists('nokri_inactive_job')) {

    function nokri_inactive_job()
    {
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $job_id = $_POST['job_id'];
        $job_status = $_POST['job_status'];

        if ($job_status == "active") {
            $post_to_change = array('ID' => $job_id, 'post_status' => 'publish');
            wp_update_post($post_to_change);

            // Clear rocket cache
            if (function_exists('rocket_clean_domain')) {
                rocket_clean_domain();
            }
        }
        if ($job_status == "inactive") {
            $post_to_change = array('ID' => $job_id, 'post_status' => 'draft');
            wp_update_post($post_to_change);
            // Clear rocket cache
            if (function_exists('rocket_clean_domain')) {
                rocket_clean_domain();
            }
        }
        if ($job_id != '') {
            update_post_meta($job_id, '_job_status', sanitize_text_field($job_status));
            // Clear rocket cache
            if (function_exists('rocket_clean_domain')) {
                rocket_clean_domain();
            }
        }
        echo 1;
        die();
    }

}

/* employer bump up jobs */
/* Employer Activating/Inactivating His Job */
add_action('wp_ajax_bump_this_job_call', 'bump_this_job_call_fun');
if (!function_exists('bump_this_job_call_fun')) {

    function bump_this_job_call_fun()
    {
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }

        $time = current_time('mysql');
        $job_id = $_POST['job_id'];
        $user_id = get_current_user_id();
        $post_to_change = array(
            'ID' => $job_id,
            'post_date' => $time,
            'post_date_gmt' => get_gmt_from_date($time)
        );
        wp_update_post($post_to_change);

        $bump_ads_limit = get_user_meta($user_id, 'bump_ads_limit', true);
        if ($bump_ads_limit != "" && $bump_ads_limit != -1) {
            $remaing_bump = absint($bump_ads_limit) - 1;
            update_user_meta($user_id, 'bump_ads_limit', $remaing_bump);
        }
        $post_to_change = array(
            'ID' => $job_id,
            'post_date' => $time,
            'post_date_gmt' => get_gmt_from_date($time));
        wp_update_post($post_to_change);
        echo 1;
        die();
    }

}
/* Employer Deleting His Job */
add_action('wp_ajax_del_emp_job', 'nokri_del_my_job');
if (!function_exists('nokri_del_my_job')) {

    function nokri_del_my_job()
    {
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $emp_job_id = $_POST['emp_job_id'];
        wp_trash_post($emp_job_id);
        echo 1;
        die();
    }

}
/* Employer Deleting His Followers */
add_action('wp_ajax_un_following_followers', 'nokri_un_following_followers');
if (!function_exists('nokri_un_following_followers')) {

    function nokri_un_following_followers()
    {
        $user_id = get_current_user_id();
        $follower_id = $_POST['follower_id'];
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        if ($follower_id != "") {
            if (delete_user_meta($follower_id, '_cand_follow_company_' . $user_id, $user_id)) {
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
/* Return Followers  ID's */
/* * ********************************* */
if (!function_exists('nokri_followers_ids')) {

    function nokri_followers_ids($user_id)
    {
        /* Query For Getting All Followed Companies */
        global $wpdb;
        $query = "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = '$user_id' AND meta_key like '_cand_follow_company_%'";
        $cand_followings = $wpdb->get_results($query);
        if (count((array)$cand_followings) > 0) {
            $ids = array();
            foreach ($cand_followings as $companies) {
                $ids[] = $companies->meta_value;
            }
            return $ids;
        }
    }

}
/* * ******************************* */
/* User updating  password       */
/* * ******************************* */
add_action('wp_ajax_change_password', 'nokri_change_password');
if (!function_exists('nokri_change_password')) {

    function nokri_change_password()
    {
        $user_id = get_current_user_id();
        $password_data = array();
        parse_str($_POST['password_data'], $password_data);
        $old_password = $password_data['old_password'];
        $new_password = $password_data['new_password'];
        $user = get_user_by('ID', $user_id);
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '4';
            die();
        }
        if ($old_password == "") {
            echo 0;
            die();
        }
        if ($new_password == "") {
            echo 1;
            die();
        }

        if ($user && wp_check_password($old_password, $user->data->user_pass, $user->ID)) {
            wp_set_password($new_password, $user_id);
            echo 2;
            die();
        } else {
            echo 3;
            die();
        }
    }

}
/* * ******************************* */
/* Del acount      */
/* * ******************************* */
add_action('wp_ajax_delete_myaccount', 'nokri_delete_my_account');
if (!function_exists('nokri_delete_my_account')) {

    function nokri_delete_my_account()
    {
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '4';
            die();
        }
        //check user is logged in or not
        $user_id = get_current_user_id();
        if (is_super_admin($user_id)) {
            echo '1';
            die();
        } else {
            // delete comment with that user id
            $c_args = array('user_id' => $user_id, 'post_type' => 'any', 'status' => 'all');
            $comments = get_comments($c_args);
            if (count((array)$comments) > 0) {
                foreach ($comments as $comment) :
                    wp_delete_comment($comment->comment_ID, true);
                endforeach;
            }
            // delete user posts
            $args = array('numberposts' => -1, 'post_type' => 'any', 'author' => $user_id);
            $user_posts = get_posts($args);
            // delete all the user posts
            if (count((array)$user_posts) > 0) {
                foreach ($user_posts as $user_post) {
                    wp_delete_post($user_post->ID, true);
                }
            }
            //now delete actual user
            wp_delete_user($user_id);
            echo 0;
            die();
        }
    }

}
/* Contact me */
add_action('wp_ajax_nopriv_contact_me', 'nokri_contact_me');
add_action('wp_ajax_contact_me', 'nokri_contact_me');
if (!function_exists('nokri_contact_me')) {

    function nokri_contact_me()
    {

        global $nokri;
        $contact_me_params = array();
        parse_str($_POST['contact_me_data'], $contact_me_params);

        if (isset($contact_me_params['g-recaptcha-response'])) {

            if (nokri_recaptcha_verify($nokri['google_recaptcha_secret_key'], $contact_me_params['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'], '')) {
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $reciver_id = $contact_me_params['receiver_id'];
                $sender_name = $contact_me_params['contact_name'];
                $sender_email = $contact_me_params['contact_email'];
                $subject = $contact_me_params['contact_subject'];
                $message = nl2br($contact_me_params['contact_message']);


                nokri_contact_me_email($reciver_id, $sender_email, $sender_name, $subject, $message);
                echo 1;
                die();
            } else {
                echo '0|' . __("Please verify captcha.", 'nokri');
                die();
            }
        } else {
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $reciver_id = $contact_me_params['receiver_id'];
            $sender_email = $contact_me_params['contact_email'];
            $sender_name = $contact_me_params['contact_name'];
            $subject = $contact_me_params['contact_subject'];
            $message = nl2br($contact_me_params['contact_message']);


            nokri_contact_me_email($reciver_id, $sender_email, $sender_name, $subject, $message);

            echo 1;
            die();
        }
    }

}
/* * ******************************************* */
/* Ajax handler for upload custom feilds attachments  */
/* * ******************************************* */
add_action('wp_ajax_job_attachments', 'job_attachments');
if (!function_exists('job_attachments')) {

    function job_attachments()
    {
        global $nokri;
        $user_id = get_current_user_id();
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '0|' . esc_html__("Edit in demo user not allowed", 'nokri');
            die();
        }
        if ($_GET['is_update'] != "") {
            $job_id = $_GET['is_update'];
        } else {
            $job_id = get_user_meta(get_current_user_id(), 'ad_in_progress', true);
        }
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        $size_arr = explode('-', $nokri['sb_upload_attach_size']);
        $display_size = $size_arr[1];
        $actual_size = $size_arr[0];
        // Allow certain file formats
        $imageFileType = strtolower(end(explode('.', $_FILES['custom_upload']['name'])));
        $formats = array();
        $is_valid = false;
        if (!empty($nokri['sb_upload_attach_format'])) {
            foreach ($nokri['sb_upload_attach_format'] as $key => $value) {
                $formats[] = $value;
            }
        }
        if (in_array($imageFileType, $formats)) {
            $is_valid = true;
        }
        if (!($is_valid)) {
            $not_allowed = "Sorry " . $imageFileType . " file type not allowed";
            echo '0|' . esc_html__($not_allowed, 'nokri');
            die();
        }
        // Check file size
        if ($_FILES['custom_upload']['size'] > $actual_size) {
            echo '0|' . esc_html__("Max allowd image size is", 'nokri') . " " . $display_size;
            die();
        }
        // Check max resume limit
        $user_resume = get_post_meta($job_id, '_job_attachment', true);
        if ($user_resume != "") {
            $media = explode(',', $user_resume);
            if (count($media) >= $nokri['sb_upload_attach_limit']) {
                echo '0|' . esc_html__("You can not upload more than ", 'nokri') . " " . $nokri['sb_upload_attach_limit'] . " " . esc_html__("attachments ", 'nokri');
                die();
            }
        }
        $attachment_id = media_handle_upload('custom_upload', 0);
        if (is_wp_error($attachment_id)) {
            echo '0|' . esc_html__("File is empty.", 'nokri');
            die();
        }
        $user_resume = get_post_meta($job_id, '_job_attachment', true);
        if ($user_resume != "") {
            $updated_resume = $user_resume . ',' . $attachment_id;
        } else {
            $updated_resume = $attachment_id;
        }
        if (is_numeric($attachment_id)) {
            update_post_meta($job_id, '_job_attachment', $updated_resume);
        }
        echo nokri_returnEcho($attachment_id);
        die();
    }

}
/* * ******************************************* */
/* Ajax handler for get custom feilds attachments  */
/* * ******************************************* */
add_action('wp_ajax_get_uploaded_job_attachments', 'get_uploaded_job_attachments');
if (!function_exists('get_uploaded_job_attachments')) {

    function get_uploaded_job_attachments()
    {
        if ($_POST['is_update'] != "") {
            $job_id = $_POST['is_update'];
        } else {
            $job_id = get_post_meta(get_current_user_id(), 'ad_in_progress', true);
        }
        $ids = get_post_meta($job_id, '_job_attachment', true);
        if (!$ids)
            return '';
        $ids_array = explode(',', $ids);
        $result = array();
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
            } else if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif') {
                $cv_icon = get_the_guid($m);;
            }
            $obj['display_name'] = basename(get_attached_file($m));
            $obj['name'] = $cv_icon;
            //$obj['name'] 	= 	get_the_guid($m);
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
/* * ********************************************* */
/* Ajax handler for del custom feilds attachments */
/* * ******************************************** */
add_action('wp_ajax_delete_uploaded_job_attachments', 'delete_uploaded_job_attachments');
if (!function_exists('delete_uploaded_job_attachments')) {

    function delete_uploaded_job_attachments()
    {
        $user_crnt_id = get_current_user_id();
        if ($user_crnt_id == "")
            die();
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '0';
            die();
        }
        if ($_POST['is_update'] != "") {
            $job_id = $_POST['is_update'];
        } else {
            $job_id = get_user_meta(get_current_user_id(), 'ad_in_progress', true);
        }
        $attachmentid = trim($_POST['img']);
        if (get_post_meta($job_id, '_job_attachment', true) != "") {
            $ids = get_post_meta($job_id, '_job_attachment', true);
            $res = get_post_meta($attachmentid, "", $ids);
            $res = str_replace(',,', ",", $res);
            $img_ids = trim($res, ',');
            update_post_meta($job_id, '_job_attachment', $img_ids);
        }
        wp_delete_attachment($attachmentid, true);
        echo "1";
        die();
    }

}
/* * ******************** */
/* Employer Saving Resume */
/* * ********************* */
add_action('wp_ajax_nopriv_emp_saving_resume', 'nokri_emp_saving_resume');
add_action('wp_ajax_emp_saving_resume', 'nokri_emp_saving_resume');
if (!function_exists('nokri_emp_saving_resume')) {

    function nokri_emp_saving_resume()
    {
        global $nokri;
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '1';
            die();
        }
        $emp_id = get_current_user_id();
        $cand_id = $_POST['cand_id'];
        /* Login check */
        nokri_check_user_activity();
        /* User type check */
        if (get_user_meta($emp_id, '_sb_reg_type', true) == '0') {
            echo '3';
            die();
        }
        $resumes = get_user_meta($emp_id, '_emp_saved_resume_' . $emp_id, true);
        $resumesArray = explode(',', $resumes);
        /* Already saved check */
        if (in_array($cand_id, $resumesArray)) {
            echo '4';
            die();
        }
        if ($resumes != "") {
            $updated_resumes = $resumes . ',' . sanitize_text_field($cand_id);
        } else {
            $updated_resumes = sanitize_text_field($cand_id);
        }
        update_user_meta($emp_id, '_emp_saved_resume_' . $emp_id, $updated_resumes);
        echo '5';
        die();
    }

}


/* * ******************************* */
/* Employer Deleting Saved Resume */
/* * ******************************* */
add_action('wp_ajax_deleting_saved_resumes', 'nokri_deleting_saved_resumes');
if (!function_exists('nokri_deleting_saved_resumes')) {

    function nokri_deleting_saved_resumes()
    {
        $emp_id = get_current_user_id();
        $resume_id = $_POST['resume_id'];
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $resume_id = trim($_POST['resume_id']);
        if (get_user_meta($emp_id, '_emp_saved_resume_' . $emp_id, true) != "") {
            $ids = get_user_meta($emp_id, '_emp_saved_resume_' . $emp_id, true);
            $res = str_replace($resume_id, "", $ids);
            $res = str_replace(',,', ",", $res);
            $img_ids = trim($res, ',');
            update_user_meta($emp_id, '_emp_saved_resume_' . $emp_id, $img_ids);
        }
        delete_user_meta($emp_id, '_emp_saved_resume_' . $emp_id, $resume_id);
        echo "1";
        die();
    }

}
/* * ******************** */
/* Employer Saving Resume */
/* * ********************* */
add_action('wp_ajax_nopriv_emp_generate_resume', 'nokri_emp_generate_resume');
add_action('wp_ajax_emp_generate_resume', 'nokri_emp_generate_resume');
if (!function_exists('nokri_emp_generate_resume')) {

    function nokri_emp_generate_resume()
    {
        global $nokri;
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '1';
            die();
        }
        $current_user_id = get_current_user_id();
        $cand_id = $_POST['cand_id'];
        $template_id = isset($_POST['template_id']) ? $_POST['template_id'] : "1";
        /* Login check */
        nokri_check_user_activity();
        /* User type check */
        if ($current_user_id = $cand_id || get_user_meta($current_user_id, '_sb_reg_type', true) == '1') {
            $page_link = isset($nokri['cand_resume_generate_page']) ? get_page_link($nokri['cand_resume_generate_page']) . "?user_id=$cand_id" . "&&tem_id=" . $template_id : "#";
            echo $page_link;
            die();
        }
        if (get_user_meta($current_user_id, '_sb_reg_type', true) == '0') {
            echo '3';
            die();
        }
    }

}
/* * ******************************* */
/* Email job to anyone popup */
/* * ******************************* */
add_action('wp_ajax_nopriv_email_this_job_popup', 'nokri_email_this_job_popup');
add_action('wp_ajax_email_this_job_popup', 'nokri_email_this_job_popup');
if (!function_exists('nokri_email_this_job_popup')) {

    function nokri_email_this_job_popup()
    {
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '5';
            die();
        }
        $job_id = ($_POST['email_job_id']);
        echo '<div class="cp-loader"></div>
				  <div class="modal fade resume-action-modal" id="myModal-job">
					<div class="modal-dialog">
					  <!-- Modal content-->
					  <div class="modal-content">
					  <form method="post" id="email_this_job" class="apply-job-modal-popup">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal">&times;</button>
						  <h4 class="modal-title">' . esc_html__("Want to email this job?", "nokri") . '</h4>
						</div>
						<div class="modal-body">
						
					   
					  <div class="col-md-12 col-sm-12 col-xs-12">
					   <div class="form-group">
						  <label>' . __('Enter Receiver Email', 'nokri') . '</label>
						  <input placeholder="' . __('Enter valid email address', 'nokri') . '" class="form-control" type="email" data-parsley-type="email" data-parsley-required="true"  data-parsley-error-message="' . __('Please enter valid email', 'nokri') . '" data-parsley-trigger="change" name="sb_reciever_email">
					   </div>
				 </div>
						
						
							
						<div class="modal-footer">
						  <button type="submit" name="submit" class="btn n-btn-flat btn-mid btn-block" id="email_this_job_btn">' . esc_html__('Send Now', 'nokri') . '</button>
						</div>
						<input type="hidden" name="current_job"   id="current_job" value="' . esc_attr($job_id) . '" />
					  </form>
					  </div>
					</div>
				</div>';
        die();
    }

}
/* * ******************************* */
/* Admin download Resumes Against a job */
/* * ******************************* */
add_action('wp_ajax_nopriv_download_admin_resumes_call', 'nokri_download_admin_resumes_call');
add_action('wp_ajax_download_admin_resumes_call', 'nokri_download_admin_resumes_call');
if (!function_exists('nokri_download_admin_resumes_call')) {

    function nokri_download_admin_resumes_call()
    {


        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $job_id = isset($_POST['download_job_id']) ? $_POST['download_job_id'] : "";

        $job_title = get_the_title($job_id);
        /* cand resume */
        $applier = array();
        $status_wise = false;
        global $wpdb;
        $extra = " AND meta_key like '_job_applied_resume_%'";
        $query = "SELECT * FROM $wpdb->postmeta WHERE post_id = '$job_id' $extra";
        $applier_resumes = $wpdb->get_results($query);


        /* Check Is Resume Exist */
        if (count($applier_resumes) != 0) {
            if (count($applier_resumes) > 0) {
                foreach ($applier_resumes as $resumes) {
                    if ($status_wise) {
                        $array_data = explode('_', $resumes->meta_key);
                        $applier[] = $array_data[4];
                    } else {
                        $array_data = explode('|', $resumes->meta_value);

                        $applier[] = $array_data[0];
                    }
                }
            }

            if ($status_wise && count($applier) == 0) {
                $applier[] = '@#/!';
            }
            $attachment_id = "";
            $pdf_files = array();
            if (!empty($applier)) {
                foreach ($applier as $apply) {
                    $cand_id = $apply;
                    $cand_resume = get_post_meta($job_id, '_job_applied_resume_' . $cand_id, true);
                    $array_data = explode('|', $cand_resume);
                    $attachment_id = isset($array_data[1]) ? $array_data[1] : '';
                    if (is_numeric($attachment_id)) {
                        $pdf_files[] = $attachment_id;
                    }
                }
            } else {
                echo '3';
                die();
            }
        } else {
            echo '3';
            die();
        }

        if (!empty($pdf_files)) {
            global $nokri;
            $job_id = isset($_POST['download_job_id']) ? $_POST['download_job_id'] : "";
            $pdf_files = implode(",", $pdf_files);
            $page_link = isset($nokri['cand_resume_generate_page']) ? get_page_link($nokri['cand_resume_generate_page']) . "?job_id=$job_id" . "&&resumes=" . $pdf_files : "#";
            echo $page_link;
            die();
        }
    }

}
/* * ******************************* */
/* Email job to anyone */
/* * ******************************* */
add_action('wp_ajax_nopriv_email_this_job', 'nokri_email_this_job');
add_action('wp_ajax_email_this_job', 'nokri_email_this_job');
if (!function_exists('nokri_email_this_job')) {

    function nokri_email_this_job()
    {
        $submit_job_data = array();
        parse_str($_POST['submit_cv_data'], $submit_job_data);
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $reciever_email = $submit_job_data['sb_reciever_email'];
        $job_id = $submit_job_data['current_job'];
        $sent = nokri_email_job_to_anyone($job_id, $reciever_email);
        if ($sent) {
            echo '1';
            die();
        }
    }

}
/* * ********************************* */
/* Ajax handler for Adding Gallery */
/* * ********************************* */
add_action('wp_ajax_nokri_upload_comp_image', 'nokri_upload_comp_image');
if (!function_exists('nokri_upload_comp_image')) {

    function nokri_upload_comp_image()
    {
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

        $size_arr = explode('-', $nokri['sb_comp_img_size']);
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
        $user_portfolio = get_user_meta($user_id, '_comp_gallery', true);
        if ($user_portfolio != "") {
            $media = explode(',', $user_portfolio);
            if (count($media) >= $nokri['sb_comp_img_limit']) {
                echo '0|' . esc_html__("You can not upload more than ", 'nokri') . " " . $nokri['sb_comp_img_limit'] . " " . esc_html__("images ", 'nokri');
                die();
            }
        }


        $attachment_id = media_handle_upload('my_file_upload', 0);

        if (!is_wp_error($attachment_id)) {

            $user_portfolio = get_user_meta($user_id, '_comp_gallery', true);
            if ($user_portfolio != "") {
                $updated_portfolio = $user_portfolio . ',' . $attachment_id;
            } else {
                $updated_portfolio = $attachment_id;
            }

            update_user_meta($user_id, '_comp_gallery', sanitize_text_field($updated_portfolio));
        } else {
            echo '0|' . esc_html__("Some thing went wrong", 'nokri');
            die();
        }

        echo nokri_returnEcho($attachment_id);
        die();
    }

}
/* * ********************************* */
/* Ajax handler for Getting Gallery */
/* * ********************************* */
add_action('wp_ajax_get_uploaded_company_images', 'nokri_get_uploaded_portfolio_images');
if (!function_exists('nokri_get_uploaded_portfolio_images')) {

    function nokri_get_uploaded_portfolio_images()
    {
        $user_id = get_current_user_id();
        $ids = get_user_meta($user_id, '_comp_gallery', true);


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
/* Ajax handler for Del Gallery */
/* * ********************************* */
add_action('wp_ajax_delete_comp_image', 'nokri_delete_ad_image');
if (!function_exists('nokri_delete_ad_image')) {

    function nokri_delete_ad_image()
    {
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
        if (get_user_meta($user_crnt_id, '_comp_gallery', true) != "") {
            $ids = get_user_meta($user_crnt_id, '_comp_gallery', true);
            $res = str_replace($attachmentid, "", $ids);
            $res = str_replace(',,', ",", $res);
            $img_ids = trim($res, ',');
            update_user_meta($user_crnt_id, '_comp_gallery', sanitize_text_field($img_ids));
        }
        echo "1";
        die();
    }

}
/* * **************************************** */
/* Ajax handler for emp deleting candidate */
/* * *************************************** */
add_action('wp_ajax_del_this_candidate', 'nokri_del_this_candidate');
add_action('nokri_del_this_candidate', 'nokri_del_this_candidate');
if (!function_exists('nokri_del_this_candidate')) {

    function nokri_del_this_candidate()
    {
        global $nokri;
        /* demo check */
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2';
            die();
        }
        $cand_id = $_POST['cand_id'];
        $job_id = $_POST['job_id'];
        delete_post_meta($job_id, '_job_applied_resume_' . $cand_id);
        delete_post_meta($job_id, '_job_applied_cover_' . $cand_id);
        delete_post_meta($job_id, '_job_applied_status_' . $cand_id);
        delete_post_meta($job_id, '_job_applied_date_' . $cand_id);
        echo "1";
        die();
    }

}


/* * *****nokri employer rating action********* */
add_action('wp_ajax_sb_post_user_ratting', 'nokri_post_user_ratting');
add_action('wp_ajax_nopriv_sb_post_user_ratting', 'nokri_post_user_ratting');
if (!function_exists('nokri_post_user_ratting')) {

    function nokri_post_user_ratting()
    {
        check_ajax_referer('nokri_rating_secure', 'security');
        global $nokri;
        //nokri_authenticate_check();
        // Getting values
        $params = array();
        $current_user = wp_get_current_user();

        parse_str($_POST['sb_data'], $params);

        if (isset($nokri['google_recaptcha_key']) && isset($nokri['google_recaptcha_secret_key']) && $nokri['google_recaptcha_key'] != '' && $nokri['google_recaptcha_secret_key'] != '') {
            if (nokri_recaptcha_verify($nokri['google_recaptcha_secret_key'], $params['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'], $params['emp_review_captcha'])) {
                $rating_service_stars = $params['rating_service'];
                $rating_process_stars = $params['rating_process'];
                $rating_selection_stars = $params['rating_selection'];
                $review_title = $params['review_title'];
                $review_message = $params['review_message'];
                $employer_id = $params['employer_id'];
                $cand = get_current_user_id();
                $user_type = get_user_meta($cand, '_sb_reg_type', true);

                if ($employer_id == $cand) {
                    echo '0|' . esc_html__("You can't rate yourself.", 'nokri');
                    die();
                }
                if ($user_type == 1) {
                    echo '0|' . esc_html__("Only candidate can do this.", 'nokri');
                    die();
                }

                if (get_user_meta($employer_id, '_is_rated_' . $cand, true) == $cand) {
                    echo '0|' . esc_html__("You have already rated this employer.", 'nokri');
                    die();
                } else {
                    $time = current_time('mysql');
                    $data = array(
                        'comment_post_ID' => $cand,
                        'comment_author' => $current_user->display_name,
                        'comment_author_email' => $current_user->user_email,
                        'comment_author_url' => '',
                        'comment_content' => sanitize_text_field($review_message),
                        'comment_type' => 'dealer_review',
                        'comment_parent' => 0,
                        'user_id' => $employer_id,
                        'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
                        'comment_date' => $time,
                        'comment_approved' => 0,
                    );
                    $comment_id = wp_insert_comment($data);
                    update_comment_meta($comment_id, '_rating_service', sanitize_text_field($rating_service_stars));
                    update_comment_meta($comment_id, '_rating_proces', sanitize_text_field($rating_process_stars));
                    update_comment_meta($comment_id, '_rating_selection', sanitize_text_field($rating_selection_stars));
                    update_comment_meta($comment_id, '_rating_title', sanitize_text_field($review_title));

                    update_user_meta($employer_id, '_is_rated_' . $cand, $cand);

                    $total_stars = $rating_service_stars + $rating_process_stars + $rating_selection_stars;
                    $ratting = round($total_stars / "3", 1);
                    // Send email if enabled

                    if (isset($nokri['email_to_user_on_rating']) && $nokri['email_to_user_on_rating']) {
                        //nokri_send_email_new_rating( $cand, $employer_id, $ratting, $review_message );
                    }
                    echo '1|' . esc_html__("You've rated this user.", 'nokri');
                }
                die();
            } else {
                echo '0|' . __("Please verify captcha.", 'nokri');
                die();
            }
        } else {
            $rating_service_stars = $params['rating_service'];
            $rating_process_stars = $params['rating_process'];
            $rating_selection_stars = $params['rating_selection'];
            $review_title = $params['review_title'];
            $review_message = $params['review_message'];
            $employer_id = $params['employer_id'];
            $cand = get_current_user_id();
            $user_type = get_user_meta($cand, '_sb_reg_type', true);


            if ($employer_id == $cand) {

                echo '0|' . esc_html__("You can't rate yourself.", 'nokri');
                die();
            }
            if ($user_type == 1) {
                echo '0|' . esc_html__("Only candidate can do this.", 'nokri');
                die();
            }
            if (get_user_meta($employer_id, '_is_rated_' . $cand, true) == $cand) {

                echo '0|' . esc_html__("You have already rated this employer.", 'nokri');
                die();
            } else {


                $time = current_time('mysql');

                $data = array(
                    'comment_post_ID' => $cand,
                    'comment_author' => $current_user->display_name,
                    'comment_author_email' => $current_user->user_email,
                    'comment_author_url' => '',
                    'comment_content' => sanitize_text_field($review_message),
                    'comment_type' => 'dealer_review',
                    'comment_parent' => 0,
                    'user_id' => $employer_id,
                    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
                    'comment_date' => $time,
                    'comment_approved' => 1,
                );
                $comment_id = wp_insert_comment($data);
                update_comment_meta($comment_id, '_rating_service', sanitize_text_field($rating_service_stars));
                update_comment_meta($comment_id, '_rating_proces', sanitize_text_field($rating_process_stars));
                update_comment_meta($comment_id, '_rating_selection', sanitize_text_field($rating_selection_stars));
                update_comment_meta($comment_id, '_rating_title', sanitize_text_field($review_title));
                update_user_meta($employer_id, '_is_rated_' . $cand, $cand);
                $total_stars = $rating_service_stars + $rating_process_stars + $rating_selection_stars;
                $ratting = round($total_stars / "3", 1);
                // Send email if enabled		
                if (isset($nokri['email_to_user_on_rating']) && $nokri['email_to_user_on_rating']) {
                    //nokri_send_email_new_rating( $cand, $employer_id, $ratting, $review_message );
                }
                echo '1|' . esc_html__("You've rated this user.", 'nokri');
            }
            die();
        }
    }

}

/* * *****nokri employer rating action********* */
add_action('wp_ajax_sb_post_cand_ratting', 'nokri_post_cand_ratting');
add_action('wp_ajax_nopriv_sb_post_cand_ratting', 'nokri_post_cand_ratting');
if (!function_exists('nokri_post_cand_ratting')) {

    function nokri_post_cand_ratting()
    {
        check_ajax_referer('nokri_rating_secure', 'security');
        global $nokri;
        //nokri_authenticate_check();
        // Getting values
        $params = array();
        $current_user = wp_get_current_user();

        parse_str($_POST['sb_data'], $params);

        if (isset($nokri['google_recaptcha_key']) && isset($nokri['google_recaptcha_secret_key']) && $nokri['google_recaptcha_key'] != '' && $nokri['google_recaptcha_secret_key'] != '') {
            if (nokri_recaptcha_verify($nokri['google_recaptcha_secret_key'], $params['g-recaptcha-response'], $_SERVER['REMOTE_ADDR'], $params['cand_review_captcha'])) {


                $rating_service_stars = $params['rating_service'];
                $rating_process_stars = $params['rating_process'];
                $rating_selection_stars = $params['rating_selection'];
                $review_title = $params['review_title'];
                $review_message = $params['review_message'];
                $employer_id = $params['emp_id'];

                $cand = get_current_user_id();


                $user_type = get_user_meta($cand, '_sb_reg_type', true);

                if ($employer_id == $cand) {
                    echo '0|' . esc_html__("You can't rate yourself.", 'nokri');
                    die();
                }
                if ($user_type == 0) {
                    echo '0|' . esc_html__("Only employer can do this.", 'nokri');
                    die();
                }

                if (get_user_meta($employer_id, '_is_rated_' . $cand, true) == $cand) {
                    echo '0|' . esc_html__("You have already rated this user.", 'nokri');
                    die();
                } else {
                    $time = current_time('mysql');
                    $data = array(
                        'comment_post_ID' => $cand,
                        'comment_author' => $current_user->display_name,
                        'comment_author_email' => $current_user->user_email,
                        'comment_author_url' => '',
                        'comment_content' => sanitize_text_field($review_message),
                        'comment_type' => 'dealer_review',
                        'comment_parent' => 0,
                        'user_id' => $employer_id,
                        'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
                        'comment_date' => $time,
                        'comment_approved' => 0,
                    );
                    $comment_id = wp_insert_comment($data);
                    update_comment_meta($comment_id, '_rating_service', sanitize_text_field($rating_service_stars));
                    update_comment_meta($comment_id, '_rating_proces', sanitize_text_field($rating_process_stars));
                    update_comment_meta($comment_id, '_rating_selection', sanitize_text_field($rating_selection_stars));
                    update_comment_meta($comment_id, '_rating_title', sanitize_text_field($review_title));

                    update_user_meta($employer_id, '_is_rated_' . $cand, $cand);

                    $total_stars = $rating_service_stars + $rating_process_stars + $rating_selection_stars;
                    $ratting = round($total_stars / "3", 1);
                    // Send email if enabled

                    if (isset($nokri['email_to_user_on_rating']) && $nokri['email_to_user_on_rating']) {
                        //nokri_send_email_new_rating( $cand, $employer_id, $ratting, $review_message );
                    }
                    echo '1|' . esc_html__("You've rated this user.", 'nokri');
                }
                die();
            } else {
                echo '0|' . __("Please verify captcha.", 'nokri');
                die();
            }
        } else {
            $rating_service_stars = $params['rating_service'];
            $rating_process_stars = $params['rating_process'];
            $rating_selection_stars = $params['rating_selection'];
            $review_title = $params['review_title'];
            $review_message = $params['review_message'];
            $employer_id = $params['emp_id'];
            $cand = get_current_user_id();
            $user_type = get_user_meta($cand, '_sb_reg_type', true);

            if ($employer_id == $cand) {

                echo '0|' . esc_html__("You can't rate yourself.", 'nokri');
                die();
            }
            if ($user_type == 0) {
                echo '0|' . esc_html__("Only employer can do this.", 'nokri');
                die();
            }
            if (get_user_meta($employer_id, '_is_rated_' . $cand, true) == $cand) {

                echo '0|' . esc_html__("You have already rated this user.", 'nokri');
                die();
            } else {


                $time = current_time('mysql');

                $data = array(
                    'comment_post_ID' => $cand,
                    'comment_author' => $current_user->display_name,
                    'comment_author_email' => $current_user->user_email,
                    'comment_author_url' => '',
                    'comment_content' => sanitize_text_field($review_message),
                    'comment_type' => 'dealer_review',
                    'comment_parent' => 0,
                    'user_id' => $employer_id,
                    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],
                    'comment_date' => $time,
                    'comment_approved' => 1,
                );
                $comment_id = wp_insert_comment($data);
                update_comment_meta($comment_id, '_rating_service', sanitize_text_field($rating_service_stars));
                update_comment_meta($comment_id, '_rating_proces', sanitize_text_field($rating_process_stars));
                update_comment_meta($comment_id, '_rating_selection', sanitize_text_field($rating_selection_stars));
                update_comment_meta($comment_id, '_rating_title', sanitize_text_field($review_title));
                update_user_meta($employer_id, '_is_rated_' . $cand, $cand);
                $total_stars = $rating_service_stars + $rating_process_stars + $rating_selection_stars;
                $ratting = round($total_stars / "3", 1);
                // Send email if enabled		
                if (isset($nokri['email_to_user_on_rating']) && $nokri['email_to_user_on_rating']) {
                    //nokri_send_email_new_rating( $cand, $employer_id, $ratting, $review_message );
                }
                echo '1|' . esc_html__("You've rated this user.", 'nokri');
            }
            die();
        }
    }

}
// Goog re-capthca verification
if (!function_exists('nokri_recaptcha_verify')) {

    function nokri_recaptcha_verify($api_secret, $code, $ip, $is_captcha)
    {
        if ($is_captcha == 'no')
            return true;
        global $carspot_theme;

        $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $api_secret . '&response=' . $code . '&remoteip=' . $ip;
        $responseData = wp_remote_get($url);
        $res = json_decode($responseData['body'], true);
        if ($res["success"] === true) {
            return true;
        } else {
            return false;
        }
    }

}

// Reply Rator
add_action('wp_ajax_sb_reply_user_rating', 'nokri_reply_rator');
add_action('wp_ajax_nopriv_sb_reply_user_rating', 'nokri_reply_rator');
if (!function_exists('carspot_reply_rator')) {

    function nokri_reply_rator()
    {
        //check_ajax_referer( 'nokri_rating_reply_secure', 'security' );


        if (get_current_user_id() == "") {
            echo '0|' . esc_html__("You are not logged in.", 'nokri');
        }
        $params = array();
        parse_str($_POST['sb_data'], $params);
        //$review_reply	=	$params['review-reply'];
        //$comment_id	=	$params['comment_id'];
        $comment_id = $_POST['cid'];
        $reply_text = $_POST['reply_text'];


        if (!get_comment_meta($comment_id, '_rating_reply', true)) {
            update_comment_meta($comment_id, '_rating_reply', sanitize_text_field($reply_text));
            echo '1|' . esc_html__("Your reply posted", 'nokri');
        } else {
            echo '0|' . esc_html__("You have already replied to this rating.", 'nokri');
        }

        die();
    }

//admin side job get author


    add_action('wp_ajax_get_job_authors', 'admin_get_job_authors');
    if (!function_exists('admin_get_job_authors')) {

        function admin_get_job_authors()
        {
            /* demo check */
            $author_id = isset($_POST['author_id']) ? $_POST['author_id'] : "";

            $users = get_users(array('fields' => array('display_name', 'ID'), 'meta_query' => array('key' => '_sb_reg_type', 'value' => '1', 'compare' => '=')));
            $options = "";
            $selected = "";
            foreach ($users as $user) {
                if (get_user_meta($user->ID, '_sb_reg_type', true) != '1')
                    continue;
                $selected = "";
                if ($user->ID == $author_id) {

                    $selected = "selected";
                }


                $options .= '<option value ="' . esc_attr($user->ID) . '"  ' . $selected . '>' . esc_html($user->display_name) . '</option>';
            }

            echo $options;
            die();
        }

    }
}

// Load more jobs on search page ajax call 
add_action('wp_ajax_nopriv_load_more_jobs', 'fun_load_more_jobs');
add_action('wp_ajax_load_more_jobs', 'fun_load_more_jobs');
if (!function_exists('fun_load_more_jobs')) {

    function fun_load_more_jobs()
    {
        /* demo check */
        global $nokri;
        $pageno = isset($_POST['pageno']) ? absint($_POST['pageno']) : 1;
        $page_url = isset($_POST['page_url']) ? $_POST['page_url'] : "";
        $arr = array();


        $query = parse_url($page_url, PHP_URL_QUERY);
        parse_str($query, $arr);
        $title = '';
        if (!empty($arr)) {

            $title = '';
            if (isset($arr['job-title']) && $arr['job-title'] != "") {
                $title = $arr['job-title'];
            }
        }
        $taxonomies = array('job_type', 'ad_title', 'cat_id', 'job_category', 'job_tags', 'job_qualifications', 'job_level', 'job_salary', 'job_currency', 'job_skills', 'job_seasons', 'job_certifications', 'job_country', 'job_state', 'job_city', 'job_experience', 'job_currency', 'job_shift', 'job_class', 'job-location');
        foreach ($taxonomies as $tax) {
            $$tax = '';
            if (isset($arr[$tax]) && $arr[$tax] != "") {
                $$tax = array(
                    array('taxonomy' => $tax, 'field' => 'term_id', 'terms' => $arr[$tax]),);
            }
        }

        $category = '';
        if (isset($arr['cat-id']) && $arr['cat-id'] != "") {
            $category = array(
                array(
                    'taxonomy' => 'job_category',
                    'field' => 'term_id',
                    'terms' => $arr['cat-id'],
                ),
            );
        }

        $location = '';
        if (isset($arr['job-location']) && $arr['job-location'] != "") {
            $location = array(
                array(
                    'taxonomy' => 'ad_location',
                    'field' => 'term_id',
                    'terms' => $arr['job-location'],
                ),
            );
        }

        $location_keyword = '';
        if (isset($arr['loc_keyword']) && $arr['loc_keyword'] != "") {
            $location_keyword = array(
                array(
                    'taxonomy' => 'ad_location',
                    'field' => 'name',
                    'terms' => $arr['loc_keyword'],
                    'operator' => 'LIKE'
                ),
            );
        }

        $lat_lng_meta_query = array();
        if (isset($arr['radius_lat']) && isset($arr['radius_long'])) {
            $latitude = $arr['radius_lat'];
            $longitude = $arr['radius_long'];
        }
        if (!empty($latitude) && !empty($longitude)) {
            $distance = '30';
            if (!empty($arr['distance']) && !empty($arr['distance'])) {
                $distance = $arr['distance'];
            }

            $data_array = array("latitude" => $latitude, "longitude" => $longitude, "distance" => $distance);
            $type_lat = "'DECIMAL'";
            $type_lon = "'DECIMAL'";
            $lats_longs = nokri_radius_search_theme($data_array, false);

            if (!empty($lats_longs) && count((array)$lats_longs) > 0) {
                if ($latitude > 0) {
                    $lat_lng_meta_query[] = array(
                        'key' => '_job_lat',
                        'value' => array($lats_longs['lat']['min'], $lats_longs['lat']['max']),
                        'compare' => 'BETWEEN',
                    );
                } else {
                    $lat_lng_meta_query[] = array(
                        'key' => '_job_lat',
                        'value' => array($lats_longs['lat']['max'], $lats_longs['lat']['min']),
                        'compare' => 'BETWEEN',
                    );
                }
                if ($longitude > 0) {
                    $lat_lng_meta_query[] = array(
                        'key' => '_job_long',
                        'value' => array($lats_longs['long']['min'], $lats_longs['long']['max']),
                        'compare' => 'BETWEEN',
                    );
                } else {
                    $lat_lng_meta_query[] = array(
                        'key' => '_job_long',
                        'value' => array($lats_longs['long']['max'], $lats_longs['long']['min']),
                        'compare' => 'BETWEEN',
                    );
                }
            }
        }

        $order = 'DESC';
        if (isset($arr['order_job'])) {
            if (isset($arr['order_job']) && $_GET['order_job'] != "") {
                $order = $arr['order_job'];
            }
        }


        $featur_excluded = "";
        if (isset($nokri['premium_jobs_list_switch']) && $nokri['premium_jobs_list_switch'] == false) {
            if (isset($nokri['premium_jobs_class']) && $nokri['premium_jobs_class'] != '') {

                $featur_excluded = array(
                    'taxonomy' => 'job_class',
                    'field' => 'term_id',
                    'terms' => $nokri['premium_jobs_class'],
                    'operator' => 'NOT IN',
                );
            }
        }

        $no_of_records_per_page = get_option('posts_per_page');
        $offset = ($pageno - 1) * $no_of_records_per_page;


        $recent_job = array(
            'tax_query' => array($category, $job_salary, $title, $job_type, $job_category, $job_tags, $job_qualifications, $job_level, $job_skills, $job_seasons, $job_certifications, $job_country, $job_state, $job_city, $job_experience, $job_currency, $job_shift, $job_class, $location, $location_keyword, $featur_excluded),
            'post_type' => 'job_post',
            'posts_per_page' => $no_of_records_per_page,
            'order' => 'DESC',
            'offset' => $offset,
            'orderby' => "date",
            's' => $title,
            'post_status' => array(
                'publish'
            ),
            'meta_query' => array(
                array(
                    'key' => '_job_status',
                    'value' => 'active',
                    'compare' => '='
                )
            )
        );

        $current_layout = $nokri['search_page_layout'];
// Create the WP_User_Query object
        $results = new WP_Query($recent_job);
        /* Regular Search Query */
        if ($results->have_posts()) {
            $layouts = array('list_1', 'list_2', 'list_3');

            if ($current_layout == "1") {

                require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-list.php";
                echo nokri_returnEcho($out);
                die();
            } else if ($current_layout == "2" || $current_layout == "3") {

                require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-full.php";
                echo nokri_returnEcho($out);
                die();
            } else {
                require trailingslashit(get_template_directory()) . "template-parts/layouts/job-style/search-layout-grid.php";
                echo nokri_returnEcho($out);
                die();
            }
            /* Restore original Post Data */
            wp_reset_postdata();
        } else {
            echo '0';
            die();
        }
    }

}
//candidate resumes upload videos
add_action('wp_ajax_upload_resume_single_video', 'nokri_upload_resume_single_video_fun');

if (!function_exists('nokri_upload_resume_single_video_fun')) {

    function nokri_upload_resume_single_video_fun()
    {
        global $nokri;

        $user_id = get_current_user_id();
        $is_demo = nokri_demo_mode();
        if ($is_demo) {
            echo '2|' . esc_html__("Demo User Not Allowed", 'nokri');
            die();
        }
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        /* get files information */
        $vid_file_name = $_FILES['resume_video']['name'];
        $vid_file_size = $_FILES['resume_video']['size'];


        $vid_convert_to_mb = ($vid_file_size / 1000000);
        $vid_file_format = explode('.', $vid_file_name);

        $format_name = strtolower(end($vid_file_format));

        $allowed_format = ['mp4', 'm4v', 'avi', '3gp', 'mov'];
        /* max upload size in MB */
        $vid_actual_size = $nokri['cand_video_resume_limit'];
        /* Check file size */
        if ($vid_convert_to_mb > $vid_actual_size) {
            echo '0|' . __("Max allowd video size in MB is", 'nokri') . " " . $vid_actual_size;
            die();
        }


        if (!in_array($format_name, $allowed_format)) {

            $format_message = __("Format not supported , allowed format are", 'nokri') . " " . "mp4,m4v,avi,3gp,mov";
            echo '0|' . $format_message;
            die();
        }


        /* get already attachment ids */
        $store_vid_ids = '';
        $store_vid_ids_arr = array();
        $store_vid_ids = get_user_meta($user_id, 'cand_video_resumes', true);
        if ($store_vid_ids != '') {

            echo '0|' . esc_html__("You can not upload more than ", 'nokri') . " " . 1;
            die();
        }
        $attachment_id = media_handle_upload('resume_video', 0);

        if (!is_wp_error($attachment_id)) {
            update_user_meta($user_id, 'cand_video_resumes', $attachment_id);

            echo '' . $attachment_id;
            die();
        } else {
            echo '0|' . __("Something went wrong please try later", 'nokri');
            die();
        }
    }

}


/* Fetch uploaded video to display after upload ... */

add_action('wp_ajax_get_uploaded_video', 'nokri_get_resumes_uploaded_video');
if (!function_exists('nokri_get_resumes_uploaded_video')) {

    function nokri_get_resumes_uploaded_video()
    {
        $user_id = get_current_user_id();
        /* get record from db */
        $video_attachment_id = get_user_meta($user_id, 'cand_video_resumes', true);
        $result = array();
        if ($video_attachment_id != "") {
            $obj = array();
            $attach_video_details = wp_get_attachment_metadata($video_attachment_id);
            $video_url = wp_get_attachment_url($video_attachment_id);
            $obj = array();
            $obj['video_name'] = basename(get_attached_file($video_attachment_id));
            $obj['video_url'] = $video_url;
            $obj['video_size'] = filesize(get_attached_file($video_attachment_id));
            $obj['video_id'] = (int)$video_attachment_id;
            $result[] = $obj;
        }
        header('Content-type: text/json');
        header('Content-type: application/json');
        if ($result != '') {
            echo json_encode($result);
            die();
        }
        die();
    }

}


/* delete video */
add_action('wp_ajax_delete_upload_video', 'nokri_delete_upload_video');
if (!function_exists('nokri_delete_upload_video')) {

    function nokri_delete_upload_video()
    {


        $attachment_id_ = $_POST['video'];
        $user_id = get_current_user_id();
        if ($attachment_id_) {
            $video_attachment_id = delete_user_meta($user_id, 'cand_video_resumes');
            wp_delete_attachment($attachment_id_, true);
            echo '1';
            die();
        } else {
            echo '0|' . __("File not Deleted", 'nokri');
            die();
        }
    }

}

add_action('wp_ajax_upload_cropped_image', 'nokri_upload_cropped_image');

function nokri_upload_cropped_image()
{


    $is_demo = nokri_demo_mode();
    if ($is_demo) {
        echo '4|' . esc_html__('Not allowed in demo', 'nokri');
        die();
    }
    if (isset($_POST['cropped_img']) && $_POST['cropped_img'] != "") {
        $dir = wp_upload_dir();
        $upload_path = str_replace('/', DIRECTORY_SEPARATOR, $dir['path']) . DIRECTORY_SEPARATOR;
        $cropped_img = $_POST['cropped_img'];
        $cropped_img = str_replace('data:image/png;base64,', '', $cropped_img);
        $cropped_img = str_replace('data:image/jpeg;base64,', '', $cropped_img);
        $cropped_img = str_replace(' ', '+', $cropped_img);
        $decoded_image = base64_decode($cropped_img);
        $filename = 'nokri.png';
        $hashed_filename = md5($filename . microtime()) . '_' . $filename;
        $image_upload = file_put_contents($upload_path . $hashed_filename, $decoded_image);


        require_once(ABSPATH . 'wp-admin/includes/file.php');

        require_once(ABSPATH . 'wp-includes/pluggable.php');
        $file = array();
        $file['error'] = '';
        $file['tmp_name'] = $upload_path . $hashed_filename;
        $file['name'] = $hashed_filename;
        $file['type'] = 'image/png';
        $file['size'] = filesize($upload_path . $hashed_filename);

        // upload file to server
        // @new use $file instead of $image_upload
        $file_return = wp_handle_sideload($file, array('test_form' => false));

        $filename = $file_return['file'];
        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $wp_upload_dir['url'] . '/' . basename($filename)
        );
        $attach_id = wp_insert_attachment($attachment, $filename, 0);

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
        wp_update_attachment_metadata($attach_id, $attach_data);
        $jsonReturn = array(
            'Status' => 'Success'
        );

        if ($attach_id != " ") {
            $user_id = get_current_user_id();
            $user_type = get_user_meta($user_id, '_sb_reg_type', true);

            if ($user_type == "0") {
                update_user_meta($user_id, '_cand_dp', $attach_id);
            } else if ($user_type == "1") {
                update_user_meta($user_id, '_sb_user_pic', $attach_id);
            }
            $image_link = wp_get_attachment_image_src($attach_id, 'nokri-user-profile');
            echo '1|' . $image_link[0];
            die();
        } else {
            echo '2|' . esc_html__('Something went wrong', 'nokri');
            die();
        }
    } else {
        echo '3|' . esc_html__('No Image found', 'nokri');
        die();
    }
}
