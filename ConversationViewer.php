<?php
/*
Plugin Name: Conversation Viewer
Plugin URI: http:/ryderdamen.com/conversation-viewer
Description: A plugin for displaying conversations in WordPress, like in their original messaging apps.
Version: 1.0
Author: Ryder Damen
*/


// WP Actions
add_action( 'wp_enqueue_scripts', 'enqueueConversationViewerScriptsAndStyles', PHP_INT_MAX );
add_shortcode( 'conversationViewer', 'createConversationViewerShortcode' );
add_action( 'wp_head', 'conversationViewerHookHeader' );

// Methods

function enqueueConversationViewerScriptsAndStyles() {
    // Enqueue the CSS and Javascript for WP
	wp_enqueue_style( 'ConversationViewerPlugin_Main_Style', plugins_url( '/css/main.css', __FILE__ ), false, false, 'all' );
    wp_enqueue_script('ConversationViewerPlugin_JavaScript', plugins_url( '/js/main.js', __FILE__ ), array('jquery'), true);    
}


function conversationViewerHookHeader() {
	echo "<!-- This site uses the Conversation Viewer plugin: Visit http://ryderdamen.com/conversation-viewer for more information. -->";
}


function createConversationViewerShortcode( $atts ) {
        
    include_once( plugin_dir_path( __FILE__ ) . "Conversation.php");
    
    // Set attributes and defaults
    $atts = shortcode_atts(
		array(
            'conversation' => '', 
            'style' => 'messenger',
            'delimiter' => '//', 		
            'json' => false,
            'background' => 'transparent',
            'clickable' => false,
            'width' => '600',
		), 
		$atts,
		'conversationViewer'
    );
    
    $conversation = new CVConversation($atts);
    
    if (htmlspecialchars($atts['json']) != false and htmlspecialchars($atts['json']) != "false"  and htmlspecialchars($atts['json']) != "") {
	    // Return JSON
	    
	    return $conversation->getJSON(true);
	    
    }

	return $conversation->getHTML();
	
}

