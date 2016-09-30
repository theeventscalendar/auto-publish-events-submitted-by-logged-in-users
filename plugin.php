<?php
/**
 * Plugin Name: The Events Calendar Extension: Auto-Publish Community Events Submitted by Logged-In Users
 * Description: The snippet below will auto-publish any event submitted by a registered, logged in user. Any other submitted events (i.e., those submitted by anonymous users) will go to whatever your default status is set to.
 * Version: 1.0.0
 * Author: Modern Tribe, Inc.
 * Author URI: http://m.tri.be/1971
 * License: GPLv2 or later
 */

defined( 'WPINC' ) or die;

class Tribe__Extension__Auto_Publish_Community_Events_Submitted_by_Logged_In_Users {

    /**
     * The semantic version number of this extension; should always match the plugin header.
     */
    const VERSION = '1.0.0';

    /**
     * Each plugin required by this extension
     *
     * @var array Plugins are listed in 'main class' => 'minimum version #' format
     */
    public $plugins_required = array(
        'Tribe__Events__Main'            => '4.2',
        'Tribe__Events__Community__Main' => '4.2',
    );

    /**
     * The constructor; delays initializing the extension until all other plugins are loaded.
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ), 100 );
    }

    /**
     * Extension hooks and initialization; exits if the extension is not authorized by Tribe Common to run.
     */
    public function init() {

        // Exit early if our framework is saying this extension should not run.
        if ( ! function_exists( 'tribe_register_plugin' ) || ! tribe_register_plugin( __FILE__, __CLASS__, self::VERSION, $this->plugins_required ) ) {
            return;
        }

        add_filter( 'tribe_events_community_sanitize_submission', array( $this, 'set_community_events_publication_status' ) );
    }

    /**
     * Set the post status for logged-in users to "Publish".
     *
     * @param array $submission
     * @return array
     */
    public function set_community_events_publication_status( $submission ) {
       
        // Escape, assuming default is set to 'draft' and 'allow anonymous submits'
        if ( ! is_user_logged_in() ) {
            return $submission;
        }

        $submission['post_status'] = 'publish';

        return $submission;
    }
}

new Tribe__Extension__Auto_Publish_Community_Events_Submitted_by_Logged_In_Users();
