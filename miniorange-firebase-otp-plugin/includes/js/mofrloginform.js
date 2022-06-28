jQuery(document).ready(function () {
    $mo = jQuery;

    $mo("form#mofr-email-login").each(function(){

        $mo("#mofr_loginwithemail_submit").click(function(e){

            $mo("#mofr_loginwithemail_error").css({ display: "none" });
            var mofruser_data = $mo("#mofr_loginwithemail_email").val();
            var mofruser_pass = $mo("#mofr_loginwithemail_password").val();
            $mo('#mofr_loginwithemail_error').empty();

            e.preventDefault();

            $mo.ajax({
                url:mofrloginform.siteURL,
                type: "POST",
                crossDomain:!0,
                data:{
                    user_data : mofruser_data,
                    user_pass : mofruser_pass,
                    user_nonce_login_with_emailpass : mofrloginform.nonce,
                    action    : "mofr_login_with_email_pass"
                },
                dataType: "json",
                success: function(o){
                    if (o.result=="error") {
                        $mo('#mofr_loginwithemail_error').append(o.message);
                        $mo("#mofr_loginwithemail_error").css({ display: "block" });
                    }
                    else{
                        window.location.replace(mofrloginform.redirecturl);
                    }

                },
                error: function(o,n,e){
                }
            });
        });
    });


    $mo("#mofr_phone_otp_login_form").each(function(){
        
        initializeFirebaseScript('form#mofr_phone_otp_login_form','mofr_loginwithotp_sendotp_captcha','#mofr_loginwithotp_sendotp','#mofr_loginwithotp_phone',"#mofr_loginwithotp_verifyotp");
        $mo("#mofr_loginwithotp_sendotp").click(function(){

            var phone_number = $mo("#mofr_loginwithotp_phone").val();
            var recaptchaToken = $mo("#moRecaptchaToken").val();
            $mo("#mofr_loginwithotp_message").css({ display: "block" });

            $mo.ajax({

                url : mofrloginform.siteURL,
                type: "POST",
                data: {
                    action:"mofr_login_with_otp_sendotp",
                    user_phone:phone_number,
                    user_nonce_login_with_otp: mofrloginform.nonce,
                    recaptcha_token: recaptchaToken,
                },
                crossDomain: !0,
                dataType: "json",
                success: function(o){

                    if (o.result == "success") {
                        $mo("#mofr_loginwithotp_message").empty();
                        $mo("#mofr_loginwithotp_message").append(o.message);
                        $mo("#mofr_loginwithotp_otpfield").show();
                        $mo("#mofr_loginwithotp_sendotp").hide();
                        $mo("#mofr_loginwithotp_verifyotp").show();
                        $mo("#mofr_loginwithotp_message").attr(
                          "class",
                          "mofr-success-container"
                        );
                    }
                    else{
                        $mo("#mofr_loginwithotp_message").empty();
                        $mo("#mofr_loginwithotp_message").append(o.message);
                        $mo("#mofr_loginwithotp_message").attr("class", "mofr-err-container");
                    }
                    
                },
                error:function(o,n,e){
                    
                }
            });

        });

        $mo("#mofr_loginwithotp_verifyotp").click(function(){
            
            var phone_number   = $mo("#mofr_loginwithotp_phone").val();
            var recaptchaToken = $mo("#moRecaptchaToken").val();
            var otpToken       = $mo("#mofr_loginwithotp_otpfield").val();

            $mo.ajax({
                url : mofrloginform.siteURL,
                type: "POST",
                data: {
                    action:"mofr_login_with_otp_verifyotp",
                    user_phone:phone_number,
                    otp_token : otpToken,
                    user_nonce_verify_login_with_otp:mofrloginform.nonce,
                    recaptcha_token: recaptchaToken,
                },
                crossDomain: !0,
                dataType: "json",
                success: function(o){
                    if (o.result=='success') {

                        $mo("#mofr_loginwithotp_message").empty();
                        $mo("#mofr_loginwithotp_message").append("Log in success!! Redirecting.");
                        $mo("#mofr_loginwithotp_message").attr("class","mofr-success-container");
                        window.location.replace(mofrloginform.redirecturl);
                    }
                    else{
                        
                        $mo("#mofr_loginwithotp_message").empty();
                        $mo("#mofr_loginwithotp_message").append(o.message);
                        $mo("#mofr_loginwithotp_message").attr("class", "mofr-err-container");
                    }                   
                },
                error:function(o,n,e){
                }
            });

        });

    });





    $mo("form#mologinform").each(function () {

        let form = $mo(this);

        let button = '<div style="margin-top: 2%;">' +
                        '<div class="">' +
                            '<input type="hidden" name="mo_login_type" id="mo_login_type" value="login_with_email_password">'+
                            '<input type="button" style="width:auto;" class="button button-primary button-large" ' +
                                    'id="miniorange_otp_token_submit" ' +
                                    'title="Please Enter your phone details to enable this." value="Login With OTP" />'+
                        '</div>' +
                      '</div>';

        let messagebox = '<div style="margin-top:2%">' +
                            '<div   id="mo_message" hidden="" ' +
                                    'style="background-color: #f7f6f7;padding: 1em 2em 1em 3.5em;">' +
                            '</div>' +
                          '</div>';

        

        let selecotor = $mo('button[name="mo-login-submit"]');


        
        // $mo(divelement).insertAfter(selecotor);
        
        $mo(button).insertAfter("#mo_login_with_otp");
        $mo(messagebox).insertAfter("#miniorange_otp_token_submit");

        var mootpfield = '<div class="mo-text" style="display:none !important;" id="mo_login_otp_field_container"> <label for="mo_otp_field_label">Enter OTP</label> <div class="mo-reg-user"> <input type="text" name="mo_otp_field" id="mo_otp_field" class="mo_text mo_reg_input input" value=""> </div> </div>';
        // $mo(mootpfield).insertAfter("#mo_message");

        var moVerifyButton = "<input type='button' id='miniorange_login_otp_token_verify' class='button alt' value='Verify OTP' style='width: 100%; display:none !important; margin-top: 15px; margin-bottom: 15px; margin-right: 0px;'>";
        // $mo(moVerifyButton).insertAfter("#mo_otp_field_container");

        // initializeFirebaseScript('form#mologinform','miniorange_otp_token_submit_captcha','#miniorange_otp_token_submit','#mo_user_login',"#miniorange_login_otp_token_verify");
        $mo("#miniorange_otp_token_submit").click(function(){

            if ($mo("#mo_login_type").val()=="login_with_email_password") {
                $mo("#mo_login_type").val("login_with_otp");
                
                ($mo("#mo_user_login").parent().parent()).children("label").text("Phone Number");
                ($mo("#mo_user_pass").parent().parent()).hide();

            }else{

                $mo("#mo_message").empty();
                $mo("#mo_message").show();

                var phone_number   = $mo("#mo_user_login").val();
                var recaptchaToken = $mo("#moRecaptchaToken").val();

                $mo.ajax({
                    url : mofrloginform.siteURL,
                    type: "POST",
                    data: {
                        action:"mo_firebase_login_form",
                        user_phone:phone_number,
                        recaptcha_token: recaptchaToken,
                    },
                    crossDomain: !0,
                    dataType: "json",
                    success: function(o){
                        if (o.result == "success") {

                            $mo("#mo_message").empty();
                            $mo("#mo_message").append(o.message);
                            $mo("#mo_message").css("border-top", "3px solid green");
                            $mo("#mo_login_otp_field_container").show();
                            $mo("#miniorange_login_otp_token_verify").show();
                        }
                        else{

                            $mo("#mo_message").empty();
                            $mo("#mo_message").append(o.message);
                            $mo("#mo_message").css("border-top", "3px solid red");
                        }
                    },
                    error:function(o,n,e){
                    }
                });
            }
        });
    });
    
});