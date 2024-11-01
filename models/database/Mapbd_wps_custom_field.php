<?php

/**
 * @since: 12/Oct/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @property:id,field_label,field_slug,help_text,choose_category,where_to_create,field_type,is_required,status,table_datalist
 * @property string input_name
 * @property string v_rules
 */
class Mapbd_wps_custom_field extends AppsBDModel{
	public $id;
	public $field_label;
	public $field_slug;
	public $help_text;
	public $choose_category;
	public $fld_option;
	public $fld_order;
	public $where_to_create;
	public $create_for;
	public $field_type;
	public $is_required;
	public $status;
	public $is_half_field;


	    /**
	     *@property id,field_label,field_slug,help_text,choose_category,where_to_create,field_type,is_required,status,table_datalist
		 */
		function __construct() {
			parent::__construct ();
			$this->SetValidation();
			$this->tableName="apbd_wps_custom_field";
			$this->primaryKey="id";
			$this->uniqueKey=array();
			$this->multiKey=array();
			$this->autoIncField=array("id");
			$this->app_base_name="support-genix-lite";

		}
		public function SetFromPostData($isNew = false)
        {
	        $category_arr=SupportGNInput::post_value('category_arr',[]);
	        $categorList = implode(',', (!empty($category_arr) ? $category_arr : []));
            if ($isNew || $categorList==='0' || !empty($categorList)) {
                $this->choose_category($categorList);
            }

            if ( isset( $_POST['field_label'] ) && isset( $_POST['field_slug'] ) ) {
                $current_field_id = APBD_GetValue( 'id' );
                $current_field_id = absint( $current_field_id );

                $field_label = sanitize_text_field( $_POST['field_label'] );
                $field_slug = sanitize_text_field( $_POST['field_slug'] );

                $field_slug = ( ( empty( $field_slug ) && ! empty( $field_label ) ) ? $field_label : $field_slug );
                $field_slug = strtolower( $field_slug );
                $field_slug = str_replace( array( ' ', '-' ), array( '_', '_' ), $field_slug );
                $field_slug = preg_replace( '/[^\w-]+$/', '', $field_slug );

                $field_slug = $this->GenerateUniqueFieldSlug( $field_slug, $current_field_id );

                $_POST['field_slug'] = $field_slug;
            }

            return parent::SetFromPostData($isNew);
        }

    function SetValidation(){
		$this->validations=array(
			"id"=>array("Text"=>"Id", "Rule"=>"max_length[11]|integer"),
			"field_label"=>array("Text"=>"Field Label", "Rule"=>"max_length[255]"),
            "field_slug"=>array("Text"=>"Field Slug", "Rule"=>"max_length[255]"),
            "help_text"=>array("Text"=>"Help Text", "Rule"=>"max_length[255]"),
			"choose_category"=>array("Text"=>"Choose Category", "Rule"=>"max_length[225]"),
			"fld_order"=>array("Text"=>"Order", "Rule"=>"max_length[3]|integer"),
			"where_to_create"=>array("Text"=>"Where To Create", "Rule"=>"max_length[1]"),
			"create_for"=>array("Text"=>"Create for", "Rule"=>"max_length[1]"),
			"field_type"=>array("Text"=>"Field Type", "Rule"=>"max_length[1]"),
			"is_required"=>array("Text"=>"Is Required", "Rule"=>"max_length[1]"),
			"status"=>array("Text"=>"Status", "Rule"=>"max_length[1]"),
			"is_half_field"=>array("Text"=>"Is Half Field", "Rule"=>"max_length[1]")
		);
	}

	public function GetPropertyRawOptions($property,$isWithSelect=false){
	    $returnObj=array();
		switch ($property) {
	      case "where_to_create":
	         $returnObj=array("I"=>"In Registration Form","T"=>"Ticket Open Form Category");
	         break;
	         case "create_for":
	         $returnObj=array("A"=>"Admin Only","B"=>"Both(Clients & Admin)");
	         break;
	      case "field_type":
	         $returnObj=array("T"=>"Textbox","N"=>"Numeric","D"=>"Date","S"=>"Switch","R"=>"Radio","W"=>"Dropdown","E"=>"Text/Instruction","U"=>"URL Input");
	         break;
	      case "is_required":
	         $returnObj=array("Y"=>"Yes","N"=>"No");
	         break;
	      case "status":
	         $returnObj=array("A"=>"Active","I"=>"Inactive");
	         break;
         case "is_half_field":
	         $returnObj=array("Y"=>"Yes","N"=>"No");
	         break;
	      default:
	    }
        if($isWithSelect){
            return array_merge(array(""=>"Select"),$returnObj);
        }
        return $returnObj;

	}
	public function GetPropertyOptionsColor($property,$isWithSelect=false){
		$returnObj=array();
		switch ($property) {
			case "is_required":
				$returnObj=array("Y"=>"success","N"=>"danger");
				break;
			case "status":
				$returnObj=array("A"=>"success","I"=>"danger");
				break;
			case "is_half_field":
				$returnObj=array("Y"=>"success","N"=>"danger");
				break;
			default:
		}
		return $returnObj;
	}

