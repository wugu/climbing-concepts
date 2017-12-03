<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    ClimbingConcepts
 * @subpackage ClimbingConcepts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ClimbingConcepts
 * @subpackage ClimbingConcepts/admin
 * @author     Your Name <email@example.com>
 */
class ClimbingConcepts_Admin {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Actions that have a view and admin menu or nav tab menu entry.
     *
     * @since 1.0.0
     * @var array
     */
    protected $view_actions = array();

    /**
     * Page hooks (i.e. names) WordPress uses for the TablePress admin screens,
     * populated in add_admin_menu_entry().
     *
     * @since 1.0.0
     * @var array
     */
    protected $page_hooks = array();

    /**
     * Instance of the Admin Page View
     *
     * @var ClimbingCocepts_Admin
     */
    protected $page_view;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Add admin entry to the correct place in the admin menu.
     *
     * @since 1.0.0
     */
    public function add_admin_menu_entry() {
        $callback = array($this, 'show_admin_page');

        $admin_menu_entry_name = apply_filters('climbing_concepts_admin_menu_entry_name',
                                               __('Climbing Concepts', 'climbing-concepts'));

        $this->init_view_actions();
        $min_access_cap = $this->view_actions['overview']['required_cap'];

        $icon_url = 'dashicons-location-alt';
        $position = ($GLOBALS['_wp_last_object_menu'] + 1);

        add_menu_page(__('Climbing Concepts', 'climbing-concepts'),
                      $admin_menu_entry_name,
                      $min_access_cap,
                      'climbing-concepts',
                      $callback,
                      $icon_url,
                      $position);

        foreach ($this->view_actions as $action => $entry) {
            $slug = 'climbing-concepts';
            if ('overview' !== $action) {
                $slug .= '_' . $action;
            }
            $this->page_hooks[] = add_submenu_page('climbing-concepts',
                                                   sprintf('%1$s &lsaquo; %2$s',
                                                           $entry['page_title'],
                                                           __('Climbing Concepts', 'climbing-concepts')),
                                                   $entry['admin_menu_title'],
                                                   $entry['required_cap'],
                                                   $slug,
                                                   $callback);
        }
    }

    public function add_admin_actions() {
        foreach ( $this->page_hooks as $page_hook ) {
            add_action("load-{$page_hook}", array($this, 'load_admin_page'));
        }
    }

    public function load_admin_page() {
        /*
         * Determine the action from either the GET parameter (for sub-menu
         * entries, and the main admin menu entry).
         *
         * Default: overview
         */
        $action = (!empty($_GET['action'])) ? $_GET['action'] : 'overview';

        $this->init_view_actions();

        if (!isset($this->view_actions[$action]) ||
            !current_user_can($this->view_actions[$action]['required_cap'])) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'default'), 403);
        }

        /*
         * Changes current screen ID and pagenow variable in JS, to enable
         * automatic meta box JS handling.
         */
        set_current_screen("climbing_concepts_{$action}");

        /*
         * Set the $typenow global to the current CPT ourselves, as
         * WP_Screen::get() does not determine the CPT correctly.
         */
        if (isset($_GET['post_type']) && post_type_exists($_GET['post_type'])) {
            $GLOBALS['typenow'] = $_GET['post_type'];
        }

        $data = array(
            'view_actions' => $this->view_actions,
            'message' => (!empty($_GET['message'])) ? $_GET['message'] : false,
        );

        /*
         * Depending on the action, load more necessary data for the
         * corresponding view.
         */
        switch ($action) {
        case 'overview':
            break;
        case 'restrictions':
            break;
        case 'rocks':
            break;
        case 'regions':
            break;
        case 'concepts':
            break;
        }

        $data = apply_filters('climbing_concepts_view_data', $data, $action);

        $this->page_view = $this->load_pageview($action, $data);
    }

    public function show_admin_page() {
        echo '<h1>' . esc_html__('Overview', 'climbing-concepts' ) . '</h1>';

        echo '<p>Hello World!</p>';
        /* $this->page_view->render(); */
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in ClimbingConcepts_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The ClimbingConcepts_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name,
                         plugin_dir_url( __FILE__ ) . 'css/climbing-concepts-admin.css', array(),
                         $this->version,
                         'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in ClimbingConcepts_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The ClimbingConcepts_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script($this->plugin_name,
                          plugin_dir_url( __FILE__ ) . 'js/climbing-concepts-admin.js',
                          array( 'jquery' ),
                          $this->version,
                          false);

    }

    /**
     * Init list of actions that have a view with their titles/names/caps.
     *
     * @since 1.0.0
     */
    protected function init_view_actions() {
        $this->view_actions = array(
            'overview' => array(
                'page_title'       => __('Overview', 'climbing-concepts'),
                'admin_menu_title' => __('Overview', 'climbing-concepts'),
                'nav_tab_title'    => __('Overview', 'climbing-concepts'),
                'required_cap'     => 'edit_pages',
            ),
            'restrictions' => array(
                'page_title'       => __('Restrictions', 'climbing-concepts'),
                'admin_menu_title' => __('Restrictions', 'climbing-concepts'),
                'nav_tab_title'    => __('Restrictions', 'climbing-concepts'),
                'required_cap'     => 'edit_pages',
            ),
            'rocks' => array(
                'page_title'       => __('Rocks', 'climbing-concepts'),
                'admin_menu_title' => __('Rocks', 'climbing-concepts'),
                'nav_tab_title'    => __('Rocks', 'climbing-concepts'),
                'required_cap'     => 'edit_pages',
            ),
            'regions' => array(
                'page_title'       => __('Regions', 'climbing-concepts'),
                'admin_menu_title' => __('Regions', 'climbing-concepts'),
                'nav_tab_title'    => __('Regions', 'climbing-concepts'),
                'required_cap'     => 'edit_pages',
            ),
            'concepts' => array(
                'page_title'       => __('Concepts', 'climbing-concepts'),
                'admin_menu_title' => __('Concepts', 'climbing-concepts'),
                'nav_tab_title'    => __('Concepts', 'climbing-concepts'),
                'required_cap'     => 'edit_pages',
            ),
        );

        /**
         * Filter the available Views/Actions and their parameters.
         */
        $this->view_actions = apply_filters('climbing_concepts_admin_view_actions',
                                            $this->view_actions);
    }

    private function load_pageview($pageview, $data = array()) {
        echo '<p>Hello World!</p>';
        //require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-climbing-concepts-admin-' . $pageview . '.php');
        return array();
    }
}
