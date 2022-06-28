<?php

namespace MoOTP\Helper;

use MoOTP\Objects\BaseMessages;
use MoOTP\Traits\Instance;

if(! defined( 'ABSPATH' )) exit;

/**
 * This is the constant class which lists all the messages
 * to be shown in the plugin.
 */
final class MoMessages extends BaseMessages
{
	use Instance;

    function __construct()
	{   
		/** created an array instead of messages instead of constant variables for Translation reasons. */
		define("MOFR_MESSAGES", serialize( array(

			//General Messages
			self::GLOBALLY_INVALID_PHONE_FORMAT       => mofr_("##phone## is not a Globally valid phone number. 
			                                            Please enter a valid Phone Number." ),
						self::INVALID_SCRIPTS       => mofr_("You cannot add script tags in the pop up template." ),
			
			self::OTP_SENT_PHONE                => mofr_("A OTP (One Time Passcode) has been sent to ##phone##.
			                                            Please enter the OTP in the field below to verify your phone." ),

			self::OTP_SENT_EMAIL                => mofr_("A One Time Passcode has been sent to ##email##. 
			                                            Please enter the OTP below to verify your Email Address. 
			                                            If you cannot see the email in your inbox, make sure to check 
			                                            your SPAM folder." ),

            self::ERROR_OTP_EMAIL               => mofr_("There was an error in sending the OTP. 
			                                            Please enter a valid email id or contact site Admin." ),

            self::ERROR_OTP_PHONE               => mofr_("There was an error in sending the OTP to the given Phone.
                                                        Number. Please Try Again or contact site Admin." ),

            self::ERROR_PHONE_FORMAT            => mofr_("##phone## is not a valid phone number. 
			                                            Please enter a valid Phone Number. E.g:+1XXXXXXXXXX" ),

            self::ERROR_EMAIL_FORMAT            => mofr_("##email## is not a valid email address. 
                                                        Please enter a valid Email Address. E.g:abc@abc.abc"),

            self::CHOOSE_METHOD                 => mofr_("Please select one of the methods below to verify your account. 
                                                        A One time passcode will be sent to the selected method." ),

			self::PLEASE_VALIDATE               => mofr_("You need to verify yourself in order to submit this form" ),

			self::ERROR_PHONE_BLOCKED           => mofr_("##phone## has been blocked by the admin. 
                                                        Please Try a different number or Contact site Admin." ),

			self::ERROR_EMAIL_BLOCKED           => mofr_("##email## has been blocked by the admin. 
			                                            Please Try a different email or Contact site Admin." ),

            self::REGISTER_WITH_US              => mofr_("<a href='{{url}}'>Register or Login with miniOrange</a> 
                                                        to enable OTP Verification"),

            self::ACTIVATE_PLUGIN              => mofr_("<a href='{{url}}'>Complete plugin activation process</a> 
                                                        to enable OTP Verification"),

            self::CONFIG_GATEWAY              =>  mofr_("<a href='{{url}}'>Please Configure Gateway </a> 
                                                             to enable OTP Verification"),  
            
			//ToolTip Messages
			self::FORM_NOT_AVAIL_HEAD    	    => mofr_("MY FORM IS NOT IN THE LIST" ),

			self::FORM_NOT_AVAIL_BODY    	    => mofr_("We are actively adding support for more forms. Please contact 
                                                        us using the support form on your right or email us at 
                                                        <a style='cursor:pointer;' onClick='otpSupportOnClick();'><font color='white'><u>".esc_attr(MoConstants::FEEDBACK_EMAIL)."</u>.</font></a> While contacting us please include
                                                        enough information about your registration form and how you
                                                        intend to use this plugin. We will respond promptly." ),

			self::CHANGE_SENDER_ID_BODY         => mofr_("SenderID/Number is gateway specific. 
			                                            You will need to use your own SMS gateway for this." ),

			self::CHANGE_SENDER_ID_HEAD         => mofr_("CHANGE SENDER ID / NUMBER" ),

			self::CHANGE_EMAIL_ID_BODY          => mofr_("Sender Email is gateway specific. 
			                                            You will need to use your own Email gateway for this." ),

			self::CHANGE_EMAIL_ID_HEAD          => mofr_("CHANGE SENDER EMAIL ADDRESS" ),

  

			//Support Query Messages
			self::SUPPORT_FORM_VALUES    	    => mofr_("Please submit your query along with email." ),

			self::SUPPORT_FORM_SENT  	        => mofr_("Thanks for getting in touch! We shall get back to you shortly." ),

			self::SUPPORT_FORM_ERROR     	    => mofr_("Your query could not be submitted. Please try again." ),

			//Feedback
            self::FEEDBACK_SENT                 => mofr_("Thank you for your feedback."),

            self::FEEDBACK_ERROR                => mofr_("Your feedback couldn't be submitted. Please try again"),

			//Setting Messages
			self::SETTINGS_SAVED     		    => mofr_("Settings saved successfully. 
			                                            You can go to your registration form page to test the plugin." ),

			self::REG_ERROR  			        => mofr_("Please register an account before trying to enable 
			                                            OTP verification for any form." ),

			self::MSG_TEMPLATE_SAVED     	    => mofr_("Settings saved successfully." ),

			self::SMS_TEMPLATE_SAVED     	    => mofr_("Your SMS configurations are saved successfully." ),

			self::SMS_TEMPLATE_ERROR            => mofr_("Please configure your gateway URL correctly."),

			self::EMAIL_TEMPLATE_SAVED   	    => mofr_("Your email configurations are saved successfully." ),

			self::CUSTOM_MSG_SENT    		    => mofr_("Message sent successfully" ),

			self::CUSTOM_MSG_SENT_FAIL   	    => mofr_("Error sending message." ),

			self::EXTRA_SETTINGS_SAVED          => mofr_("Settings saved successfully." ),

			
			//Common AJAX Form Error Messages
			


			//Registration Messages
			self::PASS_LENGTH    			    => mofr_("Choose a password with minimum length 6." ),

			self::PASS_MISMATCH  		        => mofr_("Password and Confirm Password do not match." ),

			self::OTP_SENT   				    => mofr_("A passcode has been sent to {{method}}. Please enter the otp
                                                        below to verify your account." ),

			self::ERR_OTP    				    => mofr_("There was an error in sending OTP. Please click on Resend 
                                                        OTP link to resend the OTP." ),

			self::REG_SUCCESS    			    => mofr_("Your account has been retrieved successfully." ),

			self::ACCOUNT_EXISTS     		    => mofr_("You already have an account with miniOrange. 
			                                            Please enter a valid password." ),

			self::REG_COMPLETE   			    => mofr_("Registration complete!" ),

			self::INVALID_OTP    			    => mofr_("Invalid one time passcode. Please enter a valid passcode." ),

			self::RESET_PASS     			    => mofr_("You password has been reset successfully and sent to your
                                                        registered email. Please check your mailbox." ),

			self::REQUIRED_FIELDS    		    => mofr_("Please enter all the required fields" ),

            self::REQUIRED_OTP   			    => mofr_("Please enter a value in OTP field." ),

            self::INVALID_SMS_OTP    		    => mofr_("There was an error in sending sms. Please Check your phone number." ),

            self::NEED_UPGRADE_MSG   		    => mofr_("You have not upgraded yet. 
                                                        Check licensing tab to upgrade to premium version." ),

            self::VERIFIED_LK    			    => mofr_("Your license is verified. You can now setup the plugin." ),

            self::LK_IN_USE 				    => mofr_("License key you have entered has already been used. Please 
                                                        enter a key which has not been used before on any other
                                                        instance or if you have exhausted all your keys then check 
                                                        licensing tab to buy more." ),

			self::INVALID_LK     			    => mofr_("You have entered an invalid license key. 
			                                            Please enter a valid license key." ),

			self::REG_REQUIRED   			    => mofr_("Please complete your registration to save configuration." ),

			//common messages
			self::UNKNOWN_ERROR  		        => mofr_("Error processing your request. Please try again." ),
			self::INVALID_OP                    => mofr_( "Invalid Operation. Please Try Again"),

			self::MO_REG_ENTER_PHONE     	    => mofr_("Phone with country code eg. +1xxxxxxxxxx" ),

			//License Messages
			self::UPGRADE_MSG    			    => mofr_("Thank you. You have upgraded to {{plan}}." ),
			self::REMAINING_TRANSACTION_MSG    	=> mofr_("Thank you. You have upgraded to {{plan}}. <br>You have <b>{{sms}}</b> SMS and <b>{{email}}</b> Email remaining." ),

			self::FREE_PLAN_MSG  		        => mofr_("You are on our FREE plan. Check Licensing Tab to learn how to upgrade." ),

			self::TRANS_LEFT_MSG     		    => mofr_("You have <b><i>{{email}} Email Transactions</i></b> and
                                                        <b><i>{{phone}} Phone Transactions</i></b> remaining." ),

			self::YOUR_GATEWAY_HEADER           => mofr_("WHAT DO YOU MEAN BY CUSTOM GATEWAY? WHEN DO I OPT FOR THIS PLAN?" ),

            self::YOUR_GATEWAY_BODY     	    => mofr_("Custom Gateway means that you have your own SMS or Email 
                                                        Gateway for delivering OTP to the user's email or phone. 
                                                        The plugin will handle OTP generation and verification but 
                                                        your existing gateway would be used to deliver the message to 
                                                        the user. <br/><br/>Hence, the One Time Cost of the plugin. 
                                                        <b><i>NOTE:</i></b> You will still need to pay SMS and Email 
                                                        delivery charges to your gateway separately." ),

			self::MO_GATEWAY_HEADER     	    => mofr_("WHAT DO YOU MEAN BY miniOrange GATEWAY? WHEN DO I OPT FOR THIS PLAN?" ),

            self::MO_GATEWAY_BODY       	    => mofr_("miniOrange Gateway means that you want the complete package 
                                                        of OTP generation, delivery ( to user's phone or email ) and
                                                        verification. Opt for this plan when you don't have your own 
                                                        SMS or Email gateway for message delivery. <br/><br/>
                                                        <b><i>NOTE:</i></b> SMS Delivery charges depend on the 
                                                        country you want to send the OTP to. Click on the Upgrade
                                                        Now button below and select your country to see the full pricing." ),
			self::INSTALL_PREMIUM_PLUGIN        => mofr_("You have Upgraded to the Firebase Gateway Plan. You will need to 
			                                            install the premium plugin from the 
			                                            <a href='".esc_attr(MoConstants::HOSTNAME)."/moas/viewpaymenthistory'>
			                                            miniOrange dashboard</a>."),
			self::MO_PAYMENT     	    => mofr_("Payment Methods which we support" ),

		

			//for onprem plugin
			self::DEFAULT_SMS_TEMPLATE          => mofr_("Dear Customer, Your OTP is ##otp##. Use this Passcode to
                                                        complete your transaction. Thank you." ),

			self::EMAIL_SUBJECT  		        => mofr_("Your Requested One Time Passcode" ),

			self::DEFAULT_EMAIL_TEMPLATE        => mofr_("Dear Customer, \n\nYour One Time Passcode for completing 
                                                        your transaction is: ##otp##\nPlease use this Passcode to
                                                        complete your transaction. Do not share this Passcode with 
                                                        anyone.\n\nThank You,\nminiOrange Team." ),

            //Common AddOn Messages
            self::ADD_ON_VERIFIED               => mofr_('Thank you for the upgrade. AddOn Settings have been verified.'),

            self::INVALID_PHONE  		        => mofr_('Please enter a valid phone number'),

            self::ERROR_SENDING_SMS  	        => mofr_('There was an error sending SMS to the user'),

            self::SMS_SENT_SUCCESS   		    => mofr_('SMS was sent successfully.'),

          
            self::REGISTRATION_ERROR            => mofr_( "There is some issue proccessing the request. Please try again or contact us at <b><i><a style='cursor:pointer;' onClick='otpSupportOnClick();'> <u>otpsupport@xecurify.com</u></a></i></b> to know more. "),
            self::MOFR_FORGOT_PASSWORD_MESSAGE             => mofr_( "Please<a href='https://login.xecurify.com/moas/idp/resetpassword ' target='_blank'> Click here </a>to reset your password"),

			self::CUSTOM_CHOOSE     			    => mofr_("Please choose a Verification Method for Your Own Form." ),

			self::CUSTOM_PACKS						=> mofr_("<a href='/wp-admin/admin.php?page=pricing&subpage=custpackage'>Checkout out our new impressive <b>Cover-Your-Site Packages</b>. All-in-one WooCommerce package, Ultimate Member Package, Login/Register with Phone number and many more.</a><input type='button' class='button button-primary button-large' value='Upgrade' id='update_custom_packages_button' style='background:orange;color:black'/>" ),
			// self::GATEWAY_PARAM_NOTE				=> mofr_("You will need to place your SMS gateway URL in the field above in order to be 
   //                                          able to send OTPs to the user's phone.<br/>Example:- http://alerts.sinfini.com/api/web2sms.php?<br/>username=XYZ&password=password&to=##phone##&sender=senderid&message=##message##"),
			self::GATEWAY_PARAM_NOTE				=> mofr_("You will need to place your SMS gateway URL in the field above in order to be 
                                            able to send OTPs to the user's phone.<br>Example:-http://alerts.sinfini.com/api/web2sms.php?username=XYZ&password=password& to=##phone##&sender=senderid& message=##message##"),
			self::CUSTOM_FORM_MESSAGE     => mofr_("<b>Your test was succesful!</b> <br> Please contact us at <a style='cursor:pointer;' href='mailto:otpsupport@xecurify.com'>otpsupport@xecurify.com</a> for full integration of your form." ),

        )));
	}



    /**
     * This function is used to fetch and process the Messages to
     * be shown to the user. It was created to mostly show dynamic
     * messages to the user.
     *
     * @param string    $messageKeys    Message Key
     * @param array     $data           The  key value pair to be replaced in the message
     *
     * @return string
     */
	public static function showMessage($messageKeys , $data=array())
	{
		$displayMessage = "";
		$messageKeys = explode(" ",$messageKeys);
		$messages = unserialize(MOFR_MESSAGES);
		foreach ($messageKeys as $messageKey)
		{
			if(MoUtility::is_blank($messageKey)) return $displayMessage;
			$formatMessage = mofr_($messages[$messageKey]);
		    foreach($data as $key => $value)
		    {
		        $formatMessage = str_replace("{{" . $key . "}}", $value ,$formatMessage);
		    }
		    $displayMessage.=$formatMessage;
		}
	    return $displayMessage;
	}
}