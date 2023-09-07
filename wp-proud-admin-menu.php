<?php
/*
Plugin Name: ProudCity Admin Menu
Plugin URI: https://proudcity.com
Description: Builds out the WP Admin Menu in the order we want with the styles to suit our theme
Version: 2023.09.07.0718
Author: ProudCity
Author URI: https://proudcity.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class ProudCity_Admin_Menu{

	private static $instance;

	/**
	 * Spins up the instance of the plugin so that we don't get many instances running at once
	 *
	 * @since 2023.08.24
	 * @author SFNdesign, Curtis McHale
	 *
	 * @uses $instance->init()                      The main get it running function
	 */
	public static function instance(){

		if ( ! self::$instance ){
			self::$instance = new ProudCity_Admin_Menu();
			self::$instance->init();
		}

	} // instance

	/**
	 * Spins up all the actions/filters in the plugin to really get the engine running
	 *
	 * @since 2023.08.24
	 * @author Curtis McHale
	 *
	 * @uses $this->constants()                 Defines our constants
	 * @uses $this->includes()                  Gets any includes we have
	 */
	public function init(){

		$this->constants();
		$this->includes();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

		add_action( 'admin_menu', array( $this, 'custom_menu_items' ), 1 );
		add_action( 'admin_menu', array( $this, 'admin_menu_order' ), 9999 );

		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );

	} // init

	public static function custom_menu_items(){


	}

	/**
	 * Manually orders the array of menu items
	 *
	 * @since 2023.08.31
	 * @access public
	 * @author Curtis
	 */
	public static function admin_menu_order(){

		global $menu, $submenu;
		
		/**
		 *  Reindex the array so that anything I don't touch gets pushed
		 *  to the bottom of the menu
		 */
		$start_index = 999;
		$menu = array_combine(
				range( $start_index,
					count($menu) + ( $start_index-1) ),
					array_values( $menu )
		);

		/** ---- unsetting stuff we don't need ----- **/
		/** By searching early we can remove this    **/
		/** and thus reduce the search space for     **/
		/** the rest of the work coming up           **/

		// links
		$links_key = self::get_key( 'menu-links', $menu );
		unset( $menu[$links_key] );

		// comments
		$comments_key = self::get_key( 'menu-comments', $menu );
		unset( $menu[$comments_key] );

		// separator 2
		$sep2_key = self::get_key( 'separator2', $menu );
		$sep2 = $menu[$sep2_key];
		unset( $menu[$sep2_key] );
		//$menu[270] = $sep1; If we want this in a new spot then we need to set it again

		// separator last
		$seplast_key = self::get_key( 'separator-last', $menu );
		$seplast = $menu[$seplast_key];
		unset( $menu[$seplast_key] );
		//$menu[270] = $sep1; If we want this in a new spot then we need to set it again

		/** ---- Setting the menu how we want it ----- **/
		// view site
		$view_site = array(
			'0' => 'View Site',
			'1' => 'read',
			'2' => site_url(),
			'3' => '',
			'4' => 'menu-top menu-view-site',
			'5' => 'menu-view-site',
			'6' => 'dashicons-admin-site',
		);
		$menu[10] = $view_site;

		// PC Analytics
		$pca_key = self::get_key( 'toplevel_page_pc-analytics', $menu );
		$pca = $menu[$pca_key];
		unset( $menu[$pca_key] );
		$menu[20] = $pca;

		// PC Dashboard
		$pcd_key = self::get_key( 'toplevel_page_proud_dashboard', $menu );
		$pcd = $menu[$pcd_key];
		unset( $menu[$pcd_key] );
		$menu[30] = $pcd;

		// Site Kit
		$gsk_key = self::get_key( 'toplevel_page_googlesitekit-dashboard', $menu );
		$gsk = $menu[$gsk_key];
		unset( $menu[$gsk_key] );
		$menu[40] = $gsk;

		// Pages
		$pages_key = self::get_key( 'menu-pages', $menu );
		$pages = $menu[$pages_key];
		unset( $menu[$pages_key] );
		$menu[50] = $pages;
		
		// Posts/News
		$posts_key = self::get_key( 'menu-posts', $menu );
		$posts = $menu[$posts_key];
		$posts[0] = 'News';
		$posts[6] = 'dashicons-text-page';
		unset( $menu[$posts_key] );
		$menu[60] = $posts;

		// Departments
		$dep_key = self::get_key( 'menu-posts-agency', $menu );
		$dep = $menu[$dep_key];
		unset( $menu[$dep_key] );
		$menu[70] = $dep;

		// Documents
		$doc_key = self::get_key( 'menu-posts-document', $menu );
		$doc = $menu[$doc_key];
		unset( $menu[$doc_key] );
		$menu[80] = $doc;

		// Forms
		$gf_key = self::get_key( 'toplevel_page_gf_edit_forms', $menu );
		$gf = $menu[$gf_key];
		unset( $menu[$gf_key] );
		$menu[90] = $gf;

		// Events
		$events_key = self::get_key( 'menu-posts-event', $menu );
		$events = $menu[$events_key];
		unset( $menu[$events_key] );
		$menu[100] = $events;

		// Meetings
		$meet_key = self::get_key( 'menu-posts-meeting', $menu );
		$meet = $menu[$meet_key];
		unset( $menu[$meet_key] );
		$menu[110] = $meet;

		// Payments
		$ans_key = self::get_key( 'menu-posts-question', $menu );
		$ans = $menu[$ans_key];
		$ans[0] = 'Answers';
		unset( $menu[$ans_key] );
		$menu[120] = $ans;

		// Staff Members/Contacts
		$contacts_key = self::get_key( 'menu-posts-staff-member', $menu );
		$contacts = $menu[$contacts_key];
		$posts[0] = 'Contacts';
		unset( $menu[$contacts_key] );
		$menu[130] = $contacts;

		// Issues
		$issues_key = self::get_key( 'menu-posts-issue', $menu );
		$issues = $menu[$issues_key];
		unset( $menu[$issues_key] );
		$menu[140] = $issues;

		// Payments
		$pay_key = self::get_key( 'menu-posts-payment', $menu );
		$pay = $menu[$pay_key];
		$pay[6] = 'dashicons-money-alt';
		unset( $menu[$pay_key] );
		$menu[150] = $pay;

		// Locations
		$loc_key = self::get_key( 'menu-posts-proud_location', $menu );
		$loc = $menu[$loc_key];
		unset( $menu[$loc_key] );
		$menu[160] = $loc;

		// Jobs
		$jobs_key = self::get_key( 'menu-posts-job_listing', $menu );
		$jobs = $menu[$jobs_key];
		unset( $menu[$jobs_key] );
		$menu[170] = $jobs;

		// media
		$media_key = self::get_key( 'menu-media', $menu );
		$media = $menu[$media_key];
		unset( $menu[$media_key] );
		$menu[180] = $media;

		// @todo WordPress menus at nav-menu.php 190
		// menu
		$wp_menu = array(
			'0' => 'Menu',
			'1' => 'read',
			'2' => admin_url() . 'nav-menus.php',
			'3' => '',
			'4' => 'menu-top menu-wp-menu',
			'5' => 'menu-wp-menu',
			'6' => 'dashicons-menu',
		);
		$menu[190] = $wp_menu;

		// @todo PC Accounts 200

		// @todo PC Tools 220

		// Proud Setings
		$pcs_key = self::get_key( 'toplevel_page_proudsettings', $menu );
		$pcs = $menu[$pcs_key];
		unset( $menu[$pcs_key] );
		$menu[230] = $pcs;

		// Service Center
		$service_key = self::get_key( 'toplevel_page_service-center', $menu );
		$service = $menu[$service_key];
		unset( $menu[$service_key] );
		$menu[240] = $service;

		// Publish Press/Future
		$ppf_key = self::get_key( 'toplevel_page_publishpress-future', $menu );
		$ppf = $menu[$ppf_key];
		unset( $menu[$ppf_key] );
		$menu[260] = $ppf;

		/** ---- Proud Admin menu items ----- **/
		// separator
		$sep1_key = self::get_key( 'wp-menu-separator', $menu );
		$sep1 = $menu[$sep1_key];
		unset( $menu[$sep1_key] );
		$menu[270] = $sep1;

		// WP Dashboard
		$wp_dash_key = self::get_key( 'menu-dashboard', $menu );
		$wp_dash = $menu[$wp_dash_key];
		unset( $menu[$wp_dash_key] );
		$menu[280] = $wp_dash;

		// Appearance
		$app_key = self::get_key( 'menu-appearance', $menu );
		$app = $menu[$app_key];
		unset( $menu[$app_key] );
		$menu[290] = $app;

		// Elasticpress
		$ela_key = self::get_key( 'toplevel_page_elasticpress', $menu );
		$ela = $menu[$ela_key];
		unset( $menu[$ela_key] );
		$menu[300] = $ela;

		// WP Users
		$users_key = self::get_key( 'menu-users', $menu );
		$users = $menu[$users_key];
		unset( $menu[$users_key] );
		$menu[310] = $users;

		// Tools
		$tools_key = self::get_key( 'menu-tools', $menu );
		$tools = $menu[$tools_key];
		unset( $menu[$tools_key] );
		$menu[320] = $tools;

		// WP Settings
		$wpsettings_key = self::get_key( 'menu-settings', $menu );
		$wpsettings = $menu[$wpsettings_key];
		unset( $menu[$wpsettings_key] );
		$menu[330] = $wpsettings;

		// WP Mail SMTP
		$smtp_key = self::get_key( 'toplevel_page_wp-mail-smtp', $menu );
		$smtp = $menu[$smtp_key];
		unset( $menu[$smtp_key] );
		$menu[340] = $smtp;

		// Yoast SEO
		$seo_key = self::get_key( 'toplevel_page_wpseo_dashboard', $menu );
		$seo = $menu[$seo_key];
		unset( $menu[$seo_key] );
		$menu[350] = $seo;

		// Plugins
		$plug_key = self::get_key( 'menu-plugins', $menu );
		$plug = $menu[$plug_key];
		unset( $menu[$plug_key] );
		$menu[360] = $plug;

		// Site Origin
		$siteorigin_key = self::get_key( 'toplevel_page_siteorigin', $menu );
		$siteorigin = $menu[$siteorigin_key];
		unset( $menu[$siteorigin_key] );
		$menu[370] = $siteorigin;

// @todo get each item I know about in a variable
// @todo put regular items in the order we want
// @todo add admin items with custom background
// @todo add unknown items above admin items
// 		- can I add a custom colour if the currently signed in user is an admin to highlight menu items we need to deal with
// @todo do_action so that plugins can hook this and add themselves in the proper spot
// @todo conditional check for stuff like MailOptin becausee it is not present on every site
/*
		echo '<pre>';
		print_r( $menu );
		echo '</pre>';
*/
	}

	/**
	 * Gets the parent key for a given menu item.
	 *
	 * Doesn't travers multi-dimensional arrays because we don't need that for
	 * searching through our menus
	 *
	 * @since 2023.08.30
	 * @author Curtis
	 * @access private
	 *
	 * @param 	string 			$searching_for 			required 				Any child item in a menu
	 * @param 	array 			$array 					required 				The menu global
	 * @return 	int 			$parent_key 									The parent key
	 */
	private static function get_key( $searching_for, $array ){

		foreach( $array as $main_key => $main_value ){
			$parent_key = $main_key;

			foreach( $main_value as $key => $value ){

				if ( $searching_for == $value ){
					return $parent_key;
				}

			} // foreach $main_value

		} // foreach $array as $main_key

	} // get_keyi


	/**
	* Registers and enqueues scripts and styles
	*
	* @uses    wp_enqueue_style
	* @uses    wp_enqueue_script
	*
	* @since   2023.08.24
	* @author  SFNdesign, Curtis McHale
	*/
	public function admin_enqueue(){

		$plugin_data = get_plugin_data( __FILE__ );
// @todo if local use time()
		$version = $plugin_data['Version'];

		// styles plugin
		wp_enqueue_style( 'wp_proud_admin_menu_styles', plugins_url( '/wp-proud-admin-menu/dist/styles/proud-admin-menu.css' ), '', esc_attr( $version ), 'all');

	}

	/**
	 * Gives us any constants we need in the plugin
	 *
	 * @since 2023.08.24
	 */
	public function constants(){

		define( 'PROUDCITY_ADMIN_MENU_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

	}

	/**
	 * Includes any externals
	 *
	 * @since 2023.08.24
	 * @author Curtis McHale
	 * @access public
	 */
	public function includes(){

	}

	/**
	 * Fired when plugin is activated
	 *
	 * @param   bool    $network_wide   TRUE if WPMU 'super admin' uses Network Activate option
	 */
	public function activate( $network_wide ){

	} // activate

	/**
	 * Fired when plugin is deactivated
	 *
	 * @param   bool    $network_wide   TRUE if WPMU 'super admin' uses Network Activate option
	 */
	public function deactivate( $network_wide ){

	} // deactivate

	/**
	 * Fired when plugin is uninstalled
	 *
	 * @param   bool    $network_wide   TRUE if WPMU 'super admin' uses Network Activate option
	 */
	public function uninstall( $network_wide ){

	} // uninstall

} // ProudCity_Admin_Menu

ProudCity_Admin_Menu::instance();
