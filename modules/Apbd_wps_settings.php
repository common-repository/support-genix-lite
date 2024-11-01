<?php
/**
 * @since: 17/Aug/2021
 * @author: Sarwar Hasan
 * @version 1.0.0
 */

class Apbd_wps_settings extends AppsBDLiteModule
{
    /**
     * @var string
     */
    private static $uploadBasePath = WP_CONTENT_DIR . "/uploads/support-genix/";

	function initialize()
	{
		parent::initialize();
		$this->disableDefaultForm();
		self::$uploadBasePath = apply_filters('apbd-wps/filter/set-upload-path', self::$uploadBasePath);
		//filters
		add_filter("apbd-wps/filter/ticket-read-attached-files", [$this, 'set_ticket_attached_files'], 2, 2);
		add_filter("apbd-wps/filter/reply-read-attached-files", [$this, 'set_ticket_reply_attached_files'], 2, 2);
		add_filter("apbd-wps/filter/ticket-custom-properties", [$this, 'ticketCustomFields'], 2, 2);
		add_filter("apbd-wps/filter/user-custom-properties", [$this, 'userCustomFields'], 2, 2);

		//actions
		add_action("apbd-wps/action/download-file", [$this, 'download_file'], 8, 3);
		add_action("apbd-wps/action/ticket-created", [$this, 'save_ticket_meta'], 8, 2);
		add_action("apbd-wps/action/user-created", [$this, 'save_user_meta'], 8, 2);
		add_action("apbd-wps/action/user-updated", [$this, 'save_user_meta'], 8, 2);
		add_action("apbd-wps/action/download-file", [$this, 'download_file'], 8, 3);
		add_action("apbd-wps/action/ticket-custom-field-update", [$this, 'update_ticket_meta'], 10, 3);
		add_action('template_redirect', [$this, 'rewrite_templates'], 1);

		add_action('apbd-wps/action/ticket-created', [$this, "ticket_assign"], 8, 2);
		add_action('apbd-wps/action/ticket-created', [$this, "send_ticket_email"], 9, 2);
		add_action('apbd-wps/action/ticket-assigned', [$this, "notify_user_on_ticket_assigned"], 9, 1);
		add_action('apbd-wps/action/ticket-replied', [$this, "send_reply_notification"], 9, 2);
		add_action('apbd-wps/action/ticket-status-change', [$this, "send_close_ticket_email"], 9, 2);
		add_action('apbd-wps/action/ticket-status-change', [$this, "add_status_ticket_log"], 9, 2);

		add_action('wp_mail_failed', [$this, "mail_send_failed"], 9, 1);

		add_filter("apbd-wps/filter/ticket-details-custom-properties", [$this, 'final_filter_custom_field'], 10, 3);
		add_filter('display_post_states', [$this, "post_states"], 10, 2);
		add_filter( 'wp_kses_allowed_html', [$this,'custom_wpkses_post_tags'], 10, 2 );
		add_action($this->getHookActionStr("app-footer"),[$this,"admin_app_footer"]);

		add_action('apbd-wps/action/client-header',[$this,"client_header_custom"]);

		add_shortcode( 'supportgenix', [$this,'shortcodes'] );
	}
    function shortcodes(){
        ob_start();
	    do_action('apbd-wps/action/client-header',true);
	    ?>
        <noscript><strong><?php $this->_e("We're sorry but wp-support doesn't work properly without JavaScript enabled. Please enable it to continue") ; ?>.</strong>
        </noscript>
        <div id="support-genix" class="support-shortcode"></div>
        <script src="<?php echo esc_url($this->get_client_url("js/wp-support.js"));?>"></script>
	    <?php
        return ob_get_clean();
    }
	function client_header_custom($isShortCode=false){
	    $logo_url=$this->GetOption("app_logo",$this->get_client_url("img/logo.png"));

	    $login_url="";
	    $reg_url="";
	    $isDefaultLogin=false;
	    $isDefaultLogin=false;
	    if($this->GetOption("is_wp_login_reg",'N')=="Y"){
		    $isDefaultLogin=true;
		    $login_url=$this->GetOption("login_page",wp_login_url());
		    if(strpos($login_url,'?') ===false){
			    $login_url.="?sg=1";
		    }
		    $reg_url=$this->GetOption("reg_page",wp_registration_url());
		    if(strpos($reg_url,'?') ===false){
			    $reg_url.="?sg=1";
		    }
	    }

		$is_shortcode = $isShortCode ? 'true' : 'false';
		$logout_url = $isDefaultLogin ? wp_logout_url() : '';
		?>
        <script>
            var apbdWpsBase="<?php echo esc_url(untrailingslashit(home_url())); ?>/";
            var appbdWps= {
                heart_bit: 120000,
                reloadOnLogin:'',
                is_shortcode:<?php echo esc_html( $is_shortcode ); ?>,
                app_title:"<?php echo esc_html( $this->GetOption("app_loading_text",get_option('blogname')) ); ?>",
                app_loader:"<?php echo esc_html( $this->GetOption( "is_app_loader", 'Y' ) ); ?>",
                disable_register_form: "<?php echo esc_html( $this->GetOption( "disable_registration_form", 'N' ) ); ?>",
                disable_guest_ticket_creation: "<?php echo esc_html( $this->GetOption( "disable_guest_ticket_creation", 'N' ) ); ?>",
                show_other_tickets_in_ticket_details_page: "<?php echo esc_html( $this->GetOption( "show_other_tickets_in_ticket_details_page", 'N' ) ); ?>",
                hide_ticket_details_info_by_default: "<?php echo esc_html( $this->GetOption( "hide_ticket_details_info_by_default", 'N' ) ); ?>",
                disable_auto_scroll_to_latest_response: "<?php echo esc_html( $this->GetOption( "disable_auto_scroll_to_latest_response", 'N' ) ); ?>",
                assets_path:'/',
                wpsnonce: "<?php echo wp_create_nonce("wp_rest"); ?>",
                is_logged_in: <?php echo is_user_logged_in()?"true":"false"; ?>,
                cp_text: <?php echo json_encode( $this->copyright_text() ); ?>,
                images:{
                    base:'<?php echo esc_url($this->get_client_url("",false)); ?>',
                    logo:'<?php echo esc_url($logo_url); ?>',
                    apploader:'<?php echo esc_url($this->GetOption("app_loader",$this->get_client_url("app-loader.svg"))); ?>',
                    image_edit_icon:'<?php echo esc_url(Apbd_wps_settings::GetModuleOption('img_url',$this->get_client_url("img/edit.png"))); ?>',
                    reg_image:'<?php echo esc_url(Apbd_wps_settings::GetModuleOption('img_url',$this->get_client_url("img/regImage.png"))); ?>',
                    dashboard:'<?php echo esc_url(Apbd_wps_settings::GetModuleOption('dash_img_url',$this->get_client_url("img/dashboard_image.png"))); ?>'
                },
                urls: {
                    login_url:"<?php echo esc_url( $login_url ); ?>",
                    logout_url:"<?php echo esc_url( $logout_url ); ?>",
                    reg_url:"<?php echo esc_url( $reg_url ); ?>",
                    home_url:"<?php echo home_url(); ?>",
                    profile_url:"<?php echo esc_url(Apbd_wps_settings::GetModuleInstance()->get_profile_link()); ?>",
                    heart_bit: apbdWpsBase + "wp-json/apbd-wps/v1/system/heart-bit",
                    settings: apbdWpsBase + "wp-json/apbd-wps/v1/basic/settings",
                    public_tickets: apbdWpsBase + "wp-json/apbd-wps/v1/ticket/public-tickets",
                    isValidCF:apbdWpsBase + "wp-json/apbd-wps/v1/basic/is-valid-custom-field",
                    unseen_notifications: apbdWpsBase + "wp-json/apbd-wps/v1/ticket/unseen-notifications",
                    notifications: apbdWpsBase + "wp-json/apbd-wps/v1/ticket/notifications",
                    update_notification: apbdWpsBase + "wp-json/apbd-wps/v1/ticket/update-notification",
                    user_login: apbdWpsBase + "wp-json/apbd-wps/v1/user/login",
                    user_logout: apbdWpsBase + "wp-json/apbd-wps/v1/user/logout",
                    create_client: apbdWpsBase + "wp-json/apbd-wps/v1/user/create-client",
                    update_client: apbdWpsBase + "wp-json/apbd-wps/v1/user/update-client",
                    reset_password: apbdWpsBase + "wp-json/apbd-wps/v1/user/reset-password",
                    create_user: apbdWpsBase + "wp-json/apbd-wps/v1/user/create",
                    create_note: apbdWpsBase + "wp-json/apbd-wps/v1/ticket/create-note",
                    agent_list: apbdWpsBase + "wp-json/apbd-wps/v1/user/agent-list",
                    get_client: apbdWpsBase + "wp-json/apbd-wps/v1/user/get-client",
                    ticket_list: apbdWpsBase + "wp-json/apbd-wps/v1/ticket/list",
                    ticket_stat: apbdWpsBase + "wp-json/apbd-wps/v1/ticket/ticket-stat",
                    trashed_tickets: apbdWpsBase + "wp-json/apbd-wps/v1/ticket/trashed-tickets",
                    ticket_details: apbdWpsBase + "wp-json/apbd-wps/v1/ticket/details",
                    user_details: apbdWpsBase + "wp-json/apbd-wps/v1/user/details",
                    create_ticket:apbdWpsBase + "wp-json/apbd-wps/v1/ticket/create-ticket",
                    reply_ticket:apbdWpsBase + "wp-json/apbd-wps/v1/ticket/ticket-reply",
                    search_ticket:apbdWpsBase + "wp-json/apbd-wps/v1/ticket/search-ticket",
                    update_ticket:apbdWpsBase + "wp-json/apbd-wps/v1/ticket/update-ticket",
                    update_custom_field:apbdWpsBase + "wp-json/apbd-wps/v1/ticket/update-custom-field",
                    move_to_trash:apbdWpsBase + "wp-json/apbd-wps/v1/ticket/move-to-trash",
                    update_privacy:apbdWpsBase + "wp-json/apbd-wps/v1/ticket/update-privacy",
                    delete_ticket:apbdWpsBase + "wp-json/apbd-wps/v1/ticket/delete-ticket",
                    restore_ticket:apbdWpsBase + "wp-json/apbd-wps/v1/ticket/restore-ticket",
                    check_unique:apbdWpsBase + "wp-json/apbd-wps/v1/user/check-unique",
                    change_password:apbdWpsBase + "wp-json/apbd-wps/v1/user/change-pass"
                },
                translationObj: {
                    availableLanguages: {
                        en_US: "American English"
                    },
                    defaultLanguage: "en_US",
                    translations: {
                        "en_US":<?php echo wp_json_encode($this->apbd_get_wps_client_language()); ?>
                    }
                }

            }
        </script>
        <link href="<?php echo esc_url($this->get_client_url("css/wp-support.css")); ?>" rel="preload" as="style">
		<link href="<?php echo esc_url($this->get_client_url("js/wp-support.js"));?>" rel="preload" as="script">
		<link href="<?php echo esc_url($this->get_client_url("css/wp-support.css"));?>" rel="stylesheet">
        <link href="<?php echo esc_url($this->get_client_url("css/custom_style.css"));?>" rel="stylesheet">
        <?php if($isShortCode){?>
        <link href="<?php echo esc_url($this->get_client_url("css/shortcode.css"));?>" rel="stylesheet">
        <?php }
    }
    function get_profile_link(){
        if($this->GetOption('is_wp_profile_link','N')=='Y'){
            $profileLink=$this->GetOption('wp_profile_link','');
            if(!empty($profileLink)){
                return $profileLink;
            }else{
                return admin_url("profile.php");
            }
        }else{
            return '';
        }
    }
	function custom_wpkses_post_tags( $tags, $context ) {
		if ( 'post' === $context ) {
			$tags['iframe'] = array(
				'src'             => true,
				'height'          => true,
				'width'           => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
			);
		}
		return $tags;
	}
	function GetMultiLangFields() {
		return [
			'ticket_page' => '',
			'img_url' => '',
			'dash_img_url' => '',
			'app_loading_text' => '',
			'login_page' => '',
			'reg_page' => '',
			'wp_profile_link' => '',
			'app_logo' => '',
			'app_loader' => '',
		];
	}
	function SetOption() {
		$module_name = get_class( $this );
		$option_name = "apbd-wp-support_o_" . $module_name;

		$this->SetMultiLangOption( $option_name );
	}
	function UpdateOption() {
		$module_name = get_class( $this );
		$option_name = "apbd-wp-support_o_" . $module_name;

		return $this->UpdateMultiLangOption( $option_name );
	}
	public function OnInit()
	{
		parent::OnInit();
		add_filter('apbd-wps/filter/attached-file', [$this, "fileCheck"], 10, 5);
		add_action('apbd-wps/action/attach-files', [$this, "attach_file"], 10, 3);
		$this->add_support_genix_rewrite();
		add_filter('query_vars', [$this, 'register_query_var']);
		add_action('admin_bar_menu', [$this, 'support_genix_admin_bar_button' ], 999);
	}
	function support_genix_admin_bar_button($wp_admin_bar){
		$page_id=$this->GetOption("ticket_page","");
        if(!empty($page_id) && ('page'===get_post_type( $page_id ))) {
			$pageUrl = get_page_link( $page_id );
			$args = array(
				'id'    => 'support-genix',
				'title' => '<span class="dashicons-apbd-support-genix"></span> '.$this->__("Support Tickets"),
				'href'  => $pageUrl,
				'target'  => "_blank"
			);
			$wp_admin_bar->add_node( $args );
		}
	}
	function copyright_text(){
		$obj=new stdClass();
		$obj->hide_pb=$this->GetOption("is_hide_cp_text","N")=="Y";
		$obj->txt=$this->___("Copyright %s © %s",
			'<a href="'.esc_url(home_url()).'">'.esc_html(get_bloginfo('name')).'</a>',
			esc_html(date('Y')));
		return $obj;
	}

