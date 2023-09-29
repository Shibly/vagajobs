<?php
/* Template Name: Dashboard */ 
get_header();
global $nokri;
echo nokri_check_if_not_logged();
$user_id = get_current_user_id();
if( current_user_can('administrator'))
{
	update_user_meta($user_id, '_sb_reg_type', 1);
}
/*Admin can post */
$admin_post = ( isset($nokri['job_post_for_admin']) && $nokri['job_post_for_admin'] != ""  ) ? $nokri['job_post_for_admin'] : '';
if($admin_post && !current_user_can('administrator') && get_user_meta($user_id, '_sb_reg_type', true) == '' ) {update_user_meta($user_id, '_sb_reg_type', 0); }
if (get_user_meta($user_id, '_sb_reg_type', true) == '')
{ 
	$term_link = '';
	if((isset($nokri['term_condition'])) && $nokri['term_condition']  != '' )
	{
		 $term_link =  ($nokri['term_condition']);
	}
	$bg_url        = nokri_section_bg_url();
    $req_mess = 'This value is required.';
?>

<section class="user-type-page n-job-pages-section user-registration">
  	<div class="container">
    	<div class="row">
            <form id="social_login_form" >
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="n-job-pages user-section">
                    <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3  col-sm-offset-2">
                     	<div class="error-404">
                        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            	<h3><?php echo esc_html__( 'Please Select Your Account Type','nokri' ); ?></h3>
                               
                                <div class="btn-group" id="status" data-toggle="buttons">
                                   <label class="btn btn-default btn-md">
                                   <input type="radio" value="1" name="sb_reg_type" data-parsley-required="true">
                                   <?php echo esc_html__( 'Employer', 'nokri' ); ?>
                                   </label>
                                   <label class="btn btn-default btn-md active">
                                   <input type="radio" value="0" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__( 'Please Select Type', 'nokri' ); ?>" name="sb_reg_type" checked="checked">
									<?php echo esc_html__( 'Candidate', 'nokri' ); ?>
                                   </label>
                                </div>
                             </div>

                            <div class="employer_form" style="text-align:center;display:none">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px">
                                    <h3 class="extrabold"><?php echo esc_html__( 'Company Information','nokri' ); ?></h3>
                                    <div class="form-group">
                                        <label class="">Company Name</label>
                                        <input id="bt_emp_field_name" required type="text" value=""  name="emp_name" class="form-control" placeholder="<?php echo esc_html__('Glacier Park Collection By Pursuit', 'nokri'); ?>" data-parsley-error-message="<?php echo esc_html__('Provide a valid US phone number', 'nokri'); ?>">
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                    <div class="form-group">
                                        <label class="">Company Phone</label>
                                        <input id="bt_format_phone_emp" required type="tel" value=""  name="sb_reg_contact" class="form-control" placeholder="<?php echo esc_html__('(123) 456-7890', 'nokri'); ?>" data-parsley-error-message="<?php echo esc_html__('Provide a valid US phone number', 'nokri'); ?>">
                                    </div>
                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                    <div class="form-group">
                                        <label class=""><?php echo nokri_feilds_label('emp_spec_label',esc_html__('Company Specialization', 'nokri' )); ?></label>
                                        <select required class="select-generat form-control" data-allow-clear="true" data-placeholder="<?php echo esc_html__('Select all that apply', 'nokri'); ?>" name="emp_cat[]" id="bt_emp_change_term1" multiple="multiple" <?php echo nokri_feilds_operat('emp_spec_setting', 'required'); ?> data-parsley-error-message="<?php echo esc_html__('Select your industry', 'nokri'); ?>">
                                        <?php echo nokri_candidate_skills('emp_specialization','_emp_skills'); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group">
                                        <label class=""><?php echo esc_html__('What states do you operate in?', 'nokri' ); ?></label>
                                        <select required class="select-generat form-control" data-allow-clear="true" data-placeholder="<?php echo esc_html__('Select all that apply', 'nokri'); ?>" name="emp_cat[]" id="bt_emp_change_term2" multiple="multiple" data-parsley-error-message="<?php echo esc_html__('Select your state', 'nokri'); ?>">
                                        <?php echo nokri_candidate_skills('emp_specialization_states','_emp_states'); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="candidate_form" style="text-align:center;display:none">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px">
                                    <h3 class="extrabold"><?php echo esc_html__( 'Personal Details','nokri' ); ?></h3>
                                    <div class="form-group">
                                        <label class=""><?php echo nokri_feilds_label('cand_name_label', esc_html__('Your Full Name', 'nokri')); ?></label>
                                        <input id="bt_cand_field_name" required autocomplete="off" data-parsley-error-message="<?php echo esc_html__('Provide your name', 'nokri'); ?>" type="text" class="form-control" value="<?php echo esc_attr($user_info->display_name); ?>" name="cand_name" placeholder="<?php echo esc_html__('John Doe', 'nokri'); ?>">
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class="bt_format_phone"><?php echo nokri_feilds_label('cand_phone_label', esc_html__('Phone', 'nokri')); ?></label>
                                        <input id="bt_cand_field_phone" required type="tel" value="<?php echo get_user_meta($user_crnt_id, '_sb_contact', true); ?>"  name="cand_phone" class="form-control" placeholder="<?php echo esc_html__('(123) 456-7890', 'nokri'); ?>" data-parsley-error-message="<?php echo esc_html__('Provide a valid US phone number', 'nokri'); ?>" >
                                    </div>
                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label class=""><?php echo nokri_feilds_label('cand_about_label', esc_html__('About yourself', 'nokri')); ?></label>
                                        <p class="control-label">Give a description of yourself and what you're all about. This is your first impression to potential employers! (Max 500 characters)</p>
                                        <textarea id="bt_cand_field_about" required name="cand_intro" data-parsley-error-message="<?php echo esc_html__('Provide description about yourself', 'nokri'); ?>" class="form-control" cols="30" rows="10" maxlength="500" ><?php echo nokri_candidate_user_meta('_cand_intro'); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h3 class="extrabold"><?php echo esc_html__( 'I Have Experience In...','nokri' ); ?></h3>
                                    <div class="skills-gen content">
                                        <div class="row group">
                                            <div class="col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <select required class="candidate-skill-gener form-control bt_cand_field_skill" name="cand_skills_new[]" data-parsley-error-message="<?php echo esc_html__('Select your skill', 'nokri'); ?>" >
                                                        <option value="">
                                                            <?php echo esc_html__('Select Option', 'nokri'); ?>
                                                        </option>
                                                        <?php echo nokri_candidate_skills('job_skills', ''); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="remove-button">
                                                <button type="button" class="btnRemove">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="skills-btn-postion">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <button type="button" id="btnAdd-2" class="outlinebluebutton n-btn-flat btn-success">
                                                        <?php echo nokri_feilds_label('cand_skills_add', esc_html__('Add Skills', 'nokri')); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="candidate_form" style="text-align:center">
                            </div>

                             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 
                                <div class="buttons-area">                                  
                                   <div class="form-group">
                                       <input type="checkbox" id="check_social_term"  name="icheck_box_terms" class="input-icheck-others" data-parsley-required="true" data-parsley-error-message="<?php echo esc_html__( 'Please accept terms and conditions.', 'nokri' ); ?>">
                                      <p><?php echo esc_html__( 'I agree to the', 'nokri' ); ?> <a href="<?php echo get_the_permalink($term_link); ?>" target="_blank"><?php echo esc_html__( 'terms and conditions', 'nokri' ); ?></a></p>
                                   </div>
                                    <input type="submit" class="bluebutton n-btn-flat btn-mid" id="social_login_btn" value="<?php echo esc_html__( 'Continue','nokri' ); ?>">
                                   
                                </div>
                                 
                                
                             </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
  </section>
<script>
    jQuery(document).ready(function( $ ){
        
        var radioValue = $("input[name='sb_reg_type']:checked").val();
        if(radioValue == 0){
            $('.candidate_form').show();
            $('.employer_form').hide();

            $('#bt_cand_field_about').attr('required', true); 
            $('#bt_cand_field_name').attr('required', true); 
            $('#bt_cand_field_phone').attr('required', true);
            $('.bt_cand_field_skill').attr('required', true); 

            $('#bt_emp_field_name').attr('required', false);
            $('#bt_format_phone_emp').attr('required', false);
            $('#bt_emp_change_term1').attr('required', false);
            $('#bt_emp_change_term2').attr('required', false);
            
            
        }else{
            $('.candidate_form').hide();
            $('.employer_form').show();
            
            $('#bt_cand_field_about').attr('required', false); 
            $('#bt_cand_field_name').attr('required', false); 
            $('#bt_cand_field_phone').attr('required', false);
            $('.bt_cand_field_skill').attr('required', false);

            $('#bt_emp_field_name').attr('required', true);
            $('#bt_format_phone_emp').attr('required', true);
            $('#bt_emp_change_term1').attr('required', true);
            $('#bt_emp_change_term2').attr('required', true);
            
        }

        $('input[type=radio][name=sb_reg_type]').change(function() {
            if(this.value == 0){
                $('.candidate_form').show();
                $('.employer_form').hide();

                $('#bt_cand_field_about').attr('required', true); 
                $('#bt_cand_field_name').attr('required', true); 
                $('#bt_cand_field_phone').attr('required', true); 
                $('.bt_cand_field_skill').attr('required', true); 

                $('#bt_emp_field_name').attr('required', false);
                $('#bt_format_phone_emp').attr('required', false);
                $('#bt_emp_change_term1').attr('required', false);
                $('#bt_emp_change_term2').attr('required', false);

            }else{
                $('.candidate_form').hide();
                $('.employer_form').show();

                $('#bt_cand_field_about').attr('required', false); 
                $('#bt_cand_field_name').attr('required', false); 
                $('#bt_cand_field_phone').attr('required', false); 
                $('.bt_cand_field_skill').attr('required', false); 

                $('#bt_emp_field_name').attr('required', true);
                $('#bt_format_phone_emp').attr('required', true);
                $('#bt_emp_change_term1').attr('required', true);
                $('#bt_emp_change_term2').attr('required', true);
            }
        });

        
        function BTformatPhoneNumber(phoneNumberString) {
            var cleaned = ('' + phoneNumberString).replace(/\D/g, '');
            if( cleaned.length > 10 ){
                cleaned = cleaned.substring(0,10);
            }
            var match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
            if (match) {
                return '(' + match[1] + ') ' + match[2] + '-' + match[3];
            }
            return null;
        }

        $( "#bt_cand_field_phone" ).focusout(function() {
            var phonenumber = BTformatPhoneNumber( $(this).val() );
            $(this).val( phonenumber );
        })

        $( "#bt_format_phone_emp" ).focusout(function() {
            var phonenumber = BTformatPhoneNumber( $(this).val() );
            $(this).val( phonenumber );
        })
        
    });
</script>
<?php 
}
else if(get_user_meta($user_id, '_sb_reg_type', true) == '1')
{
	 get_template_part( 'template-parts/employer/employer', 'dashboard');
}
else
{ 
	 get_template_part( 'template-parts/candidate/candidate', 'dashboard');
}
get_footer();