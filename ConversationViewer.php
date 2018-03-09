<?php
/*
Plugin Name: Conversation Viewer - Display Chat Bubbles
Plugin URI: http:/ryderdamen.com/projects/conversation-viewer
Description: A plugin for displaying conversations in WordPress, like in their original messaging apps.
Version: 1.1
Author: Ryder Damen
Author URI: http://ryderdamen.com
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
	$CVattributionString = "This site uses the Conversation Viewer plugin: Visit http://ryderdamen.com/conversation-viewer for more information.";
	echo "<!-- " . esc_html($CVattributionString) . " -->";
}

function createConversationViewerShortcode( $atts, $content = null ) {
    // Creates the shortcode    
    include_once( plugin_dir_path( __FILE__ ) . "Conversation.php");
    
    // Set attributes and defaults
    $atts = shortcode_atts(
		array(
            'conversation' => '', 
            'style' => 'messenger',
            'delimiter' => '//', 		
            'json' => false,
            'background' => 'default',
            'clickable' => false,
            'width' => '600',
            'padding' => '25',
		), 
		$atts,
		'conversationViewer'
    );
    
    try {
    	$conversation = new CVConversation($atts, $content);
    	
	    if ( htmlspecialchars($atts['json']) != false and 
	    	 htmlspecialchars($atts['json']) != "false"  and 
	    	 htmlspecialchars($atts['json']) != "") {
		    // If the user wants JSON, Return JSON
		    return $conversation->getJSON(true);
	    }
	    
		// Return the stylized HTML
		return $conversation->getHTML();
		
	}
	catch (Exception $e) {
		$CvErrorString = "Conversation Viewer Error: ";
		return "<strong>" . esc_html($CvErrorString . $e->getMessage()) . "</strong>";
	}
}

