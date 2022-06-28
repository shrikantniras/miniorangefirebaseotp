<?php

$button = '<div style="margin-top: 2%;">'.
            '<div class="">'.
                '<input type="button" style="width:100%;" class="mofr-btn mofr-primary mofr-br-rounded" '.
                        'id="miniorange_otp_token_submit" '.
                        'title="Please Enter your phone details to enable this." value="Send OTP" />'.
            '</div>'.
          '</div>';
$moRecaptchaToken = '<input type="hidden" value="" name="moRecaptchaToken" id="moRecaptchaToken">';
$messagebox = '<div>'.
                '<div   id="mofrreg_sendotp_message" class="mofr-err-container" '.
                '</div>'.
              '</div>';

$moRegOtpField = '<div class="mofr-space-rg"></div><input type="text" name="mo_otp_field" id="mo_otp_field" style="display:none;width:100%" class="mofr-user-input mofr-br-rounded" placeholder="Enter OTP code" value=""> ';

$moVerifyButton = "<input type='button' id='miniorange_otp_token_verify' class='mofr-btn mofr-primary mofr-br-rounded' value='Verify OTP' style='width: 100%; margin-top: 15px;display:none; margin-bottom: 15px; margin-right: 0px;'>";

$showotpverificationfieldsonRegForm = $isRegistrationOtpEnabled ? "block" : "none";

$showLoginwithPhoneForm = $isLoginOtpEnabled ? "block" :"none";


echo ' <style>
          '.esc_attr($customloginregCSS).'    
       </style>';
