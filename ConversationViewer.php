<?php
/*
Plugin Name: Conversation Viewer
Plugin URI: http:/ryderdamen.com/conversation-viewer
Description: A plugin for displaying conversations in WordPress, like in their original messaging apps.
Version: 1.0
Author: Ryder Damen
*/


// Global Variables
$CVerrorString = "<p>Sorry, an error occurred.</p>";
$methodsToInclude = array(); // Needs a global scope to be placed in the footer

// WP Actions
add_action( 'wp_enqueue_scripts', 'enqueueConversationViewerScriptsAndStyles', PHP_INT_MAX );
add_shortcode( 'conversationViewer', 'createConversationViewerShortcode' );
add_action( 'wp_head', 'conversationViewerHookHeader' );

// Methods

function enqueueConversationViewerScriptsAndStyles() {
    // Enqueue the CSS and Javascript for WP

    // Enqueue CSS
	wp_enqueue_style( 'ConversationViewerPlugin_Main_Style', plugins_url( '/css/main.css', __FILE__ ), false, false, 'all' );
    
    // Enqueue JavaScript - Main File with JQuery Dependency
    wp_enqueue_script('ConversationViewerPlugin_JavaScript', plugins_url( '/js/main.js', __FILE__ ), array('jquery'), true); // Global JS for the library. Requires jQuery to be loaded first.
    
}


