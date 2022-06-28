<?php

use OTP\Helper\MoMessages;

echo'   <div class="mofr-otp-row" style="padding-left: 2%">
          <div class="mofr_halfcontainer mofr-otp-col-md-12" >
            <div style="margin-bottom:10px;">
              <h4 class="mofr-otp-h4"><b>Configure Login and Signup Form</b></h4><hr>
              <form name="f" method="post" action="" id="mo_firebase_loginregform_settings">
                <input type="hidden" name="option" value="mo_firebase_loginreg_form_save_option" />
                <input type="hidden" name="nonce_mo_firebase_loginreg_form_save_option" value="'.esc_attr($mo_nonce_mo_firebase_form_save_option).'" />
                <ol>
                  <li>Use this shortcode to show the login form <b>[mo_wp_loginreg_form]</b></li>
                  <li><a href="'.esc_attr($mo_create_new_page).'" target="_blank">'. esc_attr(mofr_("Click here")).'</a> '.esc_attr( mofr_(" to create a new page." )).'</li>
                  <li>You can add the CSS to the form using the following CSS box.</li>
                  <p style="margin-left:2%;">
                    <i><b>'.esc_attr(mofr_("Login/Registration Form CSS Style")).':</b></i><br>
                    <textarea id="mo_firebase_loginreg_form_css" '.esc_attr($disabled).' style="width:60%;height:110px;;" class="mo_loginform_table_textbox" 
                    name="mo_firebase_loginreg_form_css" placeholder="" />'.esc_attr($mo_firebase_loginreg_FormCSS).'</textarea>          
                  </p>
                </ol>
                <input '.esc_attr($disabled).' type="submit" name="mo_firebase_login_form_save" '.esc_attr($disabled).'id="mo_firebase_login_form_save"  class="mofr-otp-btn mofr-otp-btn-info"
                    value="'.esc_attr(mofr_('Save Settings')).'">
              </form>
            </div>
          </div>
        </div>
        <div class="mofr-otp-row" style="padding-left: 2%">
          <div class="mofr_halfcontainer mofr-otp-col-md-6" >
            <div class="mo_firebase_login_form_container">
              <form name="f" method="post" action="" id="mo_firebase_loginform_settings">
                <input type="hidden" name="option" value="mo_firebase_login_form_save_option" />
                <input type="hidden" name="nonce_mo_firebase_login_form_save_option" value="'.esc_attr($mo_nonce_mo_firebase_form_save_option).'" />
                <input  type="checkbox" '.esc_attr($disabled).' id="mo_firebase_login_form_enable" value="1" data-toggle="mo_firebase_login_form_enable_options" 
                    class="app_enable" '.esc_attr($mo_firebase_login_form_enable).' name="mo_firebase_login_form_enable" />
                    <b style="font-size:20px;"> Enable OTP Verification on Login Form. </b>
                    <br><br>
                    <ol>
                      <li>Select redirection page ';
                        wp_dropdown_pages(array("selected" => esc_attr($mo_firebase_loginredirectPageurl)));
echo'                 </li>
                    </ol>
                   <br>
                   <input '.esc_attr($disabled).' type="submit" name="mo_firebase_login_form_save" id="mo_firebase_login_form_save"  class="mofr-otp-btn mofr-otp-btn-info"
                    value="'.esc_attr(mofr_('Save Settings')).'">
              </form>
            </div>
          </div>
          <div class=" mofr-otp-col-md-6" style="margin-top:1%">
            <div class="mofr_halfcontainer" >
              <div class="mo_firebase_login_form_container">
                <form name="f" method="post" action="" id="mo_firebase_registrationform_settings">
                  <input type="hidden" name="option" value="mo_firebase_registration_form_save_option" />
                  <input type="hidden" name="nonce_mo_firebase_registration_form_save_option" value="'.esc_attr($mo_nonce_mo_firebase_form_save_option).'" />
                  <input  type="checkbox" '.esc_attr($disabled).' id="mo_firebase_registration_form_enable" value="1" data-toggle="mo_firebase_registration_form_enable_options" 
                    class="app_enable" '.esc_attr($mo_firebase_registration_form_enable).' name="mo_firebase_registration_form_enable" />
                  <b style="font-size:20px;"> Enable OTP Verification on Registration Form. </b>
                  <br><br>
                  <ol>
                    <li>Select redirection page ';
                      wp_dropdown_pages(array("selected" => esc_attr($mo_firebase_regredirectPageurl)));
echo'               </li>
                    <li> Select User Role:
                      <select name="mo_firebase_default_user_role" id="mo_firebase_default_user_role">';
                        global $wp_roles;
                        $selected="";
                        foreach ($wp_roles->roles as $key => $value) {
                          if ($key==$mo_firebase__default_user_role) {
                            $selected="selected";
                          }
                          echo '<option '.esc_attr($selected).' value="'.esc_attr($key).'">'.esc_attr($value['name']).'</option>';
                          $selected="";
                        }
echo'                 </select>
                    </li>
                  </ol>
                  <br>
                  <input '.esc_attr($disabled).' type="submit" name="mo_firebase_registration_form_save" id="mo_firebase_registration_form_save"  class="mofr-otp-btn mofr-otp-btn-info"
                    value="'.esc_attr(mofr_('Save Settings')).'">
                </form>
              </div>
            </div>
          </div>
        </div>';