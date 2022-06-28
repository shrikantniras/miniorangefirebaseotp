<?php

use OTP\Helper\MoMessages;

echo'   <div class="mofr_halfcontainer" >
            <div class="mo_firebase_login_form_container">
            <form name="f" method="post" action="" id="mo_firebase_loginform_settings">
                <input type="hidden" name="option" value="mo_firebase_login_form_save_option" />
                <input  type="checkbox" '.esc_attr($disabled).' id="mo_firebase_login_form_enable" value="1" data-toggle="mo_firebase_login_form_enable_options" 
                    class="app_enable" '.esc_attr($mo_firebase_login_form_enable).' name="mo_firebase_login_form_enable" />
                    <b style="font-size:20px;"> Enable Login Form. </b>
                    <br>
                    <ol style="font-size:14px;">
                      <li>Use this shortcode to show the login form <b>[mo_wp_login_form]</b></li>
                      <li>You can add the CSS to this form using the following CSS box.</li>
                      <p style="margin-left:2%;">
                        <i><b>'.esc_attr(mofr_("Login Form CSS Style")).':</b></i><br>
                        <textarea id="mo_firebase_login_form_css" '.esc_attr($disabled).' style="width:80%;height:110px;;" class="mo_loginform_table_textbox" 
                        name="mo_firebase_login_form_css" placeholder="" />'.esc_attr($mo_firebase_login_FormCSS).'</textarea>          
                      </p>
                      <li>Select redirection page ';
                        wp_dropdown_pages(array("selected" => esc_attr($mo_firebase_loginredirectPageurl)));
echo'                 </li>
                    </ol>
                   <br><br>
                   <input  type="submit" '.esc_attr($disabled).' name="mo_firebase_login_form_save" id="mo_firebase_login_form_save"  class="button button-primary button-large" 
                    value="'.esc_attr(mofr_('Save Settings')).'">
            </form>
            </div>';

echo'   </div>';