<?php

/**
 * @since: 17/Aug/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @property:id,name,slug,role_description,status
 */
class Mapbd_wps_role extends AppsBDModel
{
    public $id;
    public $name;
    public $parent_role;
    public $slug;
    public $role_description;
    public $is_agent;
    public $is_editable;
    public $status;
    public $is_admin_role=false;
    /**
     * @var  Mapbd_wps_role[]
     */
    protected static $_rolelist=null;

    /**
     * @property id,name,slug,role_description,status
     */
    function __construct()
    {
        parent::__construct();
        $this->SetValidation();
        $this->tableName = "apbd_wps_role";
        $this->primaryKey = "id";
        $this->uniqueKey=array(array("slug"));
        $this->multiKey=array();
        $this->autoIncField=array("id");
        $this->app_base_name="support-genix-lite";

    }


    function SetValidation()
    {
        $this->validations = array(
            "id" => array("Text" => "Id", "Rule" => "max_length[11]|integer"),
            "name" => array("Text" => "Name", "Rule" => "max_length[150]"),
            "parent_role" => array("Text" => "Parent Role", "Rule" => "max_length[11]"),
            "slug" => array("Text" => "Slug", "Rule" => "max_length[255]"),
            "is_editable" => array("Text" => "Status", "Rule" => "max_length[1]"),
            "is_agent" => array("Text" => "Is Agent", "Rule" => "max_length[1]"),
            "status" => array("Text" => "Status", "Rule" => "max_length[1]")

        );
    }

    public function GetPropertyRawOptions($property, $isWithSelect = false)
    {
        $returnObj = array();
        switch ($property) {
            case "status":
                $returnObj = array("A" => "Active", "I" => "Inactive");
                break;
            case "is_agent":
                $returnObj = array("Y" => "Yes", "N" => "No");
                break;
            default:
        }
        if ($isWithSelect) {
            return array_merge(array("" => "Select"), $returnObj);
        }
        return $returnObj;

    }


    public function GetPropertyOptionsColor($property){
        $returnObj=array();
        switch ($property) {
            case "status":
                $returnObj = array("A" => "success", "I" => "danger");
                break;
            default:
        }
        return $returnObj;

    }
    /**
     * @return array
     */
    public static function getAgentRoles()
    {
        $agent_roles = Mapbd_wps_role::FindAllBy("status", "A", ["is_agent" => "Y"]);
        $res=[];
        foreach ($agent_roles as $agent_role) {
            $res[]=$agent_role->slug;
        }
        return $res;
    }
    static function DeleteBySlug($slug){
        if(self::DeleteByKeyValue("slug",$slug)){
            Mapbd_wps_role_access::DeleteByRoleSlug($slug);
            return true;
        }
        return false;
    }