	public function GenerateUniqueFieldSlug( $field_slug, $current_field_id ) {
        if ( ! empty( $field_slug ) ) {
            $existing_field = Mapbd_wps_custom_field::FindBy( 'field_slug', $field_slug );
            $existing_field_id = ( ( is_object( $existing_field ) && isset( $existing_field->id ) ) ? absint( $existing_field->id ) : 0 );

            if ( ! empty( $existing_field_id ) && ( $existing_field_id !== $current_field_id ) ) {
                $field_slug = $field_slug . '_2';
                $field_slug = $this->GenerateUniqueFieldSlug( $field_slug, $current_field_id );
            }
        }

        return $field_slug;
    }



    /**
     * @param $datalist
     * @param self $fld
     * @param array $currentArray
     * @return array|mixed
     */
    static function getParentArray(&$datalist, $choose_category,$label=1)    {
        if($label>=10){
            return [];
        }
        $currentArray = explode(",", $choose_category);
        foreach ($currentArray as $key=>$ctg_id) {
            $currentArray[$key]=$ctg_id;
            if (!empty($ctg_id) && $ctg_id != 0 && !empty($datalist[$ctg_id])) {
                $itemArray = self::getParentArray($datalist, $datalist[$ctg_id]->parent_category,$label+1);
                $currentArray = array_merge($currentArray, $itemArray);
            }elseif(empty($ctg_id) || $ctg_id == 0){
                unset($currentArray[$key]);
            }
        }
        return $currentArray;
    }
    static function getCustomFieldForAPI()
    {
        $mainobj=new self();
	    $mainobj->status('A');
        $custom_fields = new stdClass();
        $custom_fields->reg_form = [];
        $custom_fields->ticket_form=[];
        $isClient=Apbd_wps_settings::isClientLoggedIn();
        if($isClient){
            $mainobj->create_for('B');
        }
        $data = $mainobj->SelectAllGridData("id,field_label,field_slug,help_text,choose_category,where_to_create,field_type,fld_option,fld_order,is_required,status,is_half_field", "fld_order", 'asc');

        $ctgs=Mapbd_wps_ticket_category::FindAllByIdentiry("status","A","id");
        foreach ($data as &$fld) {
            $fld->input_name="D".$fld->id;
            $fld->v_rules=($fld->is_required=="Y"?'required':'');
            if ($fld->where_to_create == "I") {
                $custom_fields->reg_form[] = $fld;
            }else{
                $fld->categories =self::getParentArray($ctgs,$fld->choose_category);
                $fld->categories=array_values(array_unique($fld->categories));
                $custom_fields->ticket_form[] = $fld;
            }
	        $fld->choose_category=explode(',',$fld->choose_category);
        }
        return $custom_fields;
    }
    static function getCustomFieldForTicketDetailsAPI($ticket_id)
    {
        $custom_fields = new stdClass();
        $custom_fields->reg_form = [];
        $custom_fields->ticket_form = [];
        $ticket=Mapbd_wps_ticket::FindBy("id",$ticket_id);
        $isClient=Apbd_wps_settings::isClientLoggedIn();
        if(!empty($ticket)) {
            $categories=Mapbd_wps_ticket_category::getAllCategoriesWithParents($ticket->cat_id);
            $mainobj = new self();
            $mainobj->status('A');
            if($isClient) {
                $mainobj->create_for('B');
            }
            $data = $mainobj->SelectAllGridData("id,field_label,field_slug,help_text,choose_category,where_to_create,field_type,fld_option,fld_order,is_required,status,is_half_field", "fld_order", 'asc');
            $ctgs = Mapbd_wps_ticket_category::FindAllByIdentiry("status", "A", "id");
            foreach ($data as &$fld) {
                $fldsCtgs=explode(",",$fld->choose_category);
                $isFound=in_array('0',$fldsCtgs);;
                if(!$isFound) {
                    foreach ($fldsCtgs as $fldsCtg) {
                        if (in_array($fldsCtg, $categories)) {
                            $isFound = true;
                            break;
                        }
                    }
                }
                if(!$isFound){
                    continue;
                }
                $fld->input_name = "D" . $fld->id;
                $fld->v_rules = ($fld->is_required == "Y" ? 'required' : '');
                if ($fld->where_to_create == "I") {
                    $custom_fields->reg_form[] = $fld;
                } else {
                    $fld->categories = self::getParentArray($ctgs, $fld->choose_category);
                    $fld->categories = array_values(array_unique($fld->categories));
                    $custom_fields->ticket_form[] = $fld;
                }
                $fld->choose_category = explode(',', $fld->choose_category);
            }
        }
        return $custom_fields;
    }
    function Save(){
        $totalFild=$this->GetNewIncId("fld_order",1);
        $this->fld_order($totalFild);
        return parent::Save();
    }

