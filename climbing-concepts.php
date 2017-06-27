<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           ClimbingConcepts
 *
 * @wordpress-plugin
 * Plugin Name:       Climbing Concepts
 * Plugin URI:        https://github.com/cryptomilk/climbing-concepts
 * Description:       This is a plugin to manage temporary climbing restrictions for rocks/walls.
 * Version:           0.0.1
 * Author:            Andreas Schneider
 * Author URI:        https://cryptomilk.org/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       climbing-concepts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-climbing-concepts-activator.php
 */
function activate_climbing_concepts() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-climbing-concepts-activator.php';
    ClimbingConcepts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-climbing-concepts-deactivator.php
 */
function deactivate_climbing_concepts() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-climbing-concepts-deactivator.php';
    ClimbingConcepts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_climbing_concepts' );
register_deactivation_hook( __FILE__, 'deactivate_climbing_concepts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-climbing-concepts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_climbing_concepts() {

    $plugin = new ClimbingConcepts();
    $plugin->run();

}
run_climbing_concepts();
