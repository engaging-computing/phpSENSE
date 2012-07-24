<?php

if(!function_exists('isense_posted_on')) {
    function isense_posted_on($authorlinkclass = 'authorlink') {
    	printf( __('<span>Posted</span> %1$s <span class="meta-sep">by</span> %2$s'),
    		    
    		    // sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>', get_permalink(),  esc_attr(get_the_time()), isense_date_diff(get_the_date())),
    		    isense_date_diff(get_the_date()),
    		    
    		    sprintf('<span class="author vcard"><a class="url fn n %1$s" href="%2$s" title="%3$s">%4$s</a></span>',
    		        $authorlinkclass,
    			    get_author_posts_url( get_the_author_meta( 'ID' ) ),
    			    sprintf( esc_attr__('View all posts by %s'), get_the_author() ),
    			    get_the_author()
    		    )
    	);
    }
}

if(!function_exists('isense_date_diff')) {
    function isense_date_diff($string) {

    	$day_1 = time();
    	$day_2 = strtotime($string);

    	$diff = $day_1 - $day_2;

        /*
    	$sec   = $diff % 60;
    	$diff  = intval($diff / 60);
    	$min   = $diff % 60;
    	$diff  = intval($diff / 60);
    	$hours = $diff % 24;
    	$days  = intval($diff / 24);
    	*/

    	if($days=intval((floor($diff/86400)))) $diff = $diff % 86400;

    	if($hours=intval((floor($diff/3600)))) $diff = $diff % 3600;

    	if($minutes=intval((floor($diff/60)))) $diff = $diff % 60;

    	$diff    =    intval( $diff );

    	$date_diff_string = "";
    	if($days != 0) {
    		$date_diff_string .= $days . " days ";
    	}

    	if($hours != 0) {
    		$date_diff_string .= $hours . " hours ";
    	}

    	if($min != 0) {
    		$date_diff_string .= $min . " minutes ";
    	}

    	$date_diff_string .= "ago";

    	return $date_diff_string;

        return strtoupper($string);
    }
    
}

if(!function_exists('isense_page_title')) {
    function isense_page_title() {
        global $title;
        
        if(isset($title)) {
            echo "iSENSE Blog - " . $title;
        }
        else {
            echo "iSENSE Blog";
        }
    }
}

if(!function_exists('get_custom_field')) {
    function get_custom_field($field) {
        global $post;
    	$custom_field = get_post_meta($post->ID, $field, true);
    	echo $custom_field;
    }
}

if(!function_exists('text_field')) {
    function text_field($args) {
        global $post;
        $description = $args[2];

        // adjust data
        $args[2] = get_post_meta($post->ID, $args[0], true);
        $args[1] = __($args[1], 'sp' );

        $label_format =
            '<div style="width: 95%%; margin: 10px auto 10px auto; background-color: #F9F9F9; border: 1px solid #DFDFDF; -moz-border-radius: 5px; -webkit-border-radius: 5px; padding: 10px;">'.
            '<p><label for="%1$s"><strong>%2$s</strong></label></p>'.
            '<p><input style="width: 80%%;" type="text" name="%1$s" value="%3$s" /></p>'.
            '<p><em>'.$description.'</em></p>'.
            '</div>';

        return vsprintf( $label_format, $args );
    }
}

if(!function_exists('text_area')) {
    function text_area($args) {
        global $post;
        $description = $args[2];
    
        // adjust data
        $args[2] = get_post_meta($post->ID, $args[0], true);
        $args[1] = __($args[1], 'sp');
        
        $label_format =
            '<div style="width: 95%%; margin: 10px auto 10px auto; background-color: #F9F9F9; border: 1px solid #DFDFDF; -moz-border-radius: 5px; -webkit-border-radius: 5px; padding: 10px;">'.
            '<p><label for="%1$s"><strong>%2$s</strong></label></p>'.
            '<p><textarea style="width: 90%%;" name="%1$s">%3$s</textarea></p>'.
            '<p><em>'.$description.'</em></p>'.
            '</div>';
            
        return vsprintf( $label_format, $args );
    }
}

if(!function_exists('field_html')) {
    function field_html($args) {
        switch($args[3]) {
            case 'textarea':
                return text_area($args);
            default:
                return text_field($args);
        }
    }
}

if(!function_exists('isense_nonce')) {
    function isense_nonce () {
        // Use nonce for verification ... ONLY USE ONCE!
        return sprintf(
            '<input type="hidden" name="%1$s" id="%1$s" value="%2$s" />',
            'isense_nonce_name',
            wp_create_nonce( plugin_basename(__FILE__) )
        );
    }
}

