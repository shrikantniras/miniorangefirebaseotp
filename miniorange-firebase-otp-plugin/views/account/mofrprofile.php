<?php

echo ' 
<div class="mofr_registration_divided_layout mofr-otp-offset-md-2 mofr-otp-col-md-8">
	<div class="mofr_registration_table_layout">
	<div class="mofr-otp-row" style="text-align:center; margin-top:10px"><div class="mofr-otp-col-md-12"><h4 class="mofr-otp-h4">Thank you for registering with us.</h4></div></div><hr>
    <div class="mofr-otp-row" style="align-items: center;">
		<div class="mofr-otp-col-md-6"><h3 class="mofr-otp-h3">'.esc_attr(mofr_("Your Profile")).'</h3></div>
        <span style="width:50%;float:left;text-align:right;margin: 1em 0 1.33em 0" class="mofr-otp-col-md-6">
            <input  type="button" '.esc_attr($disabled).' 
                    name="check_btn" 
                    id="check_btn" 
                    class="mofr-otp-btn mofr-otp-btn-info"
                    value="'.esc_attr(mofr_("Check License")).'"/>
            <input  type="button" '.esc_attr($disabled).' 
                    name="remove_accnt" 
                    id="remove_accnt" 
                    class="mofr-otp-btn mofr-otp-btn-info"
                    value="'.esc_attr(mofr_("Remove Account")).'"/>
        </span>
	</div>
		<table border="1" class="profile-table">
			<tr>
				<td style="width:45%; padding: 10px;"><b>'.esc_attr(mofr_("Registered Email")).'</b></td>
				<td style="width:55%; padding: 10px;">'.esc_attr($admin_email).'</td>
			</tr>
			<tr>
				<td style="width:45%; padding: 10px;"><b>'.esc_attr(mofr_("Customer ID")).'</b></td>
				<td style="width:55%; padding: 10px;">'.esc_attr($customer_id).'</td>
			</tr>
			<tr>
				<td style="width:45%; padding: 10px;"><b>'.esc_attr(mofr_("API Key")).'</b></td>
				<td style="width:55%; padding: 10px;">'.esc_attr($api_key).'</td>
			</tr>
			<tr>
				<td style="width:45%; padding: 10px;"><b>'.esc_attr(mofr_("Token Key")).'</b></td>
				<td style="width:55%; padding: 10px;">'.esc_attr($token).'</td>
			</tr>
		</table><br/>
		<form id="mo_ln_form" style="display:none;" action="" method="post">';
			wp_nonce_field( $nonce );
echo		'<input type="hidden" name="option" value="mofr_check_mo_ln" />
		</form>
		<form id="remove_accnt_form" style="display:none;" action="" method="post">';
			wp_nonce_field( $regnonce );
echo'		<input type="hidden" name="option" value="mofr_remove_account" />
		</form>
		<br/>
	</div>
</div>';