function processConversation( $atts ) {
	
	// Sanitizing and assigning variables
    $inputString = htmlspecialchars( $atts['conversation'] );
    $style = htmlspecialchars($atts['style']);
    $delimiter = $atts['delimiter'];
    $printJson = $atts['json'];
    $containerMaxWidthInPixels = $atts['width']; // Default of 400px
    $backgroundHex = $atts['background'];
    $clickable = $atts['clickable']; // Clickable (default is false)
	



    
   // Style selector
   $style_meMessages = null;
   $style_otherMessages = null;
   $style_otherMessagePerson = null;
   $style_messageCommand = null;
   $style_messageContainer = null;
   $meMessageNeedsAuthor = false;
   
   // Snapchat hex color array
  
   
    switch ($style) {
        case "android":
        	$style_meMessages = "CV-me-message-style-android";
            $style_otherMessages = "CV-message-style-android";
            $style_otherMessagePerson = "CV-message-person-style-android";
            $style_messageCommand = "CV-message-command-style-android";
            break;
        case "whatsapp":
        	$style_meMessages = "CV-me-message-style-whatsapp";
            $style_otherMessages = "CV-message-style-whatsapp";
            $style_otherMessagePerson = "CV-message-person-style-whatsapp";
            $style_messageCommand = "CV-message-command-style-whatsapp";
            break;
        case "snapchat":
        	$style_meMessages = "CV-me-message-style-snap";
            $style_otherMessages = "CV-message-style-snap";
            $style_otherMessagePerson = "CV-message-person-style-snap";
            $style_messageCommand = "CV-message-command-style-snap";
            $style_messageContainer = "CV-message-container-snap";
            $meMessageNeedsAuthor = true;
            break;
        case "ios":
        	$style_meMessages = "CV-me-message-style-ios";
            $style_otherMessages = "CV-message-style-ios";
            $style_otherMessagePerson = "CV-message-person-style-ios";
            $style_messageCommand = "CV-message-command-style-ios";
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
    
    if ($delimiter == "") {
	    return '<strong>Conversation Viewer Error: Your delimiter is empty, just remove the delimiter="" from your shortcode. :) </strong>';
    }


    // Split the conversation array into individual messages
    try {
    	$explodedInput = explode($delimiter, $inputString);
    }
    catch (Exception $e){
	    return '<strong>Conversation Viewer Error: Please use a different delimiter.</strong>';
	    
    }

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
            'message' => $message,
            'snapColor' => null
        );
        $sanitizedMessagesArray[] = $messageAndSender; // Append to a final array

    } // End of ForEach

    // Remove duplicate persons involved
    $people = array_unique($personsInvolvedArray);
    
    if ($style == "snapchat") { // If this is snap styling, we need an individual colour for each person
	    
	    $prettyColoursArray = array(
			    '#A569BD', '#5499C7', '#48C9B0', '#52BE80', '#F4D03F', '#EB984E', '#1A5276'
		);
    
	    // Then for each person involved, assign them a unique colour (snapchat)
	    foreach($personsInvolvedArray as $personInvolved) {
		    // For each person, assign them a unique colour
		    
		    if ( count($personsInvolvedArray) > count($prettyColoursArray) ) {
			    // If there's more people than colours, Just get some randomass colour
			    $snapHexStyle = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT) . ';';
		    }
		    else {
			    // Pick a random colour from the array, then remove that value from the array
				$r = rand(0, count($prettyColoursArray));
				$snapHexStyle = $prettyColoursArray[$r];
		    }
		    
		 		    
		    // Then, cycle through the $messageAndSender array and append the color to their name
		    foreach ($sanitizedMessagesArray as $key => $msg) {
			    if ($msg['person'] == $personInvolved) {
				    $sanitizedMessagesArray[$key]['snapColor'] = $snapHexStyle; // This is sketchy logic
			    }
			    
		    }
		        
	    }

    }
    
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
    
    // Holy hell this quickly got out of control
    
    // Cycle through the messages and print them to the string
    foreach($sanitizedMessagesArray as $msg) {
	    
	    $clickableClass = "CV-Clickable-" . str_replace(' ','',$msg['person']);
	    $methodsToInclude[] = 'highlightPersonOnClick(".' . $clickableClass . '"); ';
	    
        if ($msg['person'] == 'me') {
            // Me messages, Me styling
            $htmlMarkup .= '<div class="msg-container ' . $style_messageContainer . '">';
            
            if ($meMessageNeedsAuthor) { // If a style needs the Me message to say Me...
	            
	            if ($style == "snapchat") {
		            $htmlMarkup .= '<div class="CV-message-author ' . $style_otherMessagePerson . 
		            '" style="color: #cc0000">' . strtoupper($msg['person']) . '</div>';
	            }
	            else {
	            	$htmlMarkup .= '<div class="CV-message-author ' . $style_otherMessagePerson . '">' . ucwords($msg['person']) . '</div>';
	            }
            }
            
            $htmlMarkup .= '<div class="CV-message CV-me-message ' 
            . $style_meMessages . ' ' . $clickableClass . '">' . $msg['message'] . '</div></div>';
            
        }
        else if ($msg['person'] == 'command') {
	        // This is a command, style it as such
	        $htmlMarkup .= '<div class="msg-container">';
	        $htmlMarkup .= '<div class="msg-command ' . $style_messageCommand . '">' . $msg['message'] . '</div>';
	        $htmlMarkup .= '</div>';
        }
        else {
            // message from someone else
            $htmlMarkup .= '<div class="msg-container ' . $style_messageContainer . '">';
            if ($style == "snapchat") {
		            $htmlMarkup .= '<div class="CV-message-author ' . $style_otherMessagePerson . 
		            '" style="color: ' . $msg['snapColor'] . '">' . strtoupper($msg['person']) . '</div>';
	            }
	            else {
	            	$htmlMarkup .= '<div class="CV-message-author ' . $style_otherMessagePerson . '">' . ucwords($msg['person']) . '</div>';
	            }
            $htmlMarkup .= '<div class="CV-message ' . $style_otherMessages . ' ' . $clickableClass . '" style=" border-left-color: ' . $msg['snapColor'] . '">' . $msg['message'] . '</div></div>';
        }
    }

    $htmlMarkup .= "</div>"; // End of container
    
    
    
     // Set up clickable highlighting
     
    if ($clickable and $clickable != "false" and $style != "snap") {
	    // Snap doesn't need to be clickable, it has the color options
		
	    // Strip methods to include into a unique array
	    $methodsToInclude = array_unique($methodsToInclude);
	    
	    $print = "<script>";
		foreach($methodsToInclude as $method ) {
			$print .= $method;
		}
		$print .= "</script>";
		$htmlMarkup .= $print;
	
	}
   
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


function conversationViewerHookHeader() {
	echo "<!-- This site uses the Conversation Viewer plugin: Visit http://ryderdamen.com/conversation-viewer for more information. -->";
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
            'clickable' => false,
            'width' => '600',
		), 
		$atts,
		'conversationViewer'
    );
    
        
    // Processing Conversation
    $print = processConversation($atts);

	// Return the printed HTML
	return $print;
	
}

