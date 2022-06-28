<?php

use OTP\Helper\MoMessages;

echo'   <div class="mofr_halfcontainer">
            <div class="mo_firebase_registration_form_container">
            <form name="f" method="post" action="" id="mo_firebase_registrationform_settings">
                <input type="hidden" name="option" value="mo_firebase_registration_form_save_option" />
                <input  type="checkbox" '.esc_attr($disabled).' id="mo_firebase_registration_form_enable" value="1" data-toggle="mo_firebase_registration_form_enable_options" 
                    class="app_enable" '.esc_attr($mo_firebase_registration_form_enable).' name="mo_firebase_registration_form_enable" />
                <b style="font-size:20px;"> Enable Registration Form. </b>
                <br>
                <ol style="font-size:14px;">
                  <li>Use this shortcode to show the registration form <b>[mo_wp_registration_form]</b></li>
                  <li>You can add the CSS to this form using the following CSS box.</li>
                  <p style="margin-left:2%;">
                    <i><b>'.esc_attr(mofr_("Registration Form CSS Style")).':</b></i><br>
                    <textarea '.esc_attr($disabled).' id="mo_firebase_registration_form_css" style="width:80%;height:110px;;" class="mo_registrationform_table_textbox" 
                    name="mo_firebase_registration_form_css" placeholder="" />'.esc_attr($mo_firebase_registration_FormCSS).'</textarea>          
                   </p>
                  <li>Select redirection page ';
                    wp_dropdown_pages(array("selected" => esc_attr($mo_firebase_regredirectPageurl)));
echo'             </li>
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
echo'               </select>
                  </li>
                </ol>
                <br><br>
                <input  type="submit" name="mo_firebase_registration_form_save" id="mo_firebase_registration_form_save"  class="button button-primary button-large" 
                    value="'.esc_attr(mofr_('Save Settings')).'">
            </form>
            </div>';

echo'   </div>';