	function post_states($post_states, $post)
	{
		if($this->GetOption('ticket_page')==$post->ID) {
			$post_states['support_genix'] = $this->__('Support Genix');
		}
		return $post_states;

	}
	function register_query_var( $vars ) {
		$vars[] = 'sg_ticket';
		$vars[] = 'sgnix';
		return $vars;
	}
	function add_support_genix_rewrite()
	{

		add_rewrite_rule('^sgnix/?([^/]*)/?', 'index.php?sgnix=true&sg_ticket=$matches[1]', 'top');
		if (!empty(get_transient('supportgenix_rwrite_rule'))) {
			flush_rewrite_rules(true);
			delete_transient('supportgenix_rwrite_rule');
		}
	}
	public function admin_app_footer(){
		$this->Display("app-footer");
	}
	public function guest_ticket_login()
	{
		$ticket_param = rtrim( APBD_GetValue('p',''),'/');
		if (!empty($ticket_param)) {
			$encKey = Apbd_wps_settings::GetEncryptionKey();
			$encObj = Apbd_WPS_EncryptionLib::getInstance($encKey);
			$requestParam = $encObj->decryptObj($ticket_param);
			if (!empty($requestParam->ticket_id) && !empty($requestParam->ticket_user)) {
				$ticket = Mapbd_wps_ticket::FindBy("id", $requestParam->ticket_id);
				if (!empty($ticket) && $ticket->ticket_user == $requestParam->ticket_user) {
					$is_guest_user = get_user_meta($ticket->ticket_user, "is_guest", true) == "Y";
					if ($is_guest_user) {
						$ticket_link = Mapbd_wps_ticket::getTicketAdminLink($ticket);
						if (is_user_logged_in()) {
							wp_logout();
						}
						wp_clear_auth_cookie();
						wp_set_current_user($ticket->ticket_user);
						wp_set_auth_cookie($ticket->ticket_user);
						wp_safe_redirect($ticket_link);
					}
				}
			}
		}
	}
	public static function CreateEncryptionKey()
	{
		$encryption_key = get_option( 'apbd_wps_encryption_key', '' );
		if ( empty( $encryption_key ) ) {
			$encryption_key = APBD_EncryptionKey();
			if ( ! empty( $encryption_key ) ) {
				update_option( 'apbd_wps_encryption_key', $encryption_key );
			}
		}
	}
	public static function GetEncryptionKey()
	{
		$encryption_key = get_option( 'apbd_wps_encryption_key', 'WPS_ABD_enc' );
		$encryption_key = ( ! empty( $encryption_key ) ? $encryption_key : 'WPS_ABD_enc' );
		return $encryption_key;
	}
	public function get_client_url($link,$withVersion=true)
	{
		if(!$withVersion){
			return plugins_url("template/main/" . $link, $this->pluginFile);
		}else {
			$version = $this->kernelObject->pluginVersion;

			$base_path = plugin_dir_path( $this->kernelObject->pluginFile );
			$file_path = realpath( $base_path . "template/main/" . $link );

			if ( file_exists( $file_path ) ) {
				$version .= '-';
				$version .= filemtime( $file_path );
			}

			return plugins_url("template/main/" . $link . "?type=lite&v=" . $version, $this->pluginFile);
		}
	}

