<?php		

echo'	<form name="f" method="post" action="">';
            wp_nonce_field($nonce);
echo'		<input type="hidden" name="option" value="mofr_registration_connect_verify_customer" />
			<div class="mofr_registration_divided_layout mofr-otp-offset-md-2 mofr-otp-col-md-8">
		        <div class="mofr_registration_table_layout">
			        <div class="mofr-otp-row" style="align-items: center;">
                        <div class="mofr-otp-col-md-7"><br><h3 class="mofr-otp-h3">
					        '.esc_attr(mofr_("LOGIN WITH MINIORANGE")).'</h3>
					    </div>
					    <span style="align-items:center;width:50%;float:left;text-align:right" class="mofr-otp-col-md-5">
                            <a href="#goBackButton" class="mofr-otp-btn mofr-otp-btn-info">Go back to Registration Page</a>
                        </span>
                    </div>
					<hr>
					<p>
					    <b>
					        '.esc_attr(mofr_("It seems you already have an account with miniOrange. Please enter your miniOrange email and password.")).'
					    </b>
					</p>
					<table class="mofr_registration_settings_table">
						<tr>
							<td><b><font color="#FF0000">*</font>'.esc_attr(mofr_("Email:")).'</b></td>
							<td><input class="mofr_registration_table_textbox" type="email" name="email"
								required placeholder="person@example.com"
								value="'.esc_attr($admin_email).'" /></td>
						</tr>
						<tr>
							<td><b><font color="#FF0000">*</font>'.esc_attr(mofr_("Password:")).'</b></td>
							<td><input class="mofr_registration_table_textbox" required type="password"
								name="password" placeholder="'.esc_attr(mofr_("Enter your miniOrange password")).'" /></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td><br>
							    <input type="submit" class="mofr-otp-btn mofr-otp-btn-info" value="Login"/>
								<a href="#mofr_forgot_password" class="mofr-otp-btn mofr-otp-btn-info">Forgot Password</a>
						</tr>
					</table>
				</div>
			</div>
		</form>
		<form id="forgotpasswordform" method="post" action="">';
			wp_nonce_field( $nonce ); 	
echo'		<input type="hidden" name="option" value="mo_registration_mofr_forgot_password" />
		</form>
		<form id="goBacktoRegistrationPage" method="post" action="">';
			wp_nonce_field( $nonce ); 	
echo'		<input type="hidden" name="option" value="mofr_registration_go_back" />
		</form>
		<script>
			jQuery(document).ready(function(){
				$mo(\'a[href="#mofr_forgot_password"]\').click(function(){
					$mo("#forgotpasswordform").submit();
				});

				$mo(\'a[href="#goBackButton"]\').click(function(){
					$mo("#goBacktoRegistrationPage").submit();
				});
			});
		</script>';