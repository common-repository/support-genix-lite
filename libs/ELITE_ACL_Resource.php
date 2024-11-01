<?php
if(!class_exists('ELITE_ACL_Resource')) {
    class ELITE_ACL_Resource
    {
        public $res_id;
        public $action_param;
        public $title;
        public $group_title;
        public $tooltip_note;


        static function &getResource($action_param,$title,$group_title,$tooltip_note=''){
            $aclObject=new ELITE_ACL_Resource();
            $aclObject->title=$title;
            $aclObject->group_title=$group_title;
            $aclObject->res_id=hash('crc32b',$action_param);
            $aclObject->action_param=$action_param;
            $aclObject->tooltip_note=esc_attr($tooltip_note);
            return $aclObject;
        }
    }
}