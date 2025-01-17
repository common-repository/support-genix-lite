<?php
	/**
	 * @property AppsBDKarnelPro kernelObject
	 * @since: 03/02/2019
	 * @author: Sarwar Hasan
	 * @version 1.0.0
	 */
	if(!class_exists("AppsBDLiteModule")) {
		abstract class AppsBDLiteModule {
			public $moduleName = "";
			public $menuTitle = "";
			public $menuIcon = "";
			public $pluginBaseName;
			public $pluginFile;
			public $multiLangCode;
			public $multiLangActive;
			public $multiLangFields;
			protected $allOptions;
			protected $options;
			/**
			 * Its property kernelObject
			 *
			 * @var AppsBDKarnelLite
			 */
			public $kernelObject;
			protected $formClass = "";
			protected $isMultipartForm = false;
			protected $formDataAttr = [];
			protected $dontAddDefaultForm = false;
			protected $_viewData = [ "_title" => "Unknown", "_subTitle" => "", "_relaod_event" => "" ];
			protected $__onTabActiveJsMethod = '';
			private static $_self = NULL;
			protected $viewPath = "";
			protected $isDisabledMenu = false;
			protected $isLastMenu = false;
			protected $isHiddenModule = false;
			protected $HTMLInputFields = [];

			/**
			 * AppsBDBaseModule constructor.
			 *
			 * @param $pluginBaseName
			 * @param AppsBDKarnelPro $kernelObject
			 */
			function __construct( $pluginBaseName, &$kernelObject ) {
				$this->kernelObject   = $kernelObject;
				$this->menuTitle      = $this->GetMenuTitle();
				$this->menuIcon       = $this->GetMenuIcon();
				$this->pluginBaseName = $pluginBaseName;
				$this->_base_path     = plugin_dir_path( $this->kernelObject->pluginFile );
				$this->pluginFile     = $this->kernelObject->pluginFile;
				$this->SetMultiLangProps();
				$this->SetOption();
				self::$_self[ get_class( $this ) ] = $this;
				$this->SetPOPUPIconClass( $this->GetMenuIcon() );
				$this->viewPath = plugin_dir_path( $this->kernelObject->pluginFile ) . "views/";
				$this->SetReloadEvent( $this->GetModuleId() );
				$this->initialize();
			}

			function initialize() {

			}

			abstract function SettingsPage();

			abstract function GetMenuTitle();

			abstract function GetMenuSubTitle();

			abstract function GetMenuIcon();

			function GetMenuCounter() {
				return '';
			}

			public function getHookActionStr( $str ) {
				return $this->kernelObject->getHookActionStr( $str );
			}

			public function getCustomizerControlId($name){
			    return $this->GetModuleId()."_cs_".$name;
            }
			public function getCustomizerControlIdToRealId($CustomizerId){
				return substr($CustomizerId,strlen($this->GetModuleId()."_cs_"));
			}

			/**
			 * @param $title
			 * @param callable $func
			 * @param $cssClass
			 * @param bool $isTab
			 */
			function addTopMenu( $title, $icon, $func, $cssClass = '', $isTab = true, $attr = [] ) {
				$this->kernelObject->AddTopMenu( $title, $icon, $func, $cssClass, $isTab, $attr );
			}

			/**
			 * @return static|null
			 */
			public static function GetModuleInstance() {
				return self::$_self[ static::class ];
			}

			public static function GetModuleOption( $key = '', $default = '' ) {
				if ( ! empty( self::$_self[ static::class ] ) ) {
					return self::$_self[ static::class ]->GetOption( $key, $default );
				} else {
					return $default;
				}
			}

			public static function GetModuleActionUrl( $actionString = '', $params = [] ) {
				if ( ! empty( self::$_self[ static::class ] ) ) {
					return self::$_self[ static::class ]->GetActionUrl( $actionString, $params );
				} else {
					return 'model not initialize';
				}
			}

			protected function AddViewData( $key, $value ) {
				$this->_viewData[ $key ] = $value;
			}

			protected function SetReloadEvent( $event ) {
				$this->_viewData["_relaod_event"] = $event;
			}

			function GetReloadEvent() {
				return $this->_viewData["_relaod_event"];
			}

			function AddError( $message, $parameter = NULL, $_ = NULL ) {
				$args    = func_get_args();
				$message = call_user_func_array( [ $this, "__" ], $args );
				APBD_AddError( $message );
			}

			function AddInfo( $message, $parameter = NULL, $_ = NULL ) {
				$args    = func_get_args();
				$message = call_user_func_array( [ $this, "__" ], $args );
				APBD_AddInfo( $message );
			}

			function AddWarning( $message, $parameter = NULL, $_ = NULL ) {
				$args    = func_get_args();
				$message = call_user_func_array( [ $this, "___" ], $args );
				APBD_AddWarning( $message );
			}

			/**
			 * @param string $viewPath
			 */
			public function setViewPath( $viewPath ) {
				$this->viewPath = $viewPath;
			}

			/**
			 * @return bool
			 */
			public function isDisabledMenu() {
				return $this->isDisabledMenu;
			}

			/**
			 * @param bool $isDisabledMenu
			 */
			public function setDisabledMenu() {
				$this->isDisabledMenu = true;
			}

			/**
			 * @return bool
			 */
			public function isLastMenu() {
				return $this->isLastMenu;
			}

			/**
			 * @param bool $isLastMenu
			 */
			public function setIsLastMenu( $isLastMenu ) {
				$this->isLastMenu = $isLastMenu;
			}

			/**
			 * @return bool
			 */
			public function isHiddenModule() {
				return $this->isHiddenModule;
			}

			/**
			 * @param bool $isHiddenModule
			 */
			public function setIsHiddenModule( $isHiddenModule ) {
				$this->isHiddenModule = $isHiddenModule;
			}

			protected function APPSBDLoadDatabaseModel( $modelName ) {
				APBD_LoadDatabaseModel( $this->kernelObject->pluginFile, $modelName, $modelName );
			}

			function OnTableCreate() {

			}

            function OnVersionUpdate( $current_version = "", $previous_version = "", $last_pro_version = "" ) {

			}

            function OnPluginVersionUpdated( $current_version = "", $previous_version = "", $last_pro_version = "" ) {
                $this->OnVersionUpdate( $current_version, $previous_version, $last_pro_version );
			}

			/**
			 * @param $filter
			 * @param callable $method
			 */
			function AddFilter( $filter, $filter_function_name ) {
				add_filter( $filter, $filter_function_name );
			}

			function AddAction( $action, $action_function_name ) {
				add_action( $action, $action_function_name );
			}

			function AddIntoOption( $key, $value ) {
				$this->options[ $key ] = $value;
			}

			function SetPOPUPIconClass( $icon_class ) {
				$this->AddViewData( "__icon_class", $icon_class );
			}

			function SetPOPUPColClass( $col_class ) {
				$this->AddViewData( "__col_class", $col_class );
			}
			function setDisableForm($status=true){
				$this->AddViewData( "__disable_form", $status );
            }

			function SetSubtitle( $title, $parameter = NULL, $_ = NULL ) {
				$args = func_get_args();
								$this->AddViewData( "_subTitle", $title );
			}

			function SetTitle( $title, $parameter = NULL, $_ = NULL ) {
				$args  = func_get_args();
				$title = call_user_func_array( [ $this, "___" ], $args );
				$this->AddViewData( "_title", $title );
			}

			function DisplayPOPUPMsg( $msg = "", $autoCloseTime = 0, $redirectPage = '', $hideCloseButton = false ) {
				$this->AddViewData( '__message', $this->__( $msg ) );
				$this->AddViewData( '__act', $autoCloseTime );
				$this->AddViewData( '__rdir_page', $redirectPage );
				$this->AddViewData( '__force_close_dis', ! $hideCloseButton );
				extract( $this->_viewData );
				include plugin_dir_path( $this->kernelObject->pluginFile ) . "/template/popup-msg.php";
				die;
			}

			function DisplayPOPUp( $viewName ) {
				$viewName = strtolower( $viewName );
				$dir      = get_class( $this );
				extract( $this->_viewData );
				$output = "";
				$dir    = strtolower( $dir );
				$path   = $this->viewPath . $dir . "/" . $viewName . ".php";
				if ( file_exists( $path ) ) {
					ob_start();
					include $path;
					$output = ob_get_clean();
				} else {
					die( "File not exists:" . $path );
				}
				include plugin_dir_path( $this->kernelObject->pluginFile ) . "/template/popup.php";
				die;
			}

			function Display( $viewName = 'main' ) {
				$viewName = strtolower( $viewName );
				$dir      = strtolower( get_class( $this ) );
				extract( $this->_viewData );
				$output = "";
				$dir    = strtolower( $dir );
				$path   = $this->viewPath . $dir . "/" . $viewName . ".php";
				if ( file_exists( $path ) ) {
					ob_start();
					include $path;
					$output = ob_get_clean();
				} else {
					ob_start();
					?>
                    <div class="notice notice-error is-dismissible">
                        <h3><?php $this->_e("File Missing") ; ?></h3>
                        <p><?php echo wp_kses_post($this->_ee("File not exists: %s",$path)); ?></p>
                    </div>
					<?php
										$output = ob_get_clean();
				}
				include plugin_dir_path( $this->kernelObject->pluginFile ) . "/template/main.php";
			}

			function LoadView( $viewName = 'main', $isReturn = false ) {
				$viewName = strtolower( $viewName );
								extract( $this->_viewData );
				$output = "";

				$path   = plugin_dir_path( $this->kernelObject->pluginFile ) . "/views/" . $viewName . ".php";

				if ( file_exists( $path ) ) {
					ob_start();
					include $path;
					$output = ob_get_clean();
				} else {
					die( "File not exists:" . $path );
				}
				if ( $isReturn ) {
					return $output;
				}

				echo wp_kses_no_null($output,array( 'slash_zero' => 'keep' ) );
			}
			final function AddClientStyle( $StyleId,$StyleFileName, $isFromRoot = false, $deps = [] ) {
				$this->kernelObject->AddAdminStyle( $StyleId,$StyleFileName, $isFromRoot, $deps );
			}
			final function AddClientScript( $ScriptId,$ScriptFileName, $isFromRoot = false, $deps = [] ) {
				$this->kernelObject->AddAdminScript( $ScriptId,$ScriptFileName, $isFromRoot, $deps );
			}
			final function AddAdminStyle( $StyleId,$StyleFileName,$isFromRoot = false, $deps = [] ) {
				$this->kernelObject->AddAdminStyle( $StyleId,$StyleFileName, $isFromRoot, $deps );
			}

			final function AddAdminScript( $ScriptId,$ScriptFileName, $isFromRoot = false, $deps = [] ) {
				$this->kernelObject->AddAdminScript(  $ScriptId,$ScriptFileName, $isFromRoot, $deps );
			}

			final function AddGlobalJSVar( $key, $value ) {
				$value = $this->__( $value );
				$this->kernelObject->AddAppGlobalVar( $key, $value );
			}

			/**
			 * @param $actionName
			 * @param callable $function_to_add
			 */
			function AddAjaxAction( $actionName, $function_to_add) {
				$actionName = $this->GetActionName( $actionName );
				add_action( 'wp_ajax_' . $actionName, function () use ( $function_to_add ) {
					$nonce = ( isset( $_REQUEST['_ajax_nonce'] ) ? sanitize_text_field( $_REQUEST['_ajax_nonce'] ) : '' );

					if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) || ! current_user_can( 'manage_options' ) ) {
						if ( wp_doing_ajax() ) {
							wp_die( -1, 403 );
						} else {
							die( '-1' );
						}
					}

					call_user_func( $function_to_add );
					die();
				} );
			}
			/**
			 * @param $actionName
			 * @param callable $function_to_add
			 */
			function AddAjaxNoPrivAction( $actionName, $function_to_add ) {
				$actionName = $this->GetActionName( $actionName );
				add_action( 'wp_ajax_nopriv_' . $actionName, function () use ( $function_to_add ) {
					$nonce = ( isset( $_REQUEST['_ajax_nonce'] ) ? sanitize_text_field( $_REQUEST['_ajax_nonce'] ) : '' );

					if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
						if ( wp_doing_ajax() ) {
							wp_die( -1, 403 );
						} else {
							die( '-1' );
						}
					}

					call_user_func( $function_to_add );
					die();
				} );
			}

			function GetActionUrl( $actionString = '', $params = [] ) {
				$actionName = $this->GetActionName( $actionString );
				$nonceStr = "&_ajax_nonce=" . wp_create_nonce( 'ajax-nonce' );
				$paramStr = count( $params ) > 0 ? "&" . http_build_query( $params ) : "";

				return admin_url( 'admin-ajax.php' ) . '?action=' . $actionName . $nonceStr . $paramStr;
			}

			function GetActionUrlWithBackButton( $actionString = '', $params = [], $backActionString = NULL, $backParams = [], $buttonName = "back" ) {
				$buttonName = $this->__( $buttonName );
				$mainUrl    = $this->GetActionUrl( $actionString, $params );
				if ( $backActionString === NULL ) {
					$buttonUrl = APBD_CurrentUrl();
				} else {
					$buttonUrl = $this->GetActionUrl( $backActionString, $backParams );
				}

				if ( strpos( $mainUrl, "?" ) !== false ) {
					return $mainUrl . "&cbtn=" . urlencode( $buttonUrl ) . "&cbtnn=" . urlencode( $buttonName );
				} else {
					return $mainUrl . "?cbtn=" . $buttonUrl . "&cbtnn=" . $buttonName;
				}
			}

			function RedirectActionUrlWithBackButton( $actionString = '', $params = [], $backActionString = NULL, $backParams = [], $buttonName = "back" ) {
				$url = $this->GetActionUrlWithBackButton( $actionString, $params, $backActionString, $backParams, $buttonName );
				$this->RedirectUrl( $url );
			}

			function RedirectActionUrl( $actionString = '', $params = [] ) {
				$url = $this->GetActionUrl( $actionString, $params );
				$this->RedirectUrl( $url );
			}

			function RedirectUrl( $url ) {
				header( "Location: $url" );
				die;
			}

			function GetAPIUrl( $actionString = '', $params = [] ) {
				return home_url() . '/wp-json/' . $actionString;
			}

			function GetActionName( $actionString = '' ) {
				if ( ! empty( $actionString ) ) {
					$actionString = '_' . $actionString;
				}

				return $this->GetMainFormId() . $actionString;
			}

			function OptionFormHeader() {
				return '';
			}

			function SetMultiLangProps() {
				$multiLangCode = 'en';
				$multiLangActive = false;

				if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ) {
					$multiLangCode = apply_filters( 'wpml_current_language', $multiLangCode );
					$multiLangActive = true;
				} elseif ( function_exists( 'pll_current_language' ) ) {
					$multiLangCode = call_user_func( 'pll_current_language' );
					$multiLangActive = true;
				}

				$multiLangCode = apply_filters( 'support_genix_current_language_key', $multiLangCode );
				$multiLangCode = sanitize_text_field( $multiLangCode );
				$multiLangCode = ( ( $multiLangCode && ( 'all' !== $multiLangCode ) ) ? $multiLangCode : 'en' );

				$this->multiLangCode = $multiLangCode;
				$this->multiLangActive = $multiLangActive;
				$this->multiLangFields = $this->GetMultiLangFields();
			}

			function GetMultiLangFields() {
				return [];
			}

			function SetOption() {
				$modulename = get_class( $this );
				$this->options = get_option( $this->pluginBaseName . "_o_" . $modulename, NULL );
			}

			function GetOption( $key = '', $default = '' ) {
				if ( empty( $key ) ) {
					return $this->options;
				} else {
					if ( ! empty( $this->options[ $key ] ) ) {
						return $this->options[ $key ];
					} else {
						return $default;
					}
				}
			}

			function AddOption( $key, $value ) {
				$this->options[ $key ] = $value;

				return $this->UpdateOption();
			}

			function UpdateOption() {
				return update_option( $this->pluginBaseName . "_o_" . $this, $this->options ) || add_option( $this->pluginBaseName . "_o_" . $this, $this->options );
			}

			function SetMultiLangOption( $optionKey = '' ) {
				$langFields = $this->multiLangFields;
				$langCode = $this->multiLangCode;
				$allOptions = get_option( $optionKey, null );
				$options = $allOptions;

				if ( is_array( $langFields ) && ! empty ( $langFields ) && is_array( $options ) && ! empty( $options ) ) {
					foreach ( $langFields as $fieldKey => $fieldValue ) {
						if ( isset( $options[ $fieldKey ] ) ) {
							$option = $options[ $fieldKey ];

							if ( is_array( $option ) ) {
								$options[ $fieldKey ] = ( isset( $option[ $langCode ] ) ? $option[ $langCode ] : ( isset( $option['en'] ) ? $option['en'] : $fieldValue ) );
							}
						}
					}
				}

				$this->allOptions = $allOptions;
				$this->options = $options;
			}

			function UpdateMultiLangOption( $optionKey = '' ) {
				$langFields = $this->multiLangFields;
				$langCode = $this->multiLangCode;
				$allOptions = $this->allOptions;
				$options = $this->options;

				$langFields = ( is_array( $langFields ) ? $langFields : [] );
				$allOptions = ( is_array( $allOptions ) ? $allOptions : [] );
				$options = ( is_array( $options ) ? $options : [] );

				if ( ! empty ( $langFields ) && ! empty( $options ) ) {
					foreach ( $options as $fieldKey => $option ) {
						if ( isset( $langFields[ $fieldKey ] ) ) {
							$allOption = ( isset( $allOptions[ $fieldKey ] ) ? $allOptions[ $fieldKey ] : null );

							if ( is_array( $allOption ) ) {
								$allOption[ $langCode ] = $option;
							} else {
								$allOption = [ $langCode => $option ];
							}

							$allOptions[ $fieldKey ] = $allOption;
						} else {
							$allOptions[ $fieldKey ] = $option;
						}
					}
				} else {
					$allOptions = $options;
				}

				$this->allOptions = $allOptions;

				return update_option( $optionKey, $allOptions ) || add_option( $optionKey, $allOptions );
			}

			function GetModuleId() {
				return get_class( $this );
			}

			function _e( $string, $parameter = NULL, $_ = NULL ) {
				$args = func_get_args();
				echo wp_kses_html(call_user_func_array( [ $this->kernelObject, "__" ], $args ));
			}

			function _ee( $string, $parameter = NULL, $_ = NULL ) {
				$args = func_get_args();
				foreach ( $args as &$arg ) {
					if ( is_string( $arg ) ) {
						$arg = $this->kernelObject->__( $arg );
					}
				}
				echo wp_kses_html(call_user_func_array( [ $this->kernelObject, "__" ], $args ));
			}

			function __( $string, $parameter = NULL, $_ = NULL ) {
				$args = func_get_args();

				return call_user_func_array( [ $this->kernelObject, "__" ], $args );
			}

			function ___( $string, $parameter = NULL, $_ = NULL ) {
				$args = func_get_args();
				foreach ( $args as &$arg ) {
					if ( is_string( $arg ) ) {
						$arg = $this->kernelObject->__( $arg );
					}
				}

				return call_user_func_array( [ $this->kernelObject, "__" ], $args );
			}

			function GetMainFormId() {
				return $this->pluginBaseName . '_AJ_' . $this;
			}

			public function __toString() {
				return get_class( $this );
			}

			function data() {
				$data = new AppsbdAjaxDataResponse();
				wp_send_json( $data );
			}

			function confirm() {
				$data = new AppsbdAjaxConfirmResponse();
                wp_send_json( $data );
			}

			function AdminScriptData() {

			}

			function OnInit() {
				$this->AddAjaxAction( '', [ $this, 'AjaxRequestCallback' ] );
				$this->AddAjaxAction( 'data', [ $this, 'data' ] );
				$this->AddAjaxAction( 'confirm', [ $this, 'confirm' ] );
			}
			public function AjaxRequestCallback() {
				$response   = new AppsbdAjaxConfirmResponse();
				$beforeSave = $this->options;
				$postData = wp_parse_args( $_POST );
				foreach ( $postData as $key=>$value ) {
                    $key=sanitize_key($key);
				    if($key=="action"){
				        continue;
                    }

					$this->options[$key]=!in_array($key,$this->HTMLInputFields)?sanitize_text_field($value):wp_kses_html($value);
				}
				if ( $beforeSave === $this->options ) {
					$response->DisplayWithResponse( false, $this->__( "No change for update" ) );
				} else {
					$response->SetResponse( false, $this->__( "No change for update" ) );
					if ( $this->UpdateOption() ) {
						$response->DisplayWithResponse( true, $this->__( "Saved Successfully" ) );
					} else {
						$response->DisplayWithResponse( false, $this->__( "No change for update" ) );
					}
				}

								$response->Display();
			}

			function IsActive() {
				return true;
			}

			function IsPageCheck( $page ) {
				return false;
			}

			function OnActive( $new_activation = true, $new_pro_activation = true ) {

			}

			function OnDeactive() {

			}

			function AdminScripts() {

			}

			function AdminStyles() {

			}

			function ClientScript() {

			}

			function ClientStyle() {

			}

			public function LinksActions( &$links ) {

			}

			public function PluginRowMeta( &$links ) {

			}

			public function AdminSubMenu() {

			}

			public function OnAdminGlobalStyles() {

			}

			public function OnAdminMainOptionStyles() {

			}

			public function OnAdminGlobalScripts() {
			}

			public function OnAdminMainOptionScripts() {

			}

			/**
			 * @return string
			 */
			public function getFormClass() {
				return $this->formClass;
			}

			/**
			 * @param string $formClass
			 */
			public function setFormClass( $formClass ) {
				$this->formClass = $formClass;
			}

			/**
			 * @return array
			 */
			public function getFormDataAttr() {
				return $this->formDataAttr;
			}

			/**
			 * @param array $formDataAttr
			 */
			public function setFormDataAttr( $formDataAttr ) {
				$this->formDataAttr = $formDataAttr;
			}

			/**
			 * @return bool
			 */
			public function isMultipartForm() {
				return $this->isMultipartForm;
			}

			/**
			 * @param bool $isMultipartForm
			 */
			public function setIsMultipartForm( $isMultipartForm ) {
				$this->isMultipartForm = $isMultipartForm;
			}

			/**
			 * @param bool $dontAddDefaultForm
			 */
			public function disableDefaultForm() {
				$this->dontAddDefaultForm = true;
			}

			/**
			 * @return bool
			 */
			public function isDontAddDefaultForm() {
				return $this->dontAddDefaultForm;
			}
		}
	}