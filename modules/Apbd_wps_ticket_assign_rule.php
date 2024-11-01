<?php
/**
 * @since: 21/Oct/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_wps_ticket_assign_rule extends AppsBDLiteModule {



	   function initialize() {
            parent::initialize();
            $this->disableDefaultForm();
            $this->AddAjaxAction("add",[$this,"add"]);
            $this->AddAjaxAction("edit",[$this,"edit"]);
            $this->AddAjaxAction("delete_item",[$this,"delete_item"]);

			$this->AddAjaxAction("status_change",[$this,"status_change"]);
        }

        function SettingsPage() {
            $this->SetTitle("Ticket Assign Rule List");
            $this->Display();
        }

       function GetMenuTitle() {
            return $this->__( "Ticket Assign Rule" );
        }

        function GetMenuSubTitle() {
            return $this->__( "Ticket Assign Rule Settings" );
        }

        function GetMenuIcon() {
            return "fa fa-th-large";
        }

       function add(){
            $this->SetTitle("Add New Rule");
            $this->SetPOPUPColClass ( "col-sm-6" );

            if(APPSBD_IsPostBack){
                $nobject=new Mapbd_wps_ticket_assign_rule();
                if($nobject->SetFromPostData(true)){
                    if($nobject->Save()){
                        $this->AddInfo("Successfully added");
                        APBD_AddLog("A",$nobject->settedPropertyforLog(),"l001","");
                        $this->DisplayPOPUPMsg();
                        return;
                    }
                }
            }
            $mainobj=new Mapbd_wps_ticket_assign_rule();
            $this->AddViewData("isUpdateMode",false);
            $this->AddViewData("mainobj",$mainobj);
            $this->DisplayPOPUp("add");
	    }
       function edit($param_id=""){
            $this->SetPOPUPColClass ( "col-sm-6" );

           $param_id=APBD_GetValue("id");
           if(empty($param_id)){
                 $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
            $this->SetTitle("Edit Ticket Assign Rule");
            if(APPSBD_IsPostBack){
                    $uobject=new Mapbd_wps_ticket_assign_rule();
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
            $mainobj=new Mapbd_wps_ticket_assign_rule();
            $mainobj->id($param_id);
            if(!$mainobj->Select()){
                 $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
            APBD_OldFields($mainobj, "cat_ids,rule_type,rule_id,status");
            $this->AddViewData("mainobj", $mainobj);
            $this->AddViewData("isUpdateMode", true);
            $this->DisplayPOPUP("add");
       }


	   function data(){
	        $mainResponse = new AppsbdAjaxDataResponse();
    		$mainResponse->setDownloadFileName("apbd-wps-ticket-assign-rule-list");
        	$mainobj=new Mapbd_wps_ticket_assign_rule();
        	$mainResponse->setDateRange($mainobj);
            $records=$mainobj->CountALL($mainResponse->srcItem, $mainResponse->srcText,$mainResponse->multiparam,"after");
        	if($records>0){
        		$mainResponse->SetGridRecords($records);
        		$result=$mainobj->SelectAllGridData("", $mainResponse->orderBy, $mainResponse->order, $mainResponse->rows, $mainResponse->limitStart, $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam,"after");
        		if($result){
                    $role_list = Mapbd_wps_role::FetchAllKeyValue("id","name");
                    $category_list = Mapbd_wps_ticket_category::FetchAllKeyValue("id","title");
                    $category_list['0']=$this->__("All Categories");
                    $status_change=$mainobj->GetPropertyOptionsTag("status");
            	    $rule_type_options=$mainobj->GetPropertyOptionsTag("rule_type");

        			foreach ($result as &$data){
        				$data->action="";
        				$data->action.="<a data-effect='mfp-move-from-top' class='popupformWR btn btn-info btn-xs' href='" .$this->GetActionUrl("edit",["id"=>$data->id])."'>".$this->__("Edit")."</a>";
                        $data->action.=" <a class='ConfirmAjaxWR btn btn-danger btn-xs' data-msg='".$this->__("Are you sure to delete?")."' href='" .$this->GetActionUrl("delete_item",["id"=>$data->id])."'>".$this->__("Delete")."</a>";

                        $ctglist=explode(',',$data->cat_ids) ;
                        $data->cat_ids="";
                        foreach ($ctglist as $ctgId){
                            $data->cat_ids.='<i class="fa fa-dot-circle-o"></i> '.APBD_getTextByKey($ctgId,$category_list)."<br>";
                        }
						$data->status=" <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='".$this->__("Are you sure to change?")."' href='" .$this->GetActionUrl("status_change",["id"=>$data->id])."'>".APBD_getTextByKey($data->status,$status_change)."</a>";
                        $user = get_userdata($data->rule_id);
                        if($data->rule_type=="A"){
                            $data->rule_id="(".__("Role").") ".APBD_getTextByKey($data->rule_id,$role_list);
                        }if($data->rule_type=="S"){
                            $data->rule_id="(".__("User").") ".$user->display_name;
                        }if($data->rule_type=="N"){
                            $data->rule_id="(".__("User").") ".$user->display_name;
                        }
						$data->rule_type=APBD_getTextByKey($data->rule_type,$rule_type_options);


        			}
        		}
        		$mainResponse->SetGridData($result);
    		}
    		$mainResponse->DisplayGridResponse();
	   }


	    function delete_item($param=""){
            $param=APBD_GetValue("id");
	        $mainResponse = new AppsbdAjaxConfirmResponse();
            if(empty($param)){
                 $mainResponse->DisplayWithResponse(false, $this->__("Invalid Request"));
                 return;
            }
            $mr=new Mapbd_wps_ticket_assign_rule();
            $mr->id($param);
            if($mr->Select()){
                if(Mapbd_wps_ticket_assign_rule::DeleteById($param)){
                    APBD_AddLog("D","id={$param}", "l003","Wp_apbd_wps_ticket_assign_rule_confirm");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully deleted"));
                }else{
                    $mainResponse->DisplayWithResponse(false,__("Delete failed try again"));
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
            $mr=new Mapbd_wps_ticket_assign_rule();
            $statusChange=$mr->GetPropertyOptionsTag("status");

            $mr->id($param);
            if($mr->Select("status")){
                $newStatus=$mr->status=="A"?"I":"A";
                $uo=new Mapbd_wps_ticket_assign_rule();
                $uo->status($newStatus);
                $uo->SetWhereUpdate("id",$param);
                if( $uo->Update()){
                    $status_text = APBD_getTextByKey($uo->status, $statusChange);
                    APBD_AddLog("U",$uo->settedPropertyforLog(), "l002","Wp_apbd_wps_ticket_assign_rule");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), $status_text);
                }else{
                    $mainResponse->DisplayWithResponse(false, $this->__("Update failed try again"));
                }

            }

        }




}