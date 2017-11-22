<?php
	
	// Conversation Viewer
	// Author: Ryder Damen
	// Version: 1.0
	// Description: This class provides the main functionality of the plugin, it converts a shortcode to a stylized conversation
	
 
class CVConversation {
	
	// Global Variables ----------------------------------------------------------------------------------------------------------------
	
	public $peopleInvolved = array();
	public $sanitizedMessages = array();
	public $styleUsed = null;
	public $jsonString = null;
	public $isClickable = false;
	private $mainContainerWidth = "600";
	private $mainContainerHex = "";
	private $mainContainerPadding = 25;
  
 
	// Constructor --------------------------------------------------------------------------------------------------------------------
	
	public function __construct($shortcodeAttributes, $shortcodeContent) {
		
		// Sanitize Shortcode Parameters
		$sanitizedShortcodeParameters = $this->cvSanitizeShortcodeParameters($shortcodeAttributes, $shortcodeContent);
		  
		// Setting and Processing Parameters
		$this->styleUsed = $sanitizedShortcodeParameters['style'];
		$this->isClickable = $sanitizedShortcodeParameters['clickable'];
		$this->mainContainerWidth = $sanitizedShortcodeParameters['width'];
		$this->mainContainerHex = $sanitizedShortcodeParameters['background'];
		$this->mainContainerPadding = $sanitizedShortcodeParameters['padding'];
		
		// Populates peopleInvolved, sanitizedMessages and JSON string
		$this->processConversationString($sanitizedShortcodeParameters['conversation'], $sanitizedShortcodeParameters['delimiter']);
	
	}
	  
	  
	// Constructor Helpers --------------------------------------------------------------------------------------------------------------
	
	private function processConversationString($inputString, $delimiter) {
		  
		// Initialize temp array of persons involved
	    $personsInvolvedArray = array();
	    
	    if ($delimiter == "") {
		    // TODO: Throw an exception
	    }
	
	    // Split the conversation array into individual messages
	    try {
	    	$explodedInput = explode($delimiter, $inputString);
	    }
	    catch (Exception $e){
		   // Throw an exception?
		   
	    }
	
	    if (!is_array($explodedInput)) {
	        // There was only one part to the conversation, do nothing
	        // Throw an exception
	    }
	
	    $sanitizedMessagesArray = array(); // Initialize a messagesArray
	    array_shift( $explodedInput ); // Remove the first value from the array
	
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
	
	        $sanitizedMessagesArray[] = array( // Append to a final array
	            'person' => $person,
	            'message' => $message,
	        );

	    } // End of ForEach
	
	    // Remove duplicate persons involved
	    $people = array_unique($personsInvolvedArray);
	    
	    // Then, assign a colour to each person
	    $prettyColoursArray = array(
			    '#A569BD', '#5499C7', '#48C9B0', '#52BE80', '#F4D03F', '#EB984E', '#1A5276'
		);
		
	    // Then for each person involved, assign them a unique colour (snapchat)
	    foreach($people as $personInvolved) {
		    // For each person, assign them a unique colour
		    
		    if ( count($people) > count($prettyColoursArray) ) {
			    // If there's more people than colours, Just get some randomass colour
			    $snapHexStyle = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT) . '';
		    }
		    else {
			    // Pick a random colour from the array, then remove that value from the array
				$r = rand(0, (count($prettyColoursArray)-1));
				$snapHexStyle = $prettyColoursArray[$r];
		    }
		    
