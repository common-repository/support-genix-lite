<?php
/**
 * @since: 21/Oct/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @property:id,cat_ids,rule_type,rule_id,status
 */
class Mapbd_wps_ticket_assign_rule extends AppsBDModel{
	public $id;
	public $cat_ids;
	public $rule_type;
	public $rule_id;
	public $status;


	    /**
	     *@property id,cat_ids,rule_type,rule_id,status
		 */
		function __construct() {
			parent::__construct ();
			$this->SetValidation();
			$this->tableName="apbd_wps_ticket_assign_rule";
			$this->primaryKey="id";
			$this->uniqueKey=array();
			$this->multiKey=array();
			$this->autoIncField=array("id");
			$this->app_base_name="support-genix-lite";

		}


	function SetValidation(){
		$this->validations=array(
			"id"=>array("Text"=>"Id", "Rule"=>"max_length[10]|integer"),
			"cat_ids"=>array("Text"=>"Cat Ids", "Rule"=>"max_length[255]"),
			"rule_type"=>array("Text"=>"Rule Type", "Rule"=>"max_length[1]"),
			"rule_id"=>array("Text"=>"Rule Id", "Rule"=>"required|max_length[10]"),
			"status"=>array("Text"=>"Status", "Rule"=>"max_length[1]")

		);
	}
    public function SetFromPostData($isNew = false)
    {
        $category_arr=SupportGNInput::post_value('category_arr','');
        $categorList = implode(',', (!empty($category_arr) ? $category_arr : []));
        if ($isNew || !empty($categorList)) {
            $this->cat_ids($categorList);
        }
        return parent::SetFromPostData($isNew);
    }

	public function GetPropertyRawOptions($property,$isWithSelect=false){
	    $returnObj=array();
		switch ($property) {
	      case "rule_type":
	         $returnObj=array("A"=>"Assign To Role","S"=>"Assign Specific Agent","N"=>"Notify to agent");
	         break;
	      case "status":
	         $returnObj=array("A"=>"Active","I"=>"Inactive");
	         break;
	      default:
	    }
        if($isWithSelect){
            return array_merge(array(""=>"Select"),$returnObj);
        }
        return $returnObj;

	}

	public function GetPropertyOptionsColor($property){
	    $returnObj=array();
		switch ($property) {
	      case "rule_type":
	         $returnObj=array("A"=>"success","S"=>"success","N"=>"success");
	         break;
            case "status":
                $returnObj=array("A"=>"success","I"=>"danger");
                break;
	      default:
	    }
        return $returnObj;

	}

	public function GetPropertyOptionsIcon($property){
	    $returnObj=array();
		switch ($property) {
	      case "rule_type":
	         $returnObj=array("A"=>"fa fa-check-circle-o","S"=>"fa fa-check-circle-o","N"=>"fa fa-bell");
	         break;
	      default:
	    }
        return $returnObj;

	}

    static function SetDefaultAssignRole() {
        $added = get_option( 'apbd_wps_default_assign_role_added', false );

        if ( true === rest_sanitize_boolean( $added ) ) {
            return;
        }

        $agentSlug = sanitize_title_with_dashes( 'awps-support-agent' );
        $managerSlug = sanitize_title_with_dashes( 'awps-support-manager' );

        $rolesSlug = [ $agentSlug, $managerSlug ];

        foreach ( $rolesSlug as $roleSlug ) {
            $roleObj = Mapbd_wps_role::FindBy( 'slug', $roleSlug, [] );
            $roleId = ( ( is_object( $roleObj ) && isset( $roleObj->id ) ) ? absint( $roleObj->id ) : 0 );

            if ( ! empty( $roleId ) ) {
                $assignRuleObj = new Mapbd_wps_ticket_assign_rule();
                $assignRuleObj->cat_ids( '0' );
                $assignRuleObj->rule_type( 'A' );
                $assignRuleObj->rule_id( $roleId );
                $assignRuleObj->status( 'A' );
                $assignRuleObj->Save();
            }
        }

        update_option( 'apbd_wps_default_assign_role_added', true );
    }

    /**
     * From version 1.0.9
     */
    static function UpdateDBTable(){
        $thisObj = new static();
        $table = $thisObj->db->prefix.$thisObj->tableName;

        if ( $thisObj->db->get_var( "show tables like '{$table}'" ) == $table ) {
            $sql = "ALTER TABLE `{$table}` MODIFY `rule_id` char(10)";
            $update = $thisObj->db->query( $sql );
        }
    }

    /**
     * From version 1.1.2
     */
	static function UpdateDBTableCharset() {
		$thisObj = new static();
        $table_name = $thisObj->db->prefix . $thisObj->tableName;
        $charset = $thisObj->db->charset;
        $collate = $thisObj->db->collate;

		$alter_query = "ALTER TABLE `{$table_name}` CONVERT TO CHARACTER SET {$charset} COLLATE {$collate}";

        $thisObj->db->query( $alter_query );
	}

