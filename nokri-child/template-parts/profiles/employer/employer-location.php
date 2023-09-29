<div class="container full-width location-content">
    <div class="row">
        
        <?php if ($housing_tags != "" && $emp_spec_switch) { ?>
	       <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 location-detail-column">
		      <div class="blue-background">
                <div class="location-detail">
                    <i class="fa fa-home"></i>
                    <h3><?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $housing_tags . '</ul></div></div>'; ?></h3>
			        <p>Employee Housing</p>
		          </div>
                </div>
            </div>

        
        <?php } if ($pets_tags != "" && $emp_spec_switch) { ?>
	       <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 location-detail-column">
		      <div class="blue-background">
                <div class="location-detail">
                    <i class="fa fa-paw"></i>
                    <h3><?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $pets_tags . '</ul></div></div>'; ?></h3>
			        <p>Pets</p>
		          </div>
                </div>
            </div>
        
        <?php } if ($meal_tags != "" && $emp_spec_switch) { ?>
	       <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 location-detail-column">
		      <div class="blue-background">
                <div class="location-detail">
                    <i class="fa fa-cutlery"></i>
                    <h3><?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $meal_tags . '</ul></div></div>'; ?></h3>
			        <p>Meal Plan</p>
		          </div>
                </div>
            </div>
        
        <?php } if ($camping_tags != "" && $emp_spec_switch) { ?>
	       <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 location-detail-column">
		      <div class="blue-background">
                <div class="location-detail">
                    <i class="fa fa-truck"></i>
                    <h3><?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $camping_tags . '</ul></div></div>'; ?></h3>
			        <p>RV / CamperVan</p>
		          </div>
                </div>
            </div>
        
        <?php } if ($wifi_tags != "" && $emp_spec_switch) { ?>
	       <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 location-detail-column">
		      <div class="blue-background">
                <div class="location-detail">
                    <i class="fa fa-wifi"></i>
                    <h3><?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $wifi_tags . '</ul></div></div>'; ?></h3>
			        <p>Wifi Access</p>
		          </div>
                </div>
            </div>
        <?php } if ($cell_tags != "" && $emp_spec_switch) { ?>
	       <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 location-detail-column">
		      <div class="blue-background">
                <div class="location-detail">
                    <i class="fa fa-mobile"></i>
                    <h3><?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $cell_tags . '</ul></div></div>'; ?></h3>
			        <p>Cell Service</p>
		          </div>
                </div>
            </div>
        <?php } ?>
        
    </div>
</div>

<div class="container full-width no-padding location-content">
    <div class="row">
        <div class="n-candidate-info">
            
            <div class="detail-column col-lg-6 col-md-6 col-sm-12 col-xs-12 no-right-padding margin-top-5">
            <?php if (isset($custom_feilds_emp_location) && $custom_feilds_emp_location != '') { ?>
                <div class="n-camp-custom-fields">
                    <?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $custom_feilds_emp_location . '</ul></div></div>'; ?>
                </div>
            <?php } ?>
            </div>
            
            <div class="detail-column col-lg-6 col-md-6 col-sm-12 col-xs-12 no-left-padding">
            <?php if ($cities_tags != "" && $emp_spec_switch) { ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 location-detail-column">
                    <div class="purewhite-background">
                        <div class="n-candidate-info">
                            <h4 class="widget-heading"><?php echo nokri_feilds_label('emp_cities_label', esc_html__('Cities', 'nokri')); ?></h4>
                            <?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $cities_tags . '</ul></div></div>'; ?>
                        </div>
                    </div>
                </div>
            <?php } if ($states_tags != "" && $emp_spec_switch) { ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 location-detail-column">
                    <div class="purewhite-background">
                        <div class="n-candidate-info">
                            <h4 class="widget-heading"><?php echo nokri_feilds_label('emp_states_label', esc_html__('States', 'nokri')); ?></h4>
                            <?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $states_tags . '</ul></div></div>'; ?>
                        </div>
                    </div>
                </div>
            <?php } if ($regions_tags != "" && $emp_spec_switch) { ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 location-detail-column">
                    <div class="purewhite-background">
                        <div class="n-candidate-info">
                            <h4 class="widget-heading"><?php echo nokri_feilds_label('emp_regions_label', esc_html__('Regions', 'nokri')); ?></h4>
                            <?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $regions_tags . '</ul></div></div>'; ?>
                        </div>
                    </div>
                </div>
            <?php } if ($natpark_tags != "" && $emp_spec_switch) { ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 location-detail-column">
                    <div class="purewhite-background">
                        <div class="n-candidate-info">
                            <h4 class="widget-heading"><?php echo nokri_feilds_label('emp_natpark_label', esc_html__('National Parks', 'nokri')); ?></h4>
                            <?php echo '<div class="n-single-meta"><div class="resume-detail-meta"><ul class="n-single-meta-detail">' . $natpark_tags . '</ul></div></div>'; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
            
        </div> 
    </div>
</div>








        