echo '<script>
    function toggleSignInForm(event,showForm, hideForm) {
      event.preventDefault()
      document.getElementById(showForm).style.display = "flex";
      document.getElementById(hideForm).style.display = "none";
    }

    function toggleSigninMethodForm(event,formName, selectedTab) {
      event.preventDefault()
      var i, tabcontent, tablinks;
      tabcontent = document.getElementsByClassName("mofr-tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }
      tablinks = document.getElementsByClassName("mofr-tab-item");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" mofr-selected", "");
      }
      document.getElementById(formName).style.display = "flex";
      document.getElementById(selectedTab).className += " mofr-selected";
    }
  </script>
  <div class="mofr-form-container">
        <input type="hidden" id="mofr-current-tab" name="mofr-current-tab" value="tab-signin">
        <div class="mofr-tabs mofr-flex-row mofr-center mofr-br-rounded">
          <div id="tab-signin" class="mofr-tab-item mofr-selected mofr-br-rounded mofr-flex-row mofr-center"
              onclick="toggleSigninMethodForm(event,\'signin-section\',\'tab-signin\')">
              Sign in
          </div>
          <div id="tab-signup" class="mofr-tab-item mofr-br-rounded mofr-flex-row mofr-center"
              onclick="toggleSigninMethodForm(event,\'signup-section\',\'tab-signup\')">
              Create Account
          </div>
        </div>
        <div class="mofr-space-rg"></div>
        <div class="mofr-form-content">
          <div id="signin-section" class="mofr-signin-section mofr-flex-column mofr-tabcontent">
              <form id="mofr-email-login" class="mofr-email-signin mofr-flex-column">
            
                <div class="mofr-space-lg"></div>
                <h3 class="mofr-heading">Sign in <br>using your Email</h3>
            
                <div class="mofr-space-lg"></div>
                <input class="mofr-user-input mofr-br-rounded" name="mofr_loginwithemail_email" id="mofr_loginwithemail_email" type="email" placeholder="Enter your email"/>
                <div class="mofr-space-rg"></div>
                <input class="mofr-user-input mofr-br-rounded" id="mofr_loginwithemail_password" name="mofr_loginwithemail_password" type="password" placeholder="Enter your password"/>
                <div class="mofr-space-rg"></div>
                <button class="mofr-btn mofr-primary mofr-br-rounded" id="mofr_loginwithemail_submit">Sign in</button>
                <div class="mofr-err-container" id="mofr_loginwithemail_error"></div>
                <div class="mofr-space-rg"></div>
                <h5 class="mofr-sec-link mofr_signin_using_phone mofr-br-rounded" style="display:'.esc_attr($showLoginwithPhoneForm).'" 
                  onclick="toggleSignInForm(event,\'mofr_phone_otp_login\',\'mofr-email-login\')">
                    Sign in using phone number
                </h5>
              </form>

              <!-- Phone Signin Section -->
              <div id="mofr_phone_otp_login" class="mofr-phone-signin mofr-flex-column mofr-hide">
                <form id="mofr_phone_otp_login_form" class="mofr-email-signin mofr-flex-column" name="mofr_phone_otp_login_form">
                <input type="hidden" value="" name="moRecaptchaToken" id="moRecaptchaToken">
                <div class="mofr-space-lg"></div>
                  <h3 class="mofr-heading">Sign in <br>using phone number</h3>
                <div class="mofr-space-lg"></div>
                <input class="mofr-user-input mofr-br-rounded" id="mofr_loginwithotp_phone" name="mofr_loginwithotp_phone" type="phone" placeholder="Enter your phone number"/>
                <div class="mofr-space-rg"></div>
                <input type="button" id="mofr_loginwithotp_sendotp" name="mofr_loginwithotp_sendotp" class="mofr-btn mofr-primary mofr-br-rounded" value="Verify Phone Number">
                <div style="display:none;"id="mofr_loginwithotp_message" class="mofr-err-container"></div>
                <input class="mofr-user-input mofr-br-rounded" id="mofr_loginwithotp_otpfield" style="display:none;" name="mofr_loginwithotp_otpfield" type="text" placeholder="Enter OTP code"/>
                <div class="mofr-space-rg"></div>
                <input type="button" id="mofr_loginwithotp_verifyotp" name="mofr_loginwithotp_verifyotp" class="mofr-btn mofr-primary mofr-br-rounded" style="display:none;"  value="Verify & Login" >
                  <h5
                    class="mofr-sec-link mofr-br-rounded" 
                    onclick="toggleSignInForm(event,\'mofr-email-login\',\'mofr_phone_otp_login\')"
                  >
                    Sign in using email
                  </h5>
                </div>
                </form>
            </div>
          <div id="signup-section" class="mofr-signup-section mofr-flex-column mofr-tabcontent mofr-hide">
          <form name="moregistrationform" id="moregistrationform" method="post" class="mofr-email-signin mofr-flex-column">
              <div class="mofr-space-lg"></div>
              <h3 class="mofr-heading">Create new account<br>to continue</h3>
            
              <div class="mofr-space-lg"></div>
              <input class="mofr-user-input mofr-br-rounded" name="mofr_reg_username" id="mofr_reg_username" required type="text" placeholder="Enter username"/>
              <div class="mofr-space-rg"></div>
              <input class="mofr-user-input mofr-br-rounded" required name="mofr_reg_email" id="mofr_reg_email" type="email" placeholder="Enter your email"/>
              <div class="mofr-space-rg"></div>
              <input class="mofr-user-input mofr-br-rounded" required name="mofr_reg_phone" id="mofr_reg_phone" type="text" placeholder="Enter your phone number"/>
              <div class="mofr-space-rg"></div>
              <div class="otpverificationfields" style="display:'.esc_attr($showotpverificationfieldsonRegForm).'">
                <div style="margin-top: 2%;">
                  <div class="">
                    <input type="button" style="width:100%;" class="mofr-btn mofr-primary mofr-br-rounded" id="miniorange_otp_token_submit"
                          title="Please Enter your phone details to enable this." value="Send OTP" />
                  </div>
                </div>
                <input type="hidden" value="" name="moRecaptchaToken" id="moRecaptchaToken">
                <div>
                  <div id="mofrreg_sendotp_message" class="mofr-err-container">
                  </div>
                </div>
                <div class="mofr-space-rg"></div>
                <input type="text" name="mo_otp_field" id="mo_otp_field" style="display:none;width:100%" class="mofr-user-input mofr-br-rounded" placeholder="Enter OTP code" value="">

                <input type="button" id="miniorange_otp_token_verify" class="mofr-btn mofr-primary mofr-br-rounded" value="Verify OTP" style="width: 100%; margin-top: 15px;display:none; margin-bottom: 15px; margin-right: 0px;">
              </div>  
              <input class="mofr-user-input mofr-br-rounded" name="mofr_reg_password" id="mofr_reg_password" 
                  type="password" required placeholder="Enter your password"/>
              <div class="mofr-space-rg"></div>
              <button type="submit" class="mofr-btn mofr-primary mofr-br-rounded" name="mofr-register-submit" id="mofr-register-submit">Sign up</button>
              <div>
                <div   id="mofrreg_validation_message"  class="mofr-err-container"> 
                </div> 
              </div>
              <div class="mofr-space-rg"></div>
              <h5 class="mofr-sec-link mofr-br-rounded"
                onclick="toggleSigninMethodForm(event,\'signin-section\',\'tab-signin\')">
                Already have an account
             </h5>
          </form>
          </div>
        </div>
    </div>
';