if(!function_exists('isense_custom_post_box')) {
    function isense_custom_post_box($obj, $box) {
        global $boxes;
        static $nonce_flag = false;
        if(!$nonce_flag) {
            echo isense_nonce();
            $nonce_flag = true;
        }

        foreach($boxes[$box['id']] as $new_box) {
            echo field_html($new_box);
        }
    }
}

// Create the global for boxes, because Wordpress is dumb
if(!function_exists('isense_save_postdata')) {
    function isense_save_postdata($post_id, $post) {
        global $boxes;
        
        if(!wp_verify_nonce($_POST['isense_nonce_name'], plugin_basename(__FILE__))) {
            return $post->ID;
        }
        
        if($_POST['post_type'] == 'page' || strpos($_POST['post_type'], 'isense') !== FALSE) {
            
            
            if($_POST['post_type'] == 'isense_faq') {
                $boxes = array(
                    'Question' => array(
                        array('_isense_faq_question', 'Question', 'What is the question this is responding to?'),
                        array('_isense_faq_audiance', 'Audience', 'Who is this response meant for?'),
                    )
                );                
            }
            else if($_POST['post_type'] == 'isense_event') {
                $boxes = array(
                    'When' => array(
                        array('_isense_event_start', 'Start Time', 'When does this event start?'),
                        array('_isense_event_end', 'End Time', 'When does this event end?')
                    ),
                    'Where' => array(
                        array('_isense_event_common_name', 'Common Name', '(ex: Olsen Hall, iRobot)'),
                        array('_isense_event_address_1', 'Address', 'Street address of this event'),
                        array('_isense_event_city', 'City', 'What city or town is this venue located?'),
                        array('_isense_event_state', 'State', 'In which great state of our blessed union will this event be taking place?'),
                        array('_isense_event_zip', 'Zip', 'What is the official US Postal designated zip code of this location?'),
                    ),
                    'Registration' => array(
                        array('_isense_event_registration', 'Registration', 'Is registration required?'),
                    ),
                    'Contact' => array(
                        array('_isense_event_contact_name', 'Contact Name', 'Who should people contact for more information or to register?'),
                        array('_isense_event_contact_phone', 'Contact Phone', 'What is the phone number of the contact?'),
                        array('_isense_event_contact_email', 'Contact Email', 'What is the email address of the contact?'),
                    )
                );
            }
            else if($_POST['post_type'] == 'isense_documentation') {
                
            }
            
            if(!current_user_can('edit_page', $post->ID)) {
                return $post->ID;
            }
            else if(!current_user_can('edit_post', $post->ID)) {
                return $post->ID;
            }
            
            $my_data = array();
            foreach($boxes as $box) {
                foreach($box as $field) {
                    add_post_meta($post->ID, $key, $field[0]);
                    $my_data[$field[0]] = $_POST[$field[0]];
                }
            }

            foreach($my_data as $key => $value) {
                if($post->post_type == 'revision') {
                    return;
                }

                $value = implode(',', (array)$value);

                if(get_post_meta($post->ID, $key, FALSE)) {
                    update_post_meta($post->ID, $key, $value);
                }
                else {
                    add_post_meta($post->ID, $key, $value);
                }
            }
            
            if($_POST['post_type'] == 'isense_event') {
                wp_set_object_terms($post->ID, 'events', 'post_tag');
            }
            else if($_POST['post_type'] == 'isense_faq') {
                wp_set_object_terms($post->ID, 'faqs', 'post_tag');
            }
            else if($_POST['post_type'] == 'isense_documentation') {
                wp_set_object_terms($post->ID, 'guides', 'post_tag');
            }

            if(!$value) {
                delete_post_meta($post->ID, $key);
            }
        }
    }
    
    add_action('save_post', 'isense_save_postdata', 1, 2);
}

// Create the isense faq custom post type and metabox callback
if(!function_exists('create_isense_faq_type') && !function_exists('isense_faq_metabox_cb')) {
    // Register action to create isense faq type
    add_action('init', 'create_isense_faq_type');
    
    function create_isense_faq_type() {
        // Create the custom post type for the iSENSE Event
        $spec = array(
            'labels' => array(
                'name' => __('FAQs'),
                'singular_name' => __('FAQ')
            ),
            'register_meta_box_cb' => 'isense_faq_metabox_cb',
            'public' => true,
            'supports' => array(
                            'title',
            				'editor',
            				'thumbnail',
            				'excerpt'
            			),
            'rewrite' => array('slug' => 'faq', 'with_front' => false),
        );

        register_post_type('isense_faq', $spec);
    }
    
    function isense_faq_metabox_cb() {
        global $boxes;
        
        $boxes = array(
            'Question' => array(
                array('_isense_faq_audiance', 'Audience', 'Who is this response meant for?'),
            )
        );
        
        // Make sure we can add a meta box
        if(function_exists('add_meta_box')) {
            foreach (array_keys($boxes) as $box_name) {
                add_meta_box($box_name, __($box_name), 'isense_custom_post_box', 'isense_faq');
            }
        }
    }
}

