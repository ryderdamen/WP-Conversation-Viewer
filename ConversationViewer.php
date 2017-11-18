<?php
/*
Plugin Name: Conversation Viewer
Plugin URI: http:/ryderdamen.com/conversationviewer
Description: A plugin for displaying conversations in WordPress, like in their original messaging apps.
Version: 1.0
Author: Ryder Damen
*/

// WP Actions
add_action( 'wp_enqueue_scripts', 'enqueueConversationViewerScriptsAndStyles', PHP_INT_MAX );
add_shortcode( 'conversationViewer', 'createConversationViewerShortcode' );


// Methods

function enqueueConversationViewerScriptsAndStyles() {
    // Enqueue the CSS and Javascript for WP

    // Enqueue CSS
	wp_enqueue_style( 'ConversationViewerPlugin_Main_Style', plugins_url( '/css/main.css', __FILE__ ), false, false, 'all' );
    
    // Enqueue JavaScript - Main File with JQuery Dependency
    wp_enqueue_script('ConversationViewerPlugin_JavaScript', plugins_url( '/js/script.js', __FILE__ ), 'jquery', true); // Global JS for the library. Requires jQuery to be loaded first.
    
}




function processConversation( $style, $inputString, $delimiter ) {
    
   // Style selector
    switch ($style) {
        case "iOS":
        	$style_meMessages = "CV-me-message-style-ios";
            $style_otherMessages = "CV-message-style-ios";
            $style_otherMessagePerson = "CV-message-person-style-ios";
            $style_messageCommand = "CV-message-command-style-ios";
            break;
        case "android":
        	$style_meMessages = "CV-me-message-style-android";
            $style_otherMessages = "CV-message-style-android";
            $style_otherMessagePerson = "CV-message-person-style-android";
            $style_messageCommand = "CV-message-command-style-android";
            break;
        default: 
            // Default is messenger
            $style_meMessages = "CV-me-message-style-facebook";
            $style_otherMessages = "CV-message-style-facebook";
            $style_otherMessagePerson = "CV-message-person-style-messenger";
            $style_messageCommand = "CV-message-command-style-messenger";
        break;
    }

    
    // Initialize array of persons involved
    $personsInvolvedArray = array();

    // Split the conversation array into individual messages
    $explodedInput = explode($delimiter, $inputString);

    if (!is_array($explodedInput)) {
        // There was only one part
        return;
    }

    // Remove the first value from the array
    array_shift( $explodedInput );

    $sanitizedMessagesArray = array();

    foreach($explodedInput as $piece) {
        // For each piece of the conversation

        // Separate the person from the comma
        $explodedPiece = explode(':', $piece);

        // Explode into single characters
        $personCharacterArray = str_split($explodedPiece[0]);

        if ($personCharacterArray[0] == ' ') {
            // If there is a space, remove it
            array_shift( $personCharacterArray );
        }

        // Implode
        $person = implode('', $personCharacterArray);

        // Convert to Lower Case
        $person = strtolower($person);

        // Add lowercase person to the persons array
        $personsInvolvedArray[] = $person;

        // Now, deal with what they said
        $message = strip_tags( html_entity_decode($explodedPiece[1]), ENT_QUOTES );

        $messageAndSender = array(
            'person' => $person,
            'message' => $message
        );

        // Append to a final array
        $sanitizedMessagesArray[] = $messageAndSender;

    } // End of ForEach

    // Remove duplicate persons involved
    $people = array_unique($personsInvolvedArray);


    // Start the markup
    $htmlMarkup = '<div class="CV-messages-container">';

    foreach($sanitizedMessagesArray as $msg) {
        if ($msg['person'] == 'me') {
            // Me messages, Me styling
            $htmlMarkup .= '<div class="msg-container"><div class="CV-message CV-me-message ' 
            . $style_meMessages . '">' . $msg['message'] . '</div></div>';
        }
        else if ($msg['person'] == 'command') {
	        // This is a command, style it as such
	        $htmlMarkup .= '<div class="msg-container">';
	        $htmlMarkup .= '<div class="msg-command ' . $style_messageCommand . '">' . $msg['message'] . '</div>';
	        $htmlMarkup .= '</div>';
        }
        else {
            // message from someone else
            $htmlMarkup .= '<div class="msg-container">';
            $htmlMarkup .= '<div class="CV-message-author ' . $style_otherMessagePerson . '">' . ucwords($msg['person']) . '</div>';
            $htmlMarkup .= '<div class="CV-message ' . $style_otherMessages . '">' . $msg['message'] . '</div></div>';
        }
    }

    $htmlMarkup .= "</div>"; // End of container

    return $htmlMarkup;

}




function createConversationViewerShortcode( $atts ) {
    
    // Set attributes and defaults
    $atts = shortcode_atts(
		array(
            'conversation' => '', 
            'style' => 'messenger',
            'delimiter' => '//', 				
		), 
		$atts,
		'conversationViewer'
    );
    
    // Assigning variables
    // TODO: SANITIZE INPUTS

    $conversation = htmlspecialchars( $atts['conversation'] );
    $style = htmlspecialchars($atts['style']);
    $delimiter = $atts['delimiter'];
    
    // Processing Conversation
    $print = processConversation($style, $conversation, $delimiter);

	// Return the printed HTML
	return $print;
	
}