    /**
     * From version 1.0.9
     */
    static function UpdateDBTable(){
        $thisObj = new static();
        $table = $thisObj->db->prefix.$thisObj->tableName;

        if ( $thisObj->db->get_var( "show tables like '{$table}'" ) == $table ) {
            $sql = "ALTER TABLE `{$table}` ADD `field_slug` char(255) NOT NULL DEFAULT ''";
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
			$sql = "CREATE TABLE `{$table}`(
                      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                      `field_label` char(255) NOT NULL DEFAULT '',
                      `field_slug` char(255) NOT NULL DEFAULT '',
                      `help_text` char(255) NOT NULL DEFAULT '',
                      `choose_category` char(225) NOT NULL DEFAULT '' COMMENT 'FK(wp_apbd_wps_ticket_category,id,title)',
                      `fld_option` text NOT NULL DEFAULT '',
                      `fld_order` int(3) unsigned NOT NULL,
                      `where_to_create` char(1) NOT NULL DEFAULT 'I' COMMENT 'radio(I=In Registartion Form,T=Ticket Open Form Category)',
                      `create_for` char(1) NOT NULL DEFAULT 'B' COMMENT 'radio(A=Admin Only,B=Both)',
                      `field_type` char(1) NOT NULL DEFAULT 'T' COMMENT 'radio(T=Textbox,N=Numeric,D=Date,S=Switch,R=Radio,W=Dropdown,E=Text/Instruction,U=URL Input)',
                      `is_required` char(1) NOT NULL COMMENT 'bool(Y=Yes,N=No)',
                      `status` char(1) NOT NULL COMMENT 'bool(A=Active,I=Inactive)',
                      `is_half_field` char(1) NOT NULL DEFAULT 'N' COMMENT 'bool(Y=Yes,N=No)',
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

    public static function changeOrder($id,$type) {
        $currentField=Mapbd_wps_custom_field::FindBy("id",$id);
        if($currentField) {
            $preOrPost = new self();
            if ( strtolower( $type ) == "u" ) {

                $preOrPost->fld_order( "<" . $currentField->fld_order ,true);
                $fields=$preOrPost->SelectAll('','fld_order','DESC',1);
            } else {
                                $preOrPost->fld_order( ">" . $currentField->fld_order ,true);
                $fields=$preOrPost->SelectAll('','fld_order','ASC',1);
            }


            if ( !empty($fields[0]) ) {
                $preOrPost=$fields[0];
                $nfirst = new self();
                $nfirst->fld_order( $preOrPost->fld_order );
                $nfirst->SetWhereUpdate( "id", $currentField->id );
                if ( $nfirst->Update() ) {
                    $nprevious = new self();
                    $nprevious->fld_order( $currentField->fld_order );
                    $nprevious->SetWhereUpdate( "id", $preOrPost->id );
                    return $nprevious->Update();
                }
            }
        }
        return false;

    }
    public static function ResetOrder(){
        $flds=Mapbd_wps_custom_field::FetchAll('','id','ASC');
        $order=1;
        foreach ( $flds as $fld ) {
            $uobj=new self();
            $uobj->fld_order($order);
            $uobj->SetWhereUpdate("id",$fld->id);
            if($uobj->Update(false,false)){

            }
            $order++;
        }

    }

    static function DeleteById($id){
        return  parent::DeleteByKeyValue("id",$id);
    }

	 function GetAddForm($label_col=5,$input_col=7,$mainobj=null,$except=array(),$disabled=array()){

				if(!$mainobj){
				$mainobj=$this;
				}
					?>

         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="field_label"><?php $this->_ee("Field Label"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="255"
                        value="<?php echo esc_attr($mainobj->GetPostValue("field_label")); ?>" id="field_label"
                        name="field_label" placeholder="<?php $this->_ee("Field Label"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Field Label"); ?>">
             </div>
             <div class="form-group col-sm">
                <label class="col-form-label" for="field_slug"><?php printf( $this->___( 'Field Slug %1$s(leave blank for auto generate)%2$s', '<span style="font-weight: 500; color: #777;">', '</span>' ) ); ?></label>
                <input class="form-control form-control-sm" type="text" maxlength="255"
                        value="<?php echo esc_attr($mainobj->GetPostValue("field_slug")); ?>" id="field_slug"
                        name="field_slug" placeholder="<?php $this->_ee("Field Slug"); ?>" data-bv-notempty="false"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Field Slug"); ?>">
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="help_text"><?php $this->_ee("Placeholder"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="255"
                        value="<?php echo esc_attr($mainobj->GetPostValue("help_text")); ?>" id="help_text"
                        name="help_text" placeholder="<?php $this->_ee("Help Text"); ?>">

             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="where_to_create"><?php $this->_ee("Where To Create"); ?></label>
                 <div class="form-row">
                     <?php
                     $where_to_create_selected = $mainobj->GetPostValue("where_to_create", "T");
                     $where_to_create_isDisabled = [
                         "T" => '<div class="apbd-rdo-container"><div class="apbd-img-ctnr"><img src="' . plugins_url('images/custom_field/tckt_open_form.png', Apbd_wps_custom_field::GetModuleInstance()->pluginFile) . '"/></div><div class="apbd-ot" >' . $this->__('Ticket Open Form') . '</div></div>',
                         "I" => '<div class="apbd-rdo-container"><div class="apbd-img-ctnr"><img src="' . plugins_url('images/custom_field/reg.png', Apbd_wps_custom_field::GetModuleInstance()->pluginFile) . '"/></div><div class="apbd-ot" >' . $this->__('In Registration Form') . '</div></div>',
                     ];

                     APBD_GetHTMLRadioBoxByArrayWithCols("row row-cols-2 row-cols-sm-2 row-cols-md-2", "col-sm p-0", $this->__("Where To Create"), "where_to_create", "where_to_create", true, $where_to_create_isDisabled, $where_to_create_selected, "", "", "has_depend_fld");

                     ?>
                 </div>
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="create_for"><?php $this->_ee("Create for"); ?></label>
                 <div class="form-row">
                     <?php
                     $rule_type_selected = $mainobj->GetPostValue("create_for", "A");
                     $rule_type_isDisabled = in_array("create_for", $disabled);
                     APBD_GetHTMLRadioByArray("Create for", "create_for", "create_for", true, $mainobj->GetPropertyOptions("create_for"), $rule_type_selected, $rule_type_isDisabled, false, "");
                     ?>

                 </div>
             </div>

         </div>
         <div class="form-row fld-where-to-create fld-where-to-create-t">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="choose_category"><?php $this->_ee("Choose Category"); ?></label>
                 <?php $options_choose_category = Mapbd_wps_ticket_category::FetchAllKeyValue("id", "title");
                 $choose_category_list = APBD_PostValue("category_arr", explode(',', $mainobj->choose_category));
                 ?>
                 <select class="custom-select app-select2-picker form-control-sm" multiple="multiple"
                         id="choose_category" name="category_arr[]">
                     <option value="0" <?php echo empty($choose_category_list) || in_array(0, $choose_category_list) ? 'selected="selected"' : ''; ?>><?php $this->_e("All Categories"); ?></option>
                     <?php
                     APBD_GetHTMLOptionByArray($options_choose_category, $choose_category_list);
                     ?>
                 </select>
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="field_type"><?php $this->_ee("Field Type"); ?></label>
                 <div class="form-row">
                     <?php
                     $field_type_selected = $mainobj->GetPostValue("field_type", "T");
                     $field_type_isDisabled = [
                         "T" => '<div class="apbd-rdo-container"><img src="' . plugins_url('images/custom_field/text_box.png', Apbd_wps_settings::GetModuleInstance()->pluginFile) . '"/><div class="apbd-ot">' . $this->__('Textbox') . '</div></div>',
                         "N" => '<div class="apbd-rdo-container"><img src="' . plugins_url('images/custom_field/numeric.png', Apbd_wps_settings::GetModuleInstance()->pluginFile) . '"/><div class="apbd-ot">' . $this->__('Numeric') . '</div></div>',
                         "D" => '<div class="apbd-rdo-container"><img src="' . plugins_url('images/custom_field/sg_date.png', Apbd_wps_settings::GetModuleInstance()->pluginFile) . '"/><div class="apbd-ot">' . $this->__('Date') . '</div></div>',
                         "S" => '<div class="apbd-rdo-container"><img src="' . plugins_url('images/custom_field/switch.png', Apbd_wps_settings::GetModuleInstance()->pluginFile) . '"/><div class="apbd-ot">' . $this->__('Switch') . '</div></div>',
                         "R" => '<div class="apbd-rdo-container"><img src="' . plugins_url('images/custom_field/radio.png', Apbd_wps_settings::GetModuleInstance()->pluginFile) . '"/><div class="apbd-ot">' . $this->__('Radio') . '</div></div>',
                         "W" => '<div class="apbd-rdo-container"><img src="' . plugins_url('images/custom_field/dropdown.png', Apbd_wps_settings::GetModuleInstance()->pluginFile) . '"/><div class="apbd-ot">' . $this->__('Dropdown') . '</div></div>',
                         "E" => '<div class="apbd-rdo-container" title="' . $this->__('Text/Instruction') . '"><img src="' . plugins_url('images/custom_field/text_inst.png', Apbd_wps_settings::GetModuleInstance()->pluginFile) . '"/><div class="apbd-ot" >' . $this->__('Text/Instruction') . '</div></div>',
                         "U" => '<div class="apbd-rdo-container"><img src="' . plugins_url('images/custom_field/url.png', Apbd_wps_settings::GetModuleInstance()->pluginFile) . '"/><div class="apbd-ot">' . $this->__('URL Input') . '</div></div>',

                     ];

                     APBD_GetHTMLRadioBoxByArrayWithCols("row row-cols-4 row-cols-sm-6 row-cols-md-8", "col p-0", $this->__("Field Type"), "field_type", "field_type", true, $field_type_isDisabled, $field_type_selected, "", "", "has_depend_fld");
                     ?>
                 </div>
             </div>
         </div>
         <div class="form-row fld-field-type fld-field-type-w fld-field-type-r fld-field-type-e">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="fld_option"><?php $this->_ee("Option"); ?></label>
                 <textarea class="form-control form-control-sm apbd-tag-input" type="text" maxlength="255"
                           id="fld_option" name="fld_option" placeholder="<?php $this->_ee("Field Option"); ?>"
                           data-bv-notempty="true"
                           data-bv-notempty-message="<?php $this->_ee("%s is required", "Field Option"); ?>"><?php echo wp_kses_post($mainobj->GetPostValue("fld_option")); ?></textarea>
                 <span class="text-warning fld-field-type fld-field-type-w fld-field-type-r"><?php $this->_e("Comma( , ) separated. Ex: OptionA,OptionB,OptionC"); ?></span>
                 <span class="text-warning fld-field-type fld-field-type-e"><?php $this->_e("Maximum 255 character"); ?></span>
             </div>
         </div>
         <div class="d-md-flex justify-content-start">
             <div class="form-group row ml-1 mr-3">
                 <label class="col-form-label mt-1 mr-1" for="cf_is_required"><?php $this->_ee("Is Required"); ?></label>
                 <div class="mt-3">
                     <?php
                     APBD_GetHTMLSwitchButton("cf_is_required", "is_required", "N", "Y", $mainobj->GetPostValue("is_required"), false, "", 'bg-mat', 'material-switch-xs ');
                     ?>
                 </div>
             </div>
             <div class="form-group row ml-1 mr-3">
                 <label class="col-form-label mt-1 mr-1" for="status"><?php $this->_ee("Status"); ?></label>
                 <div class="mt-3">
                     <?php
                     APBD_GetHTMLSwitchButton("status", "status", "I", "A", $mainobj->GetPostValue("status", "A"), false, "", 'bg-mat', 'material-switch-xs ');
                     ?>
                 </div>
             </div>
             <div class="form-group row ml-1 mr-3">
                 <label class="col-form-label mt-1 mr-1"
                        for="is_half_field"><?php $this->_ee("Is Half Field"); ?></label>
                 <div class="mt-3">
                     <?php
                     APBD_GetHTMLSwitchButton("is_half_field", "is_half_field", "N", "Y", $mainobj->GetPostValue("is_half_field"), false, "", 'bg-mat', 'material-switch-xs ');
                     ?>
                 </div>
             </div>
         </div>
			<?php
	}


}
