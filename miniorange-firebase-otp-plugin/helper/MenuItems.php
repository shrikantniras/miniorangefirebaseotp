<?php

namespace MoOTP\Helper;

if(! defined( 'ABSPATH' )) exit;

use MoOTP\MoOTPInitializer;
use MoOTP\Objects\PluginPageDetails;
use MoOTP\Objects\TabDetails;
use MoOTP\Traits\Instance;


final class MenuItems
{
    use Instance;

    
    private $_callback;

    
    private $_menuSlug;

    
    private $_menuLogo;

    
    private $_tabDetails;

    
    private function __construct()
    {
        $this->_callback  = [  MoOTPInitializer::instance(), 'mo_customer_validation_options' ];
        $this->_menuLogo  =  MOFLR_ICON;
        
        $tabDetails = TabDetails::instance();
        $this->_tabDetails = $tabDetails->_tabDetails;
        $this->_menuSlug = $tabDetails->_parentSlug;
        $this->add_main_menu();
        $this->add_sub_menus();
    }

    private function add_main_menu()
    {
        add_menu_page (
            'Firebase Login/Registation' ,
            'Firebase Login/Registation' ,
            'manage_options',
            $this->_menuSlug ,
            $this->_callback,
            $this->_menuLogo
        );
    }

    private function add_sub_menus()
    {
        
        foreach ($this->_tabDetails as $tabDetail) {
            
            add_submenu_page(
                $this->_menuSlug,
                $tabDetail->_pageTitle,
                $tabDetail->_menuTitle,
                'manage_options',
                $tabDetail->_menuSlug,
                $this->_callback
            );
        }
    }
}