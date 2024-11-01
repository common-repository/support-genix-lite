<?php
class  Apbd_WPS_API_Data_Response {
	public $page = 1;
	public $limit = 10;
	public $total = 0;
	public $pagetotal = 1;
	public $rowdata = [];
	public $data=null;

	function SetRowData( $data ) {
		$this->rowdata = $data;
	}
    function SetData( $data ) {
        $this->data = $data;
    }

}