<?php
/**
 * @since: 07/Nov/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_wps_debug_log extends AppsBDLiteModule {



	   function initialize() {
            parent::initialize();
            $this->disableDefaultForm();
            $this->AddAjaxAction("add",[$this,"add"]);
            $this->AddAjaxAction("edit",[$this,"edit"]);
            $this->AddAjaxAction("delete_item",[$this,"delete_item"]);
            $this->AddAjaxAction("clean_data",[$this,"clean_data"]);
            $this->AddAjaxAction("view_dtls",[$this,"view_dtls"]);

        }

        function SettingsPage() {
            $this->SetTitle("Debug Log List");
            $this->Display();
        }

        function GetMenuTitle() {
            return $this->__( "Debug Log" );
        }

        function GetMenuSubTitle() {
            return $this->__( "Debug Log Settings" );
        }

        function GetMenuIcon() {
            return "fa fa-bug";
        }


        function view_dtls($param_id=""){
            $this->SetPOPUPColClass ( "col-sm-8" );

            $param_id=APBD_GetValue("id");
            if(empty($param_id)){
                $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
            $this->SetTitle("Details Debug Log");

            $mainobj=new Mapbd_wps_debug_log();
            $mainobj->id($param_id);
            if(!$mainobj->Select()){
                $this->AddError("Invalid request");
                $this->DisplayPOPUPMsg();
                return;
            }
             $this->AddViewData("mainobj", $mainobj);
            $this->AddViewData("isUpdateMode", true);
            $this->DisplayPOPUP("view_dtls");
       }


	   function data(){
	        $mainResponse = new AppsbdAjaxDataResponse();
    		$mainResponse->setDownloadFileName("apbd-wps-debug-log-list");
        	$mainobj=new Mapbd_wps_debug_log();
        	$mainResponse->setDateRange($mainobj);
            $records=$mainobj->CountALL($mainResponse->srcItem, $mainResponse->srcText,$mainResponse->multiparam,"after");
        	if($records>0){
        		$mainResponse->SetGridRecords($records);
        		$result=$mainobj->SelectAllGridData("", $mainResponse->orderBy, $mainResponse->order, $mainResponse->rows, $mainResponse->limitStart, $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam,"after");
        		if($result){

            	    $entry_type_options=$mainobj->GetPropertyOptionsTag("entry_type");
					$log_type_options=$mainobj->GetPropertyOptionsTag("log_type");
					$status_options=$mainobj->GetPropertyOptionsTag("status");

        			foreach ($result as &$data){
        				$data->action="";
        				$data->action.="<a data-effect='mfp-move-from-top' class='popupformWR btn btn-info btn-xs' href='" .$this->GetActionUrl("view_dtls",["id"=>$data->id])."'>"."<i class='fa fa-eye'></i> ".$this->__("View Details")."</a>";

						$data->entry_type=APBD_getTextByKey($data->entry_type,$entry_type_options);
						$data->log_type=APBD_getTextByKey($data->log_type,$log_type_options);
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
            $mr=new Mapbd_wps_debug_log();
            $mr->id($param);
            if($mr->Select()){
                if(Mapbd_wps_debug_log::DeleteByKeyValue("id",$param)){
                    APBD_AddLog("D","id={$param}", "l003","Wp_apbd_wps_debug_log_confirm");
                    $mainResponse->DisplayWithResponse(true, $this->__("Successfully deleted"));
                }else{
                    $mainResponse->DisplayWithResponse(false,$this->__("Delete failed try again"));
                }
            }
        }
    function clean_data() {
        $mainResponse = new AppsbdAjaxConfirmResponse();
        if ( Mapbd_wps_debug_log::ClearAll()) {
            APBD_AddLog("D","clear all", "l003","Debug_log_confirm");
            $mainResponse->DisplayWithResponse(true, $this->__("Clear failed try again"));
        } else {
            $mainResponse->DisplayWithResponse(true, $this->__("Successfully Cleared"));
        }
    }

}