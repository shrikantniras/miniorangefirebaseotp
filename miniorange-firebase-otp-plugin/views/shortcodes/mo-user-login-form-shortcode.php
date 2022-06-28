<?php
echo ' <style>
          '.esc_attr($customCSS).'    
       </style>';
echo ' <div class="mo-login-form" style="padding: 2% !important;border: 1px solid #000;border-radius: 4px;">
        <div class="mo-login-form-container">
        <div style="display:flex !important;">
        <div style="padding: 2%;">
        <h5 style="text-align: center;"><strong>Login With Password</strong></h5><br>
         <form name="mologinform" id="mologinform" method="post">
			<div class="mo-user-name">
				<label for="user_login">Username or Email Address</label>
				<div class="mo-user">
				 <input type="text" name="mo_log_user" id="mo_user_login" class="mo_user_login mo_log_input input" value="" autocapitalize="off">
				</div>
			</div>

			<div class="user-pass-wrap">
				<label for="user_pass">Password</label>
				<div class="wp-pwd">
				  <input type="password" name="mopwd_pwd" id="mo_user_pass" class="mo_user_pass mo_log_input input password-input" value="">
				</div>
			</div><br>
			<div class="submit">
				<button type="submit" name="mo-login-submit" id="mo-wp-login-submit" class="mo_login_button button button-primary button-large">Log In</button>
			</div>
			<div class="mo-text" style="display:none !important;" id="mo_login_otp_field_container"> <label for="mo_otp_field_label">Enter OTP</label> <div class="mo-reg-user"> <input type="text" name="mo_otp_field" id="mo_otp_field" class="mo_text mo_reg_input input" value=""> </div> 
			</div>
		 </form></div>
		 <div style="border-left: 1px solid #000000;height: 400px;"><h5></h5></div>
		 <div style="padding: 2%;">
		 <h5 style="text-align: center;"><strong>Login With OTP</strong></h5><br>
         <form>
         <div class="mo-user-name">
			<label for="user_login">Phone Number</label>
			<div class="mo-user">
			 <input type="text" name="phone" id="miniorange_login_verify" class="mo_user_login mo_log_input input" value="" autocapitalize="off">
			</div>
		</div>
         
         <input type="button" id="miniorange_login_otp_token_verify" class="button alt" value="Verify OTP" style=" margin-top: 15px; margin-bottom: 15px; margin-right: 0px;">
         </form>
		 </div>
		 </div>
	    <div class="mo-error">
	     <div id="mo_login_message" hidden style="background-color: rgb(255 134 164);border-radius: 6px;padding: 4px 7px;">
	     </div>
	     </div>
	    </div>
	 </div>';

