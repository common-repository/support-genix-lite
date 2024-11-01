<?php
if(!class_exists("Apbd_WPS_API_Base")) {
    abstract class Apbd_WPS_API_Base
    {
        /**
         * @var Apbd_WPS_API_Response
         */
        public $response;
        public $namespace;
        public $version;
        protected $api_base='';
        public $logged_user;
        /**
         * @var false|string
         */
        public $payload;
        public static $payload_obj;
        public static $isLoadedPayload=false;

        public function __construct($namespace) {
	        $this->LoadPayload();
	        $this->response    = new Apbd_WPS_API_Response();
	        $this->namespace   = $namespace;
	        $this->logged_user = wp_get_current_user();
	        ob_start();
	        $this->api_base = $this->setAPIBase();
	        $this->routes();
	        add_action( 'set_logged_in_cookie', function ( $logged_in_cookie ) {
		        $_COOKIE[ LOGGED_IN_COOKIE ] = $logged_in_cookie;
	        });
        }

        function LoadPayload() {
			$html_fields=['reply_text','ticket_body'];
	        if ( ! self::$isLoadedPayload ) {
		        if ( ! empty( $_POST['payload'] ) ) {
			        self::$payload_obj =  SupportGNInput::sanitize_array(json_decode( stripslashes( $_POST['payload'] ), true ),$html_fields);
		        } else {
			        self::$payload_obj = APBD_read_php_input_stream();
			        if ( ! empty( self::$payload_obj ) ) {
				        self::$payload_obj = SupportGNInput::sanitize_array(json_decode( self::$payload_obj, true ),$html_fields);
				        if(empty(self::$payload_obj)){
                            self::$payload_obj = SupportGNInput::sanitize_array($_POST,$html_fields);
                        }
			        }
		        }
		        self::$isLoadedPayload = true;
	        }
	        $this->payload =& self::$payload_obj;

        }
        function get_current_user_id(){
            return $this->logged_user->ID;
        }
        function AddError( $message, $parameter = NULL, $_ = NULL ) {
            APBD_AddError( $message );
        }
        function filter_for_api(&$item){
            if($item instanceof AppsBDModel){
                            }
        }
        function GetPayload($key,$default=null){
            return !empty($this->payload[$key])?$this->payload[$key]:$default;
        }
        function AddInfo( $message, $parameter = NULL, $_ = NULL ) {
            APBD_AddInfo( $message );
        }
        function AddDebug( $obj) {
            APBD_AddInfo(APBD_DebugLog($obj,true));
        }
        function AddWarning( $message, $parameter = NULL, $_ = NULL ) {
            APBD_AddWarning( $message );
        }

        abstract function routes();
        abstract function setAPIBase();


        /**
         * @param $methods
         * @param $route
         * @param callable $callback
         * @param string $permission_callback
         */
        public function RegisterRestRoute($methods,$route,$callback,$permission_callback='')
        {
            $thisobj=&$this;
            if (empty($permission_callback)) {
                $permission_callback = function (WP_REST_Request $request) use ($route, $thisobj) {
                    $permission = false;
                    $mainroutes = explode( "/", $route );
                    $mainroute = ( isset( $mainroutes[0] ) ? strval( $mainroutes[0] ) : '' );

                    if ( ! empty( $mainroute ) ) {
                        $permission = $this->SetRoutePermission( $mainroute );
                        $permission = apply_filters( 'apbd-wps/filter/api-permission', $permission, $mainroute, $request );
                        $permission = apply_filters( 'apbd-wps/filter/api-permission/' . $this->api_base . "/" . $mainroute, $permission, $request );
                    }

                    return $permission;
                };
            }
            if (!empty($this->api_base)) {

                register_rest_route($this->namespace, '/' . $this->api_base . '/' . $route, array(
                    'methods' => $methods,
                    'callback' => $callback,
                    'permission_callback' => $permission_callback
                ));
            }
        }
        public function __destruct()
        {
            $debuglog = ob_get_clean();
        }
        function SetResponse($status, $message = '', $data = NULL)
        {
            $this->response->status = $status;
            $this->response->data = $data;
            $this->response->msg = $message;

        }
        function SetRoutePermission($route){
            return is_user_logged_in();
        }
    }
}
