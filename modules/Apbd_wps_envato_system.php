<?php
/**
 * @since: 17/Aug/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_wps_envato_system extends AppsBDLiteModule
{


    function initialize()
    {
        parent::initialize();
    }
    public function OnActive( $new_activation = true, $new_pro_activation = true ) {
        parent::OnActive( $new_activation, $new_pro_activation );
    }
    public function OnInit()
    {
        parent::OnInit();
        add_filter('apbd-wps/acl-resource', [$this, 'envato_resources']);
        if ($this->GetOption('envato_status', 'I') == 'A') {
            add_filter('apbd-wps/filter/before-custom-get', [$this, "set_envato_custom_field"]);
            add_filter('apbd-wps/filter/custom-additional-fields', [$this, "set_additional_custom_field"]);

            if ($this->GetOption("show_in_reg_form", "N") == "Y") {
                add_action("apbd-wps/action/user-created", [$this, 'save_envato_user_meta'], 10, 2);
                add_action("apbd-wps/action/user-updated", [$this, 'save_envato_user_meta'], 10, 2);
            }
            if ($this->GetOption("show_in_tckt_form", "Y") == "Y") {
                add_filter("apbd-wps/filter/ticket-custom-field-valid", [$this, 'valid_post_data'], 10, 2);
                add_action("apbd-wps/action/ticket-created", [$this, 'save_envato_ticket_meta'], 10, 2);
            }
            add_filter("apbd-wps/filter/custom-field-validate", [$this, 'valid_custom_field'], 10, 3);
            add_action("apbd-wps/action/ticket-custom-field-update", [$this, 'update_ticket_meta'], 10, 3);
            add_filter("apbd-wps/filter/ticket-details-custom-properties", [$this, 'final_filter_custom_field'], 10, 3);
        }

    }
	function SetOption() {
		$module_name = get_class( $this );
		$this->options = get_option( "apbd-wp-support_o_" . $module_name, NULL );
	}
	function UpdateOption() {
		$module_name = get_class( $this );
		return update_option( "apbd-wp-support_o_" . $module_name, $this->options ) || add_option( "apbd-wp-support_o_" . $module_name, $this->options );
	}
    function SettingsPage() {
        $this->SetTitle("Envato Settings");
        $this->Display();
    }

    function GetMenuTitle()
    {
        return $this->__("Envato");
    }

    function GetMenuSubTitle()
    {
        return $this->__("Envato Settings");
    }

    function GetMenuIcon()
    {
        return "fa fa-envira";
    }
	function envato_resources($resources){

		$resources[]=ELITE_ACL_Resource::getResource("edit-envato-purchase-code","Edit Purchase Code","Envato","");

		return $resources;
	}
    public function AjaxRequestCallback() {
        $response   = new AppsbdAjaxConfirmResponse();
        $beforeSave = $this->options;
        $postData = wp_parse_args( $_POST );
        foreach ( $postData as $key=>$value ) {
            $key=sanitize_key($key);
            if($key=="action"){
                continue;
            }
            $this->options[$key]=sanitize_text_field($value);
        }
        if ( $beforeSave === $this->options ) {
            $response->DisplayWithResponse( false, $this->__( "No change for update" ) );
        } else {
            $response->SetResponse(false, $this->__("No change for update"));
            if ($this->checkAPIKEY($response, $this->options)) {
                if ($this->UpdateOption()) {
                    $response->SetResponse(true, $this->__("Saved Successfully"));
                } else {
                    $response->SetResponse(false, $this->__("No change for update"));
                }
            }
        }
        $response->Display();

    }

    /**
     * @param  AppsbdAjaxConfirmResponse $response
     */
    function checkAPIKEY(&$response ,$options)
    {

            $api_key = !empty($options["api_token"]) ? $options["api_token"] : "";
            $isEnvato = !empty($options["envato_status"]) ? $options["envato_status"] : "I";
            if ($isEnvato == "A") {
                if (empty($api_key)) {
                    $response->SetResponse(false, $this->__("API Key is required"));
                    return false;
                } else {
                    $obj = new APBD_WPS_EnvatoAPI($api_key);
                    if (!$obj->checkAPI($error)) {
                        $response->SetResponse(false, $this->__("API Key is invalid<br/>" . $error));
                        return false;
                    }
                }
            }
        return true;
    }

    /**
 * @param Mapbd_wps_ticket $ticket
 * @param $custom_fields
 */
    function valid_post_data($isValid,$custom_fields)
    {
        if (!empty($custom_fields) && is_array($custom_fields)) {
            foreach ($custom_fields as $key => $custom_field) {
                if ($key == "E1") {
                    if(!$this->valid_license_key($custom_field)){
                        $this->AddError("Purchase code is invalid");
                        $isValid=false;
                    }
                }
            }

        }
        return $isValid;
    }
    function valid_custom_field($isValid, $fieldName, $field_value)
    {
        if ($this->GetOption('envato_status','I') == "A") {
            if ($fieldName == "E1") {
                $result=$this->valid_license_key($field_value);
                if (empty($result->item)) {
                    $this->AddError("Purchase code is invalid");
                    $isValid = false;
                }
            }
        }
        return $isValid;
    }
    function valid_license_key($license_key)
    {
        if ($this->GetOption('envato_status','I') == "A") {
            $api_key = $this->GetOption("api_token",'');
            if (empty($api_key)) {
                return null;
            } else {
                $obj = new APBD_WPS_EnvatoAPI($api_key);
                $result = $obj->getSale($license_key);
                if(!empty($result->item)){
                    unset($result->item->attributes);
                    unset($result->item->description);
                    unset($result->item->tags);
                   return $result;
                }else{
                    return null;
                }
            }
        }
        return null;
    }
    /**
     * @param Apbd_WPS_User $userData
     * @param $custom_fields
     */
    function save_envato_user_meta($userData,$custom_fields){
        if(!empty($custom_fields) && is_array($custom_fields)){
            foreach ($custom_fields as $key=>$custom_field) {
                if(substr($key,0,1)=="E"){
                    $c=new Mapbd_wps_support_meta();
                    $c->item_id($userData->id);
                    $c->item_type('U');
                    $c->meta_key(preg_replace("#[^0-9]#",'',$key));
                    $c->meta_type('E');
                    if($c->Select()){
                        $u=new Mapbd_wps_support_meta();
                        $u->SetWhereUpdate("id",$c->id);
                        $u->meta_value($custom_field);
                        $u->Update();
                    }else{
                        $n=new Mapbd_wps_support_meta();
                        $n->item_id($userData->id);
                        $n->item_type('U');
                        $n->meta_key(preg_replace("#[^0-9]#",'',$key));
                        $n->meta_type('E');
                        $n->meta_value($custom_field);
                        $n->Save();
                    }
                }
            }
        }
    }

    /**
     * @param Mapbd_wps_ticket $ticket
     * @param $custom_fields
     */
    function save_envato_ticket_meta($ticket,$custom_fields){
        if(!empty($custom_fields) && is_array($custom_fields)){
            foreach ($custom_fields as $key=>$custom_field) {
                if(substr($key,0,1)=="E"){
                    $n=new Mapbd_wps_support_meta();
                    $n->item_id($ticket->id);
                    $n->item_type('T');
                    $n->meta_key(preg_replace("#[^0-9]#",'',$key));
                    $n->meta_type('E');
                    $n->meta_value($custom_field);
                    $n->Save();
                }
            }
        }
    }
	function set_envato_custom_field($custom_fields){
        $fld=new stdClass();
        $fld->id='E1';
        $fld->is_required=$this->GetOption("is_required","N");
        $fld->field_label=$this->__("Purchase Code");
        $fld->input_name="E1";
        $fld->v_rules= ltrim(($fld->is_required=="Y"?"required":'')."|isValid:custom,E1,34",'|');
        $fld->help_text=$this->__("Enter your purchase code");
        $fld->choose_category=["0"];
        $fld->fld_option='';
        $fld->fld_order="2";
        $fld->where_to_create="T";
        $fld->field_type="T";
        $fld->status="A";
        $fld->is_half_field="N";
        $fld->categories=[];
        if($this->GetOption("show_in_reg_form","N")=="Y") {
            $custom_fields->reg_form[] = $fld;
        }
        if($this->GetOption("show_in_tckt_form","Y")=="Y") {
            $custom_fields->ticket_form[] = $fld;
        }
        return $custom_fields;
    }
    private function getNonEditableField($id_or_name,$fld_label='',$field_value='',$field_type='',$help_text='',$fld_order='')
    {
        $fld=new stdClass();
        $fld->id=$id_or_name;
        $fld->is_required="N";
        $fld->field_label=$fld_label;
        $fld->input_name=$id_or_name;
        $fld->v_rules= "";
        $fld->help_text=$help_text;
        $fld->choose_category=["0"];
        $fld->fld_option='';
        $fld->fld_order=$fld_order;
        $fld->where_to_create="T";
        $fld->field_type=$field_type;
        $fld->status="A";
        $fld->is_half_field="N";
        $fld->categories=[];
        $fld->field_value=$field_value;
        $fld->is_editable=false;
        return $fld;
    }
    function set_additional_custom_field($custom_fields)
    {
        $newFields=$custom_fields;

        if(!empty($custom_fields)) {
            $foundArray = [];
            $counter = 2;

            foreach ($custom_fields as $custom_field) {
                if ($custom_field->id == "E1") {
                    $result = $this->valid_license_key($custom_field->field_value);
                    if (!empty($result->item)) {
                        $foundArray[$custom_field->field_value] = $result;
                        $newFields[] = $this->getNonEditableField("E" . $counter++, $this->__("Product Name"), $result->item->name,"E", "", $custom_field->fld_order);
                        $newFields[] = $this->getNonEditableField(
                            "E" . $counter++, $this->__("License Type"),
                           $result->license,
                            "E",
                            "",
                            $custom_field->fld_order
                        );
                        $newFields[] = $this->getNonEditableField(
                            "E" . $counter++, $this->__("Support Time"),
                            date("M d, Y",strtotime($result->supported_until)),
                            "E",
                            "",
                            $custom_field->fld_order
                        );
                        $newFields[] = $this->getNonEditableField("E" . $counter++, $this->__("Site"), $result->item->site, "T","", $custom_field->fld_order);
                    }
                }
            }
        }

        return $newFields;
    }
    function final_filter_custom_field($custom_fields, $ticket_or_user_id=''){
        $isClient=Apbd_wps_settings::isClientLoggedIn();
        if($isClient) {
            foreach ($custom_fields as &$custom_field) {
                if ($custom_field->id == "E1" && !empty($custom_field->field_value)) {
                    $custom_field->is_editable=false;
                }
            }
        } elseif( ! current_user_can( 'edit-envato-purchase-code' ) ) {
            foreach ($custom_fields as &$custom_field) {
                if ($custom_field->id == "E1") {
                    $custom_field->is_editable=false;
                }
            }
        }
        return $custom_fields;
    }

    function update_ticket_meta($ticket_id, $pro_name, $value)
    {
        if (strtoupper(substr($pro_name, 0, 1)) == "E") {
            $s=new Mapbd_wps_support_meta();
            $s->item_id($ticket_id);
            $s->meta_key(preg_replace("#[^0-9]#", '', $pro_name));
            $s->meta_type('E');
            if($s->Select()) {
                $n = new Mapbd_wps_support_meta();
                $n->meta_value($value);
                $n->SetWhereUpdate("item_id", $ticket_id);
                $n->SetWhereUpdate("meta_key", preg_replace("#[^0-9]#", '', $pro_name));
                $n->SetWhereUpdate("meta_type", 'L');
                if (!$n->Update()) {
                    Mapbd_wps_debug_log::AddGeneralLog("Custom field update failed", APBD_GetMsg_API() . "\nTicket ID: $ticket_id, Custom Name: $pro_name, value:$value");
                }
            }else{
                $n = new Mapbd_wps_support_meta();
                $n->meta_value($value);
                $n->item_id($ticket_id);
                $n->item_type('T');
                $n->meta_key(preg_replace("#[^0-9]#", '', $pro_name));
                $n->meta_type('E');
                $n->meta_value($value);
                if (!$n->Save()) {
                    Mapbd_wps_debug_log::AddGeneralLog("Custom field update failed", APBD_GetMsg_API() . "\nTicket ID: $ticket_id, Custom Name: $pro_name, value:$value");
                }
            }
        }
    }


}