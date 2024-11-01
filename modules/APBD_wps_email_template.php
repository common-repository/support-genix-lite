<?php
/**
 * @since: 30/Mar/2019
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class APBD_wps_email_template extends AppsBDLiteModule {


	function initialize() {
		parent::initialize();
		$this->disableDefaultForm();
		$this->AddAjaxAction( "add", [ $this, "add" ] );
		$this->AddAjaxAction( "edit", [ $this, "edit" ] );
		$this->AddAjaxAction( "view_params", [ $this, "view_params" ] );
		$this->AddAjaxAction( "status_change", [ $this, "status_change" ] );
	}

	/**
	 * @param $title
	 * @param callable $handler
	 * @param string $icon
	 */
	function OnActive( $new_activation = true, $new_pro_activation = true ) {
		parent::OnActive( $new_activation, $new_pro_activation );

		Mapbd_wps_email_templates::CreateDBTable();

		$this->AddDefaultTemplates();
	}

	function AddDefaultTemplates(){
		Mapbd_wps_email_templates::AddNewTemplate('ANT', 'To Admin or Agent', 'Ticket Created', 'A', 'New Ticket: {{ticket_title}} #{{ticket_track_id}}', '', '<p>A new ticket <a href=\"{{ticket_link}}\">{{ticket_title}}</a> has been submitted by {{ticket_user}}<br /><br />Ticket Body:<br />{{ticket_body}}<br /><br />{{view_ticket_anchor}}<br /><br />Thanks<br />{{site_name}}</p><p>&nbsp;</p><p>&nbsp;</p>');
		Mapbd_wps_email_templates::AddNewTemplate('AAT', 'To Admin or Agent', 'Ticket Assigned', 'A', 'Ticket Assigned: {{ticket_title}} #{{ticket_track_id}}', '', '<p>A ticket {{ticket_title}} has been assigned to you</p><p>Ticket Body:</p><p>{{ticket_body}}</p><p><br />{{view_ticket_anchor}}<br /><br />Thanks<br />{{site_name}}</p><p>&nbsp;</p><p>&nbsp;</p>');
		Mapbd_wps_email_templates::AddNewTemplate('ANR', 'To Admin or Agent', 'Ticket Replied', 'A', 'New Response: {{ticket_title}} #{{ticket_track_id}}', '', '<p>A new response has been added to ticket <a href="{{ticket_link}}">{{ticket_title}}</a> by {{ticket_user}}</p><p><strong>Ticket Reply Text: </strong></p><div>{{replied_text}}</div><div><strong><a href="{{ticket_link}}">View Ticket</a></strong></div><div>Thanks</div><div>{{site_name}}</div>');

		Mapbd_wps_email_templates::AddNewTemplate('UOT', 'To Customer (Created by Ticket Form)', 'Ticket Created', 'A', 'Re: {{ticket_title}} #{{ticket_track_id}}', '', '<p>Dear {{ticket_user}},</p><p>Your request (<a href="{{ticket_link}}">#{{ticket_track_id}}</a>) has been received and is being reviewed by our support staff. You will receive a response as soon as possible. To add additional comments, follow the link below:</p><p>{{view_ticket_anchor}}</p><p>Thanks,<br />{{site_name}}</p>');
		Mapbd_wps_email_templates::AddNewTemplate('TRR', 'To Customer (Created by Ticket Form)', 'Ticket Replied', 'A', 'Re: {{ticket_title}} #{{ticket_track_id}}', '', '<p>Dear {{ticket_user}},</p><p>One of our team members just replied to your ticket (<a href="{{ticket_link}}">#{{ticket_track_id}}</a>).</p><p>You can follow the link below to add comments.</p><p><strong><a href="{{ticket_link}}">View Ticket</a></strong></p><p>Thanks,<br />{{ticket_replied_user}}<br />{{site_name}}</p>');
		Mapbd_wps_email_templates::AddNewTemplate('TCL', 'To Customer (Created by Ticket Form)', 'Ticket Closed', 'A', 'Re: {{ticket_title}} #{{ticket_track_id}}', '', '<p>Dear {{ticket_user}},</p><p>Your ticket (#{{ticket_track_id}}) has been closed.</p><p>We hope that the ticket was resolved to your satisfaction. Please reply to this email if you believe that the ticket should not be closed or if it has not been resolved.</p><p>Thanks,<br />{{site_name}}</p>');

		Mapbd_wps_email_templates::AddNewTemplate('EOT', 'To Customer (Email to Ticket)', 'Ticket Created', 'A', 'Re: {{ticket_title}}', '', '<p>Dear {{ticket_user}},</p><p>Your request (#{{ticket_track_id}}) has been received and is being reviewed by our support staff. You will receive a response as soon as possible.</p><p>Thanks,<br />{{ticket_replied_user}}<br />{{site_name}}</p><p>&nbsp;</p><p>&nbsp;</p>');
		Mapbd_wps_email_templates::AddNewTemplate('ETR', 'To Customer (Email to Ticket)', 'Ticket Replied', 'A', 'Re: {{ticket_title}}', '', '<p>{{replied_text}}</p><p>Thanks,<br />{{site_name}}</p>');
		Mapbd_wps_email_templates::AddNewTemplate('ETC', 'To Customer (Email to Ticket)', 'Ticket Closed', 'A', 'Re: {{ticket_title}}', '', '<p>Dear {{ticket_user}},</p><p>Your ticket (#{{ticket_track_id}}) has been closed.</p><p>We hope that the ticket was resolved to your satisfaction. Please reply to this email if you believe that the ticket should not be closed or if it has not been resolved.</p><p>Thanks,<br />{{site_name}}</p>');
	}

	function SettingsPage() {
		$this->SetTitle( "Email Template" );
		$this->Display();
	}

	function GetMenuTitle() {
		return $this->__( "Email Template" );
	}

	function GetMenuSubTitle() {
		return $this->__( "Email Template Settings" );
	}

	function GetMenuIcon() {
		return "fa fa-envelope-open-o";
	}

	function add() {
		$this->SetTitle( "Add New Template " );
		$this->SetPOPUPColClass( "col-sm-12" );

		if ( APPSBD_IsPostBack ) {
			$nobject = new Mapbd_wps_email_templates();
			if ( $nobject->SetFromPostData( true ) ) {
				if ( $nobject->Save() ) {
					$this->AddInfo( "Successfully added" );
					APBD_AddLog( "A", $nobject->settedPropertyforLog(), "l001", "" );
					$this->DisplayPOPUPMsg();
					return;
				}
			}
		}
		$mainobj = new Mapbd_wps_email_templates();
		$this->AddViewData( "isUpdateMode", false );
		$this->AddViewData( "mainobj", $mainobj );
		$this->DisplayPOPUp( "add" );
	}

	function edit( $param_id = "" ) {
		$this->SetPOPUPColClass( "col-sm-12" );

		$param_id = APBD_GetValue( "id" );
		if ( empty( $param_id ) ) {
			$this->AddError( "Invalid request" );
			$this->DisplayPOPUPMsg();

			return;
		}
		$this->SetTitle( "Edit Email Template" );
		if ( APPSBD_IsPostBack ) {
			$uobject = new Mapbd_wps_email_templates();
			if ( $uobject->SetFromPostData( false ) ) {
				$uobject->SetWhereUpdate( "k_word", $param_id );
				if ( $uobject->Update() ) {
					APBD_AddLog( "U", $uobject->settedPropertyforLog(), "l002", "" );
					$this->AddInfo( "Successfully updated" );
					$this->DisplayPOPUPMsg();

					return;
				}
			}
		}
		$mainobj = new Mapbd_wps_email_templates();
		$mainobj->k_word( $param_id );
		if ( ! $mainobj->Select() ) {
			$this->AddError( "Invalid request" );
			$this->DisplayPOPUPMsg();

			return;
		}
		APBD_OldFields( $mainobj, "status,subject" );
		$this->AddViewData( "mainobj", $mainobj );
		$this->AddViewData( "isUpdateMode", true );
		$this->DisplayPOPUP( "add" );
	}


	function data() {
		$mainResponse = new AppsbdAjaxDataResponse();
		$mainResponse->setDownloadFileName( "apbd-email-templates-list" );
		$mainobj = new Mapbd_wps_email_templates();
		$mainResponse->setDateRange( $mainobj );
		$records = $mainobj->CountALL( $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam, "after" );
		if ( $records > 0 ) {
			$mainResponse->SetGridRecords( $records );
			$result = $mainobj->SelectAllGridData( "k_word,grp,title,status,subject", [
				"grp"   => "ASC",
				"title" => "ASC"
			], $mainResponse->order, $mainResponse->rows, $mainResponse->limitStart, $mainResponse->srcItem, $mainResponse->srcText, $mainResponse->multiparam, "after" );
			if ( $result ) {

				$status_change = $mainobj->GetPropertyOptionsTag( "status" );

				foreach ( $result as &$data ) {
					$data->action = "";
					$data->action .= "<a data-effect='mfp-move-from-top' class='popupformWR btn btn-info btn-xs' href='" . $this->GetActionUrl( "edit", [ "id" => $data->k_word ] ) . "'>" . $this->__( "Edit" ) . "</a>";
					if ( $this->kernelObject->isDevelopmode() ) {
						$data->action .= " <a class='popupform btn btn-info btn-xs' href='" . $this->GetActionUrl( "view_params", [ "id" => $data->k_word ] ) . "'>" . $this->__( "View Param" ) . "</a>";
					}
					$data->status = " <a class='ConfirmAjaxWR' data-on-complete='APPSBDAPPJS.confirmAjax.ConfirmWRChange' data-msg='" . $this->__( "Are you sure to change?" ) . "' href='" . $this->GetActionUrl( "status_change", [ "id" => $data->k_word ] ) . "'>" . APBD_getTextByKey( $data->status, $status_change ) . "</a>";

				}
			}
			$mainResponse->SetGridData( $result );
		}
		$mainResponse->DisplayGridResponse();
	}


	function delete_item( $param = "" ) {
		$mainResponse = new AppsbdAjaxConfirmResponse();
				$mainResponse->DisplayWithResponse( false, __( "Delete is temporary disabled" ) );

		return;
		if ( empty( $param ) ) {
			$mainResponse->DisplayWithResponse( false, __( "Invalid Request" ) );

			return;
		}
		$mr = new Mapbd_wps_email_templates();
		$mr->k_word( $param );
		if ( $mr->Select() ) {
			if ( Mapbd_wps_email_templates::DeleteByKeyValue( "k_word", $param ) ) {
				APBD_AddLog( "D", "k_word={$param}", "l003", "APBDEmailTemplate_confirm" );
				$mainResponse->DisplayWithResponse( true, __( "Successfully deleted" ) );
			} else {
				$mainResponse->DisplayWithResponse( false, __( "Delete failed try again" ) );
			}
		}
	}

	function status_change() {
		$param = APBD_GetValue( "id" );
		if ( empty( $param ) ) {
			$this->DisplayWithResponse( false, __( "Invalid Request" ) );

			return;
		}
		$mainResponse = new AppsbdAjaxConfirmResponse();
		$mr           = new Mapbd_wps_email_templates();
		$statusChange = $mr->GetPropertyOptionsTag( "status" );

		$mr->k_word( $param );
		if ( $mr->Select( "status" ) ) {
			$newStatus = $mr->status == "A" ? "I" : "A";
			$uo        = new Mapbd_wps_email_templates();
			$uo->status( $newStatus );
			$uo->SetWhereUpdate( "k_word", $param );
			if ( $uo->Update() ) {
				$status_text = APBD_getTextByKey( $uo->status, $statusChange );
				APBD_AddLog( "U", $uo->settedPropertyforLog(), "l002", "APBDEmailTemplate" );
				$mainResponse->DisplayWithResponse( true, __( "Successfully Updated" ), $status_text );
			} else {
				$mainResponse->DisplayWithResponse( false, __( "Update failed try again" ) );
			}

		}

	}


	function view_params() {
		$this->AddDefaultTemplates();
		$this->SetTitle( "View Params " );
		$this->SetPOPUPColClass( "col-sm-12" );
		$param_id = APBD_GetValue( "id" );
		$mainobj = new Mapbd_wps_email_templates();
		$mainobj->k_word( $param_id );
		$this->AddViewData( "isUpdateMode", false );
		$this->AddViewData( "mainobj", $mainobj );
		$this->DisplayPOPUp( "view_param" );
	}


}