	public static function get_upload_path()
	{
		return self::$uploadBasePath;
	}

	public static function isClientLoggedIn($user = null)
	{
		return !self::isAgentLoggedIn($user);
	}
	public static function isAgentLoggedIn($user = null) {
		if ( empty( $user ) ) {
			$user = wp_get_current_user();
		}

		if ( empty( $user ) || empty( $user->roles ) ) {
			return false;
		}
		if ( in_array( 'administrator', $user->roles ) ) {
			return true;
		}
		$agent_roles = Mapbd_wps_role::FindAllBy( "status", "A", [ "is_agent" => "Y" ] );
		foreach ( $agent_roles as $agent_role ) {
			if ( in_array( $agent_role->slug, $user->roles ) ) {
				return true;
			}
		}

		return false;
	}
	public static function getSupportGenixRole($user){
		if ( in_array( 'administrator', $user->roles ) ) {
			return self::GetModuleInstance()->__("Administrator");
		}
		$agent_roles = Mapbd_wps_role::FindAllBy( "status", "A", [ "is_agent" => "Y" ] );
		foreach ( $agent_roles as $agent_role ) {
			if ( in_array( $agent_role->slug, $user->roles ) ) {
				return $agent_role->name;
			}
		}
	}
	public static function GetCaptchaSetting(){
		$rc_set= new stdClass();
		$rc_set->status=Apbd_wps_settings::GetModuleOption("recaptcha_v3_status","I")=="A";
		if($rc_set->status) {
			$rc_set->hide_badge       = Apbd_wps_settings::GetModuleOption( "recaptcha_v3_hide_badge", "N" ) == "Y";
			$rc_set->site_key         = Apbd_wps_settings::GetModuleOption( "recaptcha_v3_site_key", "" );
			$rc_set->on_login_form    = Apbd_wps_settings::GetModuleOption( "captcha_on_login_form", "Y" ) == "Y";
			$rc_set->on_create_ticket = Apbd_wps_settings::GetModuleOption( "captcha_on_create_tckt", "Y" ) == "Y";
			$rc_set->on_reg_form      = Apbd_wps_settings::GetModuleOption( "captcha_on_reg_form", "Y" ) == "Y";
		}
		$rc_set=apply_filters('apbd-wps/captcha-settings',$rc_set);
		return $rc_set;
	}

	public function GetAllowedFileType() {
		$key = "allowed_type";
		$options = $this->options;
		$defaultType = [ "image", "docs", "text", "pdf" ];
		$allowedType = ( ( isset( $options[ $key ] ) && is_array( $options[ $key ] ) ) ? array_map( 'sanitize_text_field', $options[ $key ] ) : $defaultType );
		$allowedType = ( ! empty( $allowedType ) ? array_map( 'strtolower', $allowedType ) : $defaultType );
		$defaultExts = [ 'jpg', 'jpeg', 'png', 'webp', 'gif', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'pdf' ];
		$allowedExts = [];

		foreach ( $allowedType as $type ) {
			switch ( $type ) {
				case 'image':
					$allowedExts = array_merge( $allowedExts, [ 'jpg', 'jpeg', 'png', 'webp', 'gif' ] );
					break;

				case 'docs':
					$allowedExts = array_merge( $allowedExts, [ 'doc', 'docx', 'xls', 'xlsx' ] );
					break;

				case 'text':
					$allowedExts = array_merge( $allowedExts, [ 'txt' ] );
					break;

				case 'csv':
					$allowedExts = array_merge( $allowedExts, [ 'csv' ] );
					break;

				case 'pdf':
					$allowedExts = array_merge( $allowedExts, [ 'pdf' ] );
					break;

				case 'zip':
					$allowedExts = array_merge( $allowedExts, [ 'zip' ] );
					break;

				case 'json':
					$allowedExts = array_merge( $allowedExts, [ 'json' ] );
					break;
			}
		}

		$allowedExts = array_unique( $allowedExts );
		$allowedExts = ( ! empty( $allowedExts ) ? $allowedExts : $defaultExts );

		return $allowedExts;
	}

	public function GetAllowedFileTypeStr() {
		return implode( ",", $this->GetAllowedFileType() );
	}

	public static function GetModuleAllowedFileType() {
		$_self = self::GetModuleInstance();
		$extns = $_self->GetAllowedFileType();

		return $extns;
	}

	public static function GetModuleAllowedFileTypeStr() {
		$_self = self::GetModuleInstance();
		$extns = $_self->GetAllowedFileTypeStr();

		return $extns;
	}

	public static function CheckCaptcha($token)
	{
		if (Apbd_wps_settings::GetModuleOption("recaptcha_v3_status", "I") == "A") {
			$secret = Apbd_wps_settings::GetModuleOption("recaptcha_v3_secret_key", "");
			return self::isValid($token, $secret);
		} else {
			return true;
		}
	}
	protected  static function isValid($token,$secret="")
	{
		if(empty($secret) || empty($token)){
			return false;
		}
		try {
			$response = wp_remote_get( add_query_arg( array(
				'secret'   => $secret,
				'response' =>$token,
			), 'https://www.google.com/recaptcha/api/siteverify' ) );

			if( is_wp_error( $response ) || empty($response['body']) || ! ($json = json_decode( $response['body'] )) || ! $json->success ) {
				return false;
			}
			return true;
		}
		catch (Exception $e) {
			return false;
		}
	}

	public function user_login()
	{
		$payload = APBD_read_php_input_stream();
		if (!empty($payload)) {
			$payload = json_decode($payload, true);
		}
		$credentials = [];
		$credentials['user_login'] = $payload['username'];
		$credentials['user_password'] = $payload['password'];
		if (is_user_logged_in()) {
			wp_logout();
		}
		$response = new Apbd_WPS_API_Response();
		$user = wp_signon($credentials);
		if (is_wp_error($user)) {
			$response->SetResponse(false, strip_tags($user->get_error_message()), $credentials);
			return $response;
		} else {
			wp_set_current_user($user->ID);
			wp_set_auth_cookie($user->ID, true);
			$responseData = new stdClass();
			$responseData->id = $user->ID;
			$responseData->wp_rest_nonce = wp_create_nonce("wp_rest");
			$responseData->username = $user->user_login;
			$responseData->email = $user->user_email;
			$responseData->name = $user->first_name . ' ' . $user->last_name;
			$responseData->loggedIn = is_user_logged_in();
			$responseData->isAgent = Apbd_wps_settings::isAgentLoggedIn();
			if (empty(trim($responseData->name))) {
				$responseData->name = $user->display_name;
			}
			$responseData->caps = $user->caps;
			$responseData->img = get_user_meta($user->ID , 'supportgenix_avatar') ? get_user_meta($user->ID , 'supportgenix_avatar') : get_avatar_url($user->ID);
			$response->SetResponse(true, "logged in Successfully", $responseData);
			wp_send_json($response);
		}
	}

