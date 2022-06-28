<?php

echo'<!--Register with miniOrange-->
	<form name="f" method="post" action="" id="register-form">';
        wp_nonce_field($nonce);
echo'	<input type="hidden" name="option" value="mofr_registration_register_customer" />
		<div class="mofr_registration_divided_layout mofr-otp-offset-md-2 mofr-otp-col-md-8">
			<div class="mofr_registration_table_layout">
			    <div class="mofr-otp-row" style="align-items: center;">
				    <div class="mofr-otp-col-md-7"><br><h3 class="mofr-otp-h3">
				        '.esc_attr(mofr_("REGISTER WITH MINIORANGE")).'</h3>
				    </div>
				    <span style="align-items:center;width:50%;float:left;text-align:right" class="mofr-otp-col-md-5">
                        <a href="#goToLoginPage" class="mofr-otp-btn mofr-otp-btn-info">'.esc_attr(mofr_("Already Have an Account? Sign In")).'</a>
                    </span>
                </div>
                <hr>
				<p>
				    <div class="mo_idp_help_desc">
                        You are using a third party service for SMS Delivery. In order to make it easy to 
                        manage licenses, download reports, track transactions and generate leads we ask you set up an account 
                        before using the plugin. The plugin ships with 10 free SMS transactions.<br/>
                        We use the personal information you provide for account creation purposes only. It allows us to 
                        reach out to you easily in case of any support.   
                    </div>
                </p>
				<table class="mofr_registration_settings_table">
					<tr>
						<td><b><font color="#FF0000">*</font>'.esc_attr(mofr_("Email:")).'</b></td>
						<td><input class="mofr_registration_table_textbox" type="email" name="email"
							required placeholder="person@example.com"
							value="'.esc_attr($current_user->user_email).'" /></td>
					</tr>

					<tr>
						<td><b><font color="#FF0000">*</font>'.esc_attr(mofr_("Website/Company Name:")).'</b></td>
						<td><input class="mofr_registration_table_textbox" type="text" name="company"
							required placeholder="'.esc_attr(mofr_("Enter your companyName")).'"
							value="'.esc_attr($_SERVER["SERVER_NAME"]).'" /></td>
						<td></td>
					</tr>

					<tr>
						<td><b>&nbsp;&nbsp;'.esc_attr(mofr_("FirstName:")).'</b></td>
						<td><input class="mofr_registration_table_textbox" type="text" name="fname"
							placeholder="'.esc_attr(mofr_("Enter your First Name")).'"
							value="'.esc_attr($current_user->user_firstname).'" /></td>
						<td></td>
					</tr>

					<tr>
						<td><b>&nbsp;&nbsp;'.esc_attr(mofr_("LastName:")).'</b></td>
						<td><input class="mofr_registration_table_textbox" type="text" name="lname"
							placeholder="'.esc_attr(mofr_("Enter your Last Name")).'"
							value="'.esc_attr($current_user->user_lastname).'" /></td>
						<td></td>
					</tr>
					
					<tr>
						<td><b><font color="#FF0000">*</font>'.esc_attr(mofr_("Password:")).'</b></td>
						<td><input class="mofr_registration_table_textbox" required type="password"
							name="password" placeholder="'.esc_attr(mofr_("Choose your password (Min. length 6)")).'" /></td>
					</tr>
					<tr>
						<td><b><font color="#FF0000">*</font>'.esc_attr(mofr_("Confirm Password:")).'</b></td>
						<td><input class="mofr_registration_table_textbox" required type="password"
							name="confirmPassword" placeholder="'.esc_attr(mofr_("Confirm your password")).'" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
						    <br /><input type="submit" name="submit" value="'.esc_attr(mofr_("Register")).'" style="width:100px;"
							class="mofr-otp-btn mofr-otp-btn-info" />
						</td>
					</tr>
				</table>
			</div>
		</div>
	</form>
	<form id="goToLoginPageForm" method="post" action="">';
        wp_nonce_field($nonce);
echo'	<input type="hidden" name="option" value="mofr_go_to_login_page" />
	</form>
	<script>
		jQuery(document).ready(function(){
			$mo(\'a[href="#mofr_forgot_password"]\').click(function(){
				$mo("#forgotpasswordform").submit();
			});

			$mo(\'a[href="#goToLoginPage"]\').click(function(){
				$mo("#goToLoginPageForm").submit();
			});
		});
	</script>';