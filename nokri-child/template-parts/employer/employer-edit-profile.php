<?php
global $nokri;
$user_info    = wp_get_current_user();
$user_crnt_id = $user_info->ID;
$mapType      = nokri_mapType();
if($mapType == 'google_map')
{
	wp_enqueue_script( 'google-map-callback',false);
}
$ad_mapLocation  ='';
$ad_mapLocation  =  get_user_meta($user_crnt_id, '_emp_map_location', true);
$headline        =  get_user_meta($user_crnt_id, '_user_headline', true);
$ad_map_lat    	 =  get_user_meta($user_crnt_id, '_emp_map_lat', true);
$ad_map_long	 =  get_user_meta($user_crnt_id, '_emp_map_long', true);
$emp_profile	 = get_user_meta($user_crnt_id, '_user_profile_status', true);
if(get_user_meta($user_crnt_id, '_emp_map_lat', true) == '')
{
	$ad_map_lat = $nokri['sb_default_lat'];
}
if(get_user_meta($user_crnt_id, '_emp_map_long', true) == '')
{
	$ad_map_long = $nokri['sb_default_long'];
}
nokri_load_search_countries(1);
/* Getting All Jobs */
$terms = get_terms(array( 'taxonomy' => 'job_category', 'hide_empty' => false, 'parent' => 0, ));
/* Getting Company Search Selected radio Btn */
$comp_search = get_user_meta($user_crnt_id, '_emp_search', true);
/*Is map show*/
$is_lat_long = isset($nokri['emp_map_switch']) ? $nokri['emp_map_switch']  : false;


/*Is account del option*/
$is_acount_del = isset($nokri['user_profile_delete_option']) ? $nokri['user_profile_delete_option']  : false;
/* For job Location level text */
$job_country_level_heading = ( isset($nokri['job_country_level_heading']) && $nokri['job_country_level_heading'] != ""  ) ? $nokri['job_country_level_heading'] : '';

$job_country_level_1 = ( isset($nokri['job_country_level_1']) && $nokri['job_country_level_1'] != ""  ) ? $nokri['job_country_level_1'] : '';

$job_country_level_2 = ( isset($nokri['job_country_level_2']) && $nokri['job_country_level_2'] != ""  ) ? $nokri['job_country_level_2'] : '';

$job_country_level_3 = ( isset($nokri['job_country_level_3']) && $nokri['job_country_level_3'] != ""  ) ? $nokri['job_country_level_3'] : '';

$job_country_level_4 = ( isset($nokri['job_country_level_4']) && $nokri['job_country_level_4'] != ""  ) ? $nokri['job_country_level_4'] : '';
/* Make location selected on update ad */
$cand_custom_loc    = get_user_meta($user_crnt_id, '_emp_custom_location', true);

$emp_currency    = get_user_meta($user_crnt_id, '_emp_currency', true);

