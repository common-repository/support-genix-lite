<?php
/**
 * @since: 25 March 2024
 * @author: Nazmul Huda
 */

class Apbd_wps_mailbox extends AppsBDLiteModule {

    function initialize() {
        parent::initialize();
    }

    function SettingsPage() {
        $this->Display();
    }

    function GetMenuTitle() {
        return $this->__( 'Email to Ticket' );
    }

    function GetMenuSubTitle() {
        return $this->__( 'Email Piping Settings' );
    }

    function GetMenuIcon() {
        return "fa fa-envelope";
    }

    function GetMenuCounter() {
        return '<span class="apbd-tab-counter badge badge-danger animated ani-count-3 delay-1s slower flash">' . $this->__( 'Pro' ) . '</span>';
    }

}