jQuery(document).ready(function () {
    $mo = jQuery;

    if($mo("#moregistrationform").length <= 0 ) {
        return;
    }

    $mo("form#moregistrationform").each(function () {

        let form = $mo(this);

        initializeFirebaseScript('form#moregistrationform','miniorange_otp_token_submit_captcha','#miniorange_otp_token_submit','#mo_user_login',"#mo-wp-submit");
        
        $mo("#miniorange_otp_token_submit").click(function(){
            
            $mo("#mofrreg_sendotp_message").empty();
            $mo("#mofrreg_sendotp_message").show();

            var phone_number   = $mo("#mofr_reg_phone").val();
            var recaptchaToken = $mo("form#moregistrationform #moRecaptchaToken").val();

            $mo.ajax({
                url : mofrregistrationform.siteURL,
                type: "POST",
                data: {
                    action:"mo_firebase_reg_send_form",
                    user_nonce_registration_sendotp : mofrregistrationform.nonce,
                    user_phone:phone_number,
                    recaptcha_token: recaptchaToken,
                },
                crossDomain: !0,
                dataType: "json",
                success: function(o){
                    if (o.result=='success') {
                        
                        $mo("#mofrreg_sendotp_message").empty();
                        $mo('#miniorange_otp_token_verify').show();
                        $mo('#mo_otp_field').show();
                        $mo("#mofrreg_sendotp_message").append(o.message);
                        $mo("#mofrreg_sendotp_message").attr(
                          "class",
                          "mofr-success-container"
                        );
                    }
                    else{
                        $mo("#mofrreg_sendotp_message").empty();
                        $mo("#mofrreg_sendotp_message").append(o.message);
                        $mo("#mofrreg_sendotp_message").attr("class", "mofr-err-container");
                    }
                },
                error:function(o,n,e){
                }
            });

        });

        $mo("#mofr-register-submit").click(function(e){

           e.preventDefault();
           $mo("#mofrreg_validation_message").show();

           var username       = $mo("#mofr_reg_username").val();
           var useremail      = $mo("#mofr_reg_email").val();
           var password       = $mo("#mofr_reg_password").val();
           var phone_number   = $mo("#mofr_reg_phone").val();

           $mo.ajax({
                url : mofrregistrationform.siteURL,
                type: "POST",
                data: {

                    action:"mo_firebase_reg_submit",
                    user_phone:phone_number,
                    user_name:username,
                    user_nonce_registration_submit:mofrregistrationform.nonce,
                    user_email: useremail,
                    user_pass:password,
                },
                crossDomain: !0,
                dataType: "json",
                success: function(o){
                    if (o.result=='success') {
                        $mo("#mofrreg_validation_message").empty();
                        $mo("#mofrreg_validation_message").attr("class","mofr-success-container");
                        $mo("#mofrreg_validation_message").append("Thanks for registering!! Redirecting.");
                        window.location.replace(mofrregistrationform.redirecturl);
                    }
                    else{
                        $mo("#mofrreg_validation_message").empty();
                        $mo("#mofrreg_validation_message").append(o.message);
                        $mo("#mofrreg_validation_message").attr("class", "mofr-err-container");
                    }
                },
                error:function(o,n,e){
                }
            });

        });

        setTimeout(function(){
            $mo("#miniorange_otp_token_verify").click(function(){
                verifyCode();
            });
        },5000);

    });
});

function verifyCode(){

  otp_token=$mo("#mo_otp_field").val();
  var phone_number = $mo("#mofr_reg_phone").val();
  $mo("#mofrreg_sendotp_message").show();

  $mo.ajax({

    type : "POST",
    url  : mofrregistrationform.siteURL,
    data : {
            action: "mo_firebase_verify_otp",
            otp_token: otp_token,
            user_phone: phone_number,
            user_nonce_registration_verifyotp : mofrregistrationform.nonce,
            recapchaToken : getCookieValue("recapchaToken")
           },
    crossDomain:true,
    success: function(response) {
      if(response.result == "success") {

        $mo("#mofrreg_sendotp_message").empty();
        $mo("#miniorange_otp_token_submit_captcha").hide();
        $mo("#mofrreg_sendotp_message").hide();
        $mo("#mo_otp_field").hide();
        $mo("#mofrreg_sendotp_message").text("Phone Number has been successfully verified");
        $mo("#miniorange_otp_token_verify").val('Phone Number has been successfully verified').attr('disabled',true);
      }
      else {
        $mo("#mofrreg_sendotp_message").empty();
        $mo("#mofrreg_sendotp_message").attr("class", "mofr-err-container");
        $mo("#mofrreg_sendotp_message").text(response.message);
      }
    },
    error:function(o){
      
    }
  })   
}