$levelz	            =	count((array) $cand_custom_loc);
$ad_countries	=	nokri_get_cats('ad_location' , 0 );
$country_html	=	'';
foreach( $ad_countries as $ad_country )
{
	$selected	=	'';
	if(isset($cand_custom_loc[0]))
	{
		if( $levelz > 0 && $ad_country->term_id == $cand_custom_loc[0])
		{
			$selected	=	'selected="selected"';
		}
	}
	$country_html	.=	'<option value="'.$ad_country->term_id.'" '.$selected.'>' . $ad_country->name .  '</option>';
}
$country_states = '';
if( $levelz >= 2 )
{

	$ad_states	=	nokri_get_cats('ad_location' , $cand_custom_loc[0] );
	$country_states	=	'';
	foreach( $ad_states as $ad_state )
	{
		$selected	=	'';
		if( $levelz > 0 && $ad_state->term_id == $cand_custom_loc[1] )
		{
			$selected	=	'selected="selected"';
		}
		$country_states	.=	'<option value="'.$ad_state->term_id.'" '.$selected.'>' . $ad_state->name .  '</option>';
		
	}
	
}
$country_cities	= '';
if( $levelz >= 3 )
{
	$ad_country_cities	=	nokri_get_cats('ad_location' , $cand_custom_loc[1] );
	$country_cities	=	'';
	foreach( $ad_country_cities as $ad_city )
	{
		$selected	=	'';
		if( $levelz > 0 && $ad_city->term_id ==  $cand_custom_loc[2] )
		{
			$selected	=	'selected="selected"';
		}
		$country_cities	.=	'<option value="'.$ad_city->term_id.'" '.$selected.'>' . $ad_city->name .  '</option>';
	}
}
$country_towns = '';
if( $levelz >= 4 )
{
	$ad_country_town	=	nokri_get_cats('ad_location' , $cand_custom_loc[2] );
	$country_towns	=	'';
	foreach( $ad_country_town as $ad_town )
	{
		$selected	=	'';
		if( $levelz > 0 && $ad_town->term_id == $cand_custom_loc[3] )
		{
			$selected	=	'selected="selected"';
		}
		$country_towns	.=	'<option value="'.$ad_town->term_id.'" '.$selected.'>' . $ad_town->name .  '</option>';
	}
}
/* Hide/show section */
$detail_sec  = (isset($nokri['emp_spec_switch'])) ? $nokri['emp_spec_switch'] : false;
$soc_sec     = (isset($nokri['emp_social_section_switch'])) ? $nokri['emp_social_section_switch'] : false;
$loc_sec     = (isset($nokri['emp_loc_switch'])) ? $nokri['emp_loc_switch'] : false;
$cust_sec    = (isset($nokri['emp_custom_switch'])) ? $nokri['emp_custom_switch'] : false;
$port_sec    = (isset($nokri['emp_port_switch'])) ? $nokri['emp_port_switch'] : false;
$port_location_sec    = (isset($nokri['emp_port_location_switch'])) ? $nokri['emp_port_location_switch'] : false;
/* Custom feilds for registration */
$custom_feilds_html = $custom_feilds_emp = $custom_feild_cand = '';
$custom_feild_txt   = (isset($nokri['user_custom_feild_txt'])) ? $nokri['user_custom_feild_txt'] : '';
$custom_feild_id    = (isset($nokri['custom_registration_feilds'])) ? $nokri['custom_registration_feilds'] : '';
if($custom_feild_id != '')
{
	$custom_feilds_html = nokri_get_custom_feilds($user_crnt_id,'Registration',$custom_feild_id,true);
}
/* Custom feilds for employer about */
$custom_feild_emp  = (isset($nokri['custom_employer_feilds'])) ? $nokri['custom_employer_feilds'] : '';
if($custom_feild_emp != '')
{
	$custom_feilds_emp = nokri_get_custom_feilds($user_crnt_id,'Employer',$custom_feild_emp,true);
}

/* Custom feilds for employer location */
$custom_feilds_html_location = $custom_feilds_emp_location = $custom_feild_cand_location = '';
$custom_feild_txt_location   = (isset($nokri['user_custom_feild_txt_location'])) ? $nokri['user_custom_feild_txt_location'] : '';
$custom_feild_emp_location  = (isset($nokri['custom_employer_location_feilds'])) ? $nokri['custom_employer_location_feilds'] : '';
if($custom_feild_emp_location != '')
{
	$custom_feilds_emp_location = nokri_get_custom_feilds($user_crnt_id,'Employer',$custom_feild_emp_location,true);
}