    public function OnVersionUpdate( $current_version = "", $previous_version = "", $last_pro_version = "" )
    {
        parent::OnVersionUpdate( $current_version, $previous_version, $last_pro_version );

        if ( empty( $previous_version ) ) {
            if ( ! empty( $last_pro_version ) ) {
                // When pro version is less than 1.3.4
                if ( 1 === version_compare( '1.3.4', $last_pro_version ) ) {
                    // From version 1.0.9
                    Mapbd_wps_custom_field::UpdateDBTable();
                }

                // When pro version is less than 1.4.0
                if ( 1 === version_compare( '1.4.0', $last_pro_version ) ) {
                    // From version 1.0.9
                    Mapbd_wps_ticket_assign_rule::UpdateDBTable();
                }

                // When pro version is less than 1.4.2
                if ( 1 === version_compare( '1.4.2', $last_pro_version ) ) {
                    // From version 1.1.0
                    Mapbd_wps_role::UpdateExStatus();;
                    Mapbd_wps_ticket::UpdateDBTable();
                    Mapbd_wps_email_templates::UpdateTemplateGroup();
                }

                // When pro version is less than 1.4.4
                if ( 1 === version_compare( '1.4.4', $last_pro_version ) ) {
                    // From version 1.1.2
                    Mapbd_wps_role::UpdateDBTableCharset();
                    Mapbd_wps_role_access::UpdateDBTableCharset();
                    Mapbd_wps_ticket_assign_rule::UpdateDBTableCharset();
                    Mapbd_wps_ticket::UpdateDBTableCharset();
                    Mapbd_wps_ticket_category::UpdateDBTableCharset();
                    Mapbd_wps_ticket_log::UpdateDBTableCharset();
                    Mapbd_wps_ticket_reply::UpdateDBTableCharset();
                    Mapbd_wps_notification::UpdateDBTableCharset();
                    Mapbd_wps_custom_field::UpdateDBTableCharset();
                    Mapbd_wps_email_templates::UpdateDBTableCharset();
                    Mapbd_wps_support_meta::UpdateDBTableCharset();
                    Mapbd_wps_debug_log::UpdateDBTableCharset();
                    Mapbd_wps_canned_msg::UpdateDBTableCharset();
                    Mapbd_wps_notes::UpdateDBTableCharset();
                }

				// When pro version is less than 1.4.5
                if ( 1 === version_compare( '1.4.5', $last_pro_version ) ) {
                    // From version 1.1.3
                    Mapbd_wps_ticket_reply::UpdateDBTable();
                }

                // When pro version is less than 1.5.4
                if ( 1 === version_compare( '1.5.4', $last_pro_version ) ) {
                    // From version 1.2.0
                    Mapbd_wps_email_templates::UpdateTemplateGroup2();
                }

                // When pro version is less than 1.5.8
                if ( 1 === version_compare( '1.5.8', $last_pro_version ) ) {
                    // From version 1.2.3
					$this->UpdateAllowedFileType();
                }

                // When pro version is less than 1.6.6
                if ( 1 === version_compare( '1.6.6', $last_pro_version ) ) {
                    // From version 1.3.1
                    Mapbd_wps_email_templates::UpdateTemplateGroup3();
                }

                // When pro version is less than 1.7.0
                if ( 1 === version_compare( '1.7.0', $last_pro_version ) ) {
                    // From version 1.3.5
                    Mapbd_wps_ticket::UpdateDBTable2();
                }

                // When pro version is less than 1.7.3
                if ( 1 === version_compare( '1.7.3', $last_pro_version ) ) {
                    // From version 1.3.8
                    Mapbd_wps_ticket_log::UpdateDBTable();
                }
            } else {
                // From version 1.1.0
                $this->CreateTicketPage();
            }
        } else {
            // From version 1.0.9
            if ( 1 === version_compare( '1.0.9', $previous_version ) ) {
                // When pro version is empty or less than 1.3.4
                if ( empty( $last_pro_version ) || ( 1 === version_compare( '1.3.4', $last_pro_version ) ) ) {
                    Mapbd_wps_custom_field::UpdateDBTable();
                }

                // When pro version is empty or less than 1.4.0
                if ( empty( $last_pro_version ) || ( 1 === version_compare( '1.4.0', $last_pro_version ) ) ) {
                    Mapbd_wps_ticket_assign_rule::UpdateDBTable();
                }
            }

            // From version 1.1.0
            if ( 1 === version_compare( '1.1.0', $previous_version ) ) {
                // When pro version is empty or less than 1.4.2
                if ( empty( $last_pro_version ) || ( 1 === version_compare( '1.4.2', $last_pro_version ) ) ) {
                    Mapbd_wps_role::UpdateExStatus();
                    Mapbd_wps_ticket::UpdateDBTable();
                    Mapbd_wps_email_templates::UpdateTemplateGroup();
                }
            }

            // From version 1.1.2
            if ( 1 === version_compare( '1.1.2', $previous_version ) ) {
                // When pro version is empty or less than 1.4.4
                if ( empty( $last_pro_version ) || ( 1 === version_compare( '1.4.4', $last_pro_version ) ) ) {
                    Mapbd_wps_role::UpdateDBTableCharset();
                    Mapbd_wps_role_access::UpdateDBTableCharset();
                    Mapbd_wps_ticket_assign_rule::UpdateDBTableCharset();
                    Mapbd_wps_ticket::UpdateDBTableCharset();
                    Mapbd_wps_ticket_category::UpdateDBTableCharset();
                    Mapbd_wps_ticket_log::UpdateDBTableCharset();
                    Mapbd_wps_ticket_reply::UpdateDBTableCharset();
                    Mapbd_wps_notification::UpdateDBTableCharset();
                    Mapbd_wps_custom_field::UpdateDBTableCharset();
                    Mapbd_wps_email_templates::UpdateDBTableCharset();
                    Mapbd_wps_support_meta::UpdateDBTableCharset();
                    Mapbd_wps_debug_log::UpdateDBTableCharset();
                    Mapbd_wps_canned_msg::UpdateDBTableCharset();
                    Mapbd_wps_notes::UpdateDBTableCharset();
                }
            }

			// From version 1.1.3
            if ( 1 === version_compare( '1.1.3', $previous_version ) ) {
                Mapbd_wps_ticket_reply::UpdateDBTable();
            }

            // From version 1.2.0
            if ( 1 === version_compare( '1.2.0', $previous_version ) ) {
                // When pro version is empty or less than 1.5.4
                if ( empty( $last_pro_version ) || ( 1 === version_compare( '1.5.4', $last_pro_version ) ) ) {
                    Mapbd_wps_email_templates::UpdateTemplateGroup2();
                }
            }

            // From version 1.2.2
            if ( 1 === version_compare( '1.2.2', $previous_version ) ) {
                $this->ConvertToMultiLangOptions();
            }

            // From version 1.2.3
            if ( 1 === version_compare( '1.2.3', $previous_version ) ) {
                $this->UpdateAllowedFileType();
            }

            // From version 1.3.1
            if ( 1 === version_compare( '1.3.1', $previous_version ) ) {
                // When pro version is empty or less than 1.6.6
                if ( empty( $last_pro_version ) || ( 1 === version_compare( '1.6.6', $last_pro_version ) ) ) {
                    Apbd_wps_settings::CreateEncryptionKey();
                    Mapbd_wps_email_templates::UpdateTemplateGroup3();
                    Mapbd_wps_role::UpdateExAccess();
                    Mapbd_wps_role::AddNewAccess();
                }
            }

            // From version 1.3.5
            if ( 1 === version_compare( '1.3.5', $previous_version ) ) {
                // When pro version is empty or less than 1.7.0
                if ( empty( $last_pro_version ) || ( 1 === version_compare( '1.7.0', $last_pro_version ) ) ) {
                    Mapbd_wps_ticket::UpdateDBTable2();
                }
            }

            // From version 1.3.6
            if ( 1 === version_compare( '1.3.6', $previous_version ) ) {
                // When pro version is empty or less than 1.7.1
                if ( empty( $last_pro_version ) || ( 1 === version_compare( '1.7.1', $last_pro_version ) ) ) {
                    Mapbd_wps_role::AddNewAccess2();
                }
            }

            // From version 1.3.8
            if ( 1 === version_compare( '1.3.8', $previous_version ) ) {
                // When pro version is empty or less than 1.7.3
                if ( empty( $last_pro_version ) || ( 1 === version_compare( '1.7.3', $last_pro_version ) ) ) {
                    Mapbd_wps_ticket_log::UpdateDBTable();
                }
            }
        }
    }

