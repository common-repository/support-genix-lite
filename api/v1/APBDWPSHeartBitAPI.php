<?php
/**
 * @since: 12/07/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class APBDWPSHeartBitAPI extends Apbd_WPS_API_Base
{
    function setAPIBase()
    {
        return 'system';
    }

    function routes()
    {
        $this->RegisterRestRoute('GET', 'heart-bit', [$this, "heart_bit"]);
    }

    function SetRoutePermission($route)
    {
        return true;
    }

    function heart_bit()
    {
        $resdata = new stdClass();
        $resdata->is_change = false;
        $resdata->is_logged_in = is_user_logged_in();
        $resdata->ticket_stat = null;
        $resdata->settings = new stdClass();
        $resdata->settings->custom_fields = Mapbd_wps_custom_field::getCustomFieldForAPI();
        $resdata->settings->custom_fields = apply_filters('apbd-wps/filter/before-custom-get', $resdata->settings->custom_fields);
        $resdata->settings->categories = Mapbd_wps_ticket_category::getAllCategories();
        $resdata->is_agent = false;
        $resdata->id = "";
        $fs = new stdClass();
        $fs->allow_upload = Apbd_wps_settings::GetModuleOption("ticket_file_upload", 'A') == 'A';
        $fs->maxsize = (float)Apbd_wps_settings::GetModuleOption("file_upload_size", 2.0);
        $fs->allowed_exts = Apbd_wps_settings::GetModuleAllowedFileType();
        $resdata->settings->file_settings = $fs;

        if ($resdata->is_logged_in) {
            $resdata->id = $this->get_current_user_id();
            $resdata->ticket_stat = Mapbd_wps_ticket::getTicketStat();
            $resdata->ticket_rcount = (int)Mapbd_wps_ticket_reply::FetchCountAll();
            $resdata->user = get_user_by('id', $this->get_current_user_id())->display_name;
            $resdata->notification = Mapbd_wps_notification::getUnseenNotification($this->get_current_user_id());
            $resdata->is_agent = Apbd_wps_settings::isAgentLoggedIn($this->get_current_user_id());
        } else {
            $resdata->user = null;
        }
        $resdata->settings->captcha = Apbd_wps_settings::GetCaptchaSetting();
        $this->response->SetResponse(true, 'pulse', $resdata);
        return $this->response;
    }

}