/* required message */
$req_mess = esc_html__( 'This value is required', 'nokri' );
?>
<form id="sb-emp-profile" method="post">
    
    
  <div class="main-body">
    <div class="dashboard-edit-profile">
      <div class="cp-loader"></div>
      <!-- Basic Informations -->
        
      <div class="dashboard-title bottom-margin">
        <h4><?php echo esc_html__( 'Profile Information', 'nokri' ); ?></h4>
    </div>
      
      <div class="dashboard-social-links">
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_name_label',esc_html__('Company Name', 'nokri' )); ?></label>
            <input type="text" value="<?php echo esc_attr($user_info->display_name); ?>" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__( 'This field is required.', 'nokri' ); ?>" name="emp_name" class="form-control">
          </div>
        </div>
        <?php  if( nokri_feilds_operat('emp_heading_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_heading_label',esc_html__('Headline', 'nokri' )); ?></label>
            <input type="text" value="<?php echo get_user_meta($user_crnt_id, '_user_headline', true); ?>" name="emp_headline" class="form-control" placeholder="<?php echo nokri_feilds_label('emp_heading_plc',esc_html__('Headline', 'nokri' )); ?>" <?php echo nokri_feilds_operat('emp_heading_setting', 'required'); ?> data-parsley-error-message="<?php echo esc_html__( 'This field is required.', 'nokri' ); ?>">
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_application_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_application_label',esc_html__('Application', 'nokri' )); ?></label>
            <select class="js-example-basic-single select-generat form-control" data-allow-clear="true" name="emp_cat[]">
                <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
                <?php echo nokri_candidate_skills('emp_specialization_application','_emp_application'); ?>
            </select>
          </div>
        </div>
        <?php } ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_email_label',esc_html__('Email*', 'nokri' )); ?></label>
            <input type="text" disabled placeholder="<?php echo  $user_info->user_email; ?>"  name="emp_email" class="form-control">
          </div> 
        </div>
        <?php if( nokri_feilds_operat('emp_no_emp_setting', 'show')) { ?>
        <div class="col-md-4 col-sm-12 col-xs-12">
          <div class="form-group">
            <label><?php echo nokri_feilds_label('emp_no_emp_label',esc_html__('Company Email', 'nokri' )); ?></label>
            <input type="email" placeholder="<?php echo nokri_feilds_label('emp_no_emp_plc',esc_html__('Public Profile Email', 'nokri' )); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_nos', true); ?>"  name="emp_nos" class="form-control" <?php echo nokri_feilds_operat('emp_no_emp_setting', 'required'); ?> data-parsley-error-message="Please enter a valid email address">
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_phone_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_phone_label',esc_html__('Phone', 'nokri' )); ?> </label>
            <input type="tel" name="sb_reg_contact" data-parsley-error-message="<?php echo esc_html__('Please enter digits only with no space','nokri'); ?>" data-parsley-type="digits"   placeholder="<?php echo nokri_feilds_label('emp_phone_plc',esc_html__('Company Phone', 'nokri' )); ?>" value="<?php echo get_user_meta($user_crnt_id, '_sb_contact', true); ?>" class="form-control" <?php echo nokri_feilds_operat('emp_phone_setting', 'required'); ?>>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_web_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_web_label',esc_html__('Website', 'nokri' )); ?></label>
            <input type="text" data-parsley-error-message="<?php echo esc_html__('Should be in url','nokri'); ?>" data-parsley-type="url"   placeholder="<?php echo nokri_feilds_label('emp_web_plc',esc_html__('Company Web Url', 'nokri' )); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_web', true); ?>" name="emp_web" class="form-control" <?php echo nokri_feilds_operat('emp_web_setting', 'required'); ?>>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_dp_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_dp_label',esc_html__('Profile Image', 'nokri' )); ?></label>
            <input id="input-b1" name="my_file_upload[]" type="file" class="file form-control sb_files-data" data-show-preview="false" data-allowed-file-extensions='["jpg", "png", "jpeg"]' data-show-upload="false">
          </div>
        </div>
        <?php } 
        if( nokri_feilds_operat('emp_cover_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_cover_label',esc_html__('Cover Image', 'nokri' )); ?></label>
            <input id="input-b1" name="my_cover_upload[]" type="file" class="file form-control sb_cover-data" data-show-preview="false" data-allowed-file-extensions='["jpg", "png", "jpeg"]' data-show-upload="false">
          </div>
        </div>
        <?php } ?>

        <!-- SAVE BUTTON --> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 save-profile"><input type="submit" id="emp_save" value="<?php echo esc_html__( 'Save Profile', 'nokri' ); ?>" class="bluebutton n-btn-flat">
          <button class="bluebutton n-btn-flat" type="button" id="emp_proc" disabled><?php echo  esc_html__( 'Processing...','nokri' ); ?></button>
		   <button class="bluebutton n-btn-flat" type="button" id="emp_redir" disabled><?php echo  esc_html__( 'Redirecting...','nokri' ); ?></button>
        </div>
          