	public function OnActive( $new_activation = true, $new_pro_activation = true )
	{
		parent::OnActive( $new_activation, $new_pro_activation );
		set_transient( 'supportgenix_rwrite_rule' ,"Yes") ;
		Mapbd_wps_ticket::CreateDBTable();
		Mapbd_wps_ticket_category::CreateDBTable();
		Mapbd_wps_ticket_log::CreateDBTable();
		Mapbd_wps_ticket_reply::CreateDBTable();
		Mapbd_wps_notification::CreateDBTable();
		Mapbd_wps_custom_field::CreateDBTable();
		Mapbd_wps_support_meta::CreateDBTable();
		Mapbd_wps_debug_log::CreateDBTable();
		Mapbd_wps_canned_msg::CreateDBTable();
		Mapbd_wps_notes::CreateDBTable();
	}

    function CreateTicketPage(){
        $pageId = absint( get_option( 'apbd_wps_ticket_page_id' ) );
        $currentPageId = absint( $this->GetOption("ticket_page", "0" ) );

        if ( ( 'page' !== get_post_type( $pageId ) ) && ( 'page' !== get_post_type( $currentPageId ) ) ) {
            $pageArgs = array(
                'post_title'   => $this->__( 'Ticket' ),
                'post_content' => '<!-- wp:shortcode -->[supportgenix]<!-- /wp:shortcode -->',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            );

            $createdPageId = wp_insert_post( $pageArgs );

            if ( $createdPageId ) {
                update_option( 'apbd_wps_ticket_page_id', $createdPageId );
                $this->AddOption( "ticket_page", $createdPageId );
            }
        }
    }

    function ConvertToMultiLangOptions() {
        $this->multiLangCode = 'en';
        $this->UpdateOption();
    }

    function UpdateAllowedFileType() {
		$allowedType = $this->GetOption( 'allowed_type', 'jpg,png,txt,pdf,docs' );
		$allowedType = explode( ',', strtolower( sanitize_text_field( $allowedType ) ) );
		$allowedType = array_map( 'trim', $allowedType );
		$updatedType = [];

		if ( ! empty( $allowedType ) ) {
			$allowedType = array_unique( $allowedType );

			foreach ( $allowedType as $type ) {
				switch ( $type ) {
					case 'jpg':
					case 'jpeg':
					case 'png':
					case 'webp':
					case 'gif':
						$updatedType[] = 'image';
						break;

					case 'doc':
					case 'docx':
					case 'xls':
					case 'xlsx':
						$updatedType[] = 'docs';
						break;

					case 'txt':
						$updatedType[] = 'text';
						break;

					case 'csv':
						$updatedType[] = 'csv';
						break;

					case 'pdf':
						$updatedType[] = 'pdf';
						break;

					case 'zip':
						$updatedType[] = 'zip';
						break;

					case 'json':
						$updatedType[] = 'json';
						break;
				}
			}
		}

		$this->options['allowed_type'] = $updatedType;
        $this->UpdateOption();
    }

	function rewrite_templates()
	{
		if ( wp_validate_boolean( get_query_var( 'sgnix' ) ) ) {
			$this->guest_ticket_login();
		}else {
			$ticketPage = $this->GetOption("ticket_page", "");
			$ticketPageShortcode = $this->GetOption("ticket_page_shortcode", "N");
			if (!empty($ticketPage)) {
				if (is_page($ticketPage) && ('Y'!==$ticketPageShortcode)) {
					$this->Display('../../template/main/client-main');
					exit;
				}
			}
		}
	}

	public function array_attrs( $arr_data ) {
		$attr_return = '';
		foreach ( $arr_data as $attr => $val ) {
			$attr_return .= $attr . '="' . $val . '" ';
		}

		return $attr_return;
	}

	function userCustomFields($customFieldWithValue, $ticket_id)
	{
		$ticketMetas = Mapbd_wps_support_meta::getUserMeta($ticket_id);
		$ticketMetas = apply_filters('apbd-wps/filter/custom-field-metadata', $ticketMetas);

		$custom_fileds = Mapbd_wps_custom_field::getCustomFieldForAPI();
		$custom_fileds = apply_filters('apbd-wps/filter/before-custom-get', $custom_fileds);
		$custom_fileds = apply_filters('apbd-wps/filter/display-properties', $custom_fileds);
		if (!empty($custom_fileds->reg_form)) {
			foreach ($custom_fileds->reg_form as $custom_filed) {
				$custom_filed->field_value = !empty($ticketMetas[$custom_filed->input_name]) ? $ticketMetas[$custom_filed->input_name] : "";
				$custom_filed->is_editable = true;
				$customFieldWithValue[] = $custom_filed;
			}
		}
		$customFieldWithValue = apply_filters('apbd-wps/filter/custom-additional-fields', $customFieldWithValue);
		return $customFieldWithValue;
	}

	function ticketCustomFields($customFieldWithValue, $ticket_id)
	{
		$ticketMetas = Mapbd_wps_support_meta::getTicketMeta($ticket_id);
		$ticketMetas = apply_filters('apbd-wps/filter/custom-field-metadata', $ticketMetas);

		$custom_fileds = Mapbd_wps_custom_field::getCustomFieldForTicketDetailsAPI($ticket_id);
		$custom_fileds = apply_filters('apbd-wps/filter/before-custom-get', $custom_fileds);
		$custom_fileds = apply_filters('apbd-wps/filter/display-properties', $custom_fileds);
		if (!empty($custom_fileds->ticket_form)) {
			foreach ($custom_fileds->ticket_form as $custom_filed) {
				$custom_filed->field_value = !empty($ticketMetas[$custom_filed->input_name]) ? $ticketMetas[$custom_filed->input_name] : "";
				if($custom_filed->field_type=='S'){
					$custom_filed->field_value=!empty($custom_filed->field_value);
				}
				$custom_filed->is_editable = true;
				$customFieldWithValue[] = $custom_filed;
			}
		}
		$customFieldWithValue = apply_filters('apbd-wps/filter/custom-additional-fields', $customFieldWithValue);
		return $customFieldWithValue;
	}

	/**
	 * @param Mapbd_wps_ticket $ticket
	 * @param $custom_fields
	 */
	function save_ticket_meta($ticket, $custom_fields)
	{
		if (!empty($custom_fields) && is_array($custom_fields)) {
			foreach ($custom_fields as $key => $custom_field) {
				if (substr($key, 0, 1) == "D") {
					$n = new Mapbd_wps_support_meta();
					$n->item_id($ticket->id);
					$n->item_type('T');
					$n->meta_key(preg_replace("#[^0-9]#", '', $key));
					$n->meta_type('D');
					$n->meta_value($custom_field);
					if (!$n->Save()) {
						Mapbd_wps_debug_log::AddGeneralLog("Custom field save failed", print_r($n,true)."\n".APBD_GetMsg_API());
					}
				}
			}

		}

	}

	/**
	 * @param Apbd_WPS_User $ticket
	 * @param $custom_fields
	 */
	function save_user_meta($userObj, $custom_fields)
	{
		if (!empty($custom_fields) && is_array($custom_fields)) {
			foreach ($custom_fields as $key => $custom_field) {
				if (substr($key, 0, 1) == "D") {
					$c = new Mapbd_wps_support_meta();
					$c->item_id($userObj->id);
					$c->item_type('U');
					$c->meta_key(preg_replace("#[^0-9]#", '', $key));
					$c->meta_type('D');
					if ($c->Select()) {
						$u = new Mapbd_wps_support_meta();
						$u->SetWhereUpdate("id", $c->id);
						$u->meta_value($custom_field);
						$u->Update();
					} else {
						$n = new Mapbd_wps_support_meta();
						$n->item_id($userObj->id);
						$n->item_type('U');
						$n->meta_key(preg_replace("#[^0-9]#", '', $key));
						$n->meta_type('D');
						$n->meta_value($custom_field);
						if (!$n->Save()) {
							Mapbd_wps_debug_log::AddGeneralLog("Custom field save failed on user meta", $n);
						}
					}
				}
			}

		}

	}

