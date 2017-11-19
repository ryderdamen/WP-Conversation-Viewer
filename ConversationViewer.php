<?php
/*
Plugin Name: Conversation Viewer
Plugin URI: http:/ryderdamen.com/conversationviewer
Description: A plugin for displaying conversations in WordPress, like in their original messaging apps.
Version: 1.0
Author: Ryder Damen
*/


// Global Variables
$CVerrorString = "<p>Sorry, an error occurred.</p>";

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


function processConversation( $atts ) {
	
	// Sanitizing and assigning variables
    $inputString = htmlspecialchars( $atts['conversation'] );
    $style = htmlspecialchars($atts['style']);
    $delimiter = $atts['delimiter'];
    $printJson = $atts['json'];
    $containerMaxWidthInPixels = $atts['width']; // Default of 400px
    $backgroundHex = $atts['background'];
	
	
    
   // Style selector
    switch ($style) {
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
        // There was only one part to the conversation, do nothing
        return $CVerrorString;
    }

    // Remove the first value from the array
    array_shift( $explodedInput );
    $sanitizedMessagesArray = array();

    foreach($explodedInput as $piece) {
        // For each piece of the conversation
        
        $explodedPiece = explode(':', $piece, 2); // Separate the person from the message, ignore everything after the first colon
        $personCharacterArray = str_split($explodedPiece[0]); // Explode into single characters

        if ($personCharacterArray[0] == ' ') {
            // If there is a space at the start, remove it
            array_shift( $personCharacterArray );
        }
        
        // THE PERSON
        $person = implode('', $personCharacterArray); // Implode
        $person = strtolower($person); // Convert to Lower Case
        $personsInvolvedArray[] = $person; // Add lowercase person to the persons array

        // THE MESSAGE
        $message = strip_tags( html_entity_decode($explodedPiece[1]), ENT_QUOTES );

        $messageAndSender = array(
            'person' => $person,
            'message' => $message
        );
        $sanitizedMessagesArray[] = $messageAndSender; // Append to a final array

    } // End of ForEach

    // Remove duplicate persons involved
    $people = array_unique($personsInvolvedArray);
    
    if ($printJson) {
	    // If the user wants to print JSON instead of a conversation array, print it and return.
	    // TODO: build out to a "get conversation JSON" button?
	    
	    $meta = array(
		    'people' => $people,
		    'numberOfMessages' => count($sanitizedMessagesArray),
	    );
	    
	    $jsonPrint = array(
		    'meta' => $meta,
		    'data' => $sanitizedMessagesArray
	    );
	    
	    $returnHtml = '<pre><code class="json">';
	    $returnHtml = json_encode($jsonPrint);
	    $returnHtml .= '</pre></code>';
	    
	    return $returnHtml;
	 
    }
    
    // Override Styles
    $containerStyleOverride = overrideStyles($containerMaxWidthInPixels, $backgroundHex);
    
    // Start the markup
    $htmlMarkup = '<div class="CV-messages-container"' . $containerStyleOverride .'>';
    
    
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



function overrideStyles($containerMaxWidthInPixels, $backgroundHex) {
	// Style Overrides
    $containerStyleOverride = 'style="'; // Open the style
    
    // Width override
    if ($containerMaxWidthInPixels != "600") {
	    // If it's not the default
	    $containerStyleOverride .= ' max-width: ' . $containerMaxWidthInPixels .'px; ';
    }
    
    // Background override
    if ($backgroundHex != "transparent") {
	    // If it's not the default
	    $containerStyleOverride .= 'background-color: ' . $backgroundHex .'; ';
    }
    
    $containerStyleOverride .= '"'; // Close the style
    return $containerStyleOverride;
}




function createConversationViewerShortcode( $atts ) {
    
    // Set attributes and defaults
    $atts = shortcode_atts(
		array(
            'conversation' => '', 
            'style' => 'messenger',
            'delimiter' => '//', 		
            'json' => false,
            'background' => 'transparent',
            'senderbubblecolor' => 'default',
            'sendertextcolor' => 'default',	
            'width' => '600'
		), 
		$atts,
		'conversationViewer'
    );
    
        
    // Processing Conversation
    $print = processConversation($atts);

	// Return the printed HTML
	return $print;
	
}

