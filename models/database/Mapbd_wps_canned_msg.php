<?php
/**
 * @since: 18/Nov/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @property:id,user_id,title,canned_msg,entry_date,added_by,canned_type,status
 */
class Mapbd_wps_canned_msg extends AppsBDModel{
	public $id;
	public $user_id;
	public $title;
	public $canned_msg;
	public $entry_date;
	public $added_by;
	public $canned_type;
	public $status;


	    /**
	     *@property id,user_id,title,canned_msg,entry_date,added_by,canned_type,status
		 */
		function __construct() {
			parent::__construct ();
			$this->SetValidation();
			$this->tableName="apbd_wps_canned_msg";
			$this->primaryKey="id";
			$this->uniqueKey=array();
			$this->multiKey=array();
			$this->autoIncField=array("id");
			$this->app_base_name="support-genix-lite";
			$this->htmlInputField=['canned_msg'];

		}


	function SetValidation(){
		$this->validations=array(
			"id"=>array("Text"=>"Id", "Rule"=>"max_length[10]|integer"),
			"user_id"=>array("Text"=>"User Id", "Rule"=>"max_length[3]"),
			"title"=>array("Text"=>"Title", "Rule"=>"max_length[150]"),
			"entry_date"=>array("Text"=>"Entry Date", "Rule"=>"max_length[20]"),
			"added_by"=>array("Text"=>"Added By", "Rule"=>"max_length[3]"),
			"canned_type"=>array("Text"=>"Canned Type", "Rule"=>"max_length[1]"),
			"status"=>array("Text"=>"Status", "Rule"=>"max_length[255]")

		);
	}

	public function GetPropertyRawOptions($property,$isWithSelect=false){
	    $returnObj=array();
		switch ($property) {
	      case "canned_type":
	         $returnObj=array("T"=>"Ticket","C"=>"Chat");
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
	      case "canned_type":
	         $returnObj=array("T"=>"success","C"=>"success");
	         break;
	      default:
	    }
        return $returnObj;

	}

	public function GetPropertyOptionsIcon($property){
	    $returnObj=array();
		switch ($property) {
	      case "canned_type":
	         $returnObj=array("T"=>"","C"=>"");
	         break;
	      default:
	    }
        return $returnObj;

	}

	function cleanCannedMsg(){
        if($this->IsSetPrperty("canned_msg")) {
            $this->canned_msg(apbd_editor_text_filter($this->canned_msg));
        }
    }
	    function Update($notLimit = false, $isShowMsg = true, $dontProcessIdWhereNotset = true)
    {
        $this->cleanCannedMsg();
        return parent::Update($notLimit, $isShowMsg, $dontProcessIdWhereNotset);
    }