	/**
	 * @param Mapbd_wps_ticket $ticket
	 * @param $custom_fields
	 */
	function update_ticket_meta($ticket_id, $pro_name, $value)
	{
		if (strtoupper(substr($pro_name, 0, 1)) == "D") {
			$s=new Mapbd_wps_support_meta();
			$s->item_id($ticket_id);
			$s->meta_key(preg_replace("#[^0-9]#", '', $pro_name));
			$s->meta_type('D');
			if($s->Select()) {
				$n = new Mapbd_wps_support_meta();
				$n->meta_value($value);
				$n->SetWhereUpdate("item_id", $ticket_id);
				$n->SetWhereUpdate("meta_key", preg_replace("#[^0-9]#", '', $pro_name));
				$n->SetWhereUpdate("meta_type", 'D');
				if (!$n->Update()) {
					Mapbd_wps_debug_log::AddGeneralLog("Custom field update failed", APBD_GetMsg_API() . "\nTicket ID: $ticket_id, Custom Name: $pro_name, value:$value");
				}
			}else{
				$n = new Mapbd_wps_support_meta();
				$n->meta_value($value);
				$n->item_id($ticket_id);
				$n->item_type('T');
				$n->meta_key(preg_replace("#[^0-9]#", '', $pro_name));
				$n->meta_type('D');
				$n->meta_value($value);
				if (!$n->Save()) {
					Mapbd_wps_debug_log::AddGeneralLog("Custom field update failed", APBD_GetMsg_API() . "\nTicket ID: $ticket_id, Custom Name: $pro_name, value:$value");
				}
			}
		}
	}

	/**
	 * @param [] $attached_files
	 * @param Mapbd_wps_ticket $ticket
	 */
	function set_ticket_attached_files($attached_files, $ticket)
	{
		$ticketDir = self::$uploadBasePath;

		if (empty($ticket->id)) {
			return $attached_files;
		} else {
			$ticketDir = $ticketDir . $ticket->id . "/attached_files/";
		}
		$this->read_all_file($attached_files, $ticketDir, "T", $ticket->id);
		return $attached_files;
	}

	/**
	 * @param $attached_files
	 * @param Mapbd_wps_ticket_reply $ticket_reply
	 * @return mixed
	 */
	function set_ticket_reply_attached_files($attached_files, $ticket_reply)
	{
		$ticketDir = self::$uploadBasePath;

		if (empty($ticket_reply->reply_id)) {
			return $attached_files;
		} else {
			$ticketDir = $ticketDir . $ticket_reply->ticket_id . "/replied/" . $ticket_reply->reply_id . "/attached_files/";
		}
		$this->read_all_file($attached_files, $ticketDir, "R", $ticket_reply->ticket_id, $ticket_reply->reply_id);
		return $attached_files;
	}

	function download_file($type, $ticket_or_reply_id, $file)
	{

	}

	function read_all_file(&$attached_files, $path, $tType, $ticket_id, $ticket_reply_id = null)
	{
		$allowed_files = $this->GetAllowedFileTypeStr();
		$path = rtrim($path, '/');
		if ($tType == 'R') {
			$ticket_id .= '_' . $ticket_reply_id;
		}
		$namespace = APBDWPSupportLite::getNamespaceStr();
		if (is_dir($path)) {
			foreach (glob($path . '/*.{' . $allowed_files . '}', GLOB_BRACE) as $file) {
				$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
				$fileProperty = new stdClass();
				$fileProperty->url = get_rest_url(null, $namespace . '/ticket/file-dl') . "/$tType/$ticket_id/" . basename($file);
				$fileProperty->type = APBD_getMimeType($file);
				$fileProperty->ext = $ext;
				$attached_files[] = $fileProperty;
			}
		}
	}

	function SettingsPage() {
		$this->Display();
	}

	function GetMenuTitle()
	{
		return $this->__("Settings");
	}

	function GetMenuSubTitle()
	{
		return $this->__("General Settings");
	}

	function GetMenuIcon()
	{
		return "fa fa-cog";
	}

    public function AjaxRequestCallback() {
        $response = new AppsbdAjaxConfirmResponse();
        $beforeSave = $this->options;
        $data = ( is_array( $_POST ) ? wp_parse_args( $_POST, [ 'allowed_type' => [] ] ) : [] );

		foreach ( $data as $key => $value ) {
            $key = sanitize_key( $key );

            if ( 'action' === $key ) {
                continue;
            }

            if ( 'allowed_type' === $key ) {
				$availableType = [ 'image', 'docs', 'text', 'csv', 'pdf', 'zip', 'json' ];
				$allowedType = [];

				if ( is_array( $value ) ) {
					foreach ( $value as $type => $status ) {
						if ( in_array( $type, $availableType ) && 1 === absint( $status ) ) {
							$allowedType[] = $type;
						}
					}
				}

                $this->options[ $key ] = $allowedType;
                continue;
            }

			if ( in_array( $key, $this->HTMLInputFields ) ) {
				$this->options[ $key ] = wp_kses_html( $value );
				continue;
			}

			$this->options[ $key ] = sanitize_text_field( $value );
        }

        if ( $beforeSave === $this->options ) {
            $response->DisplayWithResponse( false, $this->__( 'No change for update' ) );
        } elseif ( $this->UpdateOption() ) {
            $response->SetResponse( true, $this->__( 'Saved Successfully' ) );
        } else {
            $response->SetResponse( false, $this->__( 'Something went wrong' ) );
        }

        $response->Display();
    }


