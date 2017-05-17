<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Add admin page for importing Review(s)
if( !class_exists( 'BUPR_Admin' ) ) {
	class BUPR_Admin{

		/**
		* Constructor.
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'bupr_add_submenu_page_admin_settings' ) );
			/* Register custom post type review */
			$bupr_post_types = get_post_types();
			if( !in_array( 'review', $bupr_post_types ) ) {
				add_action( 'init', array( $this, 'bupr_review_cpt' ) );
				add_action( 'init', array( $this, 'bupr_review_taxonomy_cpt' ) );
			}
		}

		/**
		* Actions performed on loading admin_menu
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_add_submenu_page_admin_settings() {
			add_submenu_page( 'edit.php?post_type=review', __( 'Reviews Admin Settings', BUPR_TEXT_DOMAIN ), __( 'BP Member Review Settings', BUPR_TEXT_DOMAIN ), 'manage_options', 'bp-member-review-settings', array( $this, 'bupr_admin_options_page' ) );
		}

		/**
		* Include admin option page
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		function bupr_admin_options_page() {
			include 'review-admin-options-page.php';
		}


		/**
		* Actions performed to create Review cpt
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_review_cpt() {
			$labels = array(
				'name'					=>	__( 'Reviews', BUPR_TEXT_DOMAIN ),
				'singular_name'			=>	__( 'Review', BUPR_TEXT_DOMAIN ),
				'menu_name'				=>	__( 'Reviews', BUPR_TEXT_DOMAIN ),
				'name_admin_bar'		=>	__( 'Reviews', BUPR_TEXT_DOMAIN ),
				'add_new'				=>	__( 'Add New Review', BUPR_TEXT_DOMAIN ),
				'add_new_item'			=>	__( 'Add New Review', BUPR_TEXT_DOMAIN ),
				'new_item'				=>	__( 'New Review', BUPR_TEXT_DOMAIN ),
				'view_item'				=>	__( 'View Reviews', BUPR_TEXT_DOMAIN ),
				'all_items'				=>	__( 'All Reviews', BUPR_TEXT_DOMAIN ),
				'search_items'			=>	__( 'Search Reviews', BUPR_TEXT_DOMAIN ),
				'parent_item_colon'		=>	__( 'Parent Review:', BUPR_TEXT_DOMAIN ),
				'not_found'				=>	__( 'No Review Found', BUPR_TEXT_DOMAIN ),
				'not_found_in_trash'	=>	__( 'No Review Found In Trash', BUPR_TEXT_DOMAIN ),
			);
			$args = array(
				'labels'				=>	$labels,
				'public'				=>	true,
				'menu_icon'				=>	BUPR_PLUGIN_URL.'admin/assets/images/review.png',
				'publicly_queryable'	=>	true,
				'show_ui'				=>	true,
				'show_in_menu'			=>	true,
				'query_var'				=>	true,
				'rewrite'				=>	array( 'slug' => 'review', 'with_front' => false ),
				'capability_type'		=>	'post',
				'capabilities'			=>	array(
					'create_posts'		=>	'do_not_allow'
				),
				'map_meta_cap'			=>	true,
				'has_archive'			=>	true,
				'hierarchical'			=>	false,
				'menu_position'			=>	null,
				'supports'				=>	array( 'title', 'editor', 'author', 'thumbnail' ),
			);
			register_post_type( 'review', $args );
		}

		/**
		* Actions performed to create Review cpt taxonomy
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_review_taxonomy_cpt() {
			$category_labels = array(
				'name'              => _x( 'Reviews Category', 'taxonomy general name' ),
				'singular_name'     => _x( 'Review Category', 'taxonomy singular name' ),
				'search_items'      => __( 'Search Categories' ),
				'all_items'         => __( 'All Categories' ),
				'parent_item'       => __( 'Parent Category' ),
				'parent_item_colon' => __( 'Parent Category:' ),
				'edit_item'         => __( 'Edit Category' ),
				'update_item'       => __( 'Update Category' ),
				'add_new_item'      => __( 'Add Category' ),
				'new_item_name'     => __( 'New Category Name' ),
				'menu_name'         => __( 'Category' ),
			);
			$category_args = array(
				'hierarchical'      => true,
				'labels'            => $category_labels,
				'show_ui'           => false,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'review_category' ),
			);
			register_taxonomy( 'review_category', array( 'review' ), $category_args );
		}
	}
	new BUPR_Admin();
}