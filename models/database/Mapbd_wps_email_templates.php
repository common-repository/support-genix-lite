<?php
/**
 * @since: 30/Mar/2019
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @property:k_word,grp,title,status,subject,props,content
 */
class Mapbd_wps_email_templates extends AppsBDModel{
	public $k_word;
	public $grp;
	public $title;
	public $status;
	public $subject;
	public $props;
	public $content;


	    /**
	     *@property k_word,grp,title,status,subject,props,content
		 */
		function __construct() {
			parent::__construct ();
			$this->SetValidation();
			$this->tableName="apbd_wps_email_templates";
			$this->primaryKey="k_word";
			$this->uniqueKey=array(array("k_word"));
			$this->multiKey=array();
			$this->autoIncField=array();
			$this->app_base_name="support-genix-lite";
			$this->htmlInputField=['content'];

		}


	function SetValidation(){
		$this->validations=array(
			"k_word"=>array("Text"=>"K Word", "Rule"=>"max_length[3]"),
			"grp"=>array("Text"=>"Grp", "Rule"=>"required|max_length[100]"),
			"title"=>array("Text"=>"Title", "Rule"=>"required|max_length[100]"),
			"status"=>array("Text"=>"Status", "Rule"=>"max_length[1]"),
			"subject"=>array("Text"=>"Subject", "Rule"=>"required|max_length[150]"),
			"props"=>array("Text"=>"Props"),
			"content"=>array("Text"=>"Content", "Rule"=>"required")

		);
	}

	public function GetPropertyRawOptions($property,$isWithSelect=false){
	    $returnObj=array();
		switch ($property) {
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
            case "status":
                $returnObj=array("A"=>"success","I"=>"danger");
                break;
	      default:
	    }
        return $returnObj;

	}

    /**
     * From version 1.1.0
     */
	static function UpdateTemplateGroup(){
        $k_words = [ 'ANT', 'AAT', 'ANR' ];

        foreach ( $k_words as $k_word ) {
            $uo = new Mapbd_wps_email_templates();
            $uo->grp( 'To Admin or Agent' );
            $uo->SetWhereUpdate( "k_word", $k_word );
            $uo->Update();
        }
    }

    /**
     * From version 1.2.0
     */
    static function UpdateTemplateGroup2(){
        $k_words = [ 'EOT', 'ETR', 'ETC' ];

        foreach ( $k_words as $k_word ) {
            $uo = new Mapbd_wps_email_templates();
            $uo->grp( 'To Customer (Email to Ticket)' );
            $uo->subject( 'Re: {{ticket_title}}' );
            $uo->SetWhereUpdate( "k_word", $k_word );
            $uo->Update();
        }
    }

    /**
     * From version 1.3.1
     */
    static function UpdateTemplateGroup3(){
        $k_words = [ 'ANT', 'AAT', 'ANR', 'UOT', 'TRR', 'TCL', 'EOT', 'ETR', 'ETC' ];

        foreach ( $k_words as $k_word ) {
            $uo = new Mapbd_wps_email_templates();
            $uo->props( '' );
            $uo->SetWhereUpdate( "k_word", $k_word );
            $uo->Update();
        }
    }

