<?php
/**
 * Register Custom Post Types.
 *
 * @package SmoothMaintenance\Core
 */

namespace SmoothMaintenance\Core;

/**
 * PostTypes class.
 */
class PostTypes {

	/**
	 * Post type slug.
	 */
	public const TEMPLATE_CPT = 'sm_template';

	/**
	 * Register the post type.
	 *
	 * @return void
	 */
	public function register(): void {
		$labels = array(
			'name'                  => _x( 'Maintenance Templates', 'Post Type General Name', 'smooth-maintenance' ),
			'singular_name'         => _x( 'Template', 'Post Type Singular Name', 'smooth-maintenance' ),
			'menu_name'             => __( 'Templates', 'smooth-maintenance' ),
			'name_admin_bar'        => __( 'Template', 'smooth-maintenance' ),
			'archives'              => __( 'Template Archives', 'smooth-maintenance' ),
			'attributes'            => __( 'Template Attributes', 'smooth-maintenance' ),
			'parent_item_colon'     => __( 'Parent Template:', 'smooth-maintenance' ),
			'all_items'             => __( 'Templates', 'smooth-maintenance' ),
			'add_new_item'          => __( 'Add New Template', 'smooth-maintenance' ),
			'add_new'               => __( 'Add New', 'smooth-maintenance' ),
			'new_item'              => __( 'New Template', 'smooth-maintenance' ),
			'edit_item'             => __( 'Edit Template', 'smooth-maintenance' ),
			'update_item'           => __( 'Update Template', 'smooth-maintenance' ),
			'view_item'             => __( 'View Template', 'smooth-maintenance' ),
			'view_items'            => __( 'View Templates', 'smooth-maintenance' ),
			'search_items'          => __( 'Search Template', 'smooth-maintenance' ),
			'not_found'             => __( 'Not found', 'smooth-maintenance' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'smooth-maintenance' ),
			'featured_image'        => __( 'Featured Image', 'smooth-maintenance' ),
			'set_featured_image'    => __( 'Set featured image', 'smooth-maintenance' ),
			'remove_featured_image' => __( 'Remove featured image', 'smooth-maintenance' ),
			'use_featured_image'    => __( 'Use as featured image', 'smooth-maintenance' ),
			'insert_into_item'      => __( 'Insert into template', 'smooth-maintenance' ),
			'uploaded_to_this_item' => __( 'Uploaded to this template', 'smooth-maintenance' ),
			'items_list'            => __( 'Templates list', 'smooth-maintenance' ),
			'items_list_navigation' => __( 'Templates list navigation', 'smooth-maintenance' ),
			'filter_items_list'     => __( 'Filter templates list', 'smooth-maintenance' ),
		);

		$args = array(
			'label'               => __( 'Template', 'smooth-maintenance' ),
			'description'         => __( 'Maintenance mode templates designed with Gutenberg', 'smooth-maintenance' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'custom-fields', 'revisions' ),
			'hierarchical'        => false,
			'public'              => false, // Don't allow direct frontend access to the CPT single view.
			'show_ui'             => true,
			'show_in_menu'        => 'smooth-maintenance', // Place under the main plugin menu.
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'post',
			'show_in_rest'        => true, // CRITICAL: Enables Gutenberg block editor.
		);

		register_post_type( self::TEMPLATE_CPT, $args );
	}
}