    static function CreateDBTable(){
		$thisObj=new static();
		$table=$thisObj->db->prefix.$thisObj->tableName;
        $charsetCollate = $thisObj->db->has_cap( 'collation' ) ? $thisObj->db->get_charset_collate() : '';

		if($thisObj->db->get_var("show tables like '{$table}'") != $table){
			$sql = "CREATE TABLE `{$table}` (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `cat_ids` char(255) NOT NULL DEFAULT '' COMMENT 'FK(wp_apbd_wps_ticket_category,id,title)',
					  `rule_type` char(1) NOT NULL DEFAULT 'A' COMMENT 'radio(A=Assign,S=Assign Specific Agent,N=Notifiy)',
					  `rule_id` char(10) NOT NULL,
					  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'bool(A=Active,I=Inactive)',
					  PRIMARY KEY (`id`) USING BTREE
					) $charsetCollate;";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	 }
	 function DropDBTable(){
		global $wpdb;

		$table_name = $wpdb->prefix . $this->tableName;
		$sql        = "DROP TABLE IF EXISTS $table_name;";
		$wpdb->query( $sql );
	 }

    static function DeleteById($id){
        return  parent::DeleteByKeyValue("id",$id);
    }
	static function getAllRuleByCategory($cat_ids)
    {
        $roles = [];
        $obj = new self();
        $obj->status('A');
        $allCategories = $obj->SelectAllGridData();
        foreach ($allCategories as $single_rule) {
            $rule_categories = explode(',', $single_rule->cat_ids);
            foreach ($rule_categories as $rule_category) {
                if ($rule_category == 0 || in_array($rule_category, $cat_ids)) {
                    $roles[] = $single_rule;
                    break;
                }
            }
        }
        return $roles;
    }
    static function getNextAgent($rule_id)
    {
        global $wpdb;
        $role = Mapbd_wps_role::FindBy("id", $rule_id);
        if(empty($role)){
            return;
        }
        $users = apbd_wps_get_role_users($role->slug, "ID", "ASC");
        if (!empty($users) && is_array($users)) {
            if (count($users) == 1 && !empty($users[0]) && $users[0] instanceof WP_User) {
              return $users[0]->ID;
            } else {
                $user_ids = [];
                $data_counter = [];
                foreach ($users as $user) {
                    $user_ids[] = $user->ID;
                    $data_counter[$user->ID] = 0;
                }
                $mticket = new Mapbd_wps_ticket();
                $stat = $mticket->SelectQuery("SELECT assigned_on as user_id, count(*) total FROM ".$wpdb->prefix."apbd_wps_ticket WHERE assigned_on IN (9,2) group by assigned_on");
                if (!empty($stat)) {
                    foreach ($stat as $st) {
                        $data_counter[$st->user_id] = $st->total;
                    }
                }
                asort($data_counter);
                if (is_array($data_counter) && count($data_counter)>0) {
                    return array_keys($data_counter)[0];
                }
            }
        }
        return null;
    }

    /**
     * @param $ticketObj
     * @return Mapbd_wps_ticket_assign_rule []
     */
    static function getRuleBy(&$ticketObj)
    {
        $allCategories = Mapbd_wps_ticket_category::getAllCategoriesWithParents($ticketObj->cat_id);
        $Rules = Mapbd_wps_ticket_assign_rule::getAllRuleByCategory($allCategories);
        return $Rules;
    }
    /**
     * @param Mapbd_wps_ticket $ticketObj
     */
    static function ProcessRuleByCategory(&$ticketObj)
    {
        $Rules = self::getRuleBy($ticketObj);
        foreach ($Rules as $rule) {
            self::ProcessRule($rule,$ticketObj);
        }
    }
    /**
     * @param Mapbd_wps_ticket_assign_rule $rule
     * @param Mapbd_wps_ticket $ticketObj
     */
    static function ProcessRule($rule,&$ticketObj)
    {
        if ($rule->rule_type == "S") {
                        if (empty($ticketObj->assigned_on)) {
                Mapbd_wps_ticket::AssignOn($ticketObj, $rule->rule_id);
            }
        } elseif ($rule->rule_type == "A") {
                        $nextAgant = self::getNextAgent($rule->rule_id);
            if (!empty($nextAgant)) {
                Mapbd_wps_ticket::AssignOn($ticketObj, $nextAgant);
            }

        } elseif ($rule->rule_type == "N") {
                        Mapbd_wps_notification::AddNotification($rule->rule_id,"New Ticket Received","A new ticket has been received","","/tickets-details/".$ticketObj->id,false,"T","A",$ticketObj->id);
            Mapbd_wps_ticket::Send_ticket_open_admin_notify_email($ticketObj,$rule->rule_id);
        }
    }

    /**
     * @param Mapbd_wps_ticket_reply $reply_obj
     * @param Mapbd_wps_ticket $ticketObj
     */
    static function ProcessReplyNotificationAndEmail(&$reply_obj,&$ticketObj)
    {
        if (($reply_obj instanceof Mapbd_wps_ticket_reply) && ($ticketObj instanceof Mapbd_wps_ticket)) {
            $rules = Mapbd_wps_ticket_assign_rule::getRuleBy($ticketObj);
            $ticket_replied_user = apbd_get_user_title_by_id($reply_obj->replied_by);
                        if ($reply_obj->replied_by != $ticketObj->assigned_on) {
                Mapbd_wps_notification::AddNotification($ticketObj->assigned_on, "Ticket replied", "Ticket replied by %s", $ticket_replied_user, "/tickets-details/" . $ticketObj->id,false,"T","A",$ticketObj->id);
                $assigned_user = get_user_by("id", $ticketObj->assigned_on);
                if ($assigned_user instanceof WP_User) {
                    Mapbd_wps_ticket::Send_ticket_replied_email_admin($assigned_user->user_email, $reply_obj, $ticketObj);
                }
            }

            if($reply_obj->replied_by_type =='A'){
                                Mapbd_wps_notification::AddNotification($ticketObj->ticket_user, "Ticket replied", "Ticket replied by %s", $ticket_replied_user, "/tickets-details/" . $ticketObj->id,false,"T","A",$ticketObj->id);
                Mapbd_wps_ticket::Send_ticket_replied_email_user($reply_obj, $ticketObj);
            }
            foreach ($rules as $rule) {
                if ($rule->rule_type == "N") {
                    Mapbd_wps_notification::AddNotification($rule->rule_id, "Ticket replied", "Ticket replied by %s", $ticket_replied_user, "/tickets-details/" . $ticketObj->id,false,"T","A",$ticketObj->id);
                                        $notify_user = get_user_by("id", $rule->rule_id);
                    if ($notify_user instanceof WP_User) {
                        Mapbd_wps_ticket::Send_ticket_replied_email_admin($notify_user->user_email, $reply_obj, $ticketObj);
                    }
                }
            }
        }
    }

	 function GetAddForm($label_col=5,$input_col=7,$mainobj=null,$except=array(),$disabled=array()){

				if(!$mainobj){
				$mainobj=$this;
				}
				if(empty($mainobj->cat_ids)){
                    $mainobj->cat_ids="";
                }
					?>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="cat_ids"><?php $this->_ee("Choose Category"); ?></label>
                 <?php $options_choose_category = Mapbd_wps_ticket_category::FetchAllKeyValue("id", "title");
                 $choose_category_list = APBD_PostValue("category_arr", explode(',', $mainobj->cat_ids));
                 ?>
                 <select class="custom-select app-select2-picker form-control-sm" multiple="multiple" id="cat_ids"
                         name="category_arr[]">
                     <option value="0" <?php echo empty($choose_category_list) || in_array(0, $choose_category_list) ? 'selected="selected"' : ''; ?>><?php $this->_e("All Categories"); ?></option>
                     <?php
                     APBD_GetHTMLOptionByArray($options_choose_category, $choose_category_list);
                     ?>
                 </select>
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="rule_type"><?php $this->_ee("Rule Type"); ?></label>
                 <div class="form-row">
                     <?php
                     $rule_type_selected = $mainobj->GetPostValue("rule_type", "A");
                     $rule_type_isDisabled = in_array("rule_type", $disabled);
                     APBD_GetHTMLRadioByArray("Rule Type", "rule_type", "rule_type", true, $mainobj->GetPropertyOptions("rule_type"), $rule_type_selected, $rule_type_isDisabled, false, "has_depend_fld");
                     ?>
                 </div>
             </div>
             <div class="form-group col-sm-2">
                 <label class="col-form-label" for="status"><?php $this->_ee("Status"); ?></label>
                 <?php
                 APBD_GetHTMLSwitchButton("status", "status", "I", "A", $mainobj->GetPostValue("status", "A"));
                 ?>
             </div>
         </div>
         <div class="form-row fld-rule-type fld-rule-type-a">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="rule_id_role"><?php $this->_ee("Select Role"); ?></label>
                 <?php $options_cat_ids = Mapbd_wps_role::FetchAllKeyValue("id", "name"); ?>
                 <select class=" custom-select form-control-sm" id="rule_id_role" name="rule_id" data-bv-notempty="true"
                         data-bv-notempty-message="<?php $this->_ee("%s is required", "Rule ID"); ?>">
                     <option value=""><?php $this->_e("Select"); ?></option>
                     <?php $role_id_selected = $mainobj->GetPostValue("rule_id");
                     APBD_GetHTMLOptionByArray($options_cat_ids, $role_id_selected);
                     ?>
                 </select>
             </div>
         </div>
         <div class="form-row fld-rule-type fld-rule-type-n fld-rule-type-s ">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="rule_id_user"><?php $this->_ee("Select User"); ?></label>
                 <select class="app-select2-picker form-control-sm " id="rule_id_user" name="rule_id"
                         data-bv-notempty="true"
                         data-bv-notempty-message="<?php $this->_ee("%s is required", "Rule ID"); ?>">
                     <option value=""><?php $this->_e("Select"); ?></option>
                     <?php
                     $client_role = Apbd_wps_settings::GetModuleOption("client_role", 'subscriber');
                     $blogusers = get_users(array('role__not_in' => array($client_role)));
                     foreach ($blogusers as $user) {
                         APBD_GetHTMLOption($user->ID, $user->display_name, $role_id_selected);
                     }
                     ?>
                 </select>
             </div>
         </div>
			<?php
	}
}