    function Save(){
	    if(!$this->IsSetPrperty("k_word")){
	        $k_word=$this->GetNewIncId("k_word","AAA");
	        $this->k_word($k_word);
	    }
	    return parent::Save();
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
                      `k_word` char(3) NOT NULL DEFAULT '',
                      `grp` char(100) NOT NULL DEFAULT '',
                      `title` char(100) NOT NULL DEFAULT '',
                      `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'bool(A=Active,I=Inactive)',
                      `subject` char(150) NOT NULL DEFAULT '',
                      `props` text NOT NULL DEFAULT '',
                      `content` text NOT NULL,
                      PRIMARY KEY (`k_word`) USING BTREE,
                      UNIQUE KEY `email_keyword` (`k_word`) USING BTREE
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

	/**
	 * @param $keyword
	 * @param string $props
	 *
	 * @return array
	 */
	public static function getEmailParamList($keyword,$props=NULL) {
		$return_obj = array();
		$return_obj["site_name"] = get_bloginfo('name');
		$return_obj["site_url"] = home_url();

		if(!$props){
		    $props=self::getEmailParamString($keyword);
        }
		if(!$props){
		    $obj=self::FindBy("k_word",$keyword);
		    $props=!empty($obj->props)?$obj->props:"";
        }
		if(!empty($props)){
		    $params=explode(",",$props);
			foreach ( $params as $param ) {
                $paramar=explode("=",$param);
                if(!empty($paramar[0]) && !empty($paramar[1])){
	                $return_obj[trim($paramar[0])]=trim($paramar[1]);
                }
		    }
        }


		return $return_obj;
	}
	public static function getEmailParamString( $keyword ) {
		$params = [
            'ANT' => 'ticket_user=Name of ticket user,ticket_link=Ticket link,ticket_track_id=Ticket track id,ticket_title=Ticket title,ticket_category_id=Ticket category id,ticket_category_title=Ticket category title,ticket_body=Ticket body,custom_field__slug=Ticket custom field',
            'AAT' => 'ticket_assigned_user=Name of ticket assigned user,ticket_user=Name of ticket user,ticket_link=Ticket URL,view_ticket_anchor=Ticket link, ticket_track_id=Ticket track id,ticket_title=Ticket title,ticket_category_id=Ticket category id,ticket_category_title=Ticket category title,ticket_body=Ticket body,custom_field__slug=Ticket custom field',
            'ANR' => 'ticket_replied_user=User who replied,replied_text=Replied Text,ticket_status=Ticket current status,ticket_assigned_user=Name of ticket assigned user,ticket_user=Name of ticket user,ticket_link=Ticket URL,view_ticket_anchor=Ticket link,ticket_track_id=Ticket track id,ticket_title=Ticket title,ticket_category_id=Ticket category id,ticket_category_title=Ticket category title,ticket_body=Ticket body,custom_field__slug=Ticket custom field',
            'UOT' => 'ticket_user=Name of ticket user,ticket_replied_user=The user who replaied last,replied_text=Replied Text,ticket_link=Ticket URL,view_ticket_anchor=Ticket link,ticket_track_id=Ticket track id,ticket_title=Ticket title,ticket_category_id=Ticket category id,ticket_category_title=Ticket category title,ticket_body=Ticket body,ticket_priroty=Ticket priroty,ticket_open_app_time=Ticket open time in app timezone (UTC),custom_field__slug=Ticket custom field',
            'TRR' => 'ticket_replied_user=The user who replied last,replied_text=Replied Text,ticket_link=Ticket link,ticket_track_id=Ticket track id,ticket_title=Ticket title,ticket_category_id=Ticket category id,ticket_category_title=Ticket category title,ticket_body=Ticket body,ticket_open_app_time=Ticket open time in app timezone (UTC),custom_field__slug=Ticket custom field',
            'TCL' => 'ticket_feedback_button=Ticket Feedback Buttons,ticket_reopen_by=The user who reopen this ticket,ticket_closing_msg=Ticket Closing Message defined in Ticket settings,ticket_user=Name of ticket user,ticket_replied_user=The user who replaied last,replied_text=Replied Text,ticket_link=Ticket URL,view_ticket_anchor=Ticket link,ticket_track_id=Ticket track id,ticket_title=Ticket title,ticket_category_id=Ticket category id,ticket_category_title=Ticket category title,ticket_body=Ticket body,ticket_priroty=Ticket priroty,ticket_open_app_time=Ticket open time in app timezone (UTC),custom_field__slug=Ticket custom field',
            'EOT' => 'ticket_user=Name of ticket user,ticket_replied_user=The user who replied last,replied_text=Replied Text,ticket_track_id=Ticket track id,ticket_link=Ticket URL,view_ticket_anchor=Ticket link,ticket_title=Ticket title,ticket_category_id=Ticket category id,ticket_category_title=Ticket category title,ticket_body=Ticket body,ticket_priroty=Ticket priroty,ticket_open_app_time=Ticket open time in app timezone (UTC),custom_field__slug=Ticket custom field',
            'ETR' => 'ticket_replied_user=The user who replied last,replied_text=Replied Text,ticket_link=Ticket URL,view_ticket_anchor=Ticket link,ticket_track_id=Ticket track id,ticket_title=Ticket title,ticket_category_id=Ticket category id,ticket_category_title=Ticket category title,ticket_body=Ticket body,ticket_open_app_time=Ticket open time in app timezone (UTC),custom_field__slug=Ticket custom field',
            'ETC' => 'ticket_feedback_button=Ticket Feedback Buttons,ticket_reopen_by=The user who reopen this ticket,ticket_closing_msg=Ticket Closing Message defined in Ticket settings,ticket_user=Name of ticket user,ticket_replied_user=The user who replaied last,replied_text=Replied Text,ticket_link=Ticket URL,view_ticket_anchor=Ticket link,ticket_track_id=Ticket track id,ticket_title=Ticket title,ticket_category_id=Ticket category id,ticket_category_title=Ticket category title,ticket_body=Ticket body,ticket_priroty=Ticket priroty,ticket_open_app_time=Ticket open time in app timezone (UTC),custom_field__slug=Ticket custom field',
        ];

        return ( isset( $params[ $keyword ] ) ? $params[ $keyword ] : '' );
	}
	public static function getEmailParamListClearData($kayword){
		$return_obj=self::getEmailParamList($kayword);
		$return_obj=array_map(function($value){
			$value="";
		}, $return_obj);
		$return_obj["site_name"] = get_bloginfo('name');;
		$return_obj["site_url"]  = home_url();
		return $return_obj;
	}
    public static function get_all_files($path)
    {
        $attached_files=[];
        $allowed_files = Apbd_wps_settings::GetModuleAllowedFileTypeStr();
        $path = rtrim($path, '/');
        if (is_dir($path)) {
            foreach (glob($path . '/*.{' . $allowed_files . '}', GLOB_BRACE) as $file) {
                $attached_files[] = realpath($file);
            }
        }
        return $attached_files;
    }
	public static function AddNewTemplate($k_word,$grp, $title,$status,$subject,$props,$content){
		$obj=new self();
		if(!$obj->IsExists("k_word",$k_word)){
			$newobj=new self();
			$newobj->k_word($k_word);
			$newobj->grp($grp);
			$newobj->title($title);
			$newobj->status($status);
			$newobj->subject($subject);
			$newobj->content($content);
			$newobj->props($props);
			return $newobj->Save();
		}else{
			return false;
		}
	}
	static  function SendEmailTemplates($keyword,$toEmail,$params=array(),$subject="",$reply_to='',$attachments=[])
    {
                if (empty($toEmail)) {
            return true;
        }
        $obj = self::FindBy("k_word", $keyword);
        if (!empty($obj)) {
            if ($obj->status != "A") {
                return true;
            }
        }
        if (!isset($params["site_name"])) {
            $params["site_name"] = get_bloginfo('name');
        }
        if (!isset($params["site_url"])) {
            $params["site_url"] = home_url();
        }
        $search = array();
        $replace = array();
        foreach ($params as $key => $value) {
            $search[] = "{{" . $key . "}}";
            $replace[] = $value;
        }
        $content = str_replace($search, $replace, $obj->content);
        if (in_array($keyword, ['UOT', 'TRO', 'TRR', 'TAC', 'EOT','ETR']) && !empty($params["ticket_track_id"]) && !empty($params["ticket_title"])) {
            $content = self::getTicketEmailText($params["ticket_track_id"], $content, $params["ticket_title"]);
        }
        if (in_array($keyword, ['EOT', 'ETR', 'ETC'])) {
            $subject = "Re: {{ticket_title}}";
        } elseif (empty($subject)) {
            $subject = $obj->subject;
        }
        $subject = str_replace($search, $replace, $subject);
        $headers = array('Content-Type: text/html; charset=UTF-8');
        if (!empty($reply_to)) {
            $headers[] = 'Reply-To: ' . $reply_to;
        }
        if (!wp_mail($toEmail, $subject, $content, $headers, $attachments)) {
            return false;
        } else {
            return true;
        }
    }
    static function getTicketEmailText($ticket_track_id,$content,$ticket_title=''){
        ob_start()?>
        <!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <style>
                html{
                    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;
                }
                .em-d-none{
                    display:none;
                }
                .em-reply-line{
                    color:rgb(226, 223, 223); border-top: 1px dotted #ccc;font-size: 12px;
                }
            </style>
        </head>
        <body data-start="start-here" itemscope itemtype="http://schema.org/EmailMessage">
            <div id="full-email-body">
                <div class="em-d-none"><?php echo wp_kses_no_null($ticket_title); ?></div>
                <div class="em-d-none" >--start--</div>
                <div class="body-container">
                    <div class="replay-line em-reply-line"><?php echo esc_html(Apbd_wps_mailbox::GetModuleOption('emr_txt'));?></div>
                    <div class="mail-container">
                        <div class="mail-content">
                            <?php echo wp_kses_no_null($content);?>
                        </div>
                    </div>
                </div>
                <div class="em-d-none">ref:<?php echo esc_attr($ticket_track_id); ?>:ref</div>
                <div class="em-d-none">--end--</div>
            </div>
        </body>
        </html><?php
        return ob_get_clean();
    }

	 function GetAddForm($label_col=5,$input_col=7,$mainobj=null,$except=array(),$disabled=array()){
        if ( ! $mainobj ) {
            $mainobj = $this;
        }

        $keyword = $mainobj->k_word;

        $paramlist = self::getEmailParamList( $keyword );
        ?>

         <div class="form-row">
             <div class="form-group col-sm-8">
                 <label class="col-form-label" for="subject"><?php $this->_ee("Subject"); ?></label>
                 <?php if ( 'EOT' === $keyword || 'ETR' === $keyword || 'ETC' === $keyword ) {
                    ?>
                    <input class="form-control form-control-sm" type="text" maxlength="150"
                        value="Re: {{ticket_title}}" id="subject"
                        placeholder="<?php $this->_ee("Subject"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Subject"); ?>" readonly>
                    <?php
                 } else {
                    ?>
                    <input class="form-control form-control-sm" type="text" maxlength="150"
                        value="<?php echo esc_attr($mainobj->GetPostValue("subject")); ?>" id="subject" name="subject"
                        placeholder="<?php $this->_ee("Subject"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Subject"); ?>">
                    <?php
                 } ?>
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="status"><?php $this->_ee("Status"); ?></label>
                 <?php
                 APBD_GetHTMLSwitchButton("status", "status", "I", "A", $mainobj->GetPostValue("status", "A"));
                 ?>
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm-8">
                 <label class="col-form-label" for="content"><?php $this->_ee("Content"); ?></label>
                 <textarea class="form-control form-control-sm apd-wp-editor h-300px" type="text" id="content"
                           name="content" placeholder="<?php $this->_ee("Content"); ?>" data-bv-notempty="true"
                           data-bv-notempty-message="<?php $this->_ee("%s is required", "Content"); ?>"><?php echo wp_kses_post( stripslashes( $mainobj->GetPostValue("content") ) ); ?></textarea>
             </div>
             <div class="col-sm-4">
                 <div class="card border-success">
                     <div class="card-header  bg-success text-white border-success">
                         <?php $this->_e("Properties"); ?>
                     </div>
                     <div class="card-body p-0 overflow-auto">
                         <table class="table table-striped m-0 table-sm table-td-wrap">
                             <thead>
                             <tr class="">
                                 <th class="w20px"></th>
                                 <th class="w120px"><?php $this->_e("Property"); ?></th>
                                 <th><?php $this->_e("Description"); ?></th>
                             </tr>
                             </thead>
                             <tbody>
                             <?php foreach ($paramlist as $key => $des) { ?>
                                 <tr>
                                     <th>
                                         <i title="<?php $this->_ee("Click to insert {{%s}} to editor", esc_attr($key)); ?>"
                                            class="fz16 apbd-editor-insert-btn ap ap-insert app-ins-btn text-green text-bold"
                                            data-tooltip-position="left" data-tooltip-delay="2000"
                                            data-text="{{<?php echo esc_attr($key); ?>}}"></i></th>
                                     <th>{{<?php echo esc_html($key); ?>}}</th>
                                     <td><?php $this->_e($des); ?></td>
                                 </tr>
                             <?php } ?>
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>
         </div>
			<?php
	}

}
?>