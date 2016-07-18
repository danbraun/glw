<?php
/*
Plugin Name: Destination Post Type
Plugin URI: http://www.imperialmultimedia.com
Description: Adding the Destination Post Type.
Version: 1.0
Author: Dan Braun
Author URI: http://www.imperialmultimedia.com
*/

// add support for file types to post editor
function update_edit_form() {  
    echo ' enctype="multipart/form-data"';  
} // end update_edit_form  
add_action('post_edit_form_tag', 'update_edit_form');

// Add to init hook
add_action( 'init', 'setup_destination' );

// Register my taxonomies with EVENT MANAGER PLUGIN

function my_em_own_taxonomy_register(){
    register_taxonomy_for_object_type('destination_category',EM_POST_TYPE_EVENT);
    register_taxonomy_for_object_type('destination_county',EM_POST_TYPE_LOCATION);
}
add_action('init','my_em_own_taxonomy_register',100);

// Add these custom post types
function setup_destination() {

    // Register post type 
	$destination_labels = array(
        'name' 				 => 'Destinations',
		'singular_label' 	 => 'Destination',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Destination',
		'edit_item'          => 'Edit Destination',
		'new_item'           => 'New Destination',
		'all_items'          => 'All Destinations',
		'view_item'          => 'View Destination',
		'search_items'       => 'Search Destinations',
		'not_found'          => 'No destinations found',
		'not_found_in_trash' => 'No destinations found in Trash',
		'parent_item_colon'  => '',
		'menu_name' 		 => 'Destinations'
	);
	$destination_args = array(
		'labels' 		  => $destination_labels,
		'rewrite'		  => array(
			'slug' => 'destinations'
		),
        'public' 		  => true,
        'show_ui' 	 	  => true,
		'menu_position'   => 2,
        'capability_type' => 'post',
        'has_archive' 	  => true,
        'hierarchical' 	  => false,
        'show_in_menu' 	  => true,
        'supports' 		  => array( 'title', 'editor', 'revisions', 'comments', 'thumbnail' )
	);
	// POST TYPE NAME MUST BE 20 CHAR MAX!
	register_post_type( 'destinations', $destination_args );
	
	// Register Counties taxonomy
	$county_labels = array(
		'name' 			=> 'Destination County',
		'singular_name' => 'Destination County',
		'search_items' 	=> 'Search County',
		'popular_items' => 'Popular County',
		'add_new_item' 	=> 'Add New County',
		'edit_item'	=> 'Edit County'
	);
	$county_args = array(
		'hierarchical'	=> true,
		'labels' 		=> $county_labels,
		'query_var' 	=> true,
		'rewrite' 		=> true
	);
	
	// TAXONOMY NAME MUST BE 32 CHAR MAX!
	register_taxonomy( 'destination_county', 'destinations', $county_args );
	
	// Register Destination Category taxonomy
	$dest_category_labels = array(
		'name' 			=> 'Destination Category',
		'singular_name' => 'Destination Category',
		'search_items' 	=> 'Search Destination Category',
		'popular_items' => 'Popular Destination Category',
		'add_new_item' 	=> 'Add New Destination Category',
		'edit_item'	=> 'Edit Destination Category'
	);
	$dest_category_args = array(
		'hierarchical'	=> true,
		'labels' 		=> $dest_category_labels,
		'query_var' 	=> true,
		'rewrite' 		=> true
	);
	
	// TAXONOMY NAME MUST BE 32 CHAR MAX!
	register_taxonomy( 'destination_category', array('destinations', 'post'), $dest_category_args );
	
	// Hide add tags
	add_action('admin_menu', 'hide_add_tags');
	function hide_add_tags() {
		wp_enqueue_style('hide-add-tags', get_template_directory_uri() . '/css/hide-add-tags.css');
	}
	
	// Add meta fields
	/**
	 * Adds a lat and lon fields to the main column on the Destination edit screens.
	 */
	function destination_add_custom_boxes() {
		add_meta_box(
	        'destination_gps',
	        'Destination GPS Coordinates',
	        'destination_gps_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'lat_field_description' => 'Latitude in decimal degrees (0.000000): ',
				'lat_key' => 'latitude',
				'lon_field_description' => 'Longitude in decimal degrees (0.000000): ',
				'lon_key' => 'longitude'
			)
	     );
		add_meta_box(
	        'destination_address',
	        'Destination Address',
	        'destination_address_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'field_description' => 'Main Address: ',
				'address_key' => 'address'
			)
	     );
		add_meta_box(
	        'destination_directions',
	        'Destination Directions',
	        'destination_directions_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'field_description' => 'Driving Directions: ',
				'directions_key' => 'directions'
			)
	     );
		add_meta_box(
	        'destination_phonenumbers',
	        'Destination Phone Numbers',
	        'destination_phone_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'phone1_field_description' => 'Phone Number 1: ',
				'phone1_key' => 'phone1',
				'phone2_field_description' => 'Phone Number 2 (if applicable): ',
				'phone2_key' => 'phone2'
			)
	     );
		add_meta_box(
	        'destination_website',
	        'Destination Website',
	        'destination_website_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'website_text_field_description' => 'Website Text: ',
				'website_text_key' => 'website_text',
				'website_url_field_description' => 'Website URL: ',
				'website_url_key' => 'website_url'
			)
	     );
		add_meta_box(
	        'destination_email',
	        'Destination Email Contact',
	        'destination_email_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'email_name_field_description' => 'Email Contact Name: ',
				'email_name_key' => 'email_name',
				'email_address_field_description' => 'Email Address: ',
				'email_address_key' => 'email_address'
			)
	     );
		add_meta_box(
	        'destination_size',
	        'Destination Size',
	        'destination_size_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'acreage_field_description' => 'Acreage (if applicable): ',
				'acreage_key' => 'acreage',
				'length_field_description' => 'Length In Miles (if applicable): ',
				'length_key' => 'length'
			)
	     );
		add_meta_box(
	        'destination_size',
	        'Destination Size',
	        'destination_size_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'acreage_field_description' => 'Acreage (if applicable): ',
				'acreage_key' => 'acreage',
				'length_field_description' => 'Length In Miles (if applicable): ',
				'length_key' => 'length'
			)
	     );
	 	add_meta_box(
	        'destination_management',
	        'Destination Management',
	        'destination_management_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'management_field_description' => 'Property Managed By: ',
				'management_key' => 'management'
			)
	     );
		add_meta_box(
	        'destination_attachment',
	        'Destination PDF Map Attachment',
	        'destination_attachment_custom_box',
	        'destinations',
			'normal',
			'high'
	     );
		add_meta_box(
	        'destination_hours',
	        'Destination Hours of Operation',
	        'destination_hours_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'hours_field_description' => 'Hours and/or Seasonal Information: ',
				'hours_key' => 'hours'
			)
	     );
		add_meta_box(
	        'destination_source',
	        'Destination Content Source',
	        'destination_source_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'field_description' => 'URL to source webpage: ',
				'source_key' => 'source'
			)
	     );
		add_meta_box(
	        'destination_featured_caption',
	        'Destination Featured Image Caption',
	        'destination_featured_caption_custom_box',
	        'destinations',
			'normal',
			'high',
			array(
				'field_description' => 'Featured image caption: ',
				'source_key' => 'featured_caption'
			)
	     );
	}
	add_action( 'add_meta_boxes', 'destination_add_custom_boxes' );

	/**
	 * Prints the box content.
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 * @param array $args The arguments being sent from destination_add_custom_boxes
	 */
	function destination_gps_custom_box( $post, $args ) {

		extract($args['args']);

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'destination_custom_box', 'destination_custom_box_nonce' );

	  	/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$lat_value = get_post_meta( $post->ID, $lat_key, true );
		$lon_value = get_post_meta( $post->ID, $lon_key, true );

	  	echo '<label for="destination_' . $lat_key . '">'. $lat_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $lat_key . '" name="destination_' . $lat_key . '" value="' . esc_attr( $lat_value ) . '" size="15" />';
		echo '<br/>';
		echo '<label for="destination_' . $lon_key . '">'. $lon_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $lon_key . '" name="destination_' . $lon_key . '" value="' . esc_attr( $lon_value ) . '" size="15" />';

	}
	function destination_address_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$address_value = get_post_meta( $post->ID, $address_key, true );
		
		echo '<label for="destination_' . $address_key . '">'. $address_field_description;
	  	echo '</label><br/> ';
	  	echo '<textarea id="destination_'.$address_key.'" name="destination_'.$address_key.'" rows="4" cols="70">'.$address_value.'</textarea>';
	}
	function destination_directions_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$directions_value = get_post_meta( $post->ID, $directions_key, true );
		
		echo '<label for="destination_' . $directions_key . '">'. $directions_field_description;
	  	echo '</label><br/> ';
	  	echo '<textarea id="destination_'.$directions_key.'" name="destination_'.$directions_key.'" rows="4" cols="70">'.$directions_value.'</textarea>';
	}
	function destination_phone_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$phone1_value = get_post_meta( $post->ID, $phone1_key, true );
		$phone2_value = get_post_meta( $post->ID, $phone2_key, true );
		
		echo '<label for="destination_' . $phone1_key . '">'. $phone1_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $phone1_key . '" name="destination_' . $phone1_key . '" value="' . esc_attr( $phone1_value ) . '" size="20" />';
		echo '<br/>';
		echo '<label for="destination_' . $phone2_key . '">'. $phone2_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $phone2_key . '" name="destination_' . $phone2_key . '" value="' . esc_attr( $phone2_value ) . '" size="20" />';
	}
	function destination_website_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$website_text_value = get_post_meta( $post->ID, $website_text_key, true );
		$website_url_value = get_post_meta( $post->ID, $website_url_key, true );
		
		echo '<label for="destination_' . $website_text_key . '">'. $website_text_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $website_text_key . '" name="destination_' . $website_text_key . '" value="' . esc_attr( $website_text_value ) . '" size="40" />';
		echo '<br/>';
		echo '<label for="destination_' . $website_url_key . '">'. $website_url_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $website_url_key . '" name="destination_' . $website_url_key . '" value="' . esc_attr( $website_url_value ) . '" size="40" />';
	}
	function destination_email_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$email_name_value = get_post_meta( $post->ID, $email_name_key, true );
		$email_address_value = get_post_meta( $post->ID, $email_address_key, true );
		
		echo '<label for="destination_' . $email_name_key . '">'. $email_name_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $email_name_key . '" name="destination_' . $email_name_key . '" value="' . esc_attr( $email_name_value ) . '" size="25" />';
		echo '<br/>';
		echo '<label for="destination_' . $email_address_key . '">'. $email_address_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $email_address_key . '" name="destination_' . $email_address_key . '" value="' . esc_attr( $email_address_value ) . '" size="25" />';
	}
	function destination_size_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$acreage_value = get_post_meta( $post->ID, $acreage_key, true );
		$length_value = get_post_meta( $post->ID, $length_key, true );
		
		echo '<label for="destination_' . $acreage_key . '">'. $acreage_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $acreage_key . '" name="destination_' . $acreage_key . '" value="' . esc_attr( $acreage_value ) . '" size="25" />';
		echo '<br/>';
		echo '<label for="destination_' . $length_key . '">'. $length_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $length_key . '" name="destination_' . $length_key . '" value="' . esc_attr( $length_value ) . '" size="25" />';
	}
	function destination_management_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$management_value = get_post_meta( $post->ID, $management_key, true );
		
		echo '<label for="destination_' . $management_key . '">'. $management_field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $management_key . '" name="destination_' . $management_key . '" value="' . esc_attr( $management_value ) . '" size="25" />';
	}
	function destination_attachment_custom_box() {
		$html = '<p class="description">';  
		$html .= 'Upload your PDF Map here.';  
		$html .= '</p>';  
		$html .= '<input type="file" id="destination_attachment" name="destination_attachment" value="" size="25">';
		
		// Grab the array of file information currently associated with the post  
		$docs = get_post_meta(get_the_ID(), 'destination_attachment', false);
		
		$html .= '<p class="description"><input type="text" id="attachment_title" name="attachment_title" value="" size="35"> Name your file here</p>';
		
		$att_count = 0;
		
		foreach ($docs as $doc) {
			
			//$html .= '<div id="doc'.$count.'">';
			// Create the input box and set the file's URL as the text element's value
			$html .= '<br/><input type="text" id="destination_attachment_url" name="destination_attachment_url-'.$att_count.'" value=" ' . basename($doc['url']) . '" size="35" readonly />';
			if(strlen(trim($doc['url'])) > 0) { 
				$html .= ' '.$doc['title'].' <a href="javascript:;" id="destination_attachment_delete">' . __('Delete File') . '</a>';
			} // end if
			//$html .= '</div>';
			$att_count++;
		}
		
		echo $html;
		
	}
	function destination_hours_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$hours_value = get_post_meta( $post->ID, $hours_key, true );
		
		echo '<label for="destination_' . $hours_key . '">'. $hours_field_description;
	  	echo '</label><br/> ';
	  	echo '<textarea id="destination_'.$hours_key.'" name="destination_'.$hours_key.'" rows="4" cols="70">'.$hours_value.'</textarea>';
	}
	
	function destination_source_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$source_value = get_post_meta( $post->ID, $source_key, true );
	
		echo '<p class="description">';  
		echo 'Enter the URL of the source of this content, if the content is not original';  
		echo '</p>';
		
		echo '<label for="destination_' . $source_key . '">'. $field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $source_key . '" name="destination_' . $source_key . '" value="' . esc_attr( $source_value ) . '" size="35" />';
	}
	function destination_featured_caption_custom_box( $post, $args ) {
		extract($args['args']);
		/*
	   	* Use get_post_meta() to retrieve an existing value
	   	* from the database and use the value for the form.
	   	*/
	  	$source_value = get_post_meta( $post->ID, $source_key, true );
	
		echo '<p class="description">';  
		echo 'Enter the caption for the Featured Image of this destination, including photo credit when applicable. You may leave this field blank and a default caption will be displayed. Please be brief as lots of text will break the design.';  
		echo '</p>';
		
		echo '<label for="destination_' . $source_key . '">'. $field_description;
	  	echo '</label><br/> ';
	  	echo '<input type="text" id="destination_' . $source_key . '" name="destination_' . $source_key . '" value="' . esc_attr( $source_value ) . '" size="35" />';
	}
	
	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	function destination_save_postdata( $post_id ) {

		/*
	  	* We need to verify this came from the our screen and with proper authorization,
	  	* because save_post can be triggered at other times.
		*/

		// Check if our nonce is set.
		if ( ! isset( $_POST['destination_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['destination_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'destination_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;

		} else {

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// GPS coordinates
		$mydata1_new = sanitize_text_field( $_POST['destination_latitude'] );
		$mydata2_new = sanitize_text_field( $_POST['destination_longitude'] );
		// Address
		$newline_replacment = "||-nl-||";
		$escaped_newlines = str_replace("\n", $newline_replacment, $_POST['destination_address']);
		$mydata3_new = sanitize_text_field($escaped_newlines);
		// Directions
		$mydata4_new = sanitize_text_field( $_POST['destination_directions'] );
		// Phone
		$mydata5_new = sanitize_text_field( $_POST['destination_phone1'] );
		$mydata6_new = sanitize_text_field( $_POST['destination_phone2'] );
		// Website
		$mydata7_new = sanitize_text_field( $_POST['destination_website_text'] );
		$mydata8_new = sanitize_text_field( $_POST['destination_website_url'] );
		// Email
		$mydata9_new = sanitize_text_field( $_POST['destination_email_name'] );
		$mydata10_new = sanitize_text_field( $_POST['destination_email_address'] );
		// Size
		$mydata11_new = sanitize_text_field( $_POST['destination_acreage'] );
		$mydata12_new = sanitize_text_field( $_POST['destination_length'] );
		// Management
		$mydata13_new = sanitize_text_field( $_POST['destination_management'] );
		// Hours
		$mydata14_new = sanitize_text_field( $_POST['destination_hours'] );
		// Source
		$mydata15_new = sanitize_text_field( $_POST['destination_source'] );
		// Featured Caption
		$mydata16_new = sanitize_text_field( $_POST['destination_featured_caption'] );

		// get old GPS coordinates
		$mydata1_old = get_post_meta( $post_id, 'latitude', true );
		$mydata2_old = get_post_meta( $post_id, 'longitude', true );
		// get old Address
		$mydata3_old = get_post_meta( $post_id, 'address', true );
		// get old Directions
		$mydata4_old = get_post_meta( $post_id, 'directions', true );
		// get old Phones
		$mydata5_old = get_post_meta( $post_id, 'phone1', true );
		$mydata6_old = get_post_meta( $post_id, 'phone2', true );
		// get old Website
		$mydata7_old = get_post_meta( $post_id, 'website_text', true );
		$mydata8_old = get_post_meta( $post_id, 'website_url', true );
		// get old Email
		$mydata9_old = get_post_meta( $post_id, 'email_name', true );
		$mydata10_old = get_post_meta( $post_id, 'email_address', true );
		// get old Size
		$mydata11_old = get_post_meta( $post_id, 'acreage', true );
		$mydata12_old = get_post_meta( $post_id, 'length', true );
		// get old Management
		$mydata13_old = get_post_meta( $post_id, 'management', true );
		// get old Hours
		$mydata14_old = get_post_meta( $post_id, 'hours', true );
		// get old source url
		$mydata15_old = get_post_meta( $post_id, 'source', true );
		// get old featured caption
		$mydata16_old = get_post_meta( $post_id, 'source', true );
		
		// LATITUDE
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata1_new && $mydata1_new !== $mydata1_old) {
			update_post_meta( $post_id, 'latitude', $mydata1_new );
		} elseif ('' == $mydata1_new && $mydata1_old) {
			delete_post_meta( $post_id, 'latitude', $mydata1_old );
		}
		
		// LONGITUDE
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata2_new && $mydata2_new !== $mydata2_old) {
			update_post_meta( $post_id, 'longitude', $mydata2_new );
		} elseif ('' == $mydata2_new && $mydata2_old) {
			delete_post_meta( $post_id, 'longitude', $mydata2_old );
		}
		//echo "<script type='text/javascript'>alert('mydata2_new: ".$mydata2_new." mydata2_old: ".$mydata2_old." - ". $val."');</script>";
		
		// ADDRESS
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		$mydata3_new_nl_restored = str_replace($newline_replacment, "\n", $mydata3_new);
		if ($mydata3_new_nl_restored && $mydata3_new_nl_restored !== $mydata3_old) {
			update_post_meta( $post_id, 'address', $mydata3_new_nl_restored );
		} elseif ('' == $mydata3_new && $mydata3_old) {
			delete_post_meta( $post_id, 'address', $mydata3_old );
		}
		
		// DIRECTIONS
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata4_new && $mydata4_new !== $mydata4_old) {
			update_post_meta( $post_id, 'directions', $mydata4_new );
		} elseif ('' == $mydata4_new && $mydata4_old) {
			delete_post_meta( $post_id, 'directions', $mydata4_old );
		}
		
		// PHONE
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata5_new && $mydata5_new !== $mydata5_old) {
			update_post_meta( $post_id, 'phone1', $mydata5_new );
		} elseif ('' == $mydata5_new && $mydata5_old) {
			delete_post_meta( $post_id, 'phone1', $mydata5_old );
		}
		if ($mydata6_new && $mydata6_new !== $mydata6_old) {
			update_post_meta( $post_id, 'phone2', $mydata6_new );
		} elseif ('' == $mydata6_new && $mydata6_old) {
			delete_post_meta( $post_id, 'phone2', $mydata6_old );
		}
		
		// WEBSITE
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata7_new && $mydata7_new !== $mydata7_old) {
			update_post_meta( $post_id, 'website_text', $mydata7_new );
		} elseif ('' == $mydata7_new && $mydata7_old) {
			delete_post_meta( $post_id, 'website_text', $mydata7_old );
		}
		if ($mydata8_new && $mydata8_new !== $mydata8_old) {
			update_post_meta( $post_id, 'website_url', $mydata8_new );
		} elseif ('' == $mydata8_new && $mydata8_old) {
			delete_post_meta( $post_id, 'website_url', $mydata8_old );
		}
		
		// EMAIL
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata9_new && $mydata9_new !== $mydata9_old) {
			update_post_meta( $post_id, 'email_name', $mydata9_new );
		} elseif ('' == $mydata9_new && $mydata9_old) {
			delete_post_meta( $post_id, 'email_name', $mydata9_old );
		}
		if ($mydata10_new && $mydata10_new !== $mydata10_old) {
			update_post_meta( $post_id, 'email_address', $mydata10_new );
		} elseif ('' == $mydata10_new && $mydata10_old) {
			delete_post_meta( $post_id, 'email_address', $mydata10_old );
		}
		
		// SIZE
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata11_new && $mydata11_new !== $mydata11_old) {
			update_post_meta( $post_id, 'acreage', $mydata11_new );
		} elseif ('' == $mydata11_new && $mydata11_old) {
			delete_post_meta( $post_id, 'acreage', $mydata11_old );
		}
		if ($mydata12_new && $mydata12_new !== $mydata12_old) {
			update_post_meta( $post_id, 'length', $mydata12_new );
		} elseif ('' == $mydata12_new && $mydata12_old) {
			delete_post_meta( $post_id, 'length', $mydata12_old );
		}
		// MANAGEMENT
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata13_new && $mydata13_new !== $mydata13_old) {
			update_post_meta( $post_id, 'management', $mydata13_new );
		} elseif ('' == $mydata13_new && $mydata13_old) {
			delete_post_meta( $post_id, 'management', $mydata13_old );
		}
		
		// HOURS
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata14_new && $mydata14_new !== $mydata14_old) {
			update_post_meta( $post_id, 'hours', $mydata14_new );
		} elseif ('' == $mydata14_new && $mydata14_old) {
			delete_post_meta( $post_id, 'hours', $mydata14_old );
		}
		
		// SOURCE
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata15_new && $mydata15_new !== $mydata15_old) {
			update_post_meta( $post_id, 'source', $mydata15_new );
		} elseif ('' == $mydata15_new && $mydata15_old) {
			delete_post_meta( $post_id, 'source', $mydata15_old );
		}
		
		// FEATURED CAPTION
		// Update the meta fields in the database. Using !== operator to keep php from evaluating strings to numbers
		// and screwing up when there are lead/trailing zeros.
		if ($mydata16_new && $mydata16_new !== $mydata16_old) {
			update_post_meta( $post_id, 'featured_caption', $mydata16_new );
		} elseif ('' == $mydata16_new && $mydata16_old) {
			delete_post_meta( $post_id, 'featured_caption', $mydata16_old );
		}
		
		// FILE ATTACHMENT	
		// Make sure the file array isn't empty  
		if(!empty($_FILES['destination_attachment']['name'])) { 
			
	        // Setup the array of supported file types. In this case, it's just PDF.  
	        $supported_types = array('application/pdf','image/jpg', 'image/jpeg');  

	        // Get the file type of the upload  
	        $arr_file_type = wp_check_filetype(basename($_FILES['destination_attachment']['name']));  
	        $uploaded_type = $arr_file_type['type'];  

	        // Check if the type is supported. If not, throw an error.  
	        if(in_array($uploaded_type, $supported_types)) {  

	            // Use the WordPress API to upload the file  
	            $upload = wp_upload_bits($_FILES['destination_attachment']['name'], null, file_get_contents($_FILES['destination_attachment']['tmp_name']));  

	            if(isset($upload['error']) && $upload['error'] != 0) {  
	                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);  
	            } else { 
					//echo '<script>alert("'.$upload['url'].'");</script>';
					$upload['title'] = $_POST['attachment_title'];
	                add_post_meta($post_id, 'destination_attachment', $upload);  
	                //update_post_meta($post_id, 'destination_attachment', $upload);
	       			
	            } // end if/else  
				unset( $_FILES['destination_attachment'] );
	        } else {  
	            wp_die("The file type that you've uploaded is not a PDF.");  
	        } // end if/else  

	    }else{
			//return $post_id;
			// Grab a reference to the file associated with this post  
	        $docs = get_post_meta($post_id, 'destination_attachment', false); 
			
			$att_count = 0;
			foreach ($docs as $doc) {
				
				// Grab the value for the URL to the file stored in the text element
				 
		        $delete_flag = $_POST['destination_attachment_url-' . $att_count]; 
				
				$att_count++;

		        //Determine if a file is associated with this post and if the delete flag has been set (by clearing out the input box) 
		        if(strlen(trim($doc['url'])) > 0 && strlen(trim($delete_flag)) == 0) { 
		        
   		            // Attempt to remove the file. If deleting it fails, print a WordPress error. 
   		            if(unlink($doc['file'])) { 
   
   		                // Delete succeeded so reset the WordPress meta data 
   						delete_post_meta($post_id, 'destination_attachment', $doc);
   		                //update_post_meta($post_id, 'destination_attachment', null); 
   		                //update_post_meta($post_id, 'destination_attachment_url', ''); 
   
   		            } else { 
   		                wp_die('There was an error trying to delete your file.'); 
   		            } // end if/el;se 
   
   		        } // end if
				
			}

		} // end if
	}
	add_action( 'save_post', 'destination_save_postdata' );
	
	// add rel canonical to pages with dup content 
	function add_canonical() {
		$post_id = get_the_ID();
		$source_url = get_post_meta($post_id, 'source', true);
		$permalink = get_permalink( $post_id );
		if ($source_url != '') {
			echo '<link rel="canonical" href="'.$source_url.'">';
		}else{
			echo '<link rel="canonical" href="'.$permalink.'">';
		}
	}
	remove_action ('wp_head', 'rel_canonical');
	add_action ( 'wp_head', 'add_canonical');
}
?>