<?php
/**
 * @since: 13/Jan/2022
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @property:ID,user_login,user_pass,user_nicename,user_email,user_url,user_registered,user_activation_key,user_status,display_name
 */
class Mapbd_wps_users extends AppsBDModel{
	public $ID;
	public $user_login;
	public $user_pass;
	public $user_nicename;
	public $user_email;
	public $user_url;
	public $user_registered;
	public $user_activation_key;
	public $user_status;
	public $display_name;


	    /**
	     *@property ID,user_login,user_pass,user_nicename,user_email,user_url,user_registered,user_activation_key,user_status,display_name
		 */
		function __construct() {
			parent::__construct ();
			$this->SetValidation();
			$this->tableName="users";
			$this->primaryKey="ID";
			$this->uniqueKey=array();
			$this->multiKey=[];
			$this->autoIncField=array("ID");
			$this->app_base_name="support-genix-lite";

		}


	function SetValidation(){
		$this->validations=array(
			"ID"=>array("Text"=>"ID", "Rule"=>"max_length[20]"),
			"user_login"=>array("Text"=>"User Login", "Rule"=>"max_length[60]"),
			"user_pass"=>array("Text"=>"User Pass", "Rule"=>"max_length[255]"),
			"user_nicename"=>array("Text"=>"User Nicename", "Rule"=>"max_length[50]"),
			"user_email"=>array("Text"=>"User Email", "Rule"=>"max_length[100]|valid_email"),
			"user_url"=>array("Text"=>"User Url", "Rule"=>"max_length[100]"),
			"user_activation_key"=>array("Text"=>"User Activation Key", "Rule"=>"max_length[255]"),
			"user_status"=>array("Text"=>"User Status", "Rule"=>"max_length[11]|integer"),
			"display_name"=>array("Text"=>"Display Name", "Rule"=>"max_length[250]")

		);
	}

    static function CreateDBTable(){
		$thisObj=new static();
		$table=$thisObj->db->prefix.$thisObj->tableName;
        $charsetCollate = $thisObj->db->has_cap( 'collation' ) ? $thisObj->db->get_charset_collate() : '';

		if($thisObj->db->get_var("show tables like '{$table}'") != $table){
			$sql = "CREATE TABLE `{$table}` (
					  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					  `user_login` varchar(60) NOT NULL DEFAULT '',
					  `user_pass` varchar(255) NOT NULL DEFAULT '',
					  `user_nicename` varchar(50) NOT NULL DEFAULT '',
					  `user_email` varchar(100) NOT NULL DEFAULT '',
					  `user_url` varchar(100) NOT NULL DEFAULT '',
					  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
					  `user_activation_key` varchar(255) NOT NULL DEFAULT '',
					  `user_status` int(11) NOT NULL DEFAULT 0,
					  `display_name` varchar(250) NOT NULL DEFAULT '',
					  PRIMARY KEY (`ID`),
					  KEY `user_login_key` (`user_login`),
					  KEY `user_nicename` (`user_nicename`),
					  KEY `user_email` (`user_email`)
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
                 <label class="col-form-label" for="user_login"><?php $this->_ee("User Login"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="60"
                        value="<?php echo esc_attr($mainobj->GetPostValue("user_login")); ?>" id="user_login"
                        name="user_login" placeholder="<?php $this->_ee("User Login"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "User Login"); ?>">
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="user_pass"><?php $this->_ee("User Pass"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="255"
                        value="<?php echo esc_attr($mainobj->GetPostValue("user_pass")); ?>" id="user_pass"
                        name="user_pass" placeholder="<?php $this->_ee("User Pass"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "User Pass"); ?>">
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="user_nicename"><?php $this->_ee("User Nicename"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="50"
                        value="<?php echo esc_attr($mainobj->GetPostValue("user_nicename")); ?>" id="user_nicename"
                        name="user_nicename" placeholder="<?php $this->_ee("User Nicename"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "User Nicename"); ?>">
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="user_email"><?php $this->_ee("User Email"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="100"
                        value="<?php echo esc_attr($mainobj->GetPostValue("user_email")); ?>" id="user_email"
                        name="user_email" placeholder="<?php $this->_ee("User Email"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "User Email"); ?>">
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="user_url"><?php $this->_ee("User Url"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="100"
                        value="<?php echo esc_attr($mainobj->GetPostValue("user_url")); ?>" id="user_url"
                        name="user_url" placeholder="<?php $this->_ee("User Url"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "User Url"); ?>">
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="user_registered"><?php $this->_ee("User Registered"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength=""
                        value="<?php echo esc_attr($mainobj->GetPostValue("user_registered")); ?>" id="user_registered"
                        name="user_registered" placeholder="<?php $this->_ee("User Registered"); ?>"
                        data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "User Registered"); ?>">
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label"
                        for="user_activation_key"><?php $this->_ee("User Activation Key"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="255"
                        value="<?php echo esc_attr($mainobj->GetPostValue("user_activation_key")); ?>"
                        id="user_activation_key" name="user_activation_key"
                        placeholder="<?php $this->_ee("User Activation Key"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "User Activation Key"); ?>">
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="user_status"><?php $this->_ee("User Status"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="11"
                        value="<?php echo esc_attr($mainobj->GetPostValue("user_status")); ?>" id="user_status"
                        name="user_status" placeholder="<?php $this->_ee("User Status"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "User Status"); ?>">
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="display_name"><?php $this->_ee("Display Name"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="250"
                        value="<?php echo esc_attr($mainobj->GetPostValue("display_name")); ?>" id="display_name"
                        name="display_name" placeholder="<?php $this->_ee("Display Name"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Display Name"); ?>">
             </div>
         </div>
			<?php
	}


}