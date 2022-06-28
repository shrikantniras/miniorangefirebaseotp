<?php
echo ' <style>
          '.esc_attr($customCSS).'    
       </style>';
echo '<div class="mo-registration-form">
        <div class="mo-registration-form-container">
         <form name="moregistrationform" id="moregistrationform" method="post">
			<div class="mo-user-name">
				<label for="mo_username_label">Username</label>
				<div class="mo-reg-user">
				 <input type="text" name="mo_username" id="mo_username" class="mo_username mo_reg_input input" value="" autocapitalize="off">
				</div>
			</div>
                        <div class="mo-user-email">
				<label for="mo_email_label">Email Address</label>
				<div class="mo-reg-user">
				 <input type="text" name="mo_email" id="mo_email" class="mo_email mo_reg_input input" value="" autocapitalize="off">
				</div>
			</div>
			<div class="mo-user-phone">
				<label for="mo_phone_label">Phone Number</label>
				<div class="mo-reg-user">
				 <input type="text" name="mo_phone" id="mo_phone" class="mo_phone mo_reg_input input" value="" autocapitalize="off">
				</div>
			</div>
			<div class="user-pass-wrap">
				<label for="user_pass_label">Password</label>
				<div class="wp-pwd">
				 <input type="password" name="mo_reg_pass" id="mo_user_password" class="mo_user_password mo_reg_input input password-input" value="">
				</div>
			</div>
			<br>
			<div class="submit">
			 <button type="submit" name="mo-register-submit" id="mo-wp-submit" class="mo_registration_button button button-primary button-large">Register</button>
			</div>
		</form><br>
	     <div class="mo-error">
	      <div id="mo_registration_message" hidden style="background-color: rgb(255 134 164);border-radius: 6px;padding: 4px 7px;">
	     </div>
	     </div>
	    </div>
	  </div>';


