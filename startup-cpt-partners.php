<?php
/*
Plugin Name: StartUp CPT Partners
Description: Le plugin pour activer le Custom Post Partners
Author: Yann Caplain
Version: 1.0.0
Text Domain: startup-cpt-partners
Domain Path: /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Include this to check if a plugin is activated with is_plugin_active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

//Include this to check dependencies
include_once( 'inc/dependencies.php' );

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

// Category taxonomy
function startup_cpt_partners_categories() {
	$labels = array(
		'name'                       => _x( 'Partner Categories', 'Taxonomy General Name', 'startup-cpt-team' ),
		'singular_name'              => _x( 'Partner Category', 'Taxonomy Singular Name', 'startup-cpt-team' ),
		'menu_name'                  => __( 'Partner Categories', 'startup-cpt-team' ),
		'all_items'                  => __( 'All Items', 'startup-cpt-team' ),
		'parent_item'                => __( 'Parent Item', 'startup-cpt-team' ),
		'parent_item_colon'          => __( 'Parent Item:', 'startup-cpt-team' ),
		'new_item_name'              => __( 'New Item Name', 'startup-cpt-team' ),
		'add_new_item'               => __( 'Add New Item', 'startup-cpt-team' ),
		'edit_item'                  => __( 'Edit Item', 'startup-cpt-team' ),
		'update_item'                => __( 'Update Item', 'startup-cpt-team' ),
		'view_item'                  => __( 'View Item', 'startup-cpt-team' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'startup-cpt-team' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'startup-cpt-team' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'startup-cpt-team' ),
		'popular_items'              => __( 'Popular Items', 'startup-cpt-team' ),
		'search_items'               => __( 'Search Items', 'startup-cpt-team' ),
		'not_found'                  => __( 'Not Found', 'startup-cpt-team' )
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false
	);
	register_taxonomy( 'partners-category', array( 'partners' ), $args );

}

add_action( 'init', 'startup_cpt_partners_categories', 0 );

// Retirer la boite de la taxonomie sur le coté
function startup_cpt_partners_categories_metabox_remove() {
	remove_meta_box( 'tagsdiv-partners-category', 'partners', 'side' );
    // tagsdiv-product_types pour les taxonomies type tags
    // custom_taxonomy_slugdiv pour les taxonomies type categories
}

add_action( 'admin_menu' , 'startup_cpt_partners_categories_metabox_remove' );

// Metaboxes
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
    
     $cmb_box->add_field( array(
		'name'     => __( 'Category', 'startup-cpt-partners' ),
		'desc'     => __( 'Select the category(ies) of the partner', 'startup-cpt-partners' ),
		'id'       => $prefix . 'category',
		'type'     => 'taxonomy_multicheck',
		'taxonomy' => 'partners-category', // Taxonomy Slug
		'inline'  => true // Toggles display to inline
	) );

}

add_action( 'cmb2_admin_init', 'startup_cpt_partners_meta' );

// Shortcode
function startup_cpt_partners_shortcode( $atts ) {

	// Attributes
    $atts = shortcode_atts(array(
            'bg' => '#6f6f6f',
            'carousel' => '',
            'order' => '',
            'cat' => '',
            'id' => '',
        ), $atts);
    
	// Code
    ob_start();
    if ( function_exists( 'startup_reloaded_setup' ) || function_exists( 'startup_revolution_setup' ) ) {
        require get_template_directory() . '/template-parts/content-partners.php';
     } else {
        echo 'You should install <a href="https://github.com/yozzi/startup-reloaded" target="_blank">StartUp Reloaded</a> or <a href="https://github.com/yozzi/startup-revolution" target="_blank">StartUp Revolution</a> theme to make things happen...';
     }
     return ob_get_clean();    
}
add_shortcode( 'partners', 'startup_cpt_partners_shortcode' );

// Shortcode UI
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
                array(
                    'label' => esc_html__( 'Carousel', 'startup-cpt-partners' ),
                    'attr'  => 'carousel',
                    'type'  => 'checkbox',
                ),
                array(
                    'label' => esc_html__( 'Order', 'startup-cpt-partners' ),
                    'attr'  => 'order',
                    'type' => 'select',
                    'options' => array(
                        'menu_order' => esc_html__( 'Menu Order', 'startup-cpt-partners' ),
                        'rand' => esc_html__( 'Random', 'startup-cpt-partners' )
                    ),
                ),
                array(
                    'label' => esc_html__( 'Category', 'startup-cpt-partners' ),
                    'attr'  => 'cat',
                    'type'  => 'text',
                ),
                array(
                    'label' => esc_html__( 'ID', 'startup-cpt-partners' ),
                    'attr'  => 'id',
					'type' => 'post_select',
					'query' => array( 'post_type' => 'partners' ),
					'multiple' => false,
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

add_action( 'wp_enqueue_scripts', 'startup_cpt_partners_scripts', 15 );

// Add code to footer
function startup_cpt_partners_footer() { ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {

            jQuery('.carousel[data-type="multi"] .item').each(function(){
                var next = jQuery(this).next();
                if (!next.length) {
                    next = jQuery(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo(jQuery(this));

                for (var i=0;i<2;i++) {
                    next=next.next();
                    if (!next.length) {
                        next = jQuery(this).siblings(':first');
                    }

                    next.children(':first-child').clone().appendTo(jQuery(this));
                }
            });

        });
    </script>
<?php }

add_action( 'wp_footer', 'startup_cpt_partners_footer' );
?>