	function CreateBaseFolder()
	{
		if (is_dir(self::$uploadBasePath)) {
			if (wp_mkdir_p(self::$uploadBasePath)) {
				apbd_file_put_contents(self::$uploadBasePath . "/.htaccess",
					'<IfModule authz_core_module>
            Require all denied
        </IfModule>
        <IfModule !authz_core_module>
            Deny from all
        </IfModule>
                    ');
			}
		}
	}
	function getTicketAttachedPath($ticket_id,$reply_id=''){
		$this->CreateBaseFolder();
		$ticketDir = self::$uploadBasePath;
		if (!empty($ticket_id)) {
			$ticketDir = $ticketDir . $ticket_id;
			if (!empty($reply_id)) {
				$ticketDir = $ticketDir . "/replied/" . $reply_id . "/attached_files/";
			} else {
				$ticketDir = $ticketDir . "/attached_files/";
			}
		}
		if (!is_dir($ticketDir)) {
			if (!wp_mkdir_p($ticketDir)) {
				$this->AddError("System couldn't create directory");
				return false;
			}
		}
		return $ticketDir;
	}
	function attach_file($ticket_files, $ticketObj, $reply_obj = null)
	{
		if(Apbd_wps_settings::GetModuleOption("ticket_file_upload",'A')=='A') {
			$this->CreateBaseFolder();
			$ticketDir = self::$uploadBasePath;
			if (!empty($ticketObj->id)) {
				$ticketDir = $ticketDir . $ticketObj->id;
				if (!empty($reply_obj->reply_id)) {
					$ticketDir = $ticketDir . "/replied/" . $reply_obj->reply_id . "/attached_files/";
				} else {
					$ticketDir = $ticketDir . "/attached_files/";
				}
			}


			if (!is_dir($ticketDir)) {
				if (!wp_mkdir_p($ticketDir)) {
					$this->AddError("System couldn't create directory");
					return false;
				}
			}

			if (is_dir($ticketDir)) {
				foreach ($ticket_files['name'] as $ind => $name) {
					$fname = preg_replace('#[^a-z0-9\-\.\_]#i', "_", $name);
					if (move_uploaded_file($ticket_files['tmp_name'][$ind], $ticketDir . $fname)) {

					}
				}
			}
		}

	}

	/**
	 * @param boolean $isOk
	 * @param string $name
	 * @param int $error
	 * @param string $type
	 * @param int $size
	 * @return boolean
	 */

	function fileCheck($isOk, $name, $error, $type, $size)
	{
		$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		$allowed=Apbd_wps_settings::GetModuleAllowedFileType();
		if(in_array($ext,['php','js','sh','bash','cgi'])){
			return false;
		}
		if(!in_array($ext,$allowed)){
			$isOk=false;
		}
		return $isOk;
	}

	/**
	 * @param Mapbd_wps_ticket $ticketObj
	 * @param $customFields
	 */
	public function ticket_assign($ticketObj, $customFields=[])
	{

		Mapbd_wps_ticket_assign_rule::ProcessRuleByCategory($ticketObj);
	}

	/**
	 * @param Mapbd_wps_ticket_reply $replyObj
	 */
	public function send_reply_notification($replyObj){
		$ticketObj=Mapbd_wps_ticket::FindBy("id",$replyObj->ticket_id);
		if(!empty($replyObj) && !empty($ticketObj)) {
			Mapbd_wps_ticket_assign_rule::ProcessReplyNotificationAndEmail($replyObj,$ticketObj);
		}
	}

	/**
	 * @param Mapbd_wps_ticket $ticketObj
	 * @param $customFields
	 */
	public function send_ticket_email($ticketObj, $customFields)
	{
		Mapbd_wps_ticket::Send_ticket_open_email($ticketObj);
	}
	/**
	 * @param Mapbd_wps_ticket $ticketObj
	 */
	public function send_close_ticket_email($ticketObj)
	{
		if ($ticketObj->status == "C") {
			Mapbd_wps_ticket::Send_ticket_close_email($ticketObj);
		}
	}
	/**
	 * @param Mapbd_wps_ticket $ticketObj
	 */
	public function add_status_ticket_log($ticketObj, $logBy = '')
	{
		if(!empty($logBy)) {
            $logByType = Apbd_wps_settings::isClientLoggedIn()?'U':'A';
            $statusArray = $ticketObj->GetPropertyRawOptions('status');
            $statusName = $statusArray[$ticketObj->status];
            Mapbd_wps_ticket_log::AddTicketLog($ticketObj->id, $logBy, $logByType, $ticketObj->___("Ticket status changed to %s", $statusName), $ticketObj->status);
		} else {
            $logBy = $ticketObj->assigned_on || 0;
            $statusArray = $ticketObj->GetPropertyRawOptions('status');
            $statusName = $statusArray[$ticketObj->status];
            Mapbd_wps_ticket_log::AddTicketLog($ticketObj->id, $logBy, 'A', $ticketObj->___("Ticket status changed to %s Automatically", $statusName), $ticketObj->status);
        }
	}
	/**
	 *@param Mapbd_wps_ticket $ticketObj
	 */
	public function notify_user_on_ticket_assigned($ticketObj)
	{
		if(!empty($ticketObj->assigned_on)) {
			$title = apbd_get_user_title_by_user($ticketObj->assigned_on);
			Mapbd_wps_ticket_log::AddTicketLog($ticketObj->id, $ticketObj->assigned_on, "A", $ticketObj->___("Ticket assigned on %s", $title), $ticketObj->status);
		}

		Mapbd_wps_notification::AddNotification($ticketObj->assigned_on,"Assigned Ticket","Ticket has been assigned to you","","/tickets-details/".$ticketObj->id,false,"T","A",$ticketObj->id);
		Mapbd_wps_ticket::Send_ticket_assigned_email($ticketObj);
	}

	/**
	 * @param WP_Error $error
	 */
	public function mail_send_failed($error)
	{
		Mapbd_wps_debug_log::AddEmailLog("Email send failed",$error);
	}
	function final_filter_custom_field($custom_fields, $ticket_or_user_id='') {
		$isClient = Apbd_wps_settings::isClientLoggedIn();
		if ( $isClient ) {
			foreach ( $custom_fields as &$custom_field ) {
				if ( substr( $custom_field->input_name, 0, 1 ) == "D" && !empty( $custom_field->field_value ) ) {
					$custom_field->is_editable = false;
				}
			}
		}
		return $custom_fields;
	}
	public function apbd_get_wps_client_language() {
        $client_language = [];
        $client_language["Loaded"] = $this->__( "Loaded" );
        $client_language["First Name"] = $this->__( "First Name" );
        $client_language["First name"] = $this->__( "First name" );
        $client_language["Last Name"] = $this->__( "Last Name" );
        $client_language["Last name"] = $this->__( "Last name" );
        $client_language["User Name"] = $this->__( "User Name" );
        $client_language["No Name Found"] = $this->__( "No Name Found" );
        $client_language["Username"] = $this->__( "Username" );
        $client_language["Username or Email"] = $this->__( "Username or Email" );
        $client_language["Username or email"] = $this->__( "Username or email" );
        $client_language["Submit"] = $this->__( "Submit" );
        $client_language["Update"] = $this->__( "Update" );
        $client_language["Make this ticket public"] = $this->__( "Make this ticket public" );
        $client_language["Ticket Visibility"] = $this->__( "Ticket Visibility" );
        $client_language["Wrong Email Or Password"] = $this->__( "Wrong Email Or Password" );
        $client_language["Profile Update"] = $this->__( "Profile Update" );
        $client_language["Sign in"] = $this->__( "Sign in" );
        $client_language["Create Ticket"] = $this->__( "Create Ticket" );
        $client_language["Submit Ticket"] = $this->__( "Submit Ticket" );
        $client_language["Submit A Ticket"] = $this->__( "Submit A Ticket" );
        $client_language["Email"] = $this->__( "Email" );
        $client_language["Choose Password"] = $this->__( "Choose Password" );
        $client_language["Choose password"] = $this->__( "Choose password" );
        $client_language["Password"] = $this->__( "Password" );
        $client_language["Retype Password"] = $this->__( "Retype Password" );
        $client_language["Retype password"] = $this->__( "Retype password" );
        $client_language["Retype Password2"] = $this->__( "Retype Password2" );
        $client_language["Select Category"] = $this->__( "Select Category" );
        $client_language["Select category"] = $this->__( "Select category" );
        $client_language["Subject"] = $this->__( "Subject" );
        $client_language["Again New Password"] = $this->__( "Again New Password" );
        $client_language["New Password"] = $this->__( "New Password" );
        $client_language["New"] = $this->__( "New" );
        $client_language["Old Password"] = $this->__( "Old Password" );
        $client_language["Old password"] = $this->__( "Old password" );
        $client_language["Change Password"] = $this->__( "Change Password" );
        $client_language["Related URL"] = $this->__( "Related URL" );
        $client_language["Description"] = $this->__( "Description" );
        $client_language["Login"] = $this->__( "Login" );
        $client_language["Remember Me"] = $this->__( "Remember Me" );
        $client_language["Register"] = $this->__( "Register" );
        $client_language["Notifications"] = $this->__( "Notifications" );
        $client_language["View All"] = $this->__( "View All" );
        $client_language["View More"] = $this->__( "View More" );
        $client_language["profile"] = $this->__( "profile" );
        $client_language["In Progress"] = $this->__( "In Progress" );
        $client_language["setting"] = $this->__( "setting" );
        $client_language["lock screen"] = $this->__( "lock screen" );
        $client_language["log out"] = $this->__( "log out" );
        $client_language["active Tickets"] = $this->__( "active Tickets" );
        $client_language["Trashed Tickets"] = $this->__( "Trashed Tickets" );
        $client_language["Inactive Tickets"] = $this->__( "Inactive Tickets" );
        $client_language["Active"] = $this->__( "Active" );
        $client_language["Inactive"] = $this->__( "Inactive" );
        $client_language["Deleted"] = $this->__( "Deleted" );
        $client_language["Closed Tickets"] = $this->__( "Closed Tickets" );
        $client_language["Close Ticket"] = $this->__( "Close Ticket" );
        $client_language["Reopen Tickets"] = $this->__( "Reopen Tickets" );
        $client_language["Re-Open"] = $this->__( "Re-Open" );
        $client_language["All Tickets"] = $this->__( "All Tickets" );
        $client_language["Summary"] = $this->__( "Summary" );
        $client_language["All States"] = $this->__( "All States" );
        $client_language["Product Types"] = $this->__( "Product Types" );
        $client_language["Operators"] = $this->__( "Operators" );
        $client_language["tags"] = $this->__( "tags" );
        $client_language["Newest First"] = $this->__( "Newest First" );
        $client_language["Rating"] = $this->__( "Rating" );
        $client_language["My Ticket"] = $this->__( "My Ticket" );
        $client_language["Ticket"] = $this->__( "Ticket" );
        $client_language["Private Ticket"] = $this->__( "Private Ticket" );
        $client_language["Ticket Details"] = $this->__( "Ticket Details" );
        $client_language["Status:"] = $this->__( "Status:" );
        $client_language["open"] = $this->__( "open" );
        $client_language["close"] = $this->__( "close" );
        $client_language["Close"] = $this->__( "Close" );
        $client_language["Closed"] = $this->__( "Closed" );
        $client_language["Assigned:"] = $this->__( "Assigned:" );
        $client_language["Assign Me"] = $this->__( "Assign Me" );
        $client_language["Assigned on"] = $this->__( "Assigned on" );
        $client_language["Assigned On"] = $this->__( "Assigned On" );
        $client_language["Category:"] = $this->__( "Category:" );
        $client_language["Low"] = $this->__( "Low" );
        $client_language["Medium"] = $this->__( "Medium" );
        $client_language["High"] = $this->__( "High" );
        $client_language["Sort by"] = $this->__( "Sort by" );
        $client_language["Name"] = $this->__( "Name" );
        $client_language["Date"] = $this->__( "Date" );
        $client_language["Today"] = $this->__( "Today" );
        $client_language["Year"] = $this->__( "Year" );
        $client_language["Month"] = $this->__( "Month" );
        $client_language["Days ago"] = $this->__( "Days ago" );
        $client_language["Home"] = $this->__( "Home" );
        $client_language["Create Another Ticket"] = $this->__( "Create Another Ticket" );
        $client_language["View Ticket Details"] = $this->__( "View Ticket Details" );
        $client_language["Ticket's Category:"] = $this->__( "Ticket's Category:" );
        $client_language["Ticket's Track Id:"] = $this->__( "Ticket's Track Id:" );
        $client_language["Select Status"] = $this->__( "Select Status" );
        $client_language["Assign Agent"] = $this->__( "Assign Agent" );
        $client_language["Move To Trash"] = $this->__( "Move To Trash" );
        $client_language["Trash"] = $this->__( "Trash" );
        $client_language["Size"] = $this->__( "Size" );
        $client_language["Ticket's Subject:"] = $this->__( "Ticket's Subject:" );
        $client_language["Thank You.Your Ticket Created Successfully."] = $this->__( "Thank You.Your Ticket Created Successfully." );
        $client_language["Created Date"] = $this->__( "Created Date" );
        $client_language["Updated"] = $this->__( "Updated" );
        $client_language["Response Time"] = $this->__( "Response Time" );
        $client_language["Create Time:"] = $this->__( "Create Time:" );
        $client_language["Item Name"] = $this->__( "Item Name" );
        $client_language["Purchase Code"] = $this->__( "Purchase Code" );
        $client_language["Related Link"] = $this->__( "Related Link" );
        $client_language["Ticket Logs"] = $this->__( "Ticket Logs" );
        $client_language["Add"] = $this->__( "Add" );
        $client_language["No notes Found"] = $this->__( "No notes Found" );
        $client_language["Customer Notes"] = $this->__( "Customer Notes" );
        $client_language["View Notes"] = $this->__( "View Notes" );
        $client_language["Delete Ticket"] = $this->__( "Delete Ticket" );
        $client_language["Type Password"] = $this->__( "Type Password" );
        $client_language["Type password"] = $this->__( "Type password" );
        $client_language["Type New Password"] = $this->__( "Type New Password" );
        $client_language["Type new password"] = $this->__( "Type new password" );
        $client_language["Public Tickets"] = $this->__( "Public Tickets" );
        $client_language["Retype New Password"] = $this->__( "Retype New Password" );
        $client_language["Retype new password"] = $this->__( "Retype new password" );
        $client_language["Retype New Password2"] = $this->__( "Retype New Password2" );
        $client_language["Search Here"] = $this->__( "Search Here" );
        $client_language["Loading ..."] = $this->__( "Loading ..." );
        $client_language["Support Genix"] = $this->__( "Support Genix" );
        $client_language["Delete"] = $this->__( "Delete" );
        $client_language["Restore"] = $this->__( "Restore" );
        $client_language["2nd Password"] = $this->__( "2nd Password" );
        $client_language["Lost your password?"] = $this->__( "Lost your password?" );
        $client_language["Username Email"] = $this->__( "Username Email" );
        $client_language["%{fld_name} is not valid"] = $this->__( "%{fld_name} is not valid" );
        $client_language["%{fld_name} not a valid email address"] = $this->__( "%{fld_name} not a valid email address" );
        $client_language["%{fld_name} is required"] = $this->__( "%{fld_name} is required" );
        $client_language["%{fld_name} doesn't match with its password"] = $this->__( "%{fld_name} doesn't match with its password" );
        $client_language["Email or username is already exists"] = $this->__( "Email or username is already exists" );
        $client_language["File size is larger then %{allowed_size}"] = $this->__( "File size is larger then %{allowed_size}" );
        $client_language["Click Attach File"] = $this->__( "Click Attach File" );
        $client_language["Write email for searching existing client"] = $this->__( "Write email for searching existing client" );
        $client_language["No Ticket Found"] = $this->__( "No Ticket Found" );
        $client_language["No ticket found, Search again."] = $this->__( "No ticket found, Search again." );
        $client_language["Are you sure to make public this ticket?"] = $this->__( "Are you sure to make public this ticket?" );
        $client_language["Are you sure to make private this ticket?"] = $this->__( "Are you sure to make private this ticket?" );
        $client_language["Are you sure?"] = $this->__( "Are you sure?" );
        $client_language["Are you sure to trash it?"] = $this->__( "Are you sure to trash it?" );
        $client_language["Yes !!"] = $this->__( "Yes !!" );
        $client_language["No"] = $this->__( "No" );
        $client_language["Cancel"] = $this->__( "Cancel" );
        $client_language["Ticket subject"] = $this->__( "Ticket subject" );
        $client_language["Page"] = $this->__( "Page" );
        $client_language["by"] = $this->__( "by" );
        $client_language["By"] = $this->__( "By" );
        $client_language["Replied"] = $this->__( "Replied" );
        $client_language["Saved Replies"] = $this->__( "Saved Replies" );
        $client_language["No Saved Replies Found"] = $this->__( "No Saved Replies Found" );
        $client_language["Ticket created successfully"] = $this->__( "Ticket created successfully" );
        $client_language["Successfully updated"] = $this->__( "Successfully updated" );
        $client_language["Successfully Updated"] = $this->__( "Successfully Updated" );
        $client_language["Want to Delete Permanently ?!"] = $this->__( "Want to Delete Permanently ?!" );
        $client_language["You can't be able to restore this ticket !"] = $this->__( "You can't be able to restore this ticket !" );
        $client_language["Are you sure to restore this ticket ?!"] = $this->__( "Are you sure to restore this ticket ?!" );
        $client_language["The ticket has been closed!"] = $this->__( "The ticket has been closed!" );
        $client_language["Seen"] = $this->__( "Seen" );
        $client_language["Unseen"] = $this->__( "Unseen" );
        $client_language["Need Reply"] = $this->__( "Need Reply" );
        $client_language["True"] = $this->__( "True" );
        $client_language["False"] = $this->__( "False" );
        $client_language["No data found"] = $this->__( "No data found" );
        $client_language["Invalid captcha, try again"] = $this->__( "Invalid captcha, try again" );
        $client_language["Client loading"] = $this->__( "Client loading" );
        $client_language["of %{totalPage}"] = $this->__( "of %{totalPage}" );
        $client_language["Total: %{totalRecords}"] = $this->__( "Total: %{totalRecords}" );
        $client_language["Reply's date"] = $this->__( "Reply's date" );
        $client_language["Opening date"] = $this->__( "Opening date" );
        $client_language["Need reply"] = $this->__( "Need reply" );
        $client_language["Category"] = $this->__( "Category" );
        $client_language["Ascending"] = $this->__( "Ascending" );
        $client_language["Descending"] = $this->__( "Descending" );
        $client_language["Powered by"] = $this->__( "Powered by" );
        $client_language["Back"] = $this->__( "Back" );
        $client_language["Incorrect username or password"] = $this->__( "Incorrect username or password" );
        $client_language["New Ticket Received"] = $this->__( "New Ticket Received" );
        $client_language["Ticket replied"] = $this->__( "Ticket replied" );
        $client_language["Ticket replied by %{user_name}"] = $this->__( "Ticket replied by %{user_name}" );
        $client_language["Assigned Ticket"] = $this->__( "Assigned Ticket" );
        $client_language["Ticket has been assigned to you"] = $this->__( "Ticket has been assigned to you" );
        $client_language["Copy"] = $this->__( "Copy" );
        $client_language["Copied"] = $this->__( "Copied" );

        return $client_language;

    }

}