		    // Then, cycle through the $messageAndSender array and append the color to their name
		    foreach ($sanitizedMessagesArray as $key => $msg) {
			    if ($msg['person'] == $personInvolved) {
				    $sanitizedMessagesArray[$key]['uniqueColor'] = $snapHexStyle; // This is sketchy logic
			    }  
		    }  
	    }
	    
	    // Finally, assign the method values to the class's global vars
	    $this->sanitizedMessages = $sanitizedMessagesArray;
	    $this->peopleInvolved = $people;
	    
	    
	    // Setting JSON Arrays
	    $meta = array(
		    'people' => array_values($people),
		    'numberOfMessages' => count($sanitizedMessagesArray),
		    'style' => $this->styleUsed,
		    'clickable' => $this->isClickable,
		    'mainContainerWidth' => $this->mainContainerWidth . 'px',
		    'mainContainerHex' => $this->mainContainerHex,
		    'mainContainerPadding' => $this->mainContainerPadding,
	    );
	    
	    $jsonPrint = array(
		    'meta' => $meta,
		    'data' => $sanitizedMessagesArray
	    );
	    
		// And assign the JSON
	    $this->jsonString = json_encode($jsonPrint, JSON_PRETTY_PRINT);
		  
	}
	
	private function cvSanitizeShortcodeParameters($shortcodeAttributes, $shortcodeContent) {
		// Sanitizes the shortcode Parameters
		 
		 // Content
		$shortcodeAttributes['conversation'] = htmlspecialchars($shortcodeAttributes['conversation']);
		
		if ($shortcodeAttributes['conversation'] == "") {
			// Use the content from in between the shortcode tags; else, use the content from within the tags
			// This supports version 1.0
			$shortcodeAttributes['conversation'] = htmlspecialchars($shortcodeContent);
		}
		
		// Attributes
		$shortcodeAttributes['style'] = htmlspecialchars($shortcodeAttributes['style']);
		$shortcodeAttributes['delimiter'] = $shortcodeAttributes['delimiter'];
		$shortcodeAttributes['json'] = htmlspecialchars($shortcodeAttributes['json']);
		$shortcodeAttributes['width'] = filter_var($shortcodeAttributes['width'], FILTER_VALIDATE_INT);
		$shortcodeAttributes['padding'] = filter_var($shortcodeAttributes['padding'], FILTER_VALIDATE_INT);
		$shortcodeAttributes['background'] = htmlspecialchars($shortcodeAttributes['background']);
		$shortcodeAttributes['clickable'] = htmlspecialchars($shortcodeAttributes['clickable']);
		
		return $shortcodeAttributes;
		
	}
	
	// Deconstructor ---------------------------------------------------------------------------------------------------------------
	
	public function __destruct() {
		// Destructor for the class
	}
	  
	// Other Methods ---------------------------------------------------------------------------------------------------------------
	
	private function assignConversationStyles() {
		
		// Inline Style Overrides (Initializing Variables)
		$mainContainer_styles = "";
		$messageContainer_styles = "";
		$incomingAuthor_styles = "";
		$outgoingAuthor_styles = "";
		$incomingMessage_styles = "";
		$outgoingMessage_styles = "";
		$command_styles = "";
		
		// Inline Style Overrides
		if ($this->styleUsed == "whatsapp" ) {
			// Set the background (only as a default)
			$mainContainer_styles = " background: url('" . plugin_dir_url( __FILE__ ) . "lib/whatsapp_background.png" . "'); padding: 50px; ";
		}
		
		if ($this->mainContainerHex != "default") {
			// There is an override for the main container
			$mainContainer_styles .= " background: none;"; // Disable background image in the case of whatsapp
			$mainContainer_styles .= " background-color: " . $this->mainContainerHex . ";";
		}
		
		if ($this->mainContainerWidth != 600) {
			// Container Width Override
			$mainContainer_styles .= " max-width: " . $this->mainContainerWidth . "px;";
		}
		
		if ($this->mainContainerPadding != 25) {
			// Padding override
			$mainContainer_styles .= " padding: " . $this->mainContainerPadding . "px;";
		}
		
		
		// Style Selector
		switch($this->styleUsed) {
			case ("ios"):
				$styleAppendix = "ios";
				break;
			case ("android"):
				$styleAppendix = "android";
				break;
			case ("whatsapp"):
				$styleAppendix = "whatsapp";
				break;
			case ("snapchat"):
				$styleAppendix = "snapchat";
				break;
			default:
				$styleAppendix = "facebook";
				break;
		}
		
		
		// Classes
		$mainContainer_classes = "CV-mainContainer-" . $styleAppendix;
		$messageContainer_classes = "CV-messageContainer-" . $styleAppendix;
		$incomingAuthor_classes = "CV-incomingAuthor-" . $styleAppendix;
		$outgoingAuthor_classes = "CV-outgoingAuthor-" . $styleAppendix;
		$incomingMessage_classes = "CV-incomingMessage-" . $styleAppendix;
		$outgoingMessage_classes = "CV-outgoingMessage-" . $styleAppendix;
		$command_classes = "CV-command-" . $styleAppendix;
				
		
		// Encode into Array
		return $styleStringsArray = array(
			'mainContainer_styles' => $mainContainer_styles,
			'mainContainer_classes' => $mainContainer_classes,
			'messageContainer_styles' => $messageContainer_styles,
			'messageContainer_classes' => $messageContainer_classes,
			'incomingAuthor_styles' => $incomingAuthor_styles,
			'incomingAuthor_classes' => $incomingAuthor_classes,
			'outgoingAuthor_styles' => $outgoingAuthor_styles,
			'outgoingAuthor_classes' => $outgoingAuthor_classes,
			'incomingMessage_styles' => $incomingMessage_styles,
			'incomingMessage_classes' => $incomingMessage_classes,
			'outgoingMessage_styles' => $outgoingMessage_styles,
			'outgoingMessage_classes' => $outgoingMessage_classes,
			'command_styles' => $command_styles,
			'command_classes' => $command_classes
		);
			
	}
	
	private function cvBuildHtmlMarkup() {
		
		// Style Overrides (Space separated class names, and space separated styles)
		
		// Retrieve Styles and Classes
		$SSA = $this->assignConversationStyles();
		
		// Decode Array
		$mainContainer_styles = $SSA['mainContainer_styles'];
		$mainContainer_classes = $SSA['mainContainer_classes'];
		
		$messageContainer_styles = $SSA['messageContainer_styles'];
		$messageContainer_classes = $SSA['messageContainer_classes'];
		
		$incomingAuthor_styles = $SSA['incomingAuthor_styles'];
		$incomingAuthor_classes = $SSA['incomingAuthor_classes'];
		
		$outgoingAuthor_styles = $SSA['outgoingAuthor_styles'];
		$outgoingAuthor_classes = $SSA['outgoingAuthor_classes'];
		
		$incomingMessage_styles = $SSA['incomingMessage_styles'];
		$incomingMessage_classes = $SSA['incomingMessage_classes'];
		
		$outgoingMessage_styles = $SSA['outgoingMessage_styles'];
		$outgoingMessage_classes = $SSA['outgoingMessage_classes'];
		
		$command_styles = $SSA['command_styles'];
		$command_classes = $SSA['command_classes'];
		
		// Initialize Variables
		$methodsToInclude = array();
		
		
		// Start DOM Markup
		$htmlMarkup = '<div class="CV-messages-container ' . esc_attr($mainContainer_classes) 
			.'" style="' . esc_attr($mainContainer_styles) .'">'; // Main Container
		
		// Pick a unique identifier for the container, preventing clicks triggering other containers to highlight
		$containerIdentifier = rand(0, 9999);
		
		// Start the loop
		foreach($this->sanitizedMessages as $msg) {
			
			// Create a clickable class identifier for each person
			$clickableClassIdentifier = "CV-Clickable-" . $containerIdentifier . "-" . str_replace(' ','',$msg['person']);
			$methodsToInclude[] = 'highlightPersonOnClick(".' . $clickableClassIdentifier . '"); '; // Include this person in an array
			
			if (!$this->isClickable) {
				// If the user did not explicitly set this conversation as clickable, disable this functionality
				$clickableClassIdentifier = null;
				$methodsToInclude = null;	
			}
			
			$htmlMarkup .= '<div class="msg-container ' . esc_attr($messageContainer_classes) 
				. '" style="' . esc_attr($messageContainer_styles) . '">'; // Message Container
			
			if ($msg['person'] == "me" ) {
				// Outgoing Message
				
				// Author
				$htmlMarkup .= '<div class="CV-message-author ' . esc_attr($outgoingAuthor_classes)
					. '" style="' . esc_attr($outgoingAuthor_styles) .'">';
				$htmlMarkup .= esc_html(ucwords($msg['person'])); // Print author name with Title Case
				$htmlMarkup .= '</div>'; // Closing Author 
				
				// Message
				$htmlMarkup .= '<div class="CV-message CV-outgoing ' . esc_attr($outgoingMessage_classes)
					. ' ' . esc_attr($clickableClassIdentifier) . '" style="' . esc_attr($outgoingMessage_styles) .'">';
				$htmlMarkup .= esc_html($msg['message']);
				$htmlMarkup .= '</div>';
				
			}
			elseif ($msg['person'] == "command") {
				// Message Command
				$htmlMarkup .= '<div class="msg-command ' . esc_attr($command_classes) 
				. '" style="' . esc_attr($command_styles) . '">' . esc_html($msg['message']) . '</div>';
			}
			else {
				// Incoming Message
				
				// Author
				$htmlMarkup .= '<div class="CV-message-author ' . esc_attr($incomingAuthor_classes) 
					. '" style="' . esc_attr($incomingAuthor_styles) . 
				esc_attr($this->snapchatColourOverride(0, $msg['uniqueColor'])) .'">';
				$htmlMarkup .= esc_html(ucwords($msg['person'])); // Print author name with Title Case
				$htmlMarkup .= '</div>'; // Closing Author 
				
				// Message
				$htmlMarkup .= '<div class="CV-message ' . esc_attr($incomingMessage_classes) 
				. ' ' . esc_attr($clickableClassIdentifier) . '" style="' 
				. esc_attr($incomingMessage_styles) . esc_attr($this->snapchatColourOverride(1, $msg['uniqueColor'])) .'">';
				$htmlMarkup .= esc_html($msg['message']);
				$htmlMarkup .= '</div>';
			}
			
			$htmlMarkup .= '</div>'; // Closing Message Container
			
		}
		
		$htmlMarkup .= '</div>'; // Closing Main Container
		
		// Clickable Scripts     
		 if ($this->isClickable and $this->isClickable != "false" and $this->styleUsed != "snap") {
		    // Snap doesn't need to be clickable, it has the color options
			
		    // Strip methods so we're not including duplicates
		    $methodsToInclude = array_unique($methodsToInclude);
		    
		    $print = "<script>";
			foreach($methodsToInclude as $method ) { // Include javascript methods for clickability
				$print .= $method;
			}
			$print .= "</script>";
			$htmlMarkup .= $print;
		}
		
		return $htmlMarkup;
		
	}  
	
	private function snapchatColourOverride($type, $hex) {
		// Handles the custom snapchat color overrides
		if ($this->styleUsed == "snapchat") {
			if ($type == 0) {
				// Author Colour
				return ' color: ' . $hex . ';';
			}
			else {
				// Border Colour
				return ' border-left-color: ' . $hex . ';';
			}
		}
		return; // If we're not using snapchat, don't return anything
	}
	  
	// Return Methods --------------------------------------------------------------------------------------------------------------
	
	public function getJSON( $wrappedInHtml = false ) {
		if ($wrappedInHtml) { // If true, wrap the pretty-print JSON in a pre and a code.
			return '<pre><code class="json">' . $this->jsonString . '</code></pre>';
		}
		return $this->jsonString;
	}
	
	public function getHTML() { // Return the built HTML markup for the conversation
		return $this->cvBuildHtmlMarkup();
	}  	 
	
	 

} // End of Class
	 
