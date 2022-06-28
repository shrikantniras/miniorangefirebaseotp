function initializeFirebaseScript(formElem,defaultsubmitbuttonselector,sendOTPbuttonselector,phoneNumberFieldId,formsubmitselector){
  
  jQuery(document).ready(function () {

    $mo = jQuery;
    
    
    var buttonVal = $mo(sendOTPbuttonselector).val();
    var buttonClass = $mo(sendOTPbuttonselector).attr("class");

    var moRecaptchaSendOtpTokenButton = "<input type='button' id='"+defaultsubmitbuttonselector+"'"+
                                " class='"+buttonClass+"' value='"+buttonVal+"' "+
                                "style='width: 100%; margin-right: 0px;'>";

    let img = '<center><div style="display:table;text-align:center;">'+
                '<img style="display:table;" alt="Loading..." src="'+mofirebaseauth.imgURL+'">' +
              '</div></center>';

    $mo(sendOTPbuttonselector).hide();
    $mo(moRecaptchaSendOtpTokenButton).insertAfter(sendOTPbuttonselector);

     moVerifyButtonVal="Verify OTP";
   

    var recapDiv = document.createElement('div');
    recapDiv.setAttribute("id", "recaptcha-container");

    $mo(formElem).each(function(){
      
      if(jQuery(formsubmitselector).length>0){
        $mo(recapDiv).insertAfter(formsubmitselector);
      }
      
    });
      
      firebaseConfig = {
        apiKey: mofirebaseauth.firebasegatewaydetails['gateway_apiKey'],//"AIzaSyBgQ5fuluF9e8opR395ZTWAz5JNeFQUCdk",
        authDomain: mofirebaseauth.firebasegatewaydetails['gateway_apiKey'],//"checing.firebaseapp.com",
        databaseURL: mofirebaseauth.firebasegatewaydetails['gateway_apiKey'],//"testusrl",
        projectId: mofirebaseauth.firebasegatewaydetails['gateway_apiKey'],//"checing",
        storageBucket:mofirebaseauth.firebasegatewaydetails['gateway_apiKey'],//"checing.appspot.com",
        messagingSenderId:mofirebaseauth.firebasegatewaydetails['gateway_apiKey'],//"1087831766460", 
        appId: mofirebaseauth.firebasegatewaydetails['gateway_apiKey'],//"1:1087831766460:web:5b515d15872246acce67b9"
      }

      $mo(".mofr_signin_using_phone").click(function(){
        $mo("#mofr-current-tab").val("tab-signin");
        getRecapchaToken("form#mofr_phone_otp_login_form",firebaseConfig,"mofr_loginwithotp_sendotp_captcha");   //defaultsubmitbuttonselector this is correct
      });

      $mo("#tab-signup").click(function(){
        $mo("#mofr-current-tab").val("tab-signup");
        getRecapchaToken("form#moregistrationform",firebaseConfig,"miniorange_otp_token_submit_captcha");   //defaultsubmitbuttonselector this is correct
      });


      var reset=true;
      $mo("#"+defaultsubmitbuttonselector).click(function(){ // added from this js
        if ($mo("#mofr-current-tab").val()=="tab-signin") {
          $mo("#mofr_loginwithotp_message").empty();
          $mo("#mofr_loginwithotp_message").show();
          $mo("#mofr_loginwithotp_message").append(img);
        }
        else{
          $mo("#mofrreg_sendotp_message").empty();
          $mo("#mofrreg_sendotp_message").show();
          $mo("#mofrreg_sendotp_message").append(img);
        }

        if(reset)
          grecaptcha.reset(); 
        console.log("outside setTimeout");
        setTimeout(function(){
          reset = false;
          console.log($mo("#mofr-current-tab").val());
          console.log("after val");
          if ($mo("#mofr-current-tab").val()=="tab-signin") {

            if($mo("form#mofr_phone_otp_login_form #moRecaptchaToken").val()!==""){
              $mo(sendOTPbuttonselector).click();
            }
            else{
              $mo("#"+defaultsubmitbuttonselector).click();
            }
          }
          else{
            if($mo("form#moregistrationform #moRecaptchaToken").val()!==""){
              $mo(sendOTPbuttonselector).click();
            }
            else{
              $mo("#"+defaultsubmitbuttonselector).click();
            }  
          }
        },4000);
      });
  });
}

function getRecapchaToken(formElem,firebaseConfig,defaultsubmitbuttonselector){ //defaultsubmitbuttonselector this is correct
  
  if (!firebase.apps.length)
    firebase.initializeApp(firebaseConfig);

      window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(defaultsubmitbuttonselector, {
        'size': 'invisible',
        'callback': function(response) {

          grecaptcha.reset(window.recaptchaWidgetId);
          document.cookie = "recapchaToken="+response+";path=/";
          $mo(formElem+" #moRecaptchaToken").val(response);
        },
        'expired-callback': function(r) {
            console.log("expired", r);
          }
      });

      window.recaptchaVerifier.render().then(function (widgetId) {
        window.recaptchaWidgetId = widgetId;
      }); 

}


function moverifyFirebaseCode(verifyField,phoneNumberFieldId,defaultsubmitbuttonselector,submitButton){
  otp_token=$mo("#"+verifyField).val();
  user_phone=$mo("#"+phoneNumberFieldId).val();

  $mo.ajax({
         type : "POST",
         url : firebaseAjax.ajaxurl,
         data : {action: "mo_firebase_verify_otp",
              otp_token: otp_token,
              user_phone:user_phone,
            recapchaToken : getCookieValue("recapchaToken")},
         crossDomain:true,
         success: function(response) {
            if(response.result == "success") {
              $mo("#mo_form_message").empty();
              $mo("#mo_form_message").text("Phone Number has been successfully verified");
              $mo("#mo_register_submit").val("Register and Login");
              $mo("#mo_reg_form_open").show();
              $mo("#mo-verify-div").hide();
              $mo("#"+phoneNumberFieldId).attr("disabled","disabled");
              $mo("#"+defaultsubmitbuttonselector).hide();
              $mo("#"+submitButton).show();
            }
            else if(response.result=="login"){
              $mo("#mo_form_message").hide();
              var url = window.location.hostname;
              window.location.replace(url+'/ash15/sample-page');
            }
            else {
            
               $mo("#mo_form_message").empty();
               $mo("#mo_form_message").text("Please verify your Phone Number again.");
            }
         },
         error:function(o){

              }
      })   
}

function getCookieValue(a) {
    var b = document.cookie.match('(^|[^;]+)\\s*' + a + '\\s*=\\s*([^;]+)');
    return b ? b.pop() : '';
}