// Create the isense event custom post type and metabox callback
if(!function_exists('create_isense_event_type') && !function_exists('isense_event_metabox_cb')) {
    
    // Register action to create isense event type
    add_action('init', 'create_isense_event_type');
    
    
    function create_isense_event_type() {
        // Create the custom post type for the iSENSE Event
        $spec = array(
            'description' => 'Events for and by the iSENSE Project',
            'show_ui' => true,
            'exclude_from_search' => false,
            'labels' => array(
                'name' => __('Events'),
                'singular_name' => __('Event'),
                'add_new' => __('Add New Event'),
                'add_new_item' => __('Add New Event'),
                'edit' => __('Edit Event'),
                'new_item' => __('New Event'),
                'view' => __('View Event'),
                'view_item' => __('View Event'),
                'search_items' => __('Search Events'),
                'not_found' => __('No events found'),
                'not_found_in_trash' => __('No events found in Trash')
            ),
            'supports' => array(
            				'title',
            				'editor',
            				'thumbnail',
            				'revisions',
            				'author',
            				'excerpt'
            			),
            'register_meta_box_cb' => 'isense_event_metabox_cb',
            'public' => true,
            'rewrite' => array('slug' => 'event', 'with_front' => false),
            'capability_type' => 'post',
            'taxonomies' => array('event')
        );

        register_post_type('isense_event', $spec);
    }
    
    function isense_event_metabox_cb() {
        global $boxes;
        
        $boxes = array(
            'When' => array(
                array('_isense_event_start', 'Start Time', 'When does this event start?'),
                array('_isense_event_end', 'End Time', 'When does this event end?')
            ),
            'Where' => array(
                array('_isense_event_common_name', 'Common Name', '(ex: Olsen Hall, iRobot)'),
                array('_isense_event_address_1', 'Address', 'Street address of this event'),
                array('_isense_event_city', 'City', 'What city or town is this venue located?'),
                array('_isense_event_state', 'State', 'In which great state of our blessed union will this event be taking place?'),
                array('_isense_event_zip', 'Zip', 'What is the official US Postal designated zip code of this location?'),
            ),
            'Registration' => array(
                array('_isense_event_registration', 'Registration', 'Is registration required?'),
            ),
            'Contact' => array(
                array('_isense_event_contact_name', 'Contact Name', 'Who should people contact for more information or to register?'),
                array('_isense_event_contact_phone', 'Contact Phone', 'What is the phone number of the contact?'),
                array('_isense_event_contact_email', 'Contact Email', 'What is the email address of the contact?'),
            )
        );
        
        // Make sure we can add a meta box
        if(function_exists('add_meta_box')) {
            foreach (array_keys($boxes) as $box_name) {
                add_meta_box($box_name, __($box_name), 'isense_custom_post_box', 'isense_event');
            }
        }
    }
}

// Create the isense documentation custom post type and metabox callback
if(!function_exists('create_isense_documentation_type') && !function_exists('isense_documentation_metabox_cb')) {
    
    // Register action to create isense documentation type
    add_action('init', 'create_isense_documentation_type');
    
    function create_isense_documentation_type() {
        // Create the custom post type for the iSENSE Event
        $spec = array(
            'labels' => array(
                'name' => __('Documentation'),
                'singular_name' => __('Documentation')
            ),
            'register_meta_box_cb' => 'isense_documentation_metabox_cb',
            'public' => true,
            'supports' => array(
            				'title',
            				'editor',
            				'thumbnail',
            				'excerpt'
            			),
            'rewrite' => array('slug' => 'doc', 'with_front' => false),
        );

        register_post_type('isense_documentation', $spec);
    }

    function isense_documentation_metabox_cb() {
        global $boxes;
        
        $boxes = array();
        
        // Make sure we can add a meta box
        if(function_exists('add_meta_box')) {
            foreach (array_keys($boxes) as $box_name) {
                add_meta_box($box_name, __($box_name), 'isense_custom_post_box', 'isense_documentation');
            }
        }
    }
}

if(!function_exists('add_event_category')) {

}

?>