    static function GetSlugBy($str){
        $slug=sanitize_title_with_dashes('awps-' . $str);
        if(strlen($slug)>97){
            $slug=substr($slug,0,97);
        }
        $obj=new self();
        $newSlug=$slug;
        $counter=1;
        while($obj->IsExists("slug",$newSlug)) {
            $obj=new self();
            $newSlug=$slug.$counter++;
        }
        if($newSlug=='administrator'){
            $newSlug='admin_'.hash('crc32b',time());
        }
        return $newSlug;

    }
    //auto generated
    static function IsBuiltInRole($role){
        $predefined = ['administrator', 'editor', 'author', 'contributor', 'subscriber'];
        return in_array($role, $predefined);
    }
    static function SetDefaultRole()
    {

        $existingRoles = wp_roles()->get_names();
        foreach ($existingRoles as $key => $existingRole) {
            if ($key == "subscriber") {
                continue;
            }
            $obj = new Mapbd_wps_role();
            $obj->slug($key);
            if (!$obj->Select()) {
                if ($key == "administrator" || !self::IsBuiltInRole($key)) {
                    $isEditable=!($key == "administrator" || self::IsBuiltInRole($key));
                    Mapbd_wps_role::AddRole($key, $existingRole, $isEditable, $key == "administrator");
                }
            }
        }
        $agent_slug=sanitize_title_with_dashes('awps-support-agent');
        $manager_slug=sanitize_title_with_dashes('awps-support-manager');
        Mapbd_wps_role::AddRoleIfNotExists($manager_slug, "Support Manager", true, true);
        Mapbd_wps_role::AddRoleIfNotExists($agent_slug, "Support Agent", true, true);
        $existingRoles = wp_roles()->get_names();

        if (!isset($existingRoles[$agent_slug])) {
            add_role($agent_slug, "Support Agent", ['read' => true, 'level_0' => true]);
        }
        if (!isset($existingRoles[$manager_slug])) {
            add_role($manager_slug, "Support Manager", ['read' => true, 'level_0' => true]);
        }

        $agent_access = [
            'assign-me', 'change-privacy', 'closed-ticket-list', 'create-note',
            'edit-assigned ', 'edit-category', 'edit-custom-field', 'edit-wc-order-source',
            'edit-elite-purchase-code', 'edit-envato-purchase-code', 'edit-status',
            'move-to-trash', 'ticket-reply', 'trash-ticket-menu', 'manage-other-agents-ticket',
            'manage-unassigned-ticket'
        ];
        $manager_access = ['delete-ticket', 'restore-ticket'];

        foreach ($agent_access as $agent_act) {
            Mapbd_wps_role_access::AddAccessIfNotExits($manager_slug,$agent_act);
            Mapbd_wps_role_access::AddAccessIfNotExits($agent_slug,$agent_act);
        }
        foreach ($manager_access as $mng_act) {
            Mapbd_wps_role_access::AddAccessIfNotExits($manager_slug,$mng_act);
        }

    }
    static function AddRole($slug,$name,$isEditable,$isAgent) {
        $n=new self();
        $n->slug($slug);
        $n->name($name);
        $n->is_editable($isEditable?'Y':'N');
        $n->is_agent($isAgent?'Y':'N');
        $n->status('A');
        return $n->Save();
    }
    static function AddRoleIfNotExists($slug,$name,$isEditable,$isAgent)
    {
        $n = new self();
        if (!$n->IsExists("slug", $slug)) {
            return self::AddRole($slug, $name, $isEditable, $isAgent);
        }
        return true;
    }


    function Save(){
        if(!$this->IsSetPrperty("slug") || empty($this->slug)) {
            $this->slug(self::GetSlugBy($this->name));
        }
        if(parent::Save()){
            do_action('apbd-wps/action/role-added',$this);
            return true;
        }else{
            return false;
        }
    }

    /**
     * From version 1.1.0
     */
	static function UpdateExStatus(){
        $rolesWithStatusY = Mapbd_wps_role::FindAllBy( 'status', 'Y', [] );
        $rolesWithStatusN = Mapbd_wps_role::FindAllBy( 'status', 'N', [] );

        $roles = array_merge( $rolesWithStatusY, $rolesWithStatusN );

        foreach ( $roles as $roleObj ) {
            $roleId = $roleObj->id;
            $roleStatus = $roleObj->status;
            $roleStatusNew = ( ( 'Y' === $roleStatus ) ? 'A' : 'I' );

            $uo = new Mapbd_wps_role();
            $uo->status( $roleStatusNew );
            $uo->SetWhereUpdate( "id", $roleId );
            $uo->Update();
        }
    }

    /**
     * From version 1.3.1
     */
    static function UpdateExAccess() {
        $allRoles = Mapbd_wps_role::GetRoleListWithCapabilities();

        if ( ! is_array( $allRoles ) || empty( $allRoles ) ) {
            return;
        }

        foreach ( $allRoles as $roleObj ) {
            if ( ! is_object( $roleObj ) ) {
                continue;
            }

            $capTxt = "capabilities";
            $roleId = ( isset( $roleObj->id ) ? $roleObj->id : 0 );
            $roleSlug = ( isset( $roleObj->slug ) ? $roleObj->slug : '' );
            $capablts = ( ( isset( $roleObj->$capTxt ) && is_array( $roleObj->$capTxt ) ) ? $roleObj->$capTxt : [] );

            if ( empty( $roleSlug ) || empty( $capablts ) || ! isset( $capablts["edit-purchase-code"] ) ) {
                continue;
            }

            $roleAccess = absint( $capablts["edit-purchase-code"] );
            $roleAccess = ( $roleAccess ? 'Y' : 'N' );
            $accessList = [ 'edit-elite-purchase-code', 'edit-envato-purchase-code' ];

            foreach ( $accessList as $resourceId ) {
                $s = new Mapbd_wps_role_access();
                $s->role_slug( $roleSlug );
                $s->resource_id( $resourceId );
                if ( ! $s->Select() ) {
                    $n = new Mapbd_wps_role_access();
                    $n->role_slug( $roleSlug );
                    $n->resource_id( $resourceId );
                    $n->role_access( $roleAccess );
                    $n->Save();
                }
            }

            Mapbd_wps_role_access::DeleteByKeyValue( 'id', $roleId );
        }
    }

