<?php
/*
Plugin Name: StartUp CPT Partners
Description: Le plugin pour activer le Custom Post Partners
Author: Yann Caplain
Version: 0.1.0
Text Domain: startup-cpt-partners
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//GitHub Plugin Updater
function startup_cpt_partners_updater() {
	include_once 'lib/updater.php';
	//define( 'WP_GITHUB_FORCE_UPDATE', true );
	if ( is_admin() ) {
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'startup-cpt-partners',
			'api_url' => 'https://api.github.com/repos/yozzi/startup-cpt-partners',
			'raw_url' => 'https://raw.github.com/yozzi/startup-cpt-partners/master',
			'github_url' => 'https://github.com/yozzi/startup-cpt-partners',
			'zip_url' => 'https://github.com/yozzi/startup-cpt-partners/archive/master.zip',
			'sslverify' => true,
			'requires' => '3.0',
			'tested' => '3.3',
			'readme' => 'README.md',
			'access_token' => '',
		);
		new WP_GitHub_Updater( $config );
	}
}

//add_action( 'init', 'startup_cpt_partners_updater' );

//CPT
function startup_cpt_partners() {
	$labels = array(
		'name'                => _x( 'Partners', 'Post Type General Name', 'startup-cpt-partners' ),
		'singular_name'       => _x( 'Partner', 'Post Type Singular Name', 'startup-cpt-partners' ),
		'menu_name'           => __( 'Partners', 'startup-cpt-partners' ),
		'name_admin_bar'      => __( 'Partners', 'startup-cpt-partners' ),
		'parent_item_colon'   => __( 'Parent Item:', 'startup-cpt-partners' ),
		'all_items'           => __( 'All Items', 'startup-cpt-partners' ),
		'add_new_item'        => __( 'Add New Item', 'startup-cpt-partners' ),
		'add_new'             => __( 'Add New', 'startup-cpt-partners' ),
		'new_item'            => __( 'New Item', 'startup-cpt-partners' ),
		'edit_item'           => __( 'Edit Item', 'startup-cpt-partners' ),
		'update_item'         => __( 'Update Item', 'startup-cpt-partners' ),
		'view_item'           => __( 'View Item', 'startup-cpt-partners' ),
		'search_items'        => __( 'Search Item', 'startup-cpt-partners' ),
		'not_found'           => __( 'Not found', 'startup-cpt-partners' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'startup-cpt-partners' )
	);
	$args = array(
		'label'               => __( 'partners', 'startup-cpt-partners' ),
        'description'         => __( '', 'startup-cpt-partners' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'revisions' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-businessman',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
        'capability_type'     => array('partner','partners'),
        'map_meta_cap'        => true
	);
	register_post_type( 'partners', $args );
}

add_action( 'init', 'startup_cpt_partners', 0 );

//Flusher les permalink à l'activation du plgin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_cpt_partners_rewrite_flush() {
    startup_cpt_partners();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_cpt_partners_rewrite_flush' );

// Capabilities
function startup_cpt_partners_caps() {	
	$role_admin = get_role( 'administrator' );
	$role_admin->add_cap( 'edit_partner' );
	$role_admin->add_cap( 'read_partner' );
	$role_admin->add_cap( 'delete_partner' );
	$role_admin->add_cap( 'edit_others_partners' );
	$role_admin->add_cap( 'publish_partners' );
	$role_admin->add_cap( 'edit_partners' );
	$role_admin->add_cap( 'read_private_partners' );
	$role_admin->add_cap( 'delete_partners' );
	$role_admin->add_cap( 'delete_private_partners' );
	$role_admin->add_cap( 'delete_published_partners' );
	$role_admin->add_cap( 'delete_others_partners' );
	$role_admin->add_cap( 'edit_private_partners' );
	$role_admin->add_cap( 'edit_published_partners' );
}

register_activation_hook( __FILE__, 'startup_cpt_partners_caps' );

// Metaboxes
/**
 * Detection de CMB2. Identique dans tous les plugins.
 */
if ( !function_exists( 'cmb2_detection' ) ) {
    function cmb2_detection() {
        if ( !is_plugin_active('CMB2/init.php')  && !function_exists( 'startup_reloaded_setup' ) ) {
            add_action( 'admin_notices', 'cmb2_notice' );
        }
    }

    function cmb2_notice() {
        if ( current_user_can( 'activate_plugins' ) ) {
            echo '<div class="error message"><p>' . __( 'CMB2 plugin or StartUp Reloaded theme must be active to use custom metaboxes.', 'startup-cpt-partners' ) . '</p></div>';
        }
    }

    add_action( 'init', 'cmb2_detection' );
}

function startup_cpt_partners_meta() {
    
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_cpt_partners_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Partner details', 'startup-cpt-partners' ),
		'object_types'  => array( 'partners' )
	) );
    
//    $cmb_box->add_field( array(
//        'name' => __( 'Show title', 'startup-cpt-partners' ),
//		'id'               => $prefix . 'title',
//		'type'             => 'checkbox',
//        'default'          => 0
//	) );
    
    $cmb_box->add_field( array(
        'name' => __( 'Partner\'s logo', 'startup-cpt-partners' ),
		'id'   => $prefix . 'logo',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'External url', 'startup-cpt-partners' ),
		'desc' => __( 'Link to te partner\'s website', 'startup-reloaded-products' ),
		'id'   => $prefix . 'url',
		'type' => 'text_url'
	) );

}

add_action( 'cmb2_admin_init', 'startup_cpt_partners_meta' );

// Shortcode
function startup_cpt_partners_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => '#6f6f6f'
        ), $atts);
    
	// Code
        ob_start();
        require get_template_directory() . '/template-parts/content-partners.php';
        return ob_get_clean();    
}
add_shortcode( 'partners', 'startup_cpt_partners_shortcode' );

// Shortcode UI
/**
 * Detecion de Shortcake. Identique dans tous les plugins.
 */
if ( !function_exists( 'shortcode_ui_detection' ) ) {
    function shortcode_ui_detection() {
        if ( !function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
            add_action( 'admin_notices', 'shortcode_ui_notice' );
        }
    }

    function shortcode_ui_notice() {
        if ( current_user_can( 'activate_plugins' ) ) {
            echo '<div class="error message"><p>' . __( 'Shortcake plugin must be active to use fast shortcodes.', 'startup-cpt-partners' ) . '</p></div>';
        }
    }

    add_action( 'init', 'shortcode_ui_detection' );
}

function startup_cpt_partners_shortcode_ui() {

    shortcode_ui_register_for_shortcode(
        'partners',
        array(
            'label' => esc_html__( 'Partners', 'startup-cpt-partners' ),
            'listItemImage' => 'dashicons-businessman',
            'attrs' => array(
                array(
                    'label' => esc_html__( 'Background', 'startup-cpt-partners' ),
                    'attr'  => 'bg',
                    'type'  => 'color',
                ),
            ),
        )
    );
};

if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
    add_action( 'init', 'startup_cpt_partners_shortcode_ui');
}


// Enqueue scripts and styles.
function startup_cpt_partners_scripts() {
    wp_enqueue_style( 'startup-cpt-partners-style', plugins_url( '/css/startup-cpt-partners.css', __FILE__ ), array( ), false, 'all' );
}

add_action( 'wp_enqueue_scripts', 'startup_cpt_partners_scripts' );
?>