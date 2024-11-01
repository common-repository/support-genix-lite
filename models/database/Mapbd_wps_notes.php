<?php
/**
 * @since: 15/Dec/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @property:id,ticket_id,entry_date,added_by,note_text
 */
class Mapbd_wps_notes extends AppsBDModel{
	public $id;
	public $ticket_id;
	public $entry_date;
	public $added_by;
	public $note_text;


	    /**
	     *@property id,ticket_id,entry_date,added_by,note_text
		 */
		function __construct() {
			parent::__construct ();
			$this->SetValidation();
			$this->tableName="apbd_wps_notes";
			$this->primaryKey="id";
			$this->uniqueKey=array();
			$this->multiKey=array();
			$this->autoIncField=array("id");
			$this->app_base_name="support-genix-lite";

		}


	function SetValidation(){
		$this->validations=array(
			"id"=>array("Text"=>"Id", "Rule"=>"max_length[11]|integer"),
			"ticket_id"=>array("Text"=>"Ticket Id", "Rule"=>"required|max_length[11]|integer"),
			"added_by"=>array("Text"=>"Added By", "Rule"=>"required|max_length[11]|integer"),
			"note_text"=>array("Text"=>"Note Text", "Rule"=>"required")

		);
	}

    static function getNoteString($note){
	    $nUser = new WP_User($note->added_by);
	    return sprintf("%s  %s",$note->note_text,'<i>-'.($nUser->first_name? $nUser->first_name.' '.$nUser->last_name:$nUser->user_login)).'</i>' ;
    }
	/**
	 * @param $ticket_id
	 *
	 * @return array
	 */
    static function getAllNotesBy($ticket_id) {
	    $obj=new Mapbd_wps_notes();
	    $obj->ticket_id($ticket_id);
	    $notes=$obj->SelectAllGridData('','entry_date','asc');
	    $returnNotes=[];
	    foreach ( $notes as $note ) {

		    $returnNotes[]= self::getNoteString($note);
	    }
	    return $returnNotes;
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
					  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
					  `ticket_id` int(11) unsigned NOT NULL,
					  `entry_date` timestamp NOT NULL DEFAULT current_timestamp(),
					  `added_by` int(11) unsigned NOT NULL,
					  `note_text` text NOT NULL,
					  PRIMARY KEY (`id`)
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
                 <label class="col-form-label" for="ticket_id"><?php $this->_ee("Ticket Id"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="11"
                        value="<?php echo esc_attr($mainobj->GetPostValue("ticket_id")); ?>" id="ticket_id"
                        name="ticket_id" placeholder="<?php $this->_ee("Ticket Id"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Ticket Id"); ?>">
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="entry_date"><?php $this->_ee("Entry Date"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength=""
                        value="<?php echo esc_attr($mainobj->GetPostValue("entry_date")); ?>" id="entry_date"
                        name="entry_date" placeholder="<?php $this->_ee("Entry Date"); ?>">

             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="added_by"><?php $this->_ee("Added By"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="11"
                        value="<?php echo esc_attr($mainobj->GetPostValue("added_by")); ?>" id="added_by"
                        name="added_by" placeholder="<?php $this->_ee("Added By"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Added By"); ?>">
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="note_text"><?php $this->_ee("Note Text"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength=""
                        value="<?php echo esc_attr($mainobj->GetPostValue("note_text")); ?>" id="note_text"
                        name="note_text" placeholder="<?php $this->_ee("Note Text"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Note Text"); ?>">
             </div>
         </div>
			<?php
	}


}