<?php
/**
 * @since: 07/Oct/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_wps_ticket_category extends AppsBDLiteModule {



	   function initialize() {
            parent::initialize();
            $this->disableDefaultForm();
            $this->AddAjaxAction("add",[$this,"add"]);
            $this->AddAjaxAction("edit",[$this,"edit"]);
            $this->AddAjaxAction("delete_item",[$this,"delete_item"]);

			$this->AddAjaxAction("status_change",[$this,"status_change"]);
        }

        function SettingsPage() {
            $this->SetTitle("Ticket Category List");
            $this->Display();
        }

        function GetMenuTitle() {
            return $this->__( "Ticket Category" );
        }

        function GetMenuSubTitle() {
            return $this->__( "Ticket Category Settings" );
        }

        function GetMenuIcon() {
            return "fa fa-ticket";
        }

       function add(){
            $this->SetTitle("Add New Category ");
            $this->SetPOPUPColClass ( "col-sm-6" );

            if(APPSBD_IsPostBack){
                $nobject=new Mapbd_wps_ticket_category();
                if($nobject->SetFromPostData(true)){
                    if($nobject->Save()){
                        $this->AddInfo("Successfully added");
                        APBD_AddLog("A",$nobject->settedPropertyforLog(),"l001","");
                        $this->DisplayPOPUPMsg();
                        return;
                    }
                }
            }
            $mainobj=new Mapbd_wps_ticket_category();
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
            $this->SetTitle("Edit Ticket Category");
            if(APPSBD_IsPostBack){
                    $uobject=new Mapbd_wps_ticket_category();
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
            $mainobj=new Mapbd_wps_ticket_category();
            $mainobj->id($param_id);
            if(!$mainobj->Select()){
                 $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
            APBD_OldFields($mainobj, "title,parent_category,parent_category_path,show_on,status");
            $this->AddViewData("mainobj", $mainobj);
            $this->AddViewData("isUpdateMode", true);
            $this->DisplayPOPUP("add");
       }


	   function data(){
	        $mainResponse = new AppsbdAjaxDataResponse();
    		$mainResponse->setDownloadFileName("apbd-wps-ticket-category-list");
        	$mainobj=new Mapbd_wps_ticket_category();
        	$mainResponse->setDateRange($mainobj);
            $records=$mainobj->CountALL($mainResponse->srcItem, $mainResponse->srcText,$mainResponse->multiparam,"after");
        	if($records>0){
                $ctgs=Mapbd_wps_ticket_category::FetchAllKeyValue("id","title");
        		$mainResponse->SetGridRecords($records);
        		$result=$mainobj->SelectAllGridData("", $mainResponse->orderBy, $mainResponse->order, $mainResponse->rows, $mainResponse->limitStart, $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam,"after");
        		if($result){

					$status_change=$mainobj->GetPropertyOptionsTag("status");
            	    $show_on_options=$mainobj->GetPropertyOptionsTag("show_on");

        			foreach ($result as &$data){
        			    $data->title.=' <span class="text-success">' . $this->___("(ID: %d)", $data->id) . '</span>';
        			    if(empty( $data->parent_category)){
                            $data->parent_category='-';
                        }else {
                            $data->parent_category = APBD_getTextByKey($data->parent_category, $ctgs);
                        }
        				$data->action="";
        				$data->action.="<a data-effect='mfp-move-from-top' class='popupformWR btn btn-info btn-xs' href='" .$this->GetActionUrl("edit",["id"=>$data->id])."'>".$this->__("Edit")."</a>";
        			    $data->action.=" <a class='ConfirmAjaxWR btn btn-danger btn-xs' data-msg='".$this->__("Are you sure to delete?")."' href='" .$this->GetActionUrl("delete_item",["id"=>$data->id])."'>".$this->__("Delete")."</a>";

                        $data->status=" <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='".$this->__("Are you sure to change?")."' href='" .$this->GetActionUrl("status_change",["id"=>$data->id])."'>".APBD_getTextByKey($data->status,$status_change)."</a>";
						$data->show_on=APBD_getTextByKey($data->show_on,$show_on_options);

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
            $mr=new Mapbd_wps_ticket_category();
            $mr->id($param);
            if($mr->Select()){
                if(Mapbd_wps_ticket_category::DeleteById($param)){
                    APBD_AddLog("D","id={$param}", "l003","Wp_apbd_wps_ticket_category_confirm");
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
            $mr=new Mapbd_wps_ticket_category();
            $statusChange=$mr->GetPropertyOptionsTag("status");

            $mr->id($param);
            if($mr->Select("status")){
                $newStatus=$mr->status=="A"?"I":"A";
                $uo=new Mapbd_wps_ticket_category();
                $uo->status($newStatus);
                $uo->SetWhereUpdate("id",$param);
                if( $uo->Update()){
                    $status_text = APBD_getTextByKey($uo->status, $statusChange);
                    APBD_AddLog("U",$uo->settedPropertyforLog(), "l002","Wp_apbd_wps_ticket_category");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully Updated"), $status_text);
                }else{
                    $mainResponse->DisplayWithResponse(false, $this->__("Update failed try again"));
                }

            }

        }

}