    function Save(){
        if(!$this->IsSetPrperty("added_by")){
            $user_id=get_current_user_id();
            $this->added_by($user_id);
        }
        $this->cleanCannedMsg();
        return parent::Save();
    }
    /**
     * @param Mapbd_wps_ticket $ticket
     */
    public static function GetAllCannedMsgBy($ticket){
        $response_obj=[];
        $cannedMsgList = new Mapbd_wps_canned_msg();
        $cannedMsgList->status('A');
        $msgs=$cannedMsgList->SelectAllGridData();
        $obj = new self();
        if(!empty($msgs)) {
            $params = self::getParamList();
            $params["site_name"] = esc_html($obj->__("Your site name"));
            $params["site_url"] = esc_html($obj->__("Your Site URL"));
            $params["ticket_user"] = esc_html($obj->__("Ticket User"));
            $user = get_user_by("ID", $ticket->ticket_user);
            if (!empty($ticket)) {
                if (!empty($user->first_name) || !empty($user->last_name)) {
                    $params["ticket_user"] = $user->first_name . ' ' . $user->last_name;
                } elseif (!empty($user->display_name)) {
                    $params["ticket_user"] = $user->display_name;
                }
            }
            $params["ticket_title"] = esc_html($obj->__($ticket->title));
            $params["reply_user"] = esc_html($obj->__("Agent"));
            $params["reply_user_grp"] = "";
            $currentUser = wp_get_current_user();
            if ($currentUser instanceof WP_User) {
                if (!empty($currentUser->first_name) || !empty($currentUser->last_name)) {
                    $params["reply_user"] = $currentUser->first_name . ' ' . $currentUser->last_name;
                } elseif (!empty($currentUser->display_name)) {
                    $params["reply_user"] = $currentUser->display_name;
                }
                $params["reply_user_grp"] = apbd_get_user_role_name($currentUser);
            }

            foreach ($msgs as $msg) {
                $nmsg=new stdClass();
                $nmsg->id=$msg->id;
                $nmsg->title=$msg->title;
                $nmsg->canned_msg=self::get_real_msg($params,$msg->canned_msg);
                $response_obj[]=$nmsg;
            }
        }
        return $response_obj;
    }
    public static function getParamList()
    {
        $obj = new self();
        $return_obj = array();
        $return_obj["site_name"] = esc_html($obj->__("Your site name"));
        $return_obj["site_url"] = esc_html($obj->__("Your Site URL"));
        $return_obj["ticket_user"] = esc_html($obj->__("The user who has opened ticket"));
        $return_obj["ticket_title"] = esc_html($obj->__("Ticket title"));
        $return_obj["reply_user"] = esc_html($obj->__("Reply user name"));
        $return_obj["reply_user_grp"] = esc_html($obj->__("Reply user group"));
        return $return_obj;
    }
    public static function getParamListClearData(){
        $return_obj=self::getParamList();
        $return_obj=array_map(function($value){
            $value="";
        }, $return_obj);
        $return_obj["site_name"]=get_bloginfo('name');
        $return_obj["site_url"]=home_url();
        return $return_obj;
    }
    static function get_real_msg($params,$str){
        if(count($params)>0){
            $search=array();
            $replace=array();
            foreach ($params as $key=>$value){
                $search[]="{{".$key."}}";
                $replace[]=$value;
            }
            return str_replace($search, $replace, $str);
        }
        return $str;
    }
    static function DeleteById($id){
        return  parent::DeleteByKeyValue("id",$id);
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
					  `user_id` char(3) NOT NULL DEFAULT '',
					  `title` char(150) NOT NULL DEFAULT '',
					  `canned_msg` text DEFAULT NULL COMMENT 'textarea',
					  `entry_date` timestamp NOT NULL DEFAULT current_timestamp(),
					  `added_by` char(3) NOT NULL DEFAULT '',
					  `canned_type` char(1) NOT NULL DEFAULT 'T' COMMENT 'drop(T=Ticket,C=Chat)',
					  `status` char(255) NOT NULL DEFAULT 'A' COMMENT 'bool(A=Active,I=Inactive)',
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



	 function GetAddForm($label_col=5,$input_col=7,$mainobj=null,$except=array(),$disabled=array()){

				if(!$mainobj){
				$mainobj=$this;
				}?>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="title"><?php $this->_ee("Title"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="150"
                        value="<?php echo esc_attr($mainobj->GetPostValue("title")); ?>" id="title" name="title"
                        placeholder="<?php $this->_ee("Title"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Title"); ?>">
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="status"><?php $this->_ee("Status"); ?></label>

                 <?php
                 APBD_GetHTMLSwitchButton("status", "status", "I", "A", $mainobj->GetPostValue("status", "A"));
                 ?>
             </div>

         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="canned_msg"><?php $this->_ee("Canned Msg"); ?></label>
                 <textarea class="form-control form-control-sm apd-wp-editor h-300px"
                           type="text"
                           id="canned_msg"
                           name="canned_msg"
                           placeholder="<?php $this->_ee("Canned Msg"); ?>"><?php echo wp_kses_post($mainobj->GetPostValue("canned_msg")); ?></textarea>
             </div>
         </div>
         <?php
	}


}