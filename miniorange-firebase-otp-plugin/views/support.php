<?php


echo'	<div class="mofr-modal-backdrop">
	        <div class="mofr_customer_validation-modal-backdrop mofr-otp-hidden">
            </div>
            <div class="mofr_registration_support_layout fixed mofr-otp-hidden" id="support_form">
                <h3>'.esc_attr(mofr_("CONTACT US")).'</h3>
                <p>'.esc_attr(mofr_("Need any help? Just send us a query and we will get in touch.")).'</p>
                    <form name="f" method="post" action="">
                        <input type="hidden" name="option" value="mofr_validation_contact_us_query_option"/>
                            <div class="mofr_support_input_container mofr_support_input_half">
                                <span class="mofr_support_input_label">Your Email</span>
                                <input type="email" class="mofr_support_input" id="query_email" name="query_email" value="'.esc_attr($email).'"
                                    placeholder="'.esc_attr(mofr_("Enter your Email")).'" required />
                            </div>
                            <div class="mofr_support_input_container mofr_support_input_half">
                                <span class="mofr_support_input_label">Your Phone Number</span>
                                <input type="text" class="mofr_support_input" name="query_phone" id="query_phone" value="'.esc_attr($phone).'"
                                    placeholder="'.esc_attr(mofr_("Enter your phone")).'"/>
                            </div>
                            <div class="mofr_support_input_container">
                                <span class="mofr_support_input_label">Your Query</span>
                                    <textarea id="query" name="query" class="mofr_support_input"
                                        style="resize: vertical;width:100%" cols="52" rows="7"
                                        onkeyup="mo_registration_valid_query(this)" onblur="mo_registration_valid_query(this)" 
                                        onkeypress="mo_registration_valid_query(this)" 
                                        placeholder="'.esc_attr(mofr_("Write your query here...")).'"></textarea>
                            </div>
                        <input type="submit" name="send_query" id="send_query" value="'.esc_attr(mofr_("Submit Query")). '" 
                                class="mofr_support_button" />
                    </form>
                    <p>You can also leave us a mail at <span class = "mofr_support_input_label_highlight">'.esc_attr($support).'</span></p>
            </div>
        </div>

		<script>
			function moSharingSizeValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:10>t&&(e.value=10)
			}
			function moSharingSpaceValidate(e){
				var t=parseInt(e.value.trim());t>50?e.value=50:0>t&&(e.value=0)
			}
			function moLoginSizeValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:20>t&&(e.value=20)
			}
			function moLoginSpaceValidate(e){
				var t=parseInt(e.value.trim());t>60?e.value=60:0>t&&(e.value=0)
			}
			function moLoginWidthValidate(e){
				var t=parseInt(e.value.trim());t>1000?e.value=1000:140>t&&(e.value=140)
			}
			function moLoginHeightValidate(e){
				var t=parseInt(e.value.trim());t>50?e.value=50:35>t&&(e.value=35)
            }
            jQuery(document).ready(function(){
                let sel = jQuery(".mofr_support_input_container");
                sel.each(function(){
                    if(jQuery(this).find(".mofr_support_input").val() !== "")
                    jQuery(this).addClass("mofr_has_value");
                });
                sel.focusout( function(){
                    if(jQuery(this).find(".mofr_support_input").val() !== "")
                        jQuery(this).addClass("mofr_has_value");
                    else jQuery(this).removeClass("mofr_has_value");
                });
            });
		</script>';