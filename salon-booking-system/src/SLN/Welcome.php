<?php

class SLN_Welcome
{
    const STORE_REDIRECT_KEY	= '_sln_welcome_redirect';
    const STORE_SHOW_PAGE_KEY	= '_sln_welcome_show_page';
    const PAGE_SLUG		= 'salon-welcome-page';

    protected $plugin;

    public function __construct(SLN_Plugin $plugin) {

	$this->plugin = $plugin;

	register_activation_hook(SLN_PLUGIN_BASENAME, array($this, 'plugin_activate'));
	register_deactivation_hook( SLN_PLUGIN_BASENAME, array($this, 'plugin_deactivate') );

	add_action( 'admin_init', array($this, 'welcome_page_redirect') );
	add_action( 'init', array($this, 'redirect_to_plugin') );

	if ( get_transient( self::STORE_SHOW_PAGE_KEY ) ) {

	    // Enqueue the styles.
	    add_action( 'admin_enqueue_scripts', array($this, 'styles') );

	    add_action( 'admin_menu', array($this, 'welcome_page') );
	}
    }

    public function plugin_activate() {
	// Transient max age is 60 seconds.
	set_transient( self::STORE_REDIRECT_KEY, true, 60 );
	set_transient( self::STORE_SHOW_PAGE_KEY, true, 60 );
    }

    public function plugin_deactivate() {
	delete_transient( self::STORE_REDIRECT_KEY );
	delete_transient( self::STORE_SHOW_PAGE_KEY );
    }

    public function welcome_page_redirect() {

	if ( ! get_transient( self::STORE_REDIRECT_KEY ) ) {
	    return;
	}

	// Delete the redirect transient.
	delete_transient( self::STORE_REDIRECT_KEY );

	// Bail if activating from network or bulk sites.
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
	  return;
	}

	// Redirect to Welcome Page.
	// Redirects to `your-domain.com/wp-admin/plugin.php?page=wpw_welcome_page`.
	wp_safe_redirect( add_query_arg( array( 'page' => self::PAGE_SLUG ), admin_url( 'plugins.php' ) ) );

    }

    public function welcome_page() {

	global $sln_welcome_sub_menu;

	$sln_welcome_sub_menu = add_submenu_page(
	    'plugins.php',
	    __( 'Welcome Page Salon Booking Plugin', 'salon-booking-system' ),
	    __( 'Welcome Page Salon Booking Plugin', 'salon-booking-system' ),
	    'read',
	    self::PAGE_SLUG,
	    array($this, 'welcome_page_content')
	);

    }

    /**
     * Welcome page content.
     *
     * @since 1.0.0
     */
    public function welcome_page_content() {

	// Delete the redirect transient.
	delete_transient( self::STORE_SHOW_PAGE_KEY );

	echo $this->plugin->loadView('welcome');
    }

    /**
     * Enqueue Styles.
     *
     * @since 1.0.0
     */
    public function styles( $hook ) {

	global $sln_welcome_sub_menu;

	// Add style to the welcome page only.
	if ( $hook != $sln_welcome_sub_menu ) {
	    return;
	}

	// Welcome page styles.
	wp_enqueue_style( 'sln_welcome_page_style', SLN_PLUGIN_URL . '/css/welcome.css', array(), SLN_Action_InitScripts::ASSETS_VERSION, 'all' );
	//Rtl support
	wp_style_add_data( 'sln_welcome_page_style', 'rtl', 'replace' );
    }

    public function redirect_to_plugin() {

	if ( isset($_GET['page']) && $_GET['page'] === self::PAGE_SLUG && ! get_transient( self::STORE_SHOW_PAGE_KEY ) ) {
	    wp_safe_redirect( add_query_arg( array( 'page' => SLN_Admin_Calendar::PAGE ), admin_url( 'admin.php' ) ) );
	    exit();
	}
    }

}
