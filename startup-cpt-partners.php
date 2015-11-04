<?php
/*
Plugin Name: StartUp Partners
Description: Le plugin pour activer le Custom Post Partners
Author: Yann Caplain
Version: 0.1.0
*/

//GitHub Plugin Updater
function startup_reloaded_partners_updater() {
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

add_action( 'init', 'startup_reloaded_partners_updater' );

//CPT
function startup_reloaded_partners() {
	$labels = array(
		'name'                => _x( 'Partners', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Partner', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Partners', 'text_domain' ),
		'name_admin_bar'      => __( 'Partners', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Items', 'text_domain' ),
		'add_new_item'        => __( 'Add New Item', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'new_item'            => __( 'New Item', 'text_domain' ),
		'edit_item'           => __( 'Edit Item', 'text_domain' ),
		'update_item'         => __( 'Update Item', 'text_domain' ),
		'view_item'           => __( 'View Item', 'text_domain' ),
		'search_items'        => __( 'Search Item', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' )
	);
	$args = array(
		'label'               => __( 'partners', 'text_domain' ),
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

add_action( 'init', 'startup_reloaded_partners', 0 );

//Flusher les permalink à l'activation du plgin pour qu'ils fonctionnent sans mise à jour manuelle
function startup_reloaded_partners_rewrite_flush() {
    startup_reloaded_partners();
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'startup_reloaded_partners_rewrite_flush' );

// Capabilities
function startup_reloaded_partners_caps() {	
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

register_activation_hook( __FILE__, 'startup_reloaded_partners_caps' );

// Metaboxes
function startup_reloaded_partners_meta() {
    
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_startup_reloaded_partners_';

	$cmb_box = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Partner details', 'cmb2' ),
		'object_types'  => array( 'partners' )
	) );
    
//    $cmb_box->add_field( array(
//        'name' => __( 'Show title', 'cmb2' ),
//		'id'               => $prefix . 'title',
//		'type'             => 'checkbox',
//        'default'          => 0
//	) );
    
    $cmb_box->add_field( array(
        'name' => __( 'Partner\'s logo', 'cmb2' ),
		'id'   => $prefix . 'logo',
		'type' => 'file',
        // Optionally hide the text input for the url:
        'options' => array(
            'url' => false,
        ),
	) );
    
    $cmb_box->add_field( array(
		'name' => __( 'External url', 'cmb2' ),
		'desc' => __( 'Link to te partner\'s website', 'startup-reloaded-products' ),
		'id'   => $prefix . 'url',
		'type' => 'text_url'
	) );

}

add_action( 'cmb2_admin_init', 'startup_reloaded_partners_meta' );

// Shortcode
add_shortcode( 'partners', function( $atts, $content= null ){
    ob_start();
    require get_template_directory() . '/template-parts/content-partners.php';
    return ob_get_clean();
});
?>