<?php
if ( !function_exists( 'add_action' ) ) {
    echo 'Code is poetry.';
    exit;
}



function WPOS_options() {

    ?>
    <div class="wrap projectStyle">
	<h2 style="margin-top:2rem;margin-left:3.45rem;margin-bottom:-1rem;"><?php echo __("One Click Optimization - Settings","WPOS-lang") ?></h2>
	<div id="whiteboxH" class="postbox">
	
	<div class="inside">
        <form action="options.php" method="post">
        <?php settings_fields("WPOS_admin_settings") ?>
            <table class="form-table">

                <tr valign="top">
                    <th scope="row"><label for="WPOP_check_enable"><?php echo __("One Click Optimization","WPOS-lang") ?></label></th>
                    <td>
					<label class="button-toggle-wrap">
					  <input <?php if( get_option("WPOP_check_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_check_enable" class="toggler" type="checkbox" data-toggle="button-toggle"/>
					  <div class="button-toggle">
						<div class="handle">
						  <div class="bars"></div>
						</div>
					  </div>
					</label>
                        <p class="description" ><?php echo __("Enable Wordpress Optimization with One Click.","WPOS-lang") ?></p>
                    </td>
                </tr>
				
				<?php if( get_option("WPOP_check_enable") == 1) { ?>
					<tr valign="top">
						<th scope="row"><label for="WPOP_adv_enable"><?php echo __("Advanced Settings","WPOS-lang") ?></label></th>
						<td>
						<label class="button-toggle-wrap">
						  <input <?php if( get_option("WPOP_adv_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_adv_enable" class="toggler" type="checkbox" data-toggle="button-toggle"/>
						  <div class="button-toggle">
							<div class="handle">
							  <div class="bars"></div>
							</div>
						  </div>
						</label>
							<p class="description" ><?php echo __("Activate Advanced Settings. <span style='color:red'>(All settings will be reset.)</span>","WPOS-lang") ?></p>
						</td>
					</tr>
					
					<?php if( get_option("WPOP_adv_enable") == 1) { ?>
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_admn_enable">
							<input <?php if( get_option("WPOP_admn_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_admn_enable" type="checkbox"/>
							<?php echo __("Enable WP-Admin Optimization. <span style='color:red'>(Use With Caution!)</span>","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_html_enable">
							<input <?php if( get_option("WPOP_html_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_html_enable" type="checkbox"/>
							<?php echo __("Enable HTML Minify.","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_comm_enable">
							<input <?php if( get_option("WPOP_comm_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_comm_enable" type="checkbox"/>
							<?php echo __("Disable comment cookies.","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_emoj_enable">
							<input <?php if( get_option("WPOP_emoj_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_emoj_enable" type="checkbox"/>
							<?php echo __("Disable WordPress Emojicons.","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_migr_enable">
							<input <?php if( get_option("WPOP_migr_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_migr_enable" type="checkbox"/>
							<?php echo __("Remove jQuery Migrate.","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_shor_enable">
							<input <?php if( get_option("WPOP_shor_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_shor_enable" type="checkbox"/>
							<?php echo __("Disable WordPress Shortlinks.","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_quer_enable">
							<input <?php if( get_option("WPOP_quer_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_quer_enable" type="checkbox"/>
							<?php echo __("Disable JavaScript Query Strings.","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_foot_enable">
							<input <?php if( get_option("WPOP_foot_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_foot_enable" type="checkbox"/>
							<?php echo __("Move Scripts to Footer. <span style='color:red'>(Use With Caution!)</span>","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_async_enable">
							<input <?php if( get_option("WPOP_async_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_async_enable" type="checkbox"/>
							<?php echo __("Enable the ASYNC Attributes for Scripts. <span style='color:red'>(Use With Caution!)</span>","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_lazy_enable">
							<input <?php if( get_option("WPOP_lazy_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_lazy_enable" type="checkbox"/>
							<?php echo __("Enable Lazy Load Feature for Images.","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_cach_enable">
							<input <?php if( get_option("WPOP_cach_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_cach_enable" type="checkbox"/>
							<?php echo __("Enable Browser Cache Feature.","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>					
					
					<tr>
						<th scope="row"></th>
						<td>
							<p style="margin:-20px 0 -20px 0">
							<label for="WPOP_embd_enable">
							<input <?php if( get_option("WPOP_embd_enable") == 1) {echo 'checked="checked"';} ?> value="1" name="WPOP_embd_enable" type="checkbox"/>
							<?php echo __("Disable WP Embed Feature. <span style='color:red'>(Use With Caution!)</span>","WPOS-lang") ?>
							</label>
							</p>
						</td>
					</tr>
					<?php } ?>
					
				<?php } ?>
	
			</table>
          
      </div>
	  
	</div>
</div>
            <div class="wrap projectStyle" id="whiteboxH">
				<div class="postbox">
				<div class="inside">
				<div style="display:inline-block">
			  
					<div class="contentDoYouLike">
					  <p><?php echo __("How would you rate <strong>One Click Optimization</strong>?", "WPOS-lang") ?></p>
					</div>

					<div class="wrapperDoYouLike">
					  <input type="checkbox" id="st1" value="1" />
					  <label for="st1"></label>
					  <input type="checkbox" id="st2" value="2" />
					  <label for="st2"></label>
					  <input type="checkbox" id="st3" value="3" />
					  <label for="st3"></label>
					  <input type="checkbox" id="st4" value="4" />
					  <label for="st4"></label>
					  <input type="checkbox" id="st5" value="5" />
					  <label for="st5"></label>
					</div>					
					
					<a target="_blank" href="https://codecanyon.net/item/one-click-optimization-wordpress-speed-optimization/reviews/21226746" class="sabutton button button-primary" style="margin: -5px 0 0 50px;"><?php echo __("Rate this plugin!", "WPOS-lang") ?></a>
				</div>
					<?php submit_button(); ?>
				</div>
				</div>
			</div>
</form>
    <?php
}

add_action("admin_init","WPOP_Admin_Reg");
function WPOP_Admin_Reg() {
	//
	register_setting("WPOS_admin_settings","WPOP_check_enable");
	register_setting("WPOS_admin_settings","WPOP_admn_enable");
	register_setting("WPOS_admin_settings","WPOP_adv_enable");
	register_setting("WPOS_admin_settings","WPOP_html_enable");
	register_setting("WPOS_admin_settings","WPOP_comm_enable");
	register_setting("WPOS_admin_settings","WPOP_emoj_enable");
	register_setting("WPOS_admin_settings","WPOP_migr_enable");
	register_setting("WPOS_admin_settings","WPOP_shor_enable");
	register_setting("WPOS_admin_settings","WPOP_quer_enable");
	register_setting("WPOS_admin_settings","WPOP_foot_enable");
	register_setting("WPOS_admin_settings","WPOP_async_enable");
	register_setting("WPOS_admin_settings","WPOP_lazy_enable");
	register_setting("WPOS_admin_settings","WPOP_cach_enable");
	register_setting("WPOS_admin_settings","WPOP_embd_enable");
	
}