    /**
     * From version 1.3.1
     */
    static function AddNewAccess() {
        $allRoles = Mapbd_wps_role::FetchAll();

        if ( ! is_array( $allRoles ) || empty( $allRoles ) ) {
            return;
        }

        foreach ( $allRoles as $roleObj ) {
            if ( ! is_object( $roleObj ) ) {
                continue;
            }

            $roleSlug = ( isset( $roleObj->slug ) ? $roleObj->slug : '' );
            $roleIsAgent = ( isset( $roleObj->is_agent ) ? $roleObj->is_agent : 'N' );

            if ( empty( $roleSlug ) || ( 'Y' !== $roleIsAgent ) ) {
                continue;
            }

            $accessList = [ 'edit-custom-field', 'edit-wc-order-source' ];

            foreach ( $accessList as $accessItem ) {
                Mapbd_wps_role_access::AddAccessIfNotExits( $roleSlug, $accessItem );
            }
        }
    }

    /**
     * From version 1.3.6
     */
    static function AddNewAccess2() {
        $allRoles = Mapbd_wps_role::FetchAll();

        if ( ! is_array( $allRoles ) || empty( $allRoles ) ) {
            return;
        }

        foreach ( $allRoles as $roleObj ) {
            if ( ! is_object( $roleObj ) ) {
                continue;
            }

            $roleSlug = ( isset( $roleObj->slug ) ? $roleObj->slug : '' );
            $roleIsAgent = ( isset( $roleObj->is_agent ) ? $roleObj->is_agent : 'N' );

            if ( empty( $roleSlug ) || ( 'Y' !== $roleIsAgent ) ) {
                continue;
            }

            $accessList = [ 'manage-other-agents-ticket', 'manage-unassigned-ticket' ];

            foreach ( $accessList as $accessItem ) {
                Mapbd_wps_role_access::AddAccessIfNotExits( $roleSlug, $accessItem );
            }
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

    static function CreateDBTable()
    {
        $thisObj = new static();
        $table = $thisObj->db->prefix . $thisObj->tableName;
        $charsetCollate = $thisObj->db->has_cap( 'collation' ) ? $thisObj->db->get_charset_collate() : '';

        if ($thisObj->db->get_var("show tables like '{$table}'") != $table) {
            $sql = "CREATE TABLE `{$table}` (
                    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL DEFAULT '',
                    `parent_role` int(11) unsigned NOT NULL DEFAULT 0,
                    `slug` char(100) NOT NULL DEFAULT '',
                    `role_description` text NOT NULL COMMENT 'textarea',
                    `is_editable` char(1) NOT NULL DEFAULT 'Y' COMMENT 'bool(Y=Yes,N=No)',
                    `is_agent` char(1) NOT NULL DEFAULT 'N' COMMENT 'bool(Y=Yes,N=No)',
                    `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'bool(A=Active,I=Inactive)',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `slug_ind` (`slug`) USING BTREE
					) $charsetCollate;";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    function DropDBTable()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . $this->tableName;
        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query($sql);
    }
    static function GetRoleObjectBy($slug,$name,$parent_role,$description,$isAdminRole=false){
        $roleObject=new self();
        $roleObject->name=$name;
        $roleObject->slug=$slug;
        $roleObject->is_admin_role=$isAdminRole;
        $roleObject->role_description=$description;
        $roleObject->parent_role=$parent_role;
        return $roleObject;

    }
    /* add custom function here*/
    /**
     * @return Mapbd_wps_role[];
     */
    static function  GetRoleList( ){
        if(is_null(self::$_rolelist)) {
            $obj=new self();
            $obj->is_agent('Y');
            $obj->status('A');
            self::$_rolelist =$obj->SelectAllWithIdentity('slug','','is_editable','ASC');
            self::$_rolelist = apply_filters('elite-wps/acl-roles', self::$_rolelist);
        }
        return self::$_rolelist;
    }


    /**
     * @return Mapbd_wps_role[]|null
     */
    static function GetRoleListWithCapabilities( ){
        $roles=self::GetRoleList();
        $acls=Mapbd_wps_role_access::FindAllBy("role_access",'Y',[],'role_slug');
        foreach ($acls as $acl) {
            if(!empty($roles[$acl->role_slug])){
                if(empty($roles[$acl->role_slug]->capabilities)){
                    $roles[$acl->role_slug]->capabilities=[];
                }
                $roles[$acl->role_slug]->capabilities[$acl->resource_id]=true;
            }
        }
        return $roles;
    }

    /**
     * @param $allCaps
     * @param WP_User $user
     */
    static function SetCapabilitiesByRole($allCaps,$user){
        $roles=self::GetRoleListWithCapabilities();
        if($user instanceof  WP_User) {
            foreach ($user->roles as $role_slug) {
                if($role_slug=='administrator'){
                    $resource=Mapbd_wps_role_access::GetResourceList();
                    foreach ($resource as $res) {
                        $allCaps[$res->action_param]=true;
                    }
                    break;
                }else {
                    if (!empty($roles[$role_slug]) && !empty($roles[$role_slug]->capabilities)) {
                        $allCaps = array_merge($allCaps, $roles[$role_slug]->capabilities);
                    }
                }
            }
        }
        return $allCaps;
    }
    static function IsAdminRole($slug)
    {
        if (is_null(self::$_rolelist)) {
            self::GetRoleList();
        }
        if (!empty(self::$_rolelist[$slug]) && self::$_rolelist[$slug]->is_admin_role) {
            return true;
        }
        return false;
    }
    /* end custom function */
    function GetAddForm($label_col = 5, $input_col = 7, $mainobj = null, $except = array(), $disabled = array())
    {

        if (!$mainobj) {
            $mainobj = $this;
        }
        ?>
        <div class="form-row">
            <div class="form-group col-sm">
                <label class="col-form-label" for="name"><?php $this->_ee("Name"); ?></label>
                <input class="form-control form-control-sm" type="text" maxlength="150"
                       value="<?php echo esc_attr($mainobj->GetPostValue("name")); ?>"
                       id="name" <?php echo in_array("name", $disabled) ? ' disabled="disabled" ' : ' name="name" '; ?>
                       placeholder="<?php $this->_ee("Name"); ?>" data-bv-notempty="true"
                       data-bv-notempty-message="<?php $this->_ee("%s is required", "Name"); ?>">

            </div>
            <div class="form-group col-sm-4">
                <label class="col-form-label" for="is_agent"><?php $this->_ee("Support Genix Agent"); ?></label>
                <?php
                APBD_GetHTMLSwitchButton("is_agent", "is_agent", "N", "Y", $mainobj->GetPostValue("is_agent", "Y"));
                ?>
            </div>
            <div class="form-group col-sm-2">
                <label class="col-form-label" for="status"><?php $this->_ee("Status"); ?></label>
                <?php
                APBD_GetHTMLSwitchButton("status", "status", "I", "A", $mainobj->GetPostValue("status", "A"));
                ?>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm">
                <label class="col-form-label"
                       for="role_description"><?php $this->_ee("Role Description"); ?></label>
                <textarea class="form-control form-control-sm"
                          id="role_description"
                          name="role_description"
                          placeholder="<?php $this->_ee("Role Description"); ?>"
                ><?php echo wp_kses_post($mainobj->GetPostValue("role_description")); ?></textarea>
            </div>
        </div>
        <?php
    }


}