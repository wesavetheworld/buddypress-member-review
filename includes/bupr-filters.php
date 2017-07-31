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
			add_action( 'bp_before_member_header_meta', array( $this, 'bupr_member_average_rating') );

			/* wbcom add new tab manage */
			//add_action( 'bp_setup_nav', array($this, 'bupr_member_profile_manage_tab' ) );

			add_action( 'bp_setup_admin_bar', array( $this, 'bupr_setup_admin_bar' ), 80 );

			add_action( 'init', array( $this, 'bupr_add_bp_member_reviews_taxonomy_term' ) );
			add_filter( 'post_row_actions', array( $this, 'bupr_bp_member_reviews_row_actions' ), 10, 2 );
			add_action( 'admin_footer-edit.php', array( $this, 'bupr_disable_review_title_edit_link' ) );
			add_filter( 'bulk_actions-edit-review', array( $this, 'bupr_remove_edit_bulk_actions' ), 10, 1 );
			add_action( 'bp_member_header_actions', array( $this, 'bupr_add_review_button_on_member_header' ) );
		}

		/**
		 * Actions performed to add a review button on member header
		 */
		function bupr_add_review_button_on_member_header() {
			$bupr_admin_settings = get_option( BUPR_GENERAL_OPTIONS , true );
			$bupr_exc_member = $bupr_admin_settings['bupr_exc_member'];

			if( !empty( $bupr_exc_member ) && !in_array( bp_displayed_user_id(), $bupr_exc_member ) ) {
				if( bp_displayed_user_id() != bp_loggedin_user_id() ) {
					$review_url = bp_core_get_userlink( bp_displayed_user_id(), false, true ).'reviews';
					?>
					<div id="bupr-add-review-btn" class="generic-button">
					<a href="<?php echo $review_url;?>" class="add-review"><?php _e( 'Add Review', BUPR_TEXT_DOMAIN );?></a>
					</div>
					<?php
				}
			}
		}

		/**
		 * Setup Reviews link in admin bar
		 */
		public function bupr_setup_admin_bar( $wp_admin_nav = array() ) {
			global $wp_admin_bar;

			$bupr_review_title 	= 'Reviews';
	   		$bupr_display_settings  = get_option( BUPR_DISPLAY_OPTIONS , true );
		    if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_review_title'])) {
		        $bupr_review_title  = $bupr_display_settings['bupr_review_title'];
		    }

		    $bupr_args = array(
				'post_type' 		=> 'review',
				'posts_per_page' 	=> -1,
				'post_status' 		=> 'publish',
				'category' 			=> 'bp-member',
				'meta_query' 		=> array(
						array(
							'key'		=>	'linked_bp_member',
							'value'		=>	get_current_user_id(),
							'compare'	=>	'=',
						),
				),
			);

			$reviews = get_posts( $bupr_args );
			$reviews_count = count( $reviews );

			$profile_menu_slug = 'reviews';

			$base_url = bp_loggedin_user_domain().$profile_menu_slug;
			if ( is_user_logged_in() ) {
				$wp_admin_bar->add_menu( array(
					'parent' => 'my-account-buddypress',
					'id' => 'my-account-'.$profile_menu_slug,
					'title' => __( $bupr_review_title.' <span class="count">'.$reviews_count.'</span>', BUPR_TEXT_DOMAIN ),
					'href' => trailingslashit( $base_url )
				) );
			}
		}

		/**
		* Actions performed to show average rating on a bp member's profile
		*
		* @since    1.0.0
		* @access   public
		* @author   Wbcom Designs
		*/
		public function bupr_member_average_rating() { ?>
			<br>
			<?php
			/* get display tab setting from db */
			$bupr_star_color 	= '#eeee22';
			$bupr_star_type 	= 'Stars Rating';
			$bupr_review_title 	= 'Reviews';
	   		$bupr_display_settings  = get_option( BUPR_DISPLAY_OPTIONS , true );
		    if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_review_title'])) {
		        $bupr_review_title  = $bupr_display_settings['bupr_review_title'];
		    }
		    if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_star_color'])) {
		        $bupr_star_color    = $bupr_display_settings['bupr_star_color'];
		    }
		    if( !empty( $bupr_display_settings ) && !empty($bupr_display_settings['bupr_star_type'])) {
		        $bupr_star_type     = $bupr_display_settings['bupr_star_type'];
		    }

			$bupr_type = 'integer';
			$bupr_avg_rating = 0;
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

			$reviews = get_posts( $bupr_args );
			$bupr_admin_settings       	= get_option( 'bupr_admin_settings' );
			if(!empty($bupr_admin_settings) && !empty($bupr_admin_settings['profile_rating_fields'])){
				$bupr_review_rating_fields 	= $bupr_admin_settings['profile_rating_fields'];
			}
			
			$bupr_total_rating  		= 0;
			$bupr_reviews_count 		= count( $reviews );
			$bupr_total_review_count	= '';
			if($bupr_reviews_count != 0){
				foreach( $reviews as $review ){
					$rate = 0;
					$reviews_field_count  = 0;
					$review_ratings       = get_post_meta( $review->ID, 'profile_star_rating', false );
					if(!empty($review_ratings[0])){
						//$reviews_field_count  = count( $bupr_review_rating_fields );
						if(!empty($bupr_review_rating_fields) && !empty($review_ratings[0])): 
							foreach($review_ratings[0] as $field => $value){
								if(array_key_exists($field,$bupr_review_rating_fields)){
									$rate += $value;
									$reviews_field_count++;	
								}
							}
							if($reviews_field_count != 0){
								$bupr_total_rating += (int)$rate/$reviews_field_count;
								$bupr_total_review_count ++;
							}
						endif; 
					}                               
				}

				if($bupr_total_review_count != 0){
					$bupr_avg_rating = $bupr_total_rating / $bupr_total_review_count;
					$bupr_type = gettype( $bupr_avg_rating );	
				}
				
				$bupr_stars_on 	 = $stars_off = $stars_half = '';
				$bupr_half_squar = '';
				if( $bupr_type == 'integer'){
					$bupr_stars_on 	= $bupr_avg_rating;
					$stars_off 		= 5 - $bupr_avg_rating;
					$stars_half 	= 0; 
				}

				if( $bupr_type == 'double' ){

					$bupr_stars_on 	= intval( $bupr_avg_rating );
					$stars_half 	= 1;
					$stars_off 		= 5 - ( $bupr_stars_on + $stars_half );
					$bupr_half_squar = 1;
				}
				if(!empty($bupr_star_type) && $bupr_star_type == 'Stars Rating'){
					for( $i = 1; $i <= $bupr_stars_on; $i++ ){ ?>
						<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
					}

					for( $i = 1; $i <= $stars_half; $i++ ){ ?>
						<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_half.png";?>" alt="star"><?php
					}

					for( $i = 1; $i <= $stars_off; $i++ ){ ?>
						<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
					}
				}else if(!empty($bupr_star_type) && $bupr_star_type == 'Numbers Rating'){
				    echo '<select class="display-square-rating-value" name="rating" autocomplete="off">';
				    echo '<option value=""></option>';
				    for($i = 1; $i <= 5 ; $i++){
				        if($i <= $bupr_stars_on){
				            echo '<option rate="selected" value="'.$i.'">'.$i.'</option>';
				        }else if(!empty($bupr_half_squar) && $i == $bupr_stars_on){
				        	echo '<option rate="selected" value="half">'.$i.'</option>';
				        }else{
				            echo '<option rate="unselected" value="0">'.$i.'</option>';
				        }
				    }
				    echo '</select>';
				}else if(!empty($bupr_star_type) && $bupr_star_type == 'Bar Rating'){ 
					//echo $bupr_stars_on;
					echo '<select class="bupr-display-pill-header bupr-display-pill-header-class"  name="rating" autocomplete="off">';
					echo '<option value=""></option>';
					for($i = 1; $i <= 5 ; $i++){
				        if($i <= $bupr_stars_on){
				            echo '<option rate="selected" value="'.$i.'"></option>';
				        }else if(!empty($bupr_half_squar) && $i == $bupr_stars_on ){
				        	echo '<option rate="selected" value="half"></option>';
				        }else{
				            echo '<option rate="unselected" value="0"></option>';
				        }
				    }
					echo '</select>';
				}else{
					for( $i = 1; $i <= $bupr_stars_on; $i++ ){ ?>
						<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star.png";?>" alt="star"><?php
					}

					for( $i = 1; $i <= $stars_half; $i++ ){ ?>
						<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_half.png";?>" alt="star"><?php
					}

					for( $i = 1; $i <= $stars_off; $i++ ){ ?>
						<img class="stars" src="<?php echo BUPR_PLUGIN_URL."assets/images/star_off.png";?>" alt="star"><?php
					}
				}
				$bupr_avg_rating = round( $bupr_avg_rating, 2 );
				_e("<span>Rating: ( $bupr_avg_rating)</span>", BUPR_TEXT_DOMAIN);
				_e("<p>Total $bupr_review_title: $bupr_reviews_count </p>" , BUPR_TEXT_DOMAIN);
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
					
					$review_url = bp_core_get_userlink( $linked_bp_member, false, true ).'reviews/view/'.$post->ID;
					$actions['view_review'] = '<a href="'.$review_url.'" title="'.$review_title.'">View Review</a>';

					//Add Approve Link for draft reviews
					if( $post->post_status == 'draft' ) {
						$actions['approve_review'] = '<a href="javascript:void(0);" title="'.$review_title.'" class="bupr-approve-review" data-rid="'.$post->ID.'">Approve</a>';
					}
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

			/* get display tab setting from db */
			$bupr_display_settings  = get_option( BUPR_DISPLAY_OPTIONS , true );
			if( !empty( $bupr_display_settings ) ) {
			    $bupr_review_title  = $bupr_display_settings['bupr_review_title'];
			    $bupr_star_color    = $bupr_display_settings['bupr_star_color'];
			    $bupr_star_type     = $bupr_display_settings['bupr_star_type'];
			} 
			if(empty($bupr_review_title)){
				$bupr_review_title = 'Reviews';
			}

			$bupr_admin_settings 	= get_option( BUPR_GENERAL_OPTIONS , true );
			if( !empty( $bupr_admin_settings ) ) {
				$bupr_exc_member 	= $bupr_admin_settings['bupr_exc_member'];
			}
			if(!empty($bupr_exc_member) && array_key_exists(bp_displayed_user_id(),$bupr_exc_member)){ 
				
            }else{
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
					'name' => __( $bupr_review_title .' '. $bupr_notification , BUPR_TEXT_DOMAIN ),
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