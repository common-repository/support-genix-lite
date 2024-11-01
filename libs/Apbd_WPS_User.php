<?php
/**
 * @since: 08/07/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_WPS_User extends AppsBDModel
{
    public $id;
    public $first_name;
    public $last_name;
    public $username;
    public $email;
    public $password;
    public $contact_no;
    public $role;

    function __construct()
    {
        parent::__construct();
        $this->SetValidation();
    }


    function SetValidation()
    {
        $this->validations = array(
            "id" => array("Text" => "Id", "Rule" => "max_length[11]|integer"),
            "first_name" => array("Text" => "First Name", "Rule" => "required|max_length[255]"),
            "last_name" => array("Text" => "Last Name", "Rule" => "max_length[255]"),
            "username" => array("Text" => "Username", "Rule" => "max_length[60]|required"),
            "password" => array("Text" => "Password", "Rule" => "max_length[60]"),
            "email" => array("Text" => "Status", "Rule" => "required|max_length[100]"),
            "role" => array("Text" => "Role", "Rule" => "max_length[100]"),
        );
    }
    static function auto_login( $user_id ) {
        if ( !is_user_logged_in() ) {
            $user = get_user_by( "ID",$user_id );
            wp_signon(['user_login'=>$user->user_login],is_ssl());
        }
    }
	/**
	 * @param WP_User $getUser
	 * @return object
	 */
	public static function get_user_data($getUser) {
		$user = new self();
		$user->id=$getUser->ID;
		$user->first_name=$getUser->first_name;
		$user->last_name = $getUser->last_name;
		$user->email = $getUser->user_email;
		$user->role = $getUser->roles[0];
		return $user;
	}
	public function IsValidForm($isNew = true, $addError = true)
    {
        if($isNew) {
            if (email_exists($this->email) || (!empty($this->username) && username_exists($this->username))) {
                $this->AddError("Email or username is already exists");
                return false;
            }
        }
        return parent::IsValidForm($isNew, $addError);     }

    /**
     * @param $userPayload
     * @param false $isCreatedByAdmin
     * @return |null
     */
    static function create_ticket_user($userPayload,$isCreatedByAdmin=false)
    {
        $userObj = new self();
        $userObj->SetFromArray($userPayload);
        $userObj->role=Apbd_wps_settings::GetModuleOption("client_role",'subscriber');
        if ($userObj->IsValidForm(true)) {
            $userObj = apply_filters('apbd-wps/filter/before-create-user', $userObj);
            if ($userObj->Save()) {
                $customFields=!empty($userPayload['custom_fields'])?$userPayload['custom_fields']:null;
                do_action('apbd-wps/action/user-created', $userObj,$customFields);
                return $userObj->id;
            }
        }
        return null;
    }
    function Save()
    {
        $user = new WP_User();
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
	    if (email_exists($this->email) || (!empty($this->username) && username_exists($this->username))) {
	        $this->AddError("Email or username is already exists");
            return false;
        }
        $user->user_nicename = $this->username;
        $user->user_login = $this->username;
        $user->user_email = $this->email;
        $user->user_status = true;
        if (empty($this->password)) {
            $this->password = wp_generate_password();
            $user->user_pass = $this->password;
        }
        $user->user_pass = $this->password;
        $user_id = wp_insert_user($user);
        $userGet = new WP_User($user_id);
        $userGet->set_role($this->role);
        if (empty($this->id) && !empty($user_id)) {
            $this->id = $user_id;
        }

        return !empty($user_id);
    }

    function Update($notLimit = false, $isShowMsg = true, $dontProcessIdWhereNotset = true)
    {
        $user = new WP_User($this->id);
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->user_email = $this->email;
        $user_id = wp_update_user($user);
        if (empty($this->id) && !empty($user_id)) {
            $this->id = $user_id;
        }
        return !empty($user_id);
    }

    function Delete($notLimit = false, $isShowMsg = true, $dontProcessIdWhereNotset = true)
    {
        return false;
    }

}

