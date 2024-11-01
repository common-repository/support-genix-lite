<?php
/**
 * @since: 17/Aug/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_wps_role extends AppsBDLiteModule
{
    function initialize()
    {
        parent::initialize();
        $this->disableDefaultForm();
        $this->AddAjaxAction("add", [$this, "add"]);
        $this->AddAjaxAction("edit", [$this, "edit"]);
        $this->AddAjaxAction("delete_item", [$this, "delete_item"]);
        $this->AddAjaxAction("access-data", [$this, "access_data"]);
        $this->AddAjaxAction("status_change", [$this, "status_change"]);
        $this->AddAjaxAction("is_agent", [$this, "is_agent_status_change"]);
        $this->AddAjaxAction("acl-toggle", [$this, "acl_toggle"]);
        add_action('apbd-wps/action/role-added',[$this,"NewRoleAdded"]);
        add_action('apbd-wps/action/role-updated',[$this,"UpdatedRole"]);
        add_action('apbd-wps/action/role-deleted',[$this,"RemovedRole"]);

        // Add default access.
        add_action('apbd-wps/action/role-added',[$this,"AddDefaultAccess"]);
        add_action('apbd-wps/action/role-updated',[$this,"AddDefaultAccess"]);
    }

    function SettingsPage()
    {
        $this->SetTitle("User Role List");
        $this->Display();
    }

    public function OnInit()
    {
        parent::OnInit();
        add_filter('apbd-wps/acl-resource', [$this, 'default_resources']);
        add_filter('editable_roles',function($all_roles){
            $roles=Mapbd_wps_role::GetRoleListWithCapabilities();
            $capabilities=['level_0' => true,'read'=>true];
            if(!empty($all_roles['subscriber']['capabilities'])){
                $capabilities=$all_roles['subscriber']['capabilities'];
            }
            if(!empty($all_roles['administrator']['capabilities'])){
                $resource=Mapbd_wps_role_access::GetResourceList();
                foreach ($resource as $res) {
                    $all_roles['administrator']['capabilities'][$res->action_param]=true;
                }
            }
            foreach ($roles as $role) {
                if ($role->slug !== 'administrator') {
                    if(empty($role->capabilities)){
                        $role->capabilities=[];
                    }
                    $all_roles[$role->slug] = ["name" => $role->name, 'capabilities' => array_merge($capabilities,$role->capabilities)];
                }
            }

            return $all_roles;
        });
        add_filter('user_has_cap',function($all_caps,$caps, $args, $user){
            $all_caps= Mapbd_wps_role::SetCapabilitiesByRole($all_caps,$user);
            return $all_caps;
        },10,4);
    }

    public function OnActive( $new_activation = true, $new_pro_activation = true )
    {
        parent::OnActive( $new_activation, $new_pro_activation );

        Mapbd_wps_role::CreateDBTable();
        Mapbd_wps_role_access::CreateDBTable();
        Mapbd_wps_ticket_assign_rule::CreateDBTable();

        Mapbd_wps_role::SetDefaultRole();

        if ( ( true === $new_activation ) && ( true === $new_pro_activation ) ) {
            Mapbd_wps_ticket_assign_rule::SetDefaultAssignRole();
        }
    }

    function GetMenuTitle()
    {
        return $this->__("User Role");
    }

    function GetMenuSubTitle()
    {
        return $this->__("User Role Settings");
    }

    function GetMenuIcon()
    {
        return "fa fa-users";
    }

    /**
     * @param Mapbd_wps_role $role
     */
    function NewRoleAdded($role){
        if($role instanceof Mapbd_wps_role) {
            $existingRoles = wp_roles()->get_names();
            if ($role->is_editable=='Y' && !isset($existingRoles[$role->slug])) {
               add_role( $role->slug, $role->name, ['read' => true,'level_0' => true]);
            }
        }
    }
    function RemovedRole($role){
        if($role instanceof Mapbd_wps_role) {
            $existingRoles = wp_roles()->get_names();
            if ($role->is_editable=='Y' && isset($existingRoles[$role->slug])) {
                remove_role($role->slug);
            }
        }
    }
    function UpdatedRole($role_id){
        $role=Mapbd_wps_role::FindBy("id",$role_id);
        if(!empty($role)) {
            $existingRoles = wp_roles()->get_names();
            if ($role->is_editable=='Y') {
                if(isset($existingRoles[$role->slug])){
                    remove_role($role->slug);
                }
                if($role->status=="A") {
                    add_role($role->slug, $role->name, ['read' => true, 'level_0' => true]);
                }
            }
        }
    }
    function default_resources($resources){
	    //Ticket
	    $resources[]=ELITE_ACL_Resource::getResource("closed-ticket-list","Closed Ticket List","Ticket","");
	    $resources[]=ELITE_ACL_Resource::getResource("assign-me","Assign Me","Ticket","");
	    $resources[]=ELITE_ACL_Resource::getResource("ticket-reply","Ticket Reply","Ticket","");

	    //Ticket Details
	    //Edit Status
	    $resources[]=ELITE_ACL_Resource::getResource("edit-status","Change Status","Ticket Details","");
	    $resources[]=ELITE_ACL_Resource::getResource("change-privacy","Change Privacy","Ticket Details","");
	    $resources[]=ELITE_ACL_Resource::getResource("edit-assigned","Assign Agent","Ticket Details","");
	    $resources[]=ELITE_ACL_Resource::getResource("edit-category","Change Category","Ticket Details","");
	    $resources[]=ELITE_ACL_Resource::getResource("move-to-trash","Move to Trash","Ticket Details","");
	    $resources[]=ELITE_ACL_Resource::getResource("create-note","Create Note","Ticket Details","");
	    $resources[]=ELITE_ACL_Resource::getResource("show-ticket-email","Show Ticket User Email","Ticket Details","This permission will allow to see ticket user email in ticket details");
	    //Delete Ticket List

	    $resources[]=ELITE_ACL_Resource::getResource("trash-ticket-menu","Trashed Ticket List","Deleted Ticket","");
	    $resources[]=ELITE_ACL_Resource::getResource("restore-ticket","Restore Ticket","Deleted Ticket","");
	    $resources[]=ELITE_ACL_Resource::getResource("delete-ticket","Delete Ticket","Deleted Ticket","");


	    return $resources;
    }
    function add()
    {
        $this->SetTitle("Add New Role");
        $this->SetPOPUPColClass("col-sm-6");

        if (APPSBD_IsPostBack) {
            $nobject = new Mapbd_wps_role();
            if ($nobject->SetFromPostData(true)) {
                $nobject->is_editable('Y');
                if ($nobject->Save()) {
                    $this->AddInfo("Successfully added");
                    APBD_AddLog("A", $nobject->settedPropertyforLog(), "l001", "");
                    $this->DisplayPOPUPMsg();
                    return;
                }
            }
        }
        $mainobj = new Mapbd_wps_role();
        $this->AddViewData("isUpdateMode", false);
        $this->AddViewData("mainobj", $mainobj);
        $this->DisplayPOPUp("add");
    }

    function edit($param_id = "")
    {
        $this->SetPOPUPColClass("col-sm-6");

        $param_id = APBD_GetValue("id");
        if (empty($param_id)) {
            $this->AddError("Invalid request");
            $this->DisplayPOPUPMsg();
            return;
        }
        $this->SetTitle("Edit Role");
        if (APPSBD_IsPostBack) {
            $uobject = new Mapbd_wps_role();
            if ($uobject->SetFromPostData(false)) {
                $uobject->SetWhereUpdate("id", $param_id);
                if ($uobject->Update()) {
                    $role=Mapbd_wps_role::FindBy("id",$param_id);
                    do_action('apbd-wps/action/role-updated', $param_id);
                    APBD_AddLog("U", $uobject->settedPropertyforLog(), "l002", "");
                    $this->AddInfo("Successfully updated");
                    $this->DisplayPOPUPMsg();
                    return;
                }
            }
        }
        $mainobj = new Mapbd_wps_role();
        $mainobj->id($param_id);
        if (!$mainobj->Select()) {
            $this->AddError("Invalid request");
            $this->DisplayPOPUPMsg();
            return;
        }
        APBD_OldFields($mainobj, "name,slug,role_description,status,is_agent");
        $this->AddViewData("mainobj", $mainobj);
        $this->AddViewData("isUpdateMode", true);
        $this->DisplayPOPUP("add");
    }


    function data()
    {
        $mainResponse = new AppsbdAjaxDataResponse();
        $mainResponse->setDownloadFileName("apbd-wps-role-list");
        $mainobj = new Mapbd_wps_role();
        $mainResponse->setDateRange($mainobj);
        $records = $mainobj->CountALL($mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam, "after");
        if ($records > 0) {
            $mainResponse->SetGridRecords($records);
            $result = $mainobj->SelectAllGridData("", $mainResponse->orderBy, $mainResponse->order, $mainResponse->rows, $mainResponse->limitStart, $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam, "after");
            if ($result) {

                $status_change = $mainobj->GetPropertyOptionsTag("status");
                $is_agent = $mainobj->GetPropertyOptionsTag("is_agent");

                foreach ($result as &$data) {
                    $data->action = "";
                    if ($data->is_editable == 'Y') {
                        $data->action .= "<a data-effect='mfp-move-from-top' class='popupformWR btn btn-info btn-xs' href='" . $this->GetActionUrl("edit", ["id" => $data->id]) . "'>" . $this->__("Edit") . "</a>";
                        $data->action .= " <a class='ConfirmAjaxWR btn btn-danger btn-xs' data-on-complete='APBD_support_genix_role_delete' data-msg='" . $this->__("Are you sure to delete?") . "' href='" . $this->GetActionUrl("delete_item", ["id" => $data->id]) . "'>" . $this->__("Delete") . "</a>";
                    } else {
                        $data->name .= ' <span class="text-info">(' . $this->__('Built-in') . ')</span>';
                    }
                    $data->status = " <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='" . $this->__("Are you sure to change?") . "' href='" . $this->GetActionUrl("status_change", ["id" => $data->id]) . "'>" . APBD_getTextByKey($data->status, $status_change) . "</a>";
                    $data->is_agent = " <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='" . $this->__("Are you sure to change?") . "' href='" . $this->GetActionUrl("is_agent", ["id" => $data->id]) . "'>" . APBD_getTextByKey($data->is_agent, $is_agent) . "</a>";
                }
            }
            $mainResponse->SetGridData($result);
        }
        $mainResponse->DisplayGridResponse();
    }
    function access_data()
    {
        $mainResponse = new AppsbdAjaxDataResponse();
        $mainResponse->setDownloadFileName("apbd-wps-role-access-list");

            $res=Mapbd_wps_role_access::GetResourceList();
            $mainResponse->SetGridRecords(count($res));
            $roles=Mapbd_wps_role::GetRoleList();
            $accessList= Mapbd_wps_role_access::GetAccessList();
            if ($res) {
                foreach ($res as &$data) {
                    if(!empty($data->tooltip_note)) {
                        $data->title = "<span class='app-tooltip' title='{$data->tooltip_note}'>{$data->title}<i class='pl-1  text-info fa fa-info-circle'  ></i></span>";
                    }
                    foreach ($roles as $role) {
                        if($role->slug=="administrator"){
                            $data->{$role->slug}="<i class='grid-icon  fa fa-check text-success'></i>";
                        }else{
	                        if(!empty($accessList[$data->action_param][$role->slug]) && $accessList[$data->action_param][$role->slug]=="Y"){
		                        $data->{$role->slug}="<a class='ConfirmAjaxWR ' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='" . $this->__("Are you sure to change?") . "' href='" . $this->GetActionUrl("acl-toggle", ["role_id" => $role->slug,"res_id"=>$data->action_param]) . "'><i class='grid-icon  fa fa-check text-success'></i></a>";
	                        }else{
		                        $data->{$role->slug}="<a class='ConfirmAjaxWR ' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='" . $this->__("Are you sure to change?") . "' href='" . $this->GetActionUrl("acl-toggle", ["role_id" => $role->slug,"res_id"=>$data->action_param]) . "'><i class='grid-icon  fa fa-times text-danger'></i></a>";
	                        }
                        }
                    }
                }
            }
            $mainResponse->SetGridData($res);

        $mainResponse->DisplayGridResponse();
    }
    function access_data2()
    {
        $mainResponse = new AppsbdAjaxDataResponse();
        $mainResponse->setDownloadFileName("apbd-wps-role-access-list");
        $mainobj = new Mapbd_wps_role_access();
        $mainResponse->setDateRange($mainobj);
        $records = $mainobj->CountALL($mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam, "after");
        if ($records > 0) {
            $mainResponse->SetGridRecords($records);
            $result = $mainobj->SelectAllGridData("", $mainResponse->orderBy, $mainResponse->order, $mainResponse->rows, $mainResponse->limitStart, $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam, "after");
            if ($result) {

                $role_access_change = $mainobj->GetPropertyOptionsTag("role_access");

                foreach ($result as &$data) {
                    $data->action = "";
                    $data->action .= "<a data-effect='mfp-move-from-top' class='popupformWR btn btn-info btn-xs' href='" . $this->GetActionUrl("edit", ["id" => $data->id]) . "'>" . $this->__("Edit") . "</a>";
                    $data->action .= " <a class='ConfirmAjaxWR btn btn-danger btn-xs' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='" . $this->__("Are you sure to delete?") . "' href='" . $this->GetActionUrl("delete_item", ["id" => $data->id]) . "'>" . $this->__("Delete") . "</a>";

                    $data->role_access = " <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='" . $this->__("Are you sure to change?") . "' href='" . $this->GetActionUrl("role_access_change", ["id" => $data->id]) . "'>" . APBD_getTextByKey($data->role_access, $role_access_change) . "</a>";

                }
            }
            $mainResponse->SetGridData($result);
        }
        $mainResponse->DisplayGridResponse();
    }

    function delete_item($param = "")
    {
        $param=APBD_GetValue("id");
        $mainResponse = new AppsbdAjaxConfirmResponse();
        if (empty($param)) {
            $mainResponse->DisplayWithResponse(false, $this->__("Invalid Request"));
            return;
        }
        $mr = new Mapbd_wps_role();
        $mr->id($param);
        if ($mr->Select()) {
            if (Mapbd_wps_role::DeleteBySlug($mr->slug )) {
                do_action('apbd-wps/action/role-deleted', $mr);
                APBD_AddLog("D", "id={$param}", "l003", "Wp_apbd_pos_role_confirm");
                $mainResponse->DisplayWithResponse(true, $this->__("Successfully deleted"));
            } else {
                $mainResponse->DisplayWithResponse(false, $this->__("Delete failed try again"));
            }
        }
    }

    function status_change()
    {
        $param = APBD_GetValue("id");
        if (empty($param)) {
            $this->DisplayWithResponse(false, $this->__("Invalid Request"));
            return;
        }
        $mainResponse = new AppsbdAjaxConfirmResponse();
        $mr = new Mapbd_wps_role();
        $statusChange = $mr->GetPropertyOptionsTag("status");

        $mr->id($param);
        if ($mr->Select("status")) {
            $newStatus = $mr->status == "A" ? "I" : "A";
            $uo = new Mapbd_wps_role();
            $uo->status($newStatus);
            $uo->SetWhereUpdate("id", $param);
            if ($uo->Update()) {
                $status_text = APBD_getTextByKey($uo->status, $statusChange);
                APBD_AddLog("U", $uo->settedPropertyforLog(), "l002", "Wp_apbd_wps_role");
                do_action('apbd-wps/action/role-updated', $param);
                $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), $status_text);
            } else {
                $mainResponse->DisplayWithResponse(false, $this->__("Update failed try again"));
            }

        }

    }
    function is_agent_status_change()
    {
        $param = APBD_GetValue("id");
        if (empty($param)) {
            $this->DisplayWithResponse(false, $this->__("Invalid Request"));
            return;
        }
        $mainResponse = new AppsbdAjaxConfirmResponse();
        $mr = new Mapbd_wps_role();
        $statusChange = $mr->GetPropertyOptionsTag("is_agent");

        $mr->id($param);
        if ($mr->Select("is_agent")) {
            $newStatus = $mr->is_agent == "Y" ? "N" : "Y";
            $uo = new Mapbd_wps_role();
            $uo->is_agent($newStatus);
            $uo->SetWhereUpdate("id", $param);
            if ($uo->Update()) {
                $status_text = APBD_getTextByKey($uo->is_agent, $statusChange);
                APBD_AddLog("U", $uo->settedPropertyforLog(), "l002", "Wp_apbd_wps_role");
                do_action('apbd-wps/action/role-updated', $param);
                $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), $status_text);
            } else {
                $mainResponse->DisplayWithResponse(false, $this->__("Update failed try again"));
            }

        }

    }
    function acl_toggle()
    {
        $role_slug = APBD_GetValue("role_id");
        $res_id = APBD_GetValue("res_id");
        $mainResponse = new AppsbdAjaxConfirmResponse();
        $acl = Mapbd_wps_role_access::FindBy('resource_id', $res_id, ['role_slug' => $role_slug]);
        $tmp=new Mapbd_wps_role_access();
        $statusList=$tmp->GetPropertyRawOptions('role_access');
        if (!empty($acl)) {
            //update
            $newStatus = $acl->role_access == "Y" ? "N" : "Y";
            if (Mapbd_wps_role_access::UpdateStatus($acl->id, $newStatus)) {
                $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), APBD_getTextByKey($newStatus,$statusList));
            } else {
                $mainResponse->DisplayWithResponse(false, $this->__("Failed to update"), APBD_getTextByKey($acl->role_access,$statusList));
            }
        } else {
            //new
            $newStatus = "Y";
            if (Mapbd_wps_role_access::AddAccessStatus($role_slug, $res_id)) {
                $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), APBD_getTextByKey($newStatus,$statusList));
            } else {
                $mainResponse->DisplayWithResponse(false, $this->__("Failed to update"), APBD_GetMsg_API());
            }

        }
    }

    function AddDefaultAccess( $param = null ) {
        $roleObj = ( is_object( $param ) ? $param : ( is_numeric( $param ) ? Mapbd_wps_role::FindBy( 'id', $param, [] ) : null ) );

        if ( ! is_object( $roleObj ) ) {
            return;
        }

        $roleSlug = ( isset( $roleObj->slug ) ? $roleObj->slug : '' );
        $roleIsAgent = ( isset( $roleObj->is_agent ) ? $roleObj->is_agent : 'N' );

        if ( empty( $roleSlug ) || ( 'Y' !== $roleIsAgent ) ) {
            return;
        }

        $accessList = [
            'assign-me', 'change-privacy', 'closed-ticket-list', 'create-note',
            'edit-assigned ', 'edit-category', 'edit-custom-field', 'edit-wc-order-source',
            'edit-elite-purchase-code', 'edit-envato-purchase-code', 'edit-status',
            'move-to-trash', 'ticket-reply', 'trash-ticket-menu', 'manage-other-agents-ticket',
            'manage-unassigned-ticket'
        ];

        foreach ( $accessList as $accessItem ) {
            Mapbd_wps_role_access::AddAccessIfNotExits( $roleSlug, $accessItem );
        }
    }

}