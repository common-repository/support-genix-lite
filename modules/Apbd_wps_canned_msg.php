<?php
/**
 * @since: 18/Nov/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_wps_canned_msg extends AppsBDLiteModule {



	   function initialize() {
            parent::initialize();
            $this->disableDefaultForm();
            $this->AddAjaxAction("add",[$this,"add"]);
            $this->AddAjaxAction("edit",[$this,"edit"]);
            $this->AddAjaxAction("delete_item",[$this,"delete_item"]);

			$this->AddAjaxAction("status_change",[$this,"status_change"]);
        }

        function SettingsPage() {
            $this->SetTitle("Saved Replies");
            $this->Display();
        }

        function GetMenuTitle() {
            return $this->__( "Saved Replies" );
        }

        function GetMenuSubTitle() {
            return $this->__( "Saved Replies Settings" );
        }

        function GetMenuIcon() {
            return "fa fa-inbox";
        }

       function add(){
            $this->SetTitle("Add New Message ");
            $this->SetPOPUPColClass ( "col-sm-12" );

            if(APPSBD_IsPostBack){
                $nobject=new Mapbd_wps_canned_msg();
                if($nobject->SetFromPostData(true)){
                    if($nobject->Save()){
                        $this->AddInfo("Successfully added");
                        APBD_AddLog("A",$nobject->settedPropertyforLog(),"l001","");
                        $this->DisplayPOPUPMsg();
                        return;
                    }
                }
            }
            $mainobj=new Mapbd_wps_canned_msg();
            $this->AddViewData("isUpdateMode",false);
            $this->AddViewData("mainobj",$mainobj);
            $this->DisplayPOPUp("add");
	    }
       function edit($param_id=""){
            $this->SetPOPUPColClass ( "col-sm-12" );

           $param_id=APBD_GetValue("id");
           if(empty($param_id)){
                 $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
            $this->SetTitle("Edit Canned Msg");
            if(APPSBD_IsPostBack){
                    $uobject=new Mapbd_wps_canned_msg();
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
            $mainobj=new Mapbd_wps_canned_msg();
            $mainobj->id($param_id);
            if(!$mainobj->Select()){
                 $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
            APBD_OldFields($mainobj, "user_id,title,canned_msg,entry_date,added_by,canned_type,status");
            $this->AddViewData("mainobj", $mainobj);
            $this->AddViewData("isUpdateMode", true);
            $this->DisplayPOPUP("add");
       }


	   function data(){
	        $mainResponse = new AppsbdAjaxDataResponse();
    		$mainResponse->setDownloadFileName("apbd-wps-canned-msg-list");
        	$mainobj=new Mapbd_wps_canned_msg();
        	$mainResponse->setDateRange($mainobj);
            $records=$mainobj->CountALL($mainResponse->srcItem, $mainResponse->srcText,$mainResponse->multiparam,"after");
        	if($records>0){
        		$mainResponse->SetGridRecords($records);
                $userObj=new Mapbd_wps_users();
                $mainobj->Join($userObj,"ID","added_by",'left');
        		$result=$mainobj->SelectAllGridData("id,title,entry_date,canned_type,status,display_name as added_by ", $mainResponse->orderBy, $mainResponse->order, $mainResponse->rows, $mainResponse->limitStart, $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam,"after");
        		if($result){
					$status_change=$mainobj->GetPropertyOptionsTag("status");
            	    $canned_type_options=$mainobj->GetPropertyOptionsTag("canned_type");

        			foreach ($result as &$data){
        				$data->action="";
        				$data->action.="<a data-effect='mfp-move-from-top' class='popupformWR btn btn-info btn-xs' href='" .$this->GetActionUrl("edit",["id"=>$data->id])."'>".$this->__("Edit")."</a>";
                        $data->action.=" <a class='ConfirmAjaxWR btn btn-danger btn-xs' data-msg='".$this->__("Are you sure to delete?")."' href='" .$this->GetActionUrl("delete_item",["id"=>$data->id])."'>".$this->__("Delete")."</a>";


                        $data->status=" <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='".$this->__("Are you sure to change?")."' href='" .$this->GetActionUrl("status_change",["id"=>$data->id])."'>".APBD_getTextByKey($data->status,$status_change)."</a>";

						$data->canned_type=APBD_getTextByKey($data->canned_type,$canned_type_options);

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
            $mr=new Mapbd_wps_canned_msg();
            $mr->id($param);
            if($mr->Select()){
                if(Mapbd_wps_canned_msg::DeleteById($param)){
                    APBD_AddLog("D","id={$param}", "l003","Wp_apbd_wps_canned_msg_confirm");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully deleted"));
                }else{
                    $mainResponse->DisplayWithResponse(false,$this->__("Delete failed try again"));
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
            $mr=new Mapbd_wps_canned_msg();
            $statusChange=$mr->GetPropertyOptionsTag("status");
            $mr->id($param);
            if($mr->Select("status")){
                $newStatus=$mr->status=="A"?"I":"A";
                $uo=new Mapbd_wps_canned_msg();
                $uo->status($newStatus);
                $uo->SetWhereUpdate("id",$param);
                if( $uo->Update()){
                    $status_text = APBD_getTextByKey($uo->status, $statusChange);
                    APBD_AddLog("U",$uo->settedPropertyforLog(), "l002","Wp_apbd_wps_canned_msg");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), $status_text);
                }else{
                    $mainResponse->DisplayWithResponse(false, $this->__("Update failed try again"));
                }

            }

        }

}