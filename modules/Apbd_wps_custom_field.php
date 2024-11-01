<?php
/**
 * @since: 12/Oct/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_wps_custom_field extends AppsBDLiteModule {



	   function initialize() {
            parent::initialize();
            $this->disableDefaultForm();
            $this->AddAjaxAction("add",[$this,"add"]);
            $this->AddAjaxAction("edit",[$this,"edit"]);
            $this->AddAjaxAction("delete_item",[$this,"delete_item"]);

			$this->AddAjaxAction("is_required_change",[$this,"is_required_change"]);
			$this->AddAjaxAction("status_change",[$this,"status_change"]);
			$this->AddAjaxAction("order_change",[$this,"order_change"]);
			$this->AddAjaxAction("reset_order",[$this,"reset_order"]);
        }

        function SettingsPage() {
            $this->SetTitle("Custom Field List");
            $this->Display();
        }

        function GetMenuTitle() {
            return $this->__( "Custom Field" );
        }

        function GetMenuSubTitle() {
            return $this->__( "Custom Field Settings" );
        }

        function GetMenuIcon() {
            return "fa fa-trello";
        }

       function add(){
            $this->SetTitle("Add New Field");
            $this->SetPOPUPColClass ( "col-lg-8" );

            if(APPSBD_IsPostBack){
                $nobject=new Mapbd_wps_custom_field();
                if($nobject->SetFromPostData(true)){
                    if($nobject->Save()){
                        $this->AddInfo("Successfully added");
                        APBD_AddLog("A",$nobject->settedPropertyforLog(),"l001","");
                        $this->DisplayPOPUPMsg();
                        return;
                    }
                }
            }
            $mainobj=new Mapbd_wps_custom_field();
            $this->AddViewData("isUpdateMode",false);
            $this->AddViewData("mainobj",$mainobj);
            $this->DisplayPOPUp("add");
	    }
       function edit($param_id=""){
            $this->SetPOPUPColClass ( "col-lg-8" );

           $param_id=APBD_GetValue("id");
           if(empty($param_id)){
                 $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
            $this->SetTitle("Edit Custom Field");
            if(APPSBD_IsPostBack){
                    $uobject=new Mapbd_wps_custom_field();
                    $category_arr=APBD_PostValue('category_arr',[]);
                    $category_arr_str = ( is_array( $category_arr ) ? implode( ',', $category_arr ) : '' );
                    $uobject->choose_category($category_arr_str);
                    if($uobject->SetFromPostData(false)){
                        $uobject->SetWhereUpdate("id",$param_id);
                        if($uobject->Update()){
                            APBD_AddLog("U",$uobject->settedPropertyforLog(),"l002","");
                            $this->AddInfo("Successfully updated");
                            $this->DisplayPOPUPMsg();
                            return;
                        }
                    }
            }
            $mainobj=new Mapbd_wps_custom_field();
            $mainobj->id($param_id);
            if(!$mainobj->Select()){
                 $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
            APBD_OldFields($mainobj, "field_label,help_text,choose_category,where_to_create,create_for,field_type,is_required,status,is_half_field");
            $this->AddViewData("mainobj", $mainobj);
            $this->AddViewData("isUpdateMode", true);
            $this->DisplayPOPUP("add");
       }


	   function data(){
	        $mainResponse = new AppsbdAjaxDataResponse();
    		$mainResponse->setDownloadFileName("apbd-wps-custom-field-list");
        	$mainobj=new Mapbd_wps_custom_field();
            $mainResponse->setOrderByIfEmpty('fld_order');
        	$mainResponse->setDateRange($mainobj);
            $records=$mainobj->CountALL($mainResponse->srcItem, $mainResponse->srcText,$mainResponse->multiparam,"after");
        	if($records>0){
        		$mainResponse->SetGridRecords($records);
        		$result=$mainobj->SelectAllGridData("", $mainResponse->orderBy, $mainResponse->order, $mainResponse->rows, $mainResponse->limitStart, $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam,"after");
        		if($result){
                    $category_list = Mapbd_wps_ticket_category::FetchAllKeyValue("id","title");
                    $category_list['0']=$this->__("All Categories");
                    $is_required_change=$mainobj->GetPropertyOptionsTag("is_required");
					$status_change=$mainobj->GetPropertyOptionsTag("status");
					$is_half_field_change=$mainobj->GetPropertyOptionsTag("is_half_field");
            	    $where_to_create_options=$mainobj->GetPropertyOptionsTag("where_to_create");
					$field_type_options=$mainobj->GetPropertyOptionsTag("field_type");
        			foreach ($result as &$data){
        				$data->action="";
        				$data->action.="<a data-effect='mfp-move-from-top' class='popupformWR btn btn-info btn-xs' href='" .$this->GetActionUrl("edit",["id"=>$data->id])."'>".$this->__("Edit")."</a>";
        			    $data->action.=" <a class='ConfirmAjaxWR btn btn-danger btn-xs' data-msg='".$this->__("Are you sure to delete?")."' href='" .$this->GetActionUrl("delete_item",["id"=>$data->id])."'>".$this->__("Delete")."</a>";

                        $data->is_required=" <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='".$this->__("Are you sure to change?")."' href='" .$this->GetActionUrl("is_required_change",["id"=>$data->id])."'>".APBD_getTextByKey($data->is_required,$is_required_change)."</a>";
						$data->status=" <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='".$this->__("Are you sure to change?")."' href='" .$this->GetActionUrl("status_change",["id"=>$data->id])."'>".APBD_getTextByKey($data->status,$status_change)."</a>";
						$data->fld_order=" <a class='ConfirmAjaxWR' data-msg='".$this->__("Are you sure to change order?")."' href='" .$this->GetActionUrl("order_change",["id"=>$data->id,"typ"=>'u'])."'><i class='text-success fa fa-2x fa-caret-up'></i> </a> ".$data->fld_order;
                        $data->fld_order.=" <a class='ConfirmAjaxWR'  data-msg='".$this->__("Are you sure to change order?")."' href='" .$this->GetActionUrl("order_change",["id"=>$data->id,"typ"=>'d'])."'><i class='text-danger fa fa-2x fa-caret-down'></i> </a>";



                        if($data->where_to_create=="I"){
                            $data->choose_category="(".APBD_getTextByKey($data->where_to_create,$where_to_create_options).")";
                        }else{
                            $ctglist=explode(',',$data->choose_category) ;
                            $data->choose_category="";
                            foreach ($ctglist as $ctgId){
                                $data->choose_category.='<i class="fa fa-dot-circle-o"></i> '.APBD_getTextByKey($ctgId,$category_list)."<br>";
                            }
                        }

                        if($data->field_type=="W" || $data->field_type=="R"){
                            $optionss = "<strong>    Option: </strong>".$data->fld_option;
                        }else{
                            $optionss ="";
                        }
						$data->field_label=$data->field_label.'<br>'.'<strong>'.$this->__('Required:').'</strong>'.$data->is_required."   ".'<strong>'.$this->__('Status:').$data->status.'</strong><br><strong>'.$this->__('Type:').'</strong>'.APBD_getTextByKey($data->field_type,$field_type_options)."   ".'<strong>'.$this->__('Half Field: ').'</strong>'.APBD_getTextByKey($data->is_half_field,$is_half_field_change).$optionss;

        			}
        		}
        		$mainResponse->SetGridData($result);
    		}
    		$mainResponse->DisplayGridResponse();
	   }


	    function delete_item(){
            $param=APBD_GetValue("id");
            $mainResponse = new AppsbdAjaxConfirmResponse();
            if(empty($param)){
                 $mainResponse->DisplayWithResponse(false, $this->__("Invalid Request"));
                 return;
            }
            $mr=new Mapbd_wps_custom_field();
            $mr->id($param);
            if($mr->Select()){
                if(Mapbd_wps_custom_field::DeleteById($param)){
                    APBD_AddLog("D","id={$param}", "l003","Wp_apbd_wps_custom_field_confirm");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully deleted"));
                }else{
                    $mainResponse->DisplayWithResponse(false,$this->__("Delete failed try again"));
                }
            }
        }

        function is_required_change(){
           $param=APBD_GetValue("id");
           if(empty($param)){
                 $this->DisplayWithResponse(false, $this->__("Invalid Request"));
                 return;
            }
            $mainResponse = new AppsbdAjaxConfirmResponse();
            $mr=new Mapbd_wps_custom_field();
            $is_requiredChange=$mr->GetPropertyOptionsTag("is_required");

            $mr->id($param);
            if($mr->Select("is_required")){
                $newStatus=$mr->is_required=="Y"?"N":"Y";
                $uo=new Mapbd_wps_custom_field();
                $uo->is_required($newStatus);
                $uo->SetWhereUpdate("id",$param);
                if( $uo->Update()){
                    $status_text = APBD_getTextByKey($uo->is_required, $is_requiredChange);
                    APBD_AddLog("U",$uo->settedPropertyforLog(), "l002","Wp_apbd_wps_custom_field");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), $status_text);
                }else{
                    $mainResponse->DisplayWithResponse(false, $this->__("Update failed try again"));
                }

            }

        }

        function status_change(){
           $param=APBD_GetValue("id");
           if(empty($param)){
                 $this->DisplayWithResponse(false, $this->__("Invalid Request"));
                 return;
            }
            $mainResponse = new AppsbdAjaxConfirmResponse();
            $mr=new Mapbd_wps_custom_field();
            $statusChange=$mr->GetPropertyOptionsTag("status");

            $mr->id($param);
            if($mr->Select("status")){
                $newStatus=$mr->status=="A"?"I":"A";
                $uo=new Mapbd_wps_custom_field();
                $uo->status($newStatus);
                $uo->SetWhereUpdate("id",$param);
                if( $uo->Update()){
                    $status_text = APBD_getTextByKey($uo->status, $statusChange);
                    APBD_AddLog("U",$uo->settedPropertyforLog(), "l002","Wp_apbd_wps_custom_field");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), $status_text);
                }else{
                    $mainResponse->DisplayWithResponse(false, $this->__("Update failed try again"));
                }

            }

        }


    function reset_order() {
        $mainResponse = new AppsbdAjaxConfirmResponse();
        Mapbd_wps_custom_field::ResetOrder();
        $mainResponse->DisplayWithResponse( true, "Successfully Reset");
    }
    function order_change( ) {
        $type= APBD_GetValue('typ');
        $id = APBD_GetValue("id");
        $mainResponse = new AppsbdAjaxConfirmResponse();
        if ( empty( $id ) || empty($type) ) {
            $mainResponse->DisplayWithResponse(false, "Invalid Request" );
            return;
        }
        if ( Mapbd_wps_custom_field::changeOrder( $id ,$type) ) {
            $mainResponse->DisplayWithResponse(true, "Successfully Updated" );
        } else {
            $mainResponse->DisplayWithResponse(false, APBD_GetMsg_API()."Failed, may be this item is first or last item" );
        }

    }
}