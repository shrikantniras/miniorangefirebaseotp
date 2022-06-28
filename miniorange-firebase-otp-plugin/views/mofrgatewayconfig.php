<?php

use OTP\Helper\MoMessages;

echo'<div class="mofr-otp-row" style="padding-left:2%"><div class="mofr_halfcontainer mofr-otp-col-md-7">
     <form name="f" method="post" action="" id="mo_firebase_gateway_settings">
        <input type="hidden" name="option" value="mo_firebase_gateway_deatils_save_option" />
        <h4 class="mofr-otp-h4"><b>Firebase Gateway Configuration:</b></h4><hr>
        <div class="mofr_gatewayconfiguration" style="margin-bottom:8px">
            <div class="mofr-gateway-text-pair mo-flex-pair mofr-otp-row" style="align-items:center;">
                <label for="mo_firebase_gateway_apiKey" class="mofr-otp-col-form-label mofr-otp-col-md-4">API Key : </label>
                <div class="mofr-otp-col-md-8 mofr-otp-textbox-padding">
                    <input type="text" '.esc_attr($disabled).' id="mo_firebase_gateway_apiKey" class="mofr_registration_table_textbox mofr-otp-form-control" name="mo_firebase_gateway_apiKey" placeholder="Enter your apiKey" value="'.esc_attr(get_mofr_option('gateway_apiKey')).'" required="">
                </div>
            </div>
            <div class="mofr-gateway-text-pair mo-flex-pair mofr-otp-row" style="align-items:center;">
                <label for="mo_firebase_gateway_authdomain" class="mofr-otp-col-form-label mofr-otp-col-md-4">Auth Domain : </label>
                <div class="mofr-otp-col-md-8 mofr-otp-textbox-padding">
                    <input type="text" '.esc_attr($disabled).' id="mo_firebase_gateway_authdomain" class="mofr_registration_table_textbox mofr-otp-form-control" name="mo_firebase_gateway_authdomain" placeholder="Enter your authDomain" value="'.esc_attr(get_mofr_option('gateway_authdomain')).'" required="">
                </div>
            </div>
            <div class="mofr-gateway-text-pair mo-flex-pair mofr-otp-row" style="align-items:center;">
                <label for="mo_firebase_gateway_databaseurl" class="mofr-otp-col-form-label mofr-otp-col-md-4">Database URL : </label>
                <div class="mofr-otp-col-md-8 mofr-otp-textbox-padding">
                    <input type="text" '.esc_attr($disabled).' id="mo_firebase_gateway_databaseurl" class="mofr_registration_table_textbox mofr-otp-form-control" name="mo_firebase_gateway_databaseurl" placeholder="Enter your databaseURL" value="'.esc_attr(get_mofr_option('gateway_databaseurl')).'" required="">
                </div>
            </div>
            <div class="mofr-gateway-text-pair mo-flex-pair mofr-otp-row" style="align-items:center;">
                <label for="mo_firebase_gateway_projectid" class="mofr-otp-col-form-label mofr-otp-col-md-4">Project Id : </label>
                <div class="mofr-otp-col-md-8 mofr-otp-textbox-padding">
                    <input type="text" '.esc_attr($disabled).' id="mo_firebase_gateway_projectid" class="mofr_registration_table_textbox mofr-otp-form-control" name="mo_firebase_gateway_projectid" placeholder="Enter your projectId" value="'.esc_attr(get_mofr_option('gateway_projectid')).'" required="">
                </div>
            </div>
            <div class="mofr-gateway-text-pair mo-flex-pair mofr-otp-row" style="align-items:center;">
                <label for="mo_firebase_gateway_storagebucket" class="mofr-otp-col-form-label mofr-otp-col-md-4">Storage Bucket : </label>
                <div class="mofr-otp-col-md-8 mofr-otp-textbox-padding">
                    <input type="text" '.esc_attr($disabled).' id="mo_firebase_gateway_storagebucket" class="mofr_registration_table_textbox mofr-otp-form-control" name="mo_firebase_gateway_storagebucket" placeholder="Enter your storageBucket" value="'.esc_attr(get_mofr_option('gateway_storagebucket')).'" required="">
                </div>
            </div>

            <div class="mofr-gateway-text-pair mo-flex-pair mofr-otp-row" style="align-items:center;">
                <label for="mo_firebase_gateway_messagingsenderid" class="mofr-otp-col-form-label mofr-otp-col-md-4">Messaging Sender Id : </label>
                <div class="mofr-otp-col-md-8 mofr-otp-textbox-padding">
                    <input type="text" '.esc_attr($disabled).' id="mo_firebase_gateway_messagingsenderid" class="mofr_registration_table_textbox mofr-otp-form-control" name="mo_firebase_gateway_messagingsenderid" placeholder="Enter your messagingSenderId" value="'.esc_attr(get_mofr_option('gateway_messagingsenderid')).'" required="">
                </div>
            </div>

            <div class="mofr-gateway-text-pair mo-flex-pair mofr-otp-row" style="align-items:center;">
                <label for="mo_firebase_gateway_appid" class="mofr-otp-col-md-4">App Id : </label>
                <div class="mofr-otp-col-md-8 mofr-otp-textbox-padding">
                    <input type="text" '.esc_attr($disabled).' id="mo_firebase_gateway_appid" class="mofr_registration_table_textbox mofr-otp-form-control" name="mo_firebase_gateway_appid" placeholder="Enter your appId" value="'.esc_attr(get_mofr_option('gateway_appid')).'" required="">
                </div>
            </div>
        </div>
        <div class="mofr-otp-col-auto">
            <input  type="submit" '.esc_attr($disabled).' name="mo_firebase_registration_form_save" id="mo_firebase_registration_form_save"  class="mo_firebase_registration_form_save mofr-otp-btn mofr-otp-btn-info"
            value="'.esc_attr(mofr_('Save Settings')).'">
        </div>
    </form>
    <br><br>
</div>
<div class="mofr-otp-col-md-5" style="margin-top: 1%;">
<div class="mofr_halfcontainer">
    <div class="mofr_otp_note" style="margin-bottom:10px; font-size:14px">
        <h4 class="mofr-otp-h4"><b>Steps To Configure Firebase Gateway</b></h4><hr>
        <ol>
            <li>Create a new project</li>
            <li>Open the Authentication section by the left side bar.</li>
            <li>On the Sign-in Method page, enable the Phone Number sign-in method.</li>
            <li>Ensure that your domain is listed in the Authorized Domains section.</li>
        </ol>
    </div>
    </div>
</div></div>';