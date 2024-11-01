<?php
/**
 * @since: 07/Oct/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 * @property:id,title,parent_category,parent_category_path,show_on,status
 */
class Mapbd_wps_ticket_category extends AppsBDModel{
	public $id;
	public $title;
	public $parent_category;
	public $parent_category_path;
	public $show_on;
	public $status;


	    /**
	     *@property id,title,parent_category,parent_category_path,show_on,status
		 */
		function __construct() {
			parent::__construct ();
			$this->SetValidation();
			$this->tableName="apbd_wps_ticket_category";
			$this->primaryKey="id";
			$this->uniqueKey=array();
			$this->multiKey=array();
			$this->autoIncField=array("id");
			$this->app_base_name="support-genix-lite";

		}


	function SetValidation(){
		$this->validations=array(
			"id"=>array("Text"=>"Id", "Rule"=>"max_length[10]|integer"),
			"title"=>array("Text"=>"Title", "Rule"=>"max_length[150]"),
			"parent_category"=>array("Text"=>"Parent Category", "Rule"=>"max_length[10]|integer"),
			"parent_category_path"=>array("Text"=>"Parent Category Path", "Rule"=>"max_length[50]"),
			"show_on"=>array("Text"=>"Show On", "Rule"=>"max_length[1]"),
			"status"=>array("Text"=>"Status", "Rule"=>"max_length[1]")

		);
	}

	public function GetPropertyRawOptions($property,$isWithSelect=false){
	    $returnObj=array();
		switch ($property) {
	      case "show_on":
	         $returnObj=array("B"=>"Both","K"=>"Only Knowledge","T"=>"Only on Ticket");
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
	      case "show_on":
	         $returnObj=array("B"=>"success","K"=>"success","T"=>"success");
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
	      case "show_on":
	         $returnObj=array("B"=>"","K"=>"","T"=>"");
	         break;
	      default:
	    }
        return $returnObj;

	}
    static function getAllCategories(){
        $mainObj=new Mapbd_wps_ticket_category();
        $mainObj->status('A');
        $categories=$mainObj->SelectAllGridData();
        $categories=apply_filters('apbd-wps/filter/category',$categories);
        return $categories;
    }
    static function getAllCategoriesWithParents($cat_id,$counter=1) {

	    $returnCats = [];
	    if ( $cat_id != 0 ) {
		    $ctg = Mapbd_wps_ticket_category::FindBy( "id", $cat_id );
		    if ( ! empty( $ctg ) ) {
			    $returnCats[] = $cat_id;
			    if ( ! empty( $ctg->parent_category ) ) {
				    if ( empty( $counter <= 15 ) ) {
					    $returnCats = array_merge( $returnCats,
						    self::getAllCategoriesWithParents( $ctg->parent_category, $counter + 1 ) );
				    }
			    }
		    }
	    }

	    return $returnCats;

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
					  `title` char(150) NOT NULL DEFAULT '',
					  `parent_category` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'FK({$table},id,title)',
					  `parent_category_path` char(50) NOT NULL,
					  `show_on` char(1) NOT NULL DEFAULT 'B' COMMENT 'radio(B=Both,K=Only Knowledge,T=Only on Ticket)',
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

	 function GetAddForm($label_col=5,$input_col=7,$mainobj=null,$except=array(),$disabled=array()){

				if(!$mainobj){
				$mainobj=$this;
				}
					?>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="title"><?php $this->_ee("Title"); ?></label>
                 <input class="form-control form-control-sm" type="text" maxlength="150"
                        value="<?php echo esc_attr($mainobj->GetPostValue("title")); ?>" id="title" name="title"
                        placeholder="<?php $this->_ee("Title"); ?>" data-bv-notempty="true"
                        data-bv-notempty-message="<?php $this->_ee("%s is required", "Title"); ?>">
             </div>
             <div class="form-group col-sm">
                 <label class="col-form-label" for="parent_category"><?php $this->_ee("Parent Category"); ?></label>
                 <?php $options_parent_category = Mapbd_wps_ticket_category::FetchAllKeyValue("id", "title"); ?>
                 <select class="custom-select form-control-sm" id="parent_category" name="parent_category">
                     <option value=""><?php $this->_e("Select"); ?></option>
                     <?php $parent_category_selected = $mainobj->GetPostValue("parent_category");
                     APBD_GetHTMLOptionByArray($options_parent_category, $parent_category_selected);
                     ?>
                 </select>
             </div>
         </div>
         <div class="form-row">
             <div class="form-group col-sm">
                 <label class="col-form-label" for="status"><?php $this->_ee("Status"); ?></label>
                 <?php
                 APBD_GetHTMLSwitchButton("status", "status", "I", "A", $mainobj->GetPostValue("status", "A"));
                 ?>
             </div>
         </div>
			<?php
	}


}