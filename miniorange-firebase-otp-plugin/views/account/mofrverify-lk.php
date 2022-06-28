<?php
echo '<div class="mofr_registration_divided_layout mofr-otp-full">
			<div class="mofr_registration_table_layout mo-otp-center">
			 <h3>
			 	'.esc_attr(mofr_("Verify Your License")).'
			 	<span style="float:right;margin-top:-10px;">
                    <a href="#goToLoginPage" class="button button-primary button-large">'.esc_attr(mofr_("Go Back")).'</a>
                </span>
			 </h3>
				<p>
					<div class="mofr_registration_help_title">[ '.esc_attr(mofr_("Where is my key?")).']</a></div>
					<div class="mofr_registration_help_desc mofr-otp-hidden">
						'.esc_attr(mofr_("You can find all your used and unused keys under the <i>View License Key</i> Section.")).' 
							<a href="'.esc_attr($url).'" target="_blank" >'.esc_attr(mofr_("Click Here")).'</a> '.esc_attr(mofr_("to see your keys.")).' 
					</div>
				</p>
								
				<form name="f" method="post" action="">';
                    wp_nonce_field($nonce);
echo'				<input type="hidden" name="option" value="mofr_registration_verify_license" />
					<table class="mofr_registration_settings_table">
						<tr>
							<td style="width:18%"><b><font color="#FF0000">*</font>License Key:</b></td>
							<td>
							    <input  class="mofr_registration_table_textbox"
							            required 
							            type="text"
								        name="email_lk" 
								        placeholder="'.esc_attr(mofr_("Enter your license key to activate the plugin")).'"/>
                            </td>
						</tr>
					</table>
					<br/>
					<div style="display:inline;">
						<b>Please read and accept the following before activating your license key :</b>
						<div class="mofr_registration_help_desc" style="margin-left:15px;">
							<div style="float:left;height:50px;margin-right:5px;"><input type="checkbox" id="lk_check1" value="1"/></div>
							<div>
							    <b><i>'.
                                    esc_attr(mofr_("License key you have entered here is associated with this site instance.")).
                                '</i></b> '.
                                esc_attr(mofr_("If you want to re-install the plugin for any reason, 
                                    you should deactivate and then delete the plugin from WordPress console. 
                                    Manually deleting the plugin folder will not free your key for re-use.")).'
                            </div>
							<br/>
							<div style="float:left;height:50px;margin-right:5px;"><input type="checkbox" id="lk_check2" value="1"/></div>
							<div>
							    <b><i>'.esc_attr(mofr_("This is not a developer license.</i></b> Making any kind of 
                                            change to the plugin\'s code will delete all your configuration and 
                                            make the plugin unusable.")).
                            '</div>
						</div>
					</div>
					<br/>
					<input  type="submit" 
					        name="submit" 
					        disabled="true" 
					        id="activate_plugin" 
					        value="'.esc_attr(mofr_("Activate License")).'" 
					        class="button button-primary button-large" />
                    <br><br> 
				</form>
			</div>
		  </div>
		  <form id="goToLoginPageForm" method="post" action="">';
                wp_nonce_field($nonce);
echo'	    	<input type="hidden" name="option" value="mofr_go_to_login_page" />
		  </form>
		  <script>
		    jQuery(document).ready(function(){	
				$mo(\'a[href="#goToLoginPage"]\').click(function(){
					$mo("#goToLoginPageForm").submit();
				});
			});
          </script>';