</div> <!-- End Profile Information --> 
        
    <div class="dashboard-title bottom-margin">
        <h4><?php echo esc_html__( 'About Company', 'nokri' ); ?></h4>
    </div>
        
    <div class="dashboard-social-links">
        <?php if( nokri_feilds_operat('emp_staff_setting', 'show')) { ?>
        <div class="col-md-6 col-xs-12 col-sm-6">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_staff_label',esc_html__('Staff Size', 'nokri' )); ?></label>
            <select class="js-example-basic-single select-generat form-control" data-allow-clear="true" data-placeholder="Select Option" name="emp_cat[]" id="change_term" <?php echo nokri_feilds_operat('emp_staff_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
                <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
              <?php echo nokri_candidate_skills('emp_specialization_staff','_emp_staff'); ?>
            </select>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_spec_setting', 'show')) { ?>
        <div class="col-md-6 col-xs-12 col-sm-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_spec_label',esc_html__('Company Specialization', 'nokri' )); ?></label>
            <select class="select-generat form-control" data-allow-clear="true" data-placeholder="Select all that apply" name="emp_cat[]" id="change_term" multiple="multiple" <?php echo nokri_feilds_operat('emp_spec_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <?php echo nokri_candidate_skills('emp_specialization','_emp_skills'); ?>
            </select>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_about_setting', 'show')) { ?>
        <div class="col-md-12 col-xs-12 col-sm-12 text-input-box">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_about_label',esc_html__('About yourself', 'nokri' )); ?></label>
            <textarea  name="emp_intro" class="form-control jquery-textarea" id=""  cols="30" rows="10"><?php echo  nokri_candidate_user_meta('_emp_intro'); ?></textarea>
          </div>
        </div>
        <?php } ?>
        <div class="custom-fields employer-about-custom-field">
        <?php if($custom_feilds_html != '' ||  $custom_feilds_emp != '' ) { ?>
                <?php echo  $custom_feilds_emp; ?>
        <?php } ?>
        </div>
        
        <!-- SAVE BUTTON --> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 save-profile"><input type="submit" id="emp_save" value="<?php echo esc_html__( 'Save Profile', 'nokri' ); ?>" class="bluebutton n-btn-flat"></div> 

    </div> <!-- End About Company --> 
        
    <div class="dashboard-title bottom-margin">
        <h4><?php echo esc_html__( 'Location Details', 'nokri' ); ?></h4>
    </div>
        
    <div class="dashboard-social-links">
        <div class="col-md-6 col-sm-12 col-xs-12 no-padding">
        <?php if( nokri_feilds_operat('emp_cities_setting', 'show')) { ?>
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
          <div class="form-group">
            <label class=""><?php echo esc_html__('What cities do you operate in?', 'nokri' ); ?></label>
            <select class="select-generat form-control" data-allow-clear="true" data-placeholder="Select all that apply" name="emp_cat[]" id="change_term sort-cities" multiple="multiple" <?php echo nokri_feilds_operat('emp_cities_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <?php echo nokri_candidate_skills('emp_specialization_cities','_emp_cities'); ?>
            </select>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_states_setting', 'show')) { ?>
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
          <div class="form-group">
            <label class=""><?php echo esc_html__('What states do you operate in?', 'nokri' ); ?></label>
            <select class="select-generat form-control" data-allow-clear="true" data-placeholder="Select all that apply" name="emp_cat[]" id="change_term" multiple="multiple" <?php echo nokri_feilds_operat('emp_states_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <?php echo nokri_candidate_skills('emp_specialization_states','_emp_states'); ?>
            </select>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_regions_setting', 'show')) { ?>
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
          <div class="form-group">
            <label class=""><?php echo esc_html__('What regions do you operate in?', 'nokri' ); ?></label>
            <select class="select-generat form-control" data-allow-clear="true" data-placeholder="Select all that apply" name="emp_cat[]" id="change_term" multiple="multiple" <?php echo nokri_feilds_operat('emp_regions_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <?php echo nokri_candidate_skills('emp_specialization_regions','_emp_regions'); ?>
            </select>
          </div>
        </div>
        <?php } ?>    
        </div>
        <div class="col-md-6 col-sm-12 col-xs-12 no-padding">
        <?php if( nokri_feilds_operat('emp_natpark_setting', 'show')) { ?>
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
          <div class="form-group">
            <label class=""><?php echo esc_html__('What National Parks are nearby?', 'nokri' ); ?></label>
            <select class="select-generat form-control" data-allow-clear="true" data-placeholder="Select all that apply" name="emp_cat[]" id="change_term" multiple="multiple" <?php echo nokri_feilds_operat('emp_natpark_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <?php echo nokri_candidate_skills('emp_specialization_natpark','_emp_natpark'); ?>
            </select>
          </div>
        </div>
        

        <?php } ?>
        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_currency_label',esc_html__('Do you accept international applicants?', 'nokri' )); ?></label>
            <select class="js-example-basic-single select-generat form-control" data-allow-clear="true" data-placeholder="Select Option" name="_emp_currency" id="change_term" <?php echo nokri_feilds_operat('emp_housing_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
                <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
                <option value='yes' <?php echo ( $emp_currency == 'yes' ? 'selected="selected"': '' ); ?> >Yes</option>
                <option value='no' <?php echo ( $emp_currency == 'no' ? 'selected="selected"': '' ); ?> >No</option>
            </select>
          </div>
        </div>
        </div>
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
        <?php if( nokri_feilds_operat('emp_housing_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_housing_label',esc_html__('Housing', 'nokri' )); ?></label>
            <select class="js-example-basic-single select-generat form-control" data-allow-clear="true" data-placeholder="Select Option" name="emp_cat[]" id="change_term" <?php echo nokri_feilds_operat('emp_housing_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
                <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
                <?php echo nokri_candidate_skills('emp_specialization_housing','_emp_housing'); ?>
            </select>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_pets_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_pets_label',esc_html__('Pets Allowed', 'nokri' )); ?></label>
            <select class="js-example-basic-single select-generat form-control" data-allow-clear="true" data-placeholder="Select Option" name="emp_cat[]" id="change_term" <?php echo nokri_feilds_operat('emp_pets_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
              <?php echo nokri_candidate_skills('emp_specialization_pets','_emp_pets'); ?>
            </select>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_meal_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_meal_label',esc_html__('Meal Plan', 'nokri' )); ?></label>
            <select class="js-example-basic-single select-generat form-control" data-allow-clear="true" data-placeholder="Select Option" name="emp_cat[]" id="change_term" <?php echo nokri_feilds_operat('emp_meal_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
              <?php echo nokri_candidate_skills('emp_specialization_meal','_emp_meal'); ?>
            </select>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_camping_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_camping_label',esc_html__('Camping Available', 'nokri' )); ?></label>
            <select class="js-example-basic-single select-generat form-control" data-allow-clear="true" data-placeholder="Select Option" name="emp_cat[]" id="change_term" <?php echo nokri_feilds_operat('emp_camping_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
              <?php echo nokri_candidate_skills('emp_specialization_camping','_emp_camping'); ?>
            </select>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_wifi_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_wifi_label',esc_html__('Wifi Available', 'nokri' )); ?></label>
            <select class="js-example-basic-single select-generat form-control" data-allow-clear="true" data-placeholder="Select Option" name="emp_cat[]" id="change_term" <?php echo nokri_feilds_operat('emp_wifi_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
              <?php echo nokri_candidate_skills('emp_specialization_wifi','_emp_wifi'); ?>
            </select>
          </div>
        </div>
        <?php } if( nokri_feilds_operat('emp_cell_setting', 'show')) { ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <label class=""><?php echo nokri_feilds_label('emp_cell_label',esc_html__('Cell Service', 'nokri' )); ?></label>
            <select class="js-example-basic-single select-generat form-control" data-allow-clear="true" data-placeholder="Select Option" name="emp_cat[]" id="change_term" <?php echo nokri_feilds_operat('emp_cell_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              <option value=""><?php echo esc_html__('Select Option', 'nokri'); ?>
              <?php echo nokri_candidate_skills('emp_specialization_cell','_emp_cell'); ?>
            </select>
          </div>
        </div>
        <?php } ?>
            </div>
        <div class="custom-fields employer-location-custom-field">
        <?php if($custom_feilds_html_location != '' ||  $custom_feilds_emp_location != '' ) { ?>
            <?php echo  $custom_feilds_html_location.$custom_feilds_emp_location; ?>
        <?php } ?>
        </div>
        
        <!-- SAVE BUTTON --> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 save-profile"><input type="submit" id="emp_save" value="<?php echo esc_html__( 'Save Profile', 'nokri' ); ?>" class="bluebutton n-btn-flat"></div> 
        
    </div> <!-- End Location Details -->
        
    <div class="dashboard-title bottom-margin">
        <h4><?php echo esc_html__( 'Video & Photos', 'nokri' ); ?></h4>
    </div>
        
    <div class="dashboard-social-links">
				<?php if( nokri_feilds_operat('emp_port_vid_setting', 'show')) { ?>
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label><?php echo nokri_feilds_label('emp_port_vid_label',esc_html__('Company Video', 'nokri' )); ?></label>
                        <input type="text" placeholder="<?php echo nokri_feilds_label('emp_port_vid_plc',esc_html__('YouTube links only!', 'nokri' )); ?>" value="<?php echo  nokri_candidate_user_meta('_emp_video'); ?>" name="emp_video" class="form-control" data-parsley-pattern="^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+" <?php echo nokri_feilds_operat('emp_port_vid_setting', 'required'); ?> >
                    </div>
                </div>
				<?php } if( nokri_feilds_operat('emp_port_setting', 'show')) { ?>
                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                 <div class="form-group">
                    <label><?php echo esc_html__('Company Photos', 'nokri' ); ?></label>
                    <p class="control-label"><?php echo nokri_feilds_label('emp_port_section_label',esc_html__('Drag, drop or click to upload your company photos.', 'nokri' )); ?></p>
                    <div id="company-dropzone" class="dropzone"></div>
                    </div>
                </div>
                <?php } if( nokri_feilds_operat('emp_port_location_setting', 'show')) { ?>
                <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                 <div class="form-group">
                    <label><?php echo esc_html__('Location Photos', 'nokri' ); ?></label>
                    <p class="control-label"><?php echo nokri_feilds_label('emp_port_location_section_label',esc_html__('Drag, drop or click to upload your location photos.', 'nokri' )); ?></p>
                    <div id="company-location-dropzone" class="dropzone"></div>
                    </div>
                </div>
                <?php } ?>
        
        <!-- SAVE BUTTON --> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 save-profile"><input type="submit" id="emp_save" value="<?php echo esc_html__( 'Save Profile', 'nokri' ); ?>" class="bluebutton n-btn-flat"></div>
        
        </div> <!-- End Photo Galleries -->
        
    <div class="dashboard-title bottom-margin">
        <h4><?php echo esc_html__( 'Social Media', 'nokri' ); ?></h4>
    </div>
        
    <div class="dashboard-social-links">
        <?php if( nokri_feilds_operat('emp_fb_setting', 'show')) { ?>
            <div class="col-md-6 col-sm-12">
              <div class="form-group">
                <label><?php echo nokri_feilds_label('emp_fb_label',esc_html__('Facebook', 'nokri' )); ?></label>
                <input type="text" placeholder="<?php echo nokri_feilds_label('emp_fb_plc',esc_html__('Profile URL', 'nokri' )); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_fb', true); ?>" name="emp_fb" class="form-control" <?php echo nokri_feilds_operat('emp_fb_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              </div>
            </div>
            <?php } if( nokri_feilds_operat('emp_twtr_setting', 'show')) { ?>
            <div class="col-md-6 col-sm-12">
              <div class="form-group">
                <label><?php echo nokri_feilds_label('emp_twtr_label',esc_html__('Twitter', 'nokri' )); ?></label>
                <input type="text" placeholder="<?php echo nokri_feilds_label('emp_twtr_plc',esc_html__('Profile URL', 'nokri' )); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_twitter', true); ?>" name="emp_twitter" class="form-control" <?php echo nokri_feilds_operat('emp_twtr_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              </div>
            </div>
            <?php } if( nokri_feilds_operat('emp_linked_setting', 'show')) { ?>
            <div class="col-md-6 col-sm-12">
              <div class="form-group">
                <label><?php echo nokri_feilds_label('emp_linked_label',esc_html__('LinkedIn', 'nokri' )); ?></label>
                <input type="text" placeholder="<?php echo nokri_feilds_label('emp_linked_plc',esc_html__('Profile URL', 'nokri' )); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_linked', true); ?>" name="emp_linked" class="form-control" <?php echo nokri_feilds_operat('emp_linked_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              </div>
            </div>
            <?php } if( nokri_feilds_operat('emp_insta_setting', 'show')) { ?>
            <div class="col-md-6 col-sm-12">
              <div class="form-group">
                <label><?php echo nokri_feilds_label('emp_insta_label',esc_html__('Instagram', 'nokri' )); ?></label>
                <input type="text" placeholder="<?php echo nokri_feilds_label('emp_insta_plc',esc_html__('Profile URL', 'nokri' )); ?>" value="<?php echo get_user_meta($user_crnt_id, '_emp_google', true); ?>" name="emp_google" class="form-control" <?php echo nokri_feilds_operat('emp_insta_setting', 'required'); ?> data-parsley-error-message="<?php echo ($req_mess); ?>">
              </div>
            </div>
            <?php }  ?>
        
        <!-- SAVE BUTTON --> 
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 save-profile"><input type="submit" id="emp_save" value="<?php echo esc_html__( 'Save Profile', 'nokri' ); ?>" class="bluebutton n-btn-flat"></div>
        
        </div> <!-- End Social Media -->
        
    


      </div>
  </div>
    



    
</form>
<!-- update password-->
<div style="display:none; visibility: hidden; position: absolute;" class="main-body change-password no-top-padding">
  <div class="dashboard-edit-profile">
      
    <div class="dashboard-title bottom-margin">
        <h4><?php echo esc_html__( 'Change Password', 'nokri' ); ?></h4>
    </div>
      
    <form id="change_password" method="post" enctype="multipart/form-data">
      <div class="dashboard-social-links">
        <div class="col-md-12 col-xs-12 col-sm-12">
          <div class="row">
            <div class="dashboard-location">
              <div class="col-md-6 col-xs-12 col-sm-6">
                <div class="form-group">
                  <label><?php echo esc_html__('Old Password','nokri'); ?></label>
                  <input type="password" class="form-control" name="old_password" placeholder="<?php echo esc_html__('Enter old password','nokri'); ?>">
                </div>
              </div>
              <div class="col-md-6 col-xs-12 col-sm-6">
                <div class="form-group">
                  <label><?php echo esc_html__('New Password','nokri'); ?></label>
                  <input type="password" name="new_password" class="form-control" placeholder="<?php echo esc_html__('Enter new password','nokri'); ?>">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
          <input type="submit" value="<?php echo esc_html__('Processing...','nokri'); ?>" class="bluebutton n-btn-flat cand_pass_pro">
          <input type="submit" value="<?php echo esc_html__('Update password','nokri'); ?>" class="bluebutton n-btn-flat change_password">
          <?php if($is_acount_del) { ?>
          <input type="button" value="<?php echo esc_html__('Delete account?','nokri'); ?>" class="outlinebluebutton n-btn-flat btn-custom del_acount">
          <?php } ?>
        </div>
      </div>
    </form>
  </div>
</div>
<?php
if($mapType == 'leafletjs_map' && $is_lat_long)
{
	echo $lat_lon_script = '<script type="text/javascript">
	var mymap = L.map(\'dvMap\').setView(['.$ad_map_lat.', '.$ad_map_long.'], 13);
		L.tileLayer(\'https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png\', {
			maxZoom: 18,
			attribution: \'\'
		}).addTo(mymap);
		var markerz = L.marker(['.$ad_map_lat.', '.$ad_map_long.'],{draggable: true}).addTo(mymap);
		var searchControl 	=	new L.Control.Search({
			url: \'//nominatim.openstreetmap.org/search?format=json&q={s}\',
			jsonpParam: \'json_callback\',
			propertyName: \'display_name\',
			propertyLoc: [\'lat\',\'lon\'],
			marker: markerz,
			autoCollapse: true,
			autoType: true,
			minLength: 2,
		});
		searchControl.on(\'search:locationfound\', function(obj) {
			
			var lt	=	obj.latlng + \'\';
			var res = lt.split( "LatLng(" );
			res = res[1].split( ")" );
			res = res[0].split( "," );
			document.getElementById(\'ad_map_lat\').value = res[0];
			document.getElementById(\'ad_map_long\').value = res[1];
		});
		mymap.addControl( searchControl );
		
		markerz.on(\'dragend\', function (e) {
		  document.getElementById(\'ad_map_lat\').value = markerz.getLatLng().lat;
		  document.getElementById(\'ad_map_long\').value = markerz.getLatLng().lng;
		});
	</script>';
}