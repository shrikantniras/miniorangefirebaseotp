<?php

echo'	<div class="wrap">
			<div><img style="float:left;" src="'.esc_attr(MOFLR_LOGO_URL).'"></div>
			<div class="mofr-otp-header">
				'.esc_attr(mofr_("Firebase Login/Registration"));

echo'	<div id="tab">
            <br>
			<nav class="mofr-otp-navbar mofr-otp-navbar-expand-md mofr-otp-navbar-light mofr-otp-bg-light" style="background-color:#f0f0f1!important;">
            <ul class="mofr-otp-nav-tab-wrapper mofr-otp-navbar-nav mofr-otp-nav-tabs mofr-otp-mr-auto">';

        
        foreach ($tabDetails->_tabDetails as $tabs)
        {
            if($tabs->_showInNav) {
                echo '<li class="mofr-otp-nav-item" style="border-top-left-radius:4px; border-top-right-radius:4px;margin-right:4px"><b><a  class="mofr-otp-nav-link
                        ' . (esc_attr($active_tab) === esc_attr($tabs->_menuSlug) ? 'mofr-otp-active mofr-otp-fadeDown' : '') . '"
                        href="' .esc_attr( $tabs->_url ). '"
                        style="font-size:18px;background:#e0e0e0;"
                        id="' .esc_attr( $tabs->_id ). '">
                        ' . esc_attr( $tabs->_tabName ). '
                    </a></b></li>';
            }
        }

        echo '</ul></nav>';

        if(!esc_attr($registered)) {
            echo '<div style="margin-left:1%; margin-bottom:0px" class="mofr-otp-alert mofr-otp-alert-danger" role="alert">
                        <h6 class="mofr-otp-h6">' .$registerMsg.'</h6>
                  </div>';
        }else if(!esc_attr($activated)) {
            echo '<div style="margin-left:1%; margin-bottom:0px" class="mofr-otp-alert mofr-otp-alert-danger mofr-otp-row">
                        <h6 class="mofr-otp-h6">' .esc_attr($activationMsg).'</h6>
                  </div>';
        }