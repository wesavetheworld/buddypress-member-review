<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
* Class to add custom hooks for this plugin
*
* @since    1.0.0
* @author   Wbcom Designs
*/
if( !class_exists( 'BUPR_Custom_Hooks' ) ) {
	class BUPR_Custom_Hooks{

		/**
		* Constructor.
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function __construct() {
			add_action( 'bp_setup_nav', array($this, 'bupr_member_profile_reviews_tab' ) );

			add_action( 'init', array( $this, 'bupr_add_bp_member_reviews_taxonomy_term' ) );
			add_filter( 'post_row_actions', array( $this, 'bupr_bp_member_reviews_row_actions' ), 10, 2 );
			add_action( 'admin_footer-edit.php', array( $this, 'bupr_disable_review_title_edit_link' ) );
			add_filter( 'bulk_actions-edit-review', array( $this, 'bupr_remove_edit_bulk_actions' ), 10, 1 );
			add_action( 'bp_before_member_header_meta', array( $this, 'bupr_member_average_rating') );

			add_action( 'bp_member_header_actions', array( $this, 'bupr_add_review_button_on_member_header' ) );
		}

		/**
		 * Actions performed to add a review button on member header
		 */
		function bupr_add_review_button_on_member_header() {
			$review_url = bp_core_get_userlink( bp_displayed_user_id(), false, true ).'reviews';
			?>
			<div id="bupr-add-review-btn" class="generic-button">
				<a href="<?php echo $review_url;?>" class="add-review"><?php _e( 'Add Review', BUPR_TEXT_DOMAIN );?></a>
			</div>
			<?php
		}

		/**
		* Actions performed to show average rating on a bp member's profile
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_member_average_rating() { ?>
			<br><span><?php _e('Rating:' , BUPR_TEXT_DOMAIN ); ?> </span>
			<?php
			/* Gather all the members reviews */
			$bupr_args = array(
				'post_type' 		=> 'review',
				'posts_per_page' 	=> -1,
				'post_status' 		=> 'publish',
				'category' 			=> 'bp-member',
				'meta_query' 		=> array(
						array(
							'key'		=>	'linked_bp_member',
							'value'		=>	bp_displayed_user_id(),
							'compare'	=>	'=',
						),
				),
			);

			$bupr_reviews 				= get_posts( $bupr_args );
            $bupr_admin_settings       	= get_option( 'bupr_admin_settings' );
            $bupr_review_rating_fields 	= $bupr_admin_settings['profile_rating_fields'];

			if( !empty( $bupr_reviews ) ){
				$bupr_total_rating = 0;
				$bupr_avg_rating   = 0;
				$bupr_type = 'integer';
				$reviews_count = count( $bupr_reviews );
				$bupr_total_review_count	= 0;
				foreach( $bupr_reviews as $review ){
					$bupr_rate 				= 0;
					$reviews_field_count 	= 0;
					$review_ratings      	= get_post_meta( $review->ID, 'profile_star_rating', false );  
					                                         
					if(!empty($bupr_review_rating_fields) && !empty($review_ratings[0])):  
						foreach($review_ratings[0] as $field => $value){
							if(in_array($field,$bupr_review_rating_fields)){
								$bupr_rate += $value;
								$reviews_field_count++;
							}
						}
						if($reviews_field_count != 0){
							$bupr_total_rating += (int)$bupr_rate / $reviews_field_count;
							$bupr_total_review_count++;
						}
						
					endif;                                 
				}
				/* get average rating of members review */
				if($bupr_total_review_count != 0){
					$bupr_avg_rating = $bupr_total_rating / $bupr_total_review_count;
					$bupr_type 		 = gettype( $bupr_avg_rating );
				}
				

				$bupr_stars_on 	 = $stars_off = $stars_half = '';

				if( $bupr_type == 'integer' ){
					$bupr_stars_on 	= $bupr_avg_rating;
					$stars_off 		= 5 - $bupr_avg_rating;
					$stars_half 	= 0;
				}

				if( $bupr_type == 'double' ){
					$bupr_stars_on 	= intval( $bupr_avg_rating );
					$stars_half 	= 1;
					$stars_off 		= 5 - ( $bupr_stars_on + $stars_half );
				}

				for( $i = 1; $i <= $bupr_stars_on; $i++ ){ ?>
					<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
				}

				for( $i = 1; $i <= $stars_half; $i++ ){ ?>
					<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_half.png";?>" alt="star"><?php
				}

				for( $i = 1; $i <= $stars_off; $i++ ){ ?>
					<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
				}

				$bupr_avg_rating = round( $bupr_avg_rating, 2 );
				_e("<span>($bupr_avg_rating)</span>", BUPR_TEXT_DOMAIN);
				_e("<p>Total Reviews: $reviews_count </p>" , BUPR_TEXT_DOMAIN);
			} else {
				_e('No Rating Available!' , BUPR_TEXT_DOMAIN);
			}
		}

		/**
		* Actions performed to remove edit from bulk options
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_remove_edit_bulk_actions( $actions ) {
			unset( $actions['edit'] );
			return $actions;
		}

		/**
		* Actions performed to disallow admin to edit review from review title
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_disable_review_title_edit_link() {
			$bupr_post_type = sanitize_text_field( $_GET['post_type'] );
			if( $bupr_post_type === 'review' ) { ?>
				<script type="text/javascript">
					jQuery('table.wp-list-table a.row-title').contents().unwrap();
				</script><?php
			}
		}

		/**
		* Actions performed to hide row actions
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_bp_member_reviews_row_actions( $actions, $post ) {
			global $bp;
			if( $post->post_type == 'review' ) {
				unset( $actions['edit'] );
				unset( $actions['view'] );
				unset( $actions['inline hide-if-no-js'] );

				if( wp_get_object_terms( $post->ID, 'review_category' )[0]->name == 'BP Member' ) {
					//Add a link to view the review
					$review_title = $post->post_title;
					$linked_bp_member = get_post_meta( $post->ID, 'linked_bp_member', true );
					$bp_member = get_userdata( $linked_bp_member );
					$bp_member_username = $bp_member->data->user_login;
					$review_url = home_url().'/members/'.$bp_member_username.'/reviews/view/'.$post->ID;
					
					$actions['view_review'] = '<a href="'.$review_url.'" title="'.$review_title.'">View Review</a>';
				}
			}
			return $actions;
		}

		/**
		* Action performed to add taxonomy term for group reviews
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_add_bp_member_reviews_taxonomy_term(){
			$termExists = term_exists( 'BP Member', 'review_category' );
			if( $termExists === 0 || $termExists === null ) {
				wp_insert_term( 'BP Member', 'review_category' );
			}
		}

		/**
		* Action performed to add a tab for member profile reviews
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_member_profile_reviews_tab() {
			global $bp;
			/* count member's review */
			$bupr_args = array(
				'post_type' => 'review',
				'posts_per_page' => -1,
				'post_status' => 'publish', 
				'category' => 'bp-member',
				'meta_query' => array(
						array(
						'key'		=>	'linked_bp_member',
						'value'		=>	bp_displayed_user_id(),
						'compare'	=>	'=',
						),
				),
			);

			$bupr_reviews = new WP_Query($bupr_args);
			if(!empty($bupr_reviews->posts)){
				$bupr_reviews = count($bupr_reviews->posts);
				if(!empty($bupr_reviews)){
					$bupr_notification = '<span class="no-count">'. $bupr_reviews .'</span>';
				}else{
					$bupr_notification = '<span class="no-count">'. 0 .'</span>';
				}
			}else{
				$bupr_notification = '<span class="no-count">'. 0 .'</span>';
			}
			
			$name = bp_get_displayed_user_username();
			$tab_args = array(
				'name' => __( 'Reviews'. $bupr_notification , BUPR_TEXT_DOMAIN ),
				'slug' => 'reviews',
				'screen_function' => array($this, 'bupr_reviews_tab_function_to_show_screen'),
				'position' => 75,
				'default_subnav_slug' => 'reviews_sub',
				'show_for_displayed_user' => true,
			);
			bp_core_new_nav_item($tab_args);

			$parent_slug = 'reviews';

			//Add subnav to view a review
			bp_core_new_subnav_item(
				array(
					'name' => __( 'View', BUPR_TEXT_DOMAIN ),
					'slug' => 'view',
					'parent_url' => $bp->loggedin_user->domain.$parent_slug.'/',
					'parent_slug' => $parent_slug,
					'screen_function' => array($this, 'bupr_view_review_tab_function_to_show_screen'),
					'position' => 100,
					'link' => site_url()."/members/$name/$parent_slug/view/",
				)
			);
		}

		/**
		* Actions performed to hide row actions
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		//Action performed to show screen of reviews listing tab
		public function bupr_reviews_tab_function_to_show_screen() {
			add_action('bp_template_content', array($this, 'bupr_reviews_tab_function_to_show_content'));
			bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
		}

		/**
		* Actions performed to hide row actions
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		//Action performed to show the content of reviews list tab
		public function bupr_reviews_tab_function_to_show_content() {
			include 'templates/bupr-reviews-tab-template.php';
		}

		/**
		* Actions performed to hide row actions
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		//Action performed to show screen of single review view tab
		public function bupr_view_review_tab_function_to_show_screen() {
			add_action('bp_template_content', array($this, 'bupr_view_review_tab_function_to_show_content'));
			bp_core_load_template(apply_filters('bp_core_template_plugin', 'members/single/plugins'));
		}

		/**
		* Actions performed to hide row actions
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		//Action performed to show the content of reviews list tab
		public function bupr_view_review_tab_function_to_show_content() {
			include 'templates/bupr-single-review-template.php';
		}
	}
	new BUPR_Custom_Hooks();
}