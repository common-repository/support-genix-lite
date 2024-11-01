<?php
/**
 * @since: 16/Oct/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_wps_notification extends AppsBDLiteModule {



	   function initialize() {
            parent::initialize();
            $this->disableDefaultForm();
            $this->AddAjaxAction("add",[$this,"add"]);
            $this->AddAjaxAction("edit",[$this,"edit"]);
            $this->AddAjaxAction("delete_item",[$this,"delete_item"]);

			$this->AddAjaxAction("is_popup_link_change",[$this,"is_popup_link_change"]);
        }

         function SettingsPage() {
             $this->SetTitle("Notification");
             $this->Display();
         }


        function GetMenuTitle() {
            return $this->__( "Notification" );
        }

        function GetMenuSubTitle() {
            return $this->__( "Notification Settings" );
        }

        function GetMenuIcon() {
            return "fa fa-circle";
        }

       function add(){
            $this->SetTitle("Add New Notification");
            $this->SetPOPUPColClass ( "col-sm-6" );

            if(APPSBD_IsPostBack){
                $nobject=new Mapbd_wps_notification();
                if($nobject->SetFromPostData(true)){
                    if($nobject->Save()){
                        $this->AddInfo("Successfully added");
                        APBD_AddLog("A",$nobject->settedPropertyforLog(),"l001","");
                        $this->DisplayPOPUPMsg();
                        return;
                    }
                }
            }
            $mainobj=new Mapbd_wps_notification();
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
            $this->SetTitle("Edit Notification");
            if(APPSBD_IsPostBack){
                    $uobject=new Mapbd_wps_notification();
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
            $mainobj=new Mapbd_wps_notification();
            $mainobj->id($param_id);
            if(!$mainobj->Select()){
                 $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
            APBD_OldFields($mainobj, "user_id,title,msg,entry_type,entry_link,n_counter,is_popup_link,view_time,entry_time,item_type,extra_param,msg_param,status");
            $this->AddViewData("mainobj", $mainobj);
            $this->AddViewData("isUpdateMode", true);
            $this->DisplayPOPUP("add");
       }


	   function data(){
	        $mainResponse = new AppsbdAjaxDataResponse();
    		$mainResponse->setDownloadFileName("apbd-wps-notification-list");
        	$mainobj=new Mapbd_wps_notification();
        	$mainResponse->setDateRange($mainobj);
            $records=$mainobj->CountALL($mainResponse->srcItem, $mainResponse->srcText,$mainResponse->multiparam,"after");
        	if($records>0){
        		$mainResponse->SetGridRecords($records);
        	    $result=$mainobj->SelectAllGridData("", $mainResponse->orderBy, $mainResponse->order, $mainResponse->rows, $mainResponse->limitStart, $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam,"after");
        		if($result){

					$is_popup_link_change=$mainobj->GetPropertyOptionsTag("is_popup_link");
            	    $entry_type_options=$mainobj->GetPropertyOptionsTag("entry_type");
					$status_options=$mainobj->GetPropertyOptionsTag("status");

        			foreach ($result as &$data){
        				$data->action="";
        				$data->action.="<a data-effect='mfp-move-from-top' class='popupformWR btn btn-info btn-xs' href='" .$this->GetActionUrl("edit",["id"=>$data->id])."'>".$this->__("Edit")."</a>";
        			    $data->action.=" <a class='ConfirmAjaxWR btn btn-danger btn-xs' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='".$this->__("Are you sure to delete?")."' href='" .$this->GetActionUrl("delete_item",["id"=>$data->id])."'>".$this->__("Delete")."</a>";

						$data->is_popup_link=" <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='".$this->__("Are you sure to change?")."' href='" .$this->GetActionUrl("is_popup_link_change",["id"=>$data->id])."'>".APBD_getTextByKey($data->is_popup_link,$is_popup_link_change)."</a>";

						$data->entry_type=APBD_getTextByKey($data->entry_type,$entry_type_options);
						$data->status=APBD_getTextByKey($data->status,$status_options);

        			}
        		}
        		$mainResponse->SetGridData($result);
    		}
    		$mainResponse->DisplayGridResponse();
	   }


	    function delete_item($param=""){
	        $mainResponse = new AppsbdAjaxConfirmResponse();
                        $mainResponse->DisplayWithResponse(false, $this->__("Delete is temporary disabled"));
            return;
            if(empty($param)){
                 $mainResponse->DisplayWithResponse(false, $this->__("Invalid Request"));
                 return;
            }
            $mr=new Mapbd_wps_notification();
            $mr->id($param);
            if($mr->Select()){
                if(Mapbd_wps_notification::DeleteByKeyValue("id",$param)){
                    APBD_AddLog("D","id={$param}", "l003","Wp_apbd_wps_notification_confirm");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully deleted"));
                }else{
                    $mainResponse->DisplayWithResponse(false,__("Delete failed try again"));
                }
            }
        }

        function is_popup_link_change(){
           $param=APBD_GetValue("id");
           if(empty($param)){
                 $this->DisplayWithResponse(false, $this->__("Invalid Request"));
                 return;
            }
            $mainResponse = new AppsbdAjaxConfirmResponse();
            $mr=new Mapbd_wps_notification();
            $is_popup_linkChange=$mr->GetPropertyOptionsTag("is_popup_link");

            $mr->id($param);
            if($mr->Select("is_popup_link")){
                $newStatus=$mr->is_popup_link=="Y"?"N":"Y";
                $uo=new Mapbd_wps_notification();
                $uo->is_popup_link($newStatus);
                $uo->SetWhereUpdate("id",$param);
                if( $uo->Update()){
                    $status_text = APBD_getTextByKey($uo->is_popup_link, $is_popup_linkChange);
                    APBD_AddLog("U",$uo->settedPropertyforLog(), "l002","Wp_apbd_wps_notification");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), $status_text);
                }else{
                    $mainResponse->DisplayWithResponse(false, $this->__("Update failed try again"));
                }

            }

        }




}