<?php
/**
 * @since: 12/07/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class APBDWPSAPIConfig extends Apbd_WPS_API_Base
{
    function setAPIBase()
    {
        return 'basic';
    }

    function routes()
    {
        $this->RegisterRestRoute('GET', 'settings', [$this, "basic_settings"]);
        $this->RegisterRestRoute('POST', 'test-echo', [$this, "test_echo"]);
        $this->RegisterRestRoute('POST', 'is-valid-custom-field', [$this, "is_valid_cf"]);
    }

    function test_echo()
    {
        $this->response->SetResponse(false, "Updated failed", "");
        return $this->response;
    }

    function basic_settings()
    {
        $settings = new stdClass();
        $ticket = new Mapbd_wps_ticket();
        $settings->ticket_status_list = $ticket->GetPropertyRawOptions('status');
        $settings->custom_fields = Mapbd_wps_custom_field::getCustomFieldForAPI();
        $settings->custom_fields = apply_filters('apbd-wps/filter/before-custom-get', $settings->custom_fields);
        $settings->categories = Mapbd_wps_ticket_category::getAllCategories();
        $settings->logged_user = null;
        global $getUser;
        $getUser = wp_get_current_user();
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $loggedUserData = new stdClass();
            $loggedUserData->id = $user->ID;
            $loggedUserData->username = $user->user_login;
            $loggedUserData->email = $user->user_email;
            $loggedUserData->name = $user->first_name . ' ' . $user->last_name;
            $loggedUserData->loggedIn = is_user_logged_in();
            $loggedUserData->isAgent = Apbd_wps_settings::isAgentLoggedIn();
            if (empty(trim($loggedUserData->name))) {
                $loggedUserData->name = $user->display_name;
            }
            $loggedUserData->caps = Mapbd_wps_role::SetCapabilitiesByRole($user->caps, $user);
            $loggedUserData->img = get_user_meta($user->ID, 'supportgenix_avatar') ? get_user_meta($user->ID, 'supportgenix_avatar') : get_avatar_url($user->ID);
            $loggedUserData = apply_filters('apbd-wps/filter/logged-user', $loggedUserData, $user);
            $settings->logged_user = $loggedUserData;
        }
        $fs = new stdClass();
        $fs->allow_upload = Apbd_wps_settings::GetModuleOption("ticket_file_upload", 'A') == 'A';
        $fs->maxsize = (float)Apbd_wps_settings::GetModuleOption("file_upload_size", 2.0);
        $fs->allowed_exts = Apbd_wps_settings::GetModuleAllowedFileType();
        $settings->file_settings = $fs;
        $settings->captcha = Apbd_wps_settings::GetCaptchaSetting();

        $publicTicketOpt = new stdClass();
		$publicTicketOpt->on_creation = Apbd_wps_settings::GetModuleOption("is_public_ticket_opt_on_creation",'N');
		$publicTicketOpt->on_details = Apbd_wps_settings::GetModuleOption("is_public_ticket_opt_on_details",'N');
		$settings->public_ticket_opt = $publicTicketOpt;
		$settings->public_tickets_menu = Apbd_wps_settings::GetModuleOption("is_public_tickets_menu",'N');

        $settings = apply_filters('apbd-wps/filter/settings-data', $settings);
        $this->response->SetResponse(true, "", $settings);
        return $this->response;
    }

    function SetRoutePermission($route)
    {
        return true;
    }

    function is_valid_cf()
    {
        $fieldName = $this->GetPayload("fld_name", "");
        $fieldvalue = $this->GetPayload("fld_value", "");
        $fieldStatus = apply_filters('apbd-wps/filter/custom-field-validate', true, $fieldName, $fieldvalue);
        $msg = trim(APBD_GetMsg_API());
        if (empty($msg) && !$fieldStatus) {
            $msg = Apbd_wps_settings::GetModuleInstance()->__("Invalid input");
        }
        $this->response->SetResponse($fieldStatus, $msg, null);
        return $this->response;
    }
}