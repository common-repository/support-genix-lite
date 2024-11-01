<?php
/**
 * @since: 07/Oct/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @property:ticket_id,log_id,log_by,log_by_type,log_msg,log_for,ticket_status,entry_time
 */
class Mapbd_wps_ticket_log extends AppsBDModel{
	public $ticket_id;
	public $log_id;
	public $log_by;
	public $log_by_type;
	public $log_msg;
	public $log_for;
	public $ticket_status;
	public $entry_time;
	    /**
	     *@property ticket_id,log_id,log_by,log_by_type,log_msg,log_for,ticket_status,entry_time
		 */
		function __construct() {
			parent::__construct ();
			$this->SetValidation();
			$this->tableName="apbd_wps_ticket_log";
			$this->primaryKey="log_id";
			$this->uniqueKey=array(array("ticket_id","log_id"));
			$this->multiKey=array(array("ticket_id"));
			$this->autoIncField=array();
			$this->app_base_name="support-genix-lite";

		}


	function SetValidation(){
		$this->validations=array(
			"ticket_id"=>array("Text"=>"Ticket Id", "Rule"=>"max_length[11]|integer"),
			"log_id"=>array("Text"=>"Log Id", "Rule"=>"max_length[11]|integer"),
			"log_by"=>array("Text"=>"Log By", "Rule"=>"max_length[11]|integer"),
			"log_by_type"=>array("Text"=>"Log By Type", "Rule"=>"max_length[1]"),
			"log_msg"=>array("Text"=>"Log Msg", "Rule"=>"max_length[150]"),
			"log_for"=>array("Text"=>"Log For", "Rule"=>"max_length[1]"),
			"ticket_status"=>array("Text"=>"Ticket Status", "Rule"=>"max_length[1]"),
			"entry_time"=>array("Text"=>"Entry Time", "Rule"=>"max_length[20]")
		);
	}

	static function getLogString($log){
		$nUser = new WP_User($log->log_by);
		$log->entry_time = APBD_getWPDateTimeWithFormat($log->entry_time, true);
		return sprintf("%s %s  %s",$log->log_msg,'<i> - '.($nUser->first_name? $nUser->first_name.' '.$nUser->last_name:$nUser->user_login).'</i>','at '.$log->entry_time);
	}
	/**
	 * @param $ticket_id
	 *
	 * @return array
	 */
	static function getAllLogsBy($ticket_id) {
		$obj=new Mapbd_wps_ticket_log();
		$obj->ticket_id($ticket_id);
		$logs=$obj->SelectAllGridData('','entry_time','desc');
		$isAgent = Apbd_wps_settings::isAgentLoggedIn();
		$returnNotes=[];
		foreach ( $logs as $log ) {
			$log_for = ( isset( $log->log_for ) ? $log->log_for : 'B' );

			if ( ( 'B' !== $log_for ) && ( ( $isAgent && 'A' !== $log_for ) || ( ! $isAgent && 'U' !== $log_for ) ) ) {
				continue;
			}

			$returnNotes[]= self::getLogString($log);
		}
		return $returnNotes;
	}

	public function GetPropertyRawOptions($property,$isWithSelect=false){
	    $returnObj=array();
		switch ($property) {
	      case "log_by_type":
	         $returnObj=array("A"=>"Staff","U"=>"Ticket User","G"=>"Guest Ticke User");
	         break;
	      case "ticket_status":
	         $returnObj=array("N"=>"New","C"=>"Closed","P"=>"In Progress","R"=>"Re-Open","W"=>"Waiting For User");
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
	      case "log_by_type":
	         $returnObj=array("A"=>"success","U"=>"success","G"=>"success");
	         break;
	      case "ticket_status":
	         $returnObj=array("N"=>"success","C"=>"success","P"=>"info","R"=>"success","W"=>"success");
	         break;
	      default:
	    }
        return $returnObj;

	}

	public function GetPropertyOptionsIcon($property){
	    $returnObj=array();
		switch ($property) {
	      case "log_by_type":
	         $returnObj=array("A"=>"fa fa-check-circle-o","U"=>"","G"=>"");
	         break;
	      case "ticket_status":
	         $returnObj=array("N"=>"","C"=>"","P"=>"fa fa-hourglass-1","R"=>"","W"=>"");
	         break;
	      default:
	    }
        return $returnObj;

	}
    function GetNewLogId( $ticket_id, $default ) {
        $query  = "SELECT max(log_id) as lastS from " . $this->db->prefix . $this->tableName." WHERE ticket_id={$ticket_id}";
        $result = $this->db->get_row( $query );
        if ( $result ) {
            if ( ! empty ( $result->lastS ) ) {
                $a = (int)$result->lastS;
                $a ++;
                return $a;
            }
        }
        return $default;
    }
    static function AddTicketLog($ticket_id,$log_by,$log_by_type,$log_msg,$ticket_status,$log_for='B'){
        $obj=new self();
        $obj->ticket_id($ticket_id);
        $log_id=$obj->GetNewLogId($ticket_id,1);
        $obj->log_id($log_id);
        $obj->log_by($log_by);
        $obj->log_by_type($log_by_type);
        $obj->log_msg($log_msg);
        $obj->ticket_status($ticket_status);
        $obj->log_for($log_for);
        $obj->entry_time(gmdate("Y-m-d H:i:s"));
        return $obj->Save();
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

    /**
     * From version 1.3.8
     */
	static function UpdateDBTable() {
		self::DBColumnAddOrModify( 'log_for', 'char', 1, "'B'", 'NOT NULL', '', 'bool(B=Both,U=User,A=Staff)' );
	}

	static function CreateDBTable(){
		$thisObj=new static();
		$table=$thisObj->db->prefix.$thisObj->tableName;
		$charsetCollate = $thisObj->db->has_cap( 'collation' ) ? $thisObj->db->get_charset_collate() : '';

		if($thisObj->db->get_var("show tables like '{$table}'") != $table){
			$sql = "CREATE TABLE `{$table}` (
					  `ticket_id` int(11) NOT NULL DEFAULT 0,
					  `log_id` int(11) NOT NULL DEFAULT 0,
					  `log_by` int(11) unsigned NOT NULL DEFAULT 0,
					  `log_by_type` char(1) NOT NULL DEFAULT 'A' COMMENT 'radio(A=Staff,U=Ticket User,G=Guest Ticket User)',
					  `log_msg` char(150) NOT NULL DEFAULT '',
					  `log_for` char(1) NOT NULL DEFAULT 'B' COMMENT 'bool(B=Both,U=User,A=Staff)',
					  `ticket_status` char(1) NOT NULL DEFAULT 'P' COMMENT 'drop(N=New,C=Closed,P=In Progress,R=Re-Open,D=Deleted)',
					  `entry_time` timestamp NOT NULL DEFAULT current_timestamp(),
					  UNIQUE KEY `ticket_id` (`ticket_id`,`log_id`) USING BTREE,
					  KEY `ticket_id_2` (`ticket_id`) USING BTREE
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
				}
					?>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="log_by"><?php $this->_ee("Log By"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="6"
                        value="<?php echo esc_attr($mainobj->GetPostValue("log_by")); ?>" id="log_by" name="log_by"
                        placeholder="<?php $this->_ee("Log By"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Log By"); ?>">
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="log_by_type"><?php $this->_ee("Log By Type"); ?></label>
                 <div class="form-row">
                     <?php
                     $log_by_type_selected = $mainobj->GetPostValue("log_by_type", "'A'");
                     $log_by_type_isDisabled = in_array("log_by_type", $disabled);
                     APBD_GetHTMLRadioByArray("Log By Type", "log_by_type", "log_by_type", true, $mainobj->GetPropertyOptions("log_by_type"), $log_by_type_selected, $log_by_type_isDisabled);
                     ?>
                 </div>
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="log_msg"><?php $this->_ee("Log Msg"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="150"
                        value="<?php echo esc_attr($mainobj->GetPostValue("log_msg")); ?>" id="log_msg" name="log_msg"
                        placeholder="<?php $this->_ee("Log Msg"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Log Msg"); ?>">
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="ticket_status"><?php $this->_ee("Ticket Status"); ?></label>
                 <select class="custom-select form-control-sm" id="ticket_status" name="ticket_status"
                         data-bv-notempty="true"
                         data-bv-notempty-message="<?php $this->_ee("%s is required", "Ticket Status"); ?>">
                     <option value=""><?php $this->_e("Select"); ?></option>
                     <?php $ticket_status_selected = $mainobj->GetPostValue("ticket_status", "'P'");
                     APBD_GetHTMLOptionByArray($mainobj->GetPropertyOptions("ticket_status"), $ticket_status_selected);
                     ?>
                 </select>
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="entry_time"><?php $this->_ee("Entry Time"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="20"
                        value="<?php echo esc_attr($mainobj->GetPostValue("entry_time")); ?>" id="entry_time"
                        name="entry_time" placeholder="<?php $this->_ee("Entry Time"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Entry Time"); ?>">
             </div>
         </div>
			<?php
	}
}