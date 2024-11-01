<?php
if(!class_exists("Apbd_WPS_API_Response")) {
    class Apbd_WPS_API_Response
    {
        public $status = false;
        public $msg = "";
        public $data = NULL;

        function SetResponse($status,$message='',$data = NULL)
        {
                $this->status=$status;
                $this->msg=$message;
                $this->data=$data;

        }
    }
}
