<?php
/**
 * Diagnostic data.
 */

// If this file is accessed directly, exit.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class.
 */
if ( ! class_exists( 'APBDWPDiagnosticData' ) ) {
    class APBDWPDiagnosticData {

        /**
         * Project name.
         */
        private $project_name;

        /**
         * Project type.
         */
        private $project_type;

        /**
         * Project version.
         */
        private $project_version;

        /**
         * Pro active.
         */
        private $project_pro_active;

        /**
         * Pro installed.
         */
        private $project_pro_installed;

        /**
         * Pro version.
         */
        private $project_pro_version;

        /**
         * Data center.
         */
        private $data_center;

        /**
         * Privacy policy.
         */
        private $privacy_policy;

        /**
         * Instance.
         */
        private static $_instance = null;

		/**
		 * Get instance.
		 */
		public static function get_instance( $version = '' ) {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self( $version );
			}

			return self::$_instance;
		}

        /**
         * Constructor.
         */
        private function __construct( $version = '' ) {
            $this->includes();

            $this->project_name = 'Support Genix';
            $this->project_type = 'wordpress-plugin';
            $this->project_version = $version;
            $this->data_center = 'https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjAwNTY1MDYzZTA0MzM1MjY1NTUzNyI_3D_pc';
            $this->privacy_policy = 'https://supportgenix.com/privacy-policy/';

            $this->project_pro_active = $this->is_pro_plugin_active();
            $this->project_pro_installed = $this->is_pro_plugin_installed();
            $this->project_pro_version = $this->get_pro_version();

            add_action( 'admin_notices', function () {
                $this->show_notices();
            }, 0 );

            add_action( 'wp_ajax_support_genix_lite_diagnostic_data', function () {
                $nonce = ( isset( $_REQUEST['_ajax_nonce'] ) ? sanitize_text_field( $_REQUEST['_ajax_nonce'] ) : '' );

                if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) || ! current_user_can( 'manage_options' ) ) {
                    if ( wp_doing_ajax() ) {
                        wp_die( -1, 403 );
                    } else {
                        die( '-1' );
                    }
                }

                $this->process_data();
            } );
        }

        /**
         * Is capable user.
         */
        private function includes() {
            if ( ! function_exists( 'is_plugin_active' ) || ! function_exists( 'get_plugins' ) || ! function_exists( 'get_plugin_data' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
        }

        /**
         * Is capable user.
         */
        private function is_capable_user() {
            $result = 'no';

            if ( current_user_can( 'manage_options' ) ) {
                $result = 'yes';
            }

            return $result;
        }

        /**
         * Is show core notice.
         */
        private function is_show_core_notice() {
            $result = get_option( 'support_genix_lite_diagnostic_data_notice', 'yes' );
            $result = ( ( 'yes' === $result ) ? 'yes' : 'no' );

            return $result;
        }

        /**
         * Is pro active.
         */
        private function is_pro_plugin_active() {
            $plugin = 'support-genix/support-genix.php';

            $result = is_plugin_active( $plugin );
            $result = ( ( true === $result ) ? 'yes' : 'no' );

            return $result;
        }

        /**
         * Is pro installed.
         */
        private function is_pro_plugin_installed() {
            $plugin = 'support-genix/support-genix.php';

            $plugins = get_plugins();
            $result = ( isset( $plugins[ $plugin ] ) ? 'yes' : 'no' );

            return $result;
        }

        /**
         * Get pro version.
         */
        private function get_pro_version() {
            $plugin = 'support-genix/support-genix.php';

            $plugins = get_plugins();
            $data = ( ( isset( $plugins[ $plugin ] ) && is_array( $plugins[ $plugin ] ) ) ? $plugins[ $plugin ] : array() );
            $version = ( isset( $data['Version'] ) ? sanitize_text_field( $data['Version'] ) : '' );

            return $version;
        }

        /**
         * Process data.
         */
        private function process_data() {
            $agreed = ( isset( $_POST['agreed'] ) ? sanitize_key( $_POST['agreed'] ) : 'no' );
            $agreed = ( ( 'yes' === $agreed ) ? 'yes' : 'no' );

            $notice = 'no';

            if ( 'yes' === $agreed ) {
                $data = $this->get_data();

                if ( ! empty( $data ) ) {
                    $response = $this->send_request( $data );

                    if ( is_wp_error( $response ) ) {
                        $agreed = 'no';
                        $notice = 'yes';
                    }
                }
            }

            update_option( 'support_genix_lite_diagnostic_data_agreed', $agreed );
            update_option( 'support_genix_lite_diagnostic_data_notice', $notice );

            $response = array(
                'success' => $agreed,
                'notice' => $notice,
            );

            if ( 'yes' === $agreed ) {
                $response['thanks_notice'] = $this->get_thanks_notice();
            }

            wp_send_json( $response );
        }

        /**
         * Get data.
         */
        private function get_data() {
            $hash = md5( current_time( 'U', true ) );

            $project = array(
                'name'          => $this->project_name,
                'type'          => $this->project_type,
                'version'       => $this->project_version,
                'pro_active'    => $this->project_pro_active,
                'pro_installed' => $this->project_pro_installed,
                'pro_version'   => $this->project_pro_version,
            );

            $site_title = get_bloginfo( 'name' );
            $site_description = get_bloginfo( 'description' );
            $site_url = wp_parse_url( home_url(), PHP_URL_HOST );
            $admin_email = get_option( 'admin_email' );

            $admin_first_name = '';
            $admin_last_name = '';
            $admin_display_name = '';

            $users = get_users( array(
                'role'    => 'administrator',
                'orderby' => 'ID',
                'order'   => 'ASC',
                'number'  => 1,
                'paged'   => 1,
            ) );

            $admin_user = ( ( is_array( $users ) && isset( $users[0] ) && is_object( $users[0] ) ) ? $users[0] : null );

            if ( ! empty( $admin_user ) ) {
                $admin_first_name = ( isset( $admin_user->first_name ) ? $admin_user->first_name : '' );
                $admin_last_name = ( isset( $admin_user->last_name ) ? $admin_user->last_name : '' );
                $admin_display_name = ( isset( $admin_user->display_name ) ? $admin_user->display_name : '' );
            }

            $ip_address = $this->get_ip_address();

            $data = array(
                'hash'               => $hash,
                'project'            => $project,
                'site_title'         => $site_title,
                'site_description'   => $site_description,
                'site_address'       => $site_url,
                'site_url'           => $site_url,
                'admin_email'        => $admin_email,
                'admin_first_name'   => $admin_first_name,
                'admin_last_name'    => $admin_last_name,
                'admin_display_name' => $admin_display_name,
                'server_info'        => $this->get_server_info(),
                'wordpress_info'     => $this->get_wordpress_info(),
                'users_count'        => $this->get_users_count(),
                'plugins_count'      => $this->get_plugins_count(),
                'ip_address'         => $ip_address,
                'country_name'       => $this->get_country_from_ip( $ip_address ),
            );

            return $data;
        }

        /**
         * Get server info.
         */
        private function get_server_info() {
            global $wpdb;

            $software = ( ( isset( $_SERVER['SERVER_SOFTWARE'] ) && ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) ? sanitize_text_field( $_SERVER['SERVER_SOFTWARE'] ) : '' );
            $php_version = ( function_exists( 'phpversion' ) ? phpversion() : '' );
            $mysql_version = ( method_exists( $wpdb, 'db_version' ) ? $wpdb->db_version() : '' );
            $php_max_upload_size = size_format( wp_max_upload_size() );
            $php_default_timezone = date_default_timezone_get();
            $php_soap = ( class_exists( 'SoapClient' ) ? 'yes' : 'no' );
            $php_fsockopen = ( function_exists( 'fsockopen' ) ? 'yes' : 'no' );
            $php_curl = ( function_exists( 'curl_init' ) ? 'yes' : 'no' );

            $server_info = array(
                'software'             => $software,
                'php_version'          => $php_version,
                'mysql_version'        => $mysql_version,
                'php_max_upload_size'  => $php_max_upload_size,
                'php_default_timezone' => $php_default_timezone,
                'php_soap'             => $php_soap,
                'php_fsockopen'        => $php_fsockopen,
                'php_curl'             => $php_curl,
            );

            return $server_info;
        }

        /**
         * Get wordpress info.
         */
        private function get_wordpress_info() {
            $wordpress_info = array();

            $memory_limit = ( defined( 'WP_MEMORY_LIMIT' ) ? WP_MEMORY_LIMIT : '' );
            $debug_mode = ( ( defined('WP_DEBUG') && WP_DEBUG ) ? 'yes' : 'no' );
            $locale = get_locale();
            $version = get_bloginfo( 'version' );
            $multisite = ( is_multisite() ? 'yes' : 'no' );
            $theme_slug = get_stylesheet();

            $wordpress_info = array(
                'memory_limit' => $memory_limit,
                'debug_mode'   => $debug_mode,
                'locale'       => $locale,
                'version'      => $version,
                'multisite'    => $multisite,
                'theme_slug'   => $theme_slug,
            );

            $theme = wp_get_theme( $wordpress_info['theme_slug'] );

            if ( is_object( $theme ) && ! empty( $theme ) && method_exists( $theme, 'get' ) ) {
                $theme_name    = $theme->get( 'Name' );
                $theme_version = $theme->get( 'Version' );
                $theme_uri     = $theme->get( 'ThemeURI' );
                $theme_author  = $theme->get( 'Author' );

                $wordpress_info = array_merge( $wordpress_info, array(
                    'theme_name'    => $theme_name,
                    'theme_version' => $theme_version,
                    'theme_uri'     => $theme_uri,
                    'theme_author'  => $theme_author,
                ) );
            }

            return $wordpress_info;
        }

        /**
         * Get users count.
         */
        private function get_users_count() {
            $users_count = array();

            $users_count_data = count_users();

            $total_users = ( isset( $users_count_data['total_users'] ) ? $users_count_data['total_users'] : 0 );
            $avail_roles = ( isset( $users_count_data['avail_roles'] ) ? $users_count_data['avail_roles'] : array() );

            $users_count['total'] = $total_users;

            if ( is_array( $avail_roles ) && ! empty( $avail_roles ) ) {
                foreach ( $avail_roles as $role => $count ) {
                    $users_count[ $role ] = $count;
                }
            }

            return $users_count;
        }

        /**
         * Get plugins count.
         */
        private function get_plugins_count() {
            $total_plugins_count = 0;
            $active_plugins_count = 0;
            $inactive_plugins_count = 0;

            $plugins = get_plugins();
            $plugins = ( is_array( $plugins ) ? $plugins : array() );

            $active_plugins = get_option( 'active_plugins', array() );
            $active_plugins = ( is_array( $active_plugins ) ? $active_plugins : array() );

            if ( ! empty( $plugins ) ) {
                foreach ( $plugins as $key => $data ) {
                    if ( in_array( $key, $active_plugins, true ) ) {
                        $active_plugins_count++;
                    } else {
                        $inactive_plugins_count++;
                    }

                    $total_plugins_count++;
                }
            }

            $plugins_count = array(
                'total'    => $total_plugins_count,
                'active'   => $active_plugins_count,
                'inactive' => $inactive_plugins_count,
            );

            return $plugins_count;
        }

        /**
         * Get IP address.
         */
        private function get_ip_address() {
            $response = wp_remote_get( 'https://icanhazip.com/' );

            if ( is_wp_error( $response ) ) {
                return '';
            }

            $ip_address = wp_remote_retrieve_body( $response );
            $ip_address = trim( $ip_address );

            if ( ! filter_var( $ip_address, FILTER_VALIDATE_IP ) ) {
                return '';
            }

            return $ip_address;
        }

        /**
         * Get country name.
         */
        private function get_country_from_ip( $ip_address ) {
            $api_url = 'http://ip-api.com/json/' . $ip_address;
            $response = wp_remote_get( $api_url );
            $country = '';

            if ( is_wp_error( $response ) ) {
                return $country;
            }

            $data = json_decode( wp_remote_retrieve_body($response) );

            if ( is_object( $data ) && isset( $data->status ) && 'success' === $data->status ) {
                $country = isset( $data->country ) ? sanitize_text_field( $data->country ) : '';
            }

            return $country;
        }

        /**
         * Send request.
         */
        private function send_request( $data = array() ) {
            if ( ! is_array( $data ) || empty( $data ) ) {
                return;
            }

            $site_url = wp_parse_url( home_url(), PHP_URL_HOST );

            $headers = array(
                'user-agent' => $this->project_name . '/' . md5( $site_url ) . ';',
                'Accept'     => 'application/json',
            );

            $response = wp_remote_post( $this->data_center, array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => false,
                'headers'     => $headers,
                'body'        => $data,
                'cookies'     => array(),
            ) );

            return $response;
        }

        /**
         * Show notices.
         */
        private function show_notices() {
            if ( 'no' === $this->is_capable_user() ) {
                return;
            }

            if ( 'yes' === $this->is_show_core_notice() ) {
                $this->show_core_notice();
            }
        }

        /**
         * Show core notice.
         */
        private function show_core_notice() {
            $message_l1 = sprintf( esc_html__( 'At %2$s%1$s%3$s, we prioritize continuous improvement and compatibility. To achieve this, we gather non-sensitive diagnostic information and details about plugin usage. This includes your site\'s URL, the versions of WordPress and PHP you\'re using, and a list of your installed plugins and themes. We also require your email address to provide you with exclusive discount coupons and updates. This data collection is crucial for ensuring that %2$s%1$s%3$s remains up-to-date and compatible with the most widely-used plugins and themes. Rest assured, your privacy is our priority - no spam, guaranteed. %4$sPrivacy Policy%5$s', 'support-genix-lite' ), esc_html( $this->project_name ), '<strong>', '</strong>', '<a target="_blank" href="' . esc_url( $this->privacy_policy ) . '">', '</a>', '<h4 class="support-genix-lite-diagnostic-data-title">', '</h4>' );
            $message_l2 = sprintf( esc_html__( 'Server information (Web server, PHP version, MySQL version), WordPress information, site name, site URL, number of plugins, number of users, your name, and email address. You can rest assured that no sensitive data will be collected or tracked. %1$sLearn more%2$s.', 'support-genix-lite' ), '<a target="_blank" href="' . esc_url( $this->privacy_policy ) . '">', '</a>' );

            $button_text_1 = esc_html__( 'Count Me In', 'support-genix-lite' );
            $button_link_1 = add_query_arg( array( 'support-genix-lite-diagnostic-data-agreed' => 1 ) );

            $button_text_2 = esc_html__( 'No, Thanks', 'support-genix-lite' );
            $button_link_2 = add_query_arg( array( 'support-genix-lite-diagnostic-data-agreed' => 0 ) );
            ?>
            <div class="support-genix-lite-diagnostic-data-style"><style>.support-genix-lite-diagnostic-data-notice,.woocommerce-embed-page .support-genix-lite-diagnostic-data-notice{padding-top:.75em;padding-bottom:.75em;}.support-genix-lite-diagnostic-data-notice .support-genix-lite-diagnostic-data-buttons,.support-genix-lite-diagnostic-data-notice .support-genix-lite-diagnostic-data-list,.support-genix-lite-diagnostic-data-notice .support-genix-lite-diagnostic-data-message{padding:.25em 2px;margin:0;}.support-genix-lite-diagnostic-data-notice .support-genix-lite-diagnostic-data-list{display:none;color:#646970;}.support-genix-lite-diagnostic-data-notice .support-genix-lite-diagnostic-data-buttons{padding-top:.75em;}.support-genix-lite-diagnostic-data-notice .support-genix-lite-diagnostic-data-buttons .button{margin-right:5px;box-shadow:none;}.support-genix-lite-diagnostic-data-loading{position:relative;}.support-genix-lite-diagnostic-data-loading::before{position:absolute;content:"";width:100%;height:100%;top:0;left:0;background-color:rgba(255,255,255,.5);z-index:999;}.support-genix-lite-diagnostic-data-disagree{border-width:0px !important;background-color: transparent!important; padding: 0!important;}h4.support-genix-lite-diagnostic-data-title {margin: 0 0 10px 0;font-size: 1.04em;font-weight: 600;}</style></div>
            <div class="support-genix-lite-diagnostic-data-notice notice notice-success">
                <h4 class="support-genix-lite-diagnostic-data-title"><?php echo sprintf( esc_html__('🌟 Enhance Your %1$s Experience as a Valued Contributor!','support-genix-lite'), esc_html( $this->project_name )); ?></h4>
                <p class="support-genix-lite-diagnostic-data-message"><?php echo wp_kses_post( $message_l1 ); ?></p>
                <p class="support-genix-lite-diagnostic-data-list"><?php echo wp_kses_post( $message_l2 ); ?></p>
                <p class="support-genix-lite-diagnostic-data-buttons">
                    <a href="<?php echo esc_url( $button_link_1 ); ?>" class="support-genix-lite-diagnostic-data-button support-genix-lite-diagnostic-data-agree button button-primary"><?php echo esc_html( $button_text_1 ); ?></a>
                    <a href="<?php echo esc_url( $button_link_2 ); ?>" class="support-genix-lite-diagnostic-data-button support-genix-lite-diagnostic-data-disagree button button-secondary"><?php echo esc_html( $button_text_2 ); ?></a>
                </p>
            </div>
            <div class="support-genix-lite-diagnostic-data-script"><script type="text/javascript">;(function($){"use strict";function supportGenixLiteDissmissThanksNotice(noticeWrap){$('.support-genix-lite-diagnostic-data-thanks .notice-dismiss').on('click',function(e){e.preventDefault();let thisButton=$(this),noticeWrap=thisButton.closest('.support-genix-lite-diagnostic-data-thanks');noticeWrap.fadeTo(100,0,function(){noticeWrap.slideUp(100,function(){noticeWrap.remove()})})})};$(".support-genix-lite-diagnostic-data-list-toogle").on("click",function(e){e.preventDefault();$(this).parents(".support-genix-lite-diagnostic-data-notice").find(".support-genix-lite-diagnostic-data-list").slideToggle("fast")});$(".support-genix-lite-diagnostic-data-button").on("click",function(e){e.preventDefault();let thisButton=$(this),noticeWrap=thisButton.closest(".support-genix-lite-diagnostic-data-notice"),agreed=thisButton.hasClass("support-genix-lite-diagnostic-data-agree")?"yes":"no",styleWrap=$(".support-genix-lite-diagnostic-data-style"),scriptWrap=$(".support-genix-lite-diagnostic-data-script");$.ajax({type:"POST",url:ajaxurl,data:{action:"support_genix_lite_diagnostic_data",agreed:agreed,_ajax_nonce:'<?php echo wp_create_nonce( 'ajax-nonce' );?>'},beforeSend:function(){noticeWrap.addClass("support-genix-lite-diagnostic-data-loading")},success:function(response){response="object"===typeof response?response:{};let success=response.hasOwnProperty("success")?response.success:"no",notice=response.hasOwnProperty("notice")?response.notice:"no",thanks_notice=response.hasOwnProperty("thanks_notice")?response.thanks_notice:"";if("yes"===success){noticeWrap.replaceWith(thanks_notice);styleWrap.remove();scriptWrap.remove()}else if("no"===notice){noticeWrap.remove();styleWrap.remove();scriptWrap.remove()};noticeWrap.removeClass("support-genix-lite-diagnostic-data-loading");supportGenixLiteDissmissThanksNotice()},error:function(){noticeWrap.removeClass("support-genix-lite-diagnostic-data-loading")},})})})(jQuery);</script></div>
            <?php
        }

        /**
         * Get thanks notice.
         */
        private function get_thanks_notice() {
            $message = sprintf( esc_html__( 'Thank you very much for supporting %2$s%1$s%3$s.', 'support-genix-lite' ), $this->project_name, '<strong>', '</strong>' );
            $notice = sprintf( '<div class="support-genix-lite-diagnostic-data-thanks notice notice-success is-dismissible"><p>%1$s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button></div>', wp_kses_post( $message ) );

            return $notice;
        }

    }

    // Returns the instance.
    APBDWPDiagnosticData::get_instance( $appWpSUpportLiteVersion );
}