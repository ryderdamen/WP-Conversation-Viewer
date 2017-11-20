<?php
	
 
class CVConversation {
	
	// Global Variables ----------------------------------------------------------------------------------------------------------------
	
	public $peopleInvolved = array();
	public $sanitizedMessages = array();
	public $styleUsed = null;
	public $jsonString = null;
	public $isClickable = false;
	private $mainContainerWidth = "600";
	private $mainContainerHex = "";
  
 
	// Constructor --------------------------------------------------------------------------------------------------------------------
	
	public function __construct($shortcodeAttributes) {
		
		// Sanitize Shortcode Parameters
		$sanitizedShortcodeParameters = $this->cvSanitizeShortcodeParameters($shortcodeAttributes);
		  
		// Setting and Processing Parameters
		$this->styleUsed = $sanitizedShortcodeParameters['style'];
		$this->isClickable = $sanitizedShortcodeParameters['clickable'];
		$this->mainContainerWidth = $sanitizedShortcodeParameters['width'];
		$this->mainContainerHex = $sanitizedShortcodeParameters['background'];
		
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
	            'uniqueColor' => null
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
	    
	    // Finally, assign the method values to the class's global vars
	    $this->sanitizedMessages = $sanitizedMessagesArray;
	    $this->peopleInvolved = $people;
	    
	    
	    // Setting JSON Arrays
	    $meta = array(
		    'people' => array_values($people),
		    'numberOfMessages' => count($sanitizedMessagesArray),
		    'style' => $this->styleUsed,
		    'clickable' => $this->isClickable,
		    'mainContainerWidth' => $this->mainContainerWidth,
		    'mainContainerHex' => $this->mainContainerHex,
	    );
	    
	    $jsonPrint = array(
		    'meta' => $meta,
		    'data' => $sanitizedMessagesArray
	    );
	    
		// And assign the JSON
	    $this->jsonString = json_encode($jsonPrint);
		  
	}
	
	private function cvSanitizeShortcodeParameters($shortcodeAttributes) {
		// Sanitizes the shortcode Parameters
		 
		$shortcodeAttributes['conversation'] = htmlspecialchars($shortcodeAttributes['conversation']);
		$shortcodeAttributes['style'] = htmlspecialchars($shortcodeAttributes['style']);
		$shortcodeAttributes['delimiter'] = $shortcodeAttributes['delimiter']; // Not yet sanitized
		$shortcodeAttributes['json'] = htmlspecialchars($shortcodeAttributes['json']);
		$shortcodeAttributes['width'] = filter_var($shortcodeAttributes['width'], FILTER_VALIDATE_INT);
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
		
		switch($this->styleUsed) {
			case ("ios"):
				$mainContainer_styles = null;
				$mainContainer_classes = null;

				$messageContainer_styles = null;
				$messageContainer_classes = null;
				
				$incomingAuthor_styles = null;
				$incomingAuthor_classes = null;
				
				$outgoingAuthor_styles = null;
				$outgoingAuthor_classes = null;
				
				$incomingMessage_styles = null;
				$incomingMessage_classes = null;
				
				$outgoingMessage_styles = null;
				$outgoingMessage_classes = null;
				
				$command_styles = null;
				$command_classes = null;
				break;
				
			case ("android"):
				$mainContainer_styles = null;
				$mainContainer_classes = null;

				$messageContainer_styles = null;
				$messageContainer_classes = null;
				
				$incomingAuthor_styles = null;
				$incomingAuthor_classes = null;
				
				$outgoingAuthor_styles = null;
				$outgoingAuthor_classes = null;
				
				$incomingMessage_styles = null;
				$incomingMessage_classes = null;
				
				$outgoingMessage_styles = null;
				$outgoingMessage_classes = null;
				
				$command_styles = null;
				$command_classes = null;
				break;
				
			case ("whatsapp"):
				$mainContainer_styles = null;
				$mainContainer_classes = null;

				$messageContainer_styles = null;
				$messageContainer_classes = null;
				
				$incomingAuthor_styles = null;
				$incomingAuthor_classes = null;
				
				$outgoingAuthor_styles = null;
				$outgoingAuthor_classes = null;
				
				$incomingMessage_styles = null;
				$incomingMessage_classes = null;
				
				$outgoingMessage_styles = null;
				$outgoingMessage_classes = null;
				
				$command_styles = null;
				$command_classes = null;
				break;
				
			case ("snap"):
				$mainContainer_styles = null;
				$mainContainer_classes = null;

				$messageContainer_styles = null;
				$messageContainer_classes = null;
				
				$incomingAuthor_styles = null;
				$incomingAuthor_classes = null;
				
				$outgoingAuthor_styles = null;
				$outgoingAuthor_classes = null;
				
				$incomingMessage_styles = null;
				$incomingMessage_classes = null;
				
				$outgoingMessage_styles = null;
				$outgoingMessage_classes = null;
				
				$command_styles = null;
				$command_classes = null;
				break;
				
			default: // (Messenger)
				$mainContainer_styles = null;
				$mainContainer_classes = "CV-mainContainer-facebook";

				$messageContainer_styles = null;
				$messageContainer_classes = "CV-messageContainer-facebook";
				
				$incomingAuthor_styles = null;
				$incomingAuthor_classes = "CV-incomingAuthor-facebook";
				
				$outgoingAuthor_styles = null;
				$outgoingAuthor_classes = "CV-outgoingAuthor-facebook";
				
				$incomingMessage_styles = null;
				$incomingMessage_classes = "CV-incomingMessage-facebook";
				
				$outgoingMessage_styles = null;
				$outgoingMessage_classes = "CV-outgoingMessage-facebook";
				
				$command_styles = null;
				$command_classes = "CV-command-facebook";
				break;
		}
		
		// Inline Style Overrides (Setting Variables)
		$mainContainer_styles = null;
		$messageContainer_styles = null;
		$incomingAuthor_styles = null;
		$outgoingAuthor_styles = null;
		$incomingMessage_styles = null;
		$outgoingMessage_styles = null;
		$command_styles = null;
		
		// Pass in any inline style overrides here
		
		if ($this->styleUsed == "whatsapp" and $this->mainContainerHex != "") {
			// Set the border background (only as a default)
			$mainContainer_styles = " background: url('" . plugin_dir_url( __FILE__ ) . "assets/whatsapp_background.png" . "'); padding: 50px; ";
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
		
		
		// Start Markup
		
		$htmlMarkup = '<div class="CV-messages-container ' . $mainContainer_classes .'" style="' . $mainContainer_styles .'">'; // Main Container
		
		// Pick a unique identifier for the container
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
			
			$htmlMarkup .= '<div class="msg-container ' . $messageContainer_classes . '" style="' . $messageContainer_styles 
			. '">'; // Message Container
			
			if ($msg['person'] == "me" ) {
				// Outgoing Message
				
				// Author
				$htmlMarkup .= '<div class="CV-message-author ' . $outgoingAuthor_classes . '" style="' . $outgoingAuthor_styles .'">';
				$htmlMarkup .= ucwords($msg['person']); // Print author name with Title Case
				$htmlMarkup .= '</div>'; // Closing Author 
				
				// Message
				$htmlMarkup .= '<div class="CV-message CV-outgoing ' 
					. $outgoingMessage_classes . ' ' . $clickableClassIdentifier . '" style="' . $outgoingMessage_styles .'">';
				$htmlMarkup .= $msg['message'];
				$htmlMarkup .= '</div>';
				
			}
			elseif ($msg['person'] == "command") {
				// Message Command
				$htmlMarkup .= '<div class="msg-command ' . $command_classes . '" style="' . $command_styles . '">' . $msg['message'] . '</div>';
			}
			else {
				// Incoming Message
				
				// Author
				$htmlMarkup .= '<div class="CV-message-author ' . $incomingAuthor_classes . '" style="' . $incomingAuthor_styles . 
				$this->snapchatColourOverride(0, $msg['snapColor']) .'">';
				$htmlMarkup .= ucwords($msg['person']); // Print author name with Title Case
				$htmlMarkup .= '</div>'; // Closing Author 
				
				// Message
				$htmlMarkup .= '<div class="CV-message ' . $incomingMessage_classes . ' ' . $clickableClassIdentifier . '" style="' 
				. $incomingMessage_styles . $this->snapchatColourOverride(1, $msg['snapColor']) .'">';
				$htmlMarkup .= $msg['message'];
				$htmlMarkup .= '</div>';
			}
			
			$htmlMarkup .= '</div>'; // Closing Message Container
			
		}
		
		$htmlMarkup .= '</div>'; // Closing Main Container
		
		// Clickable Scripts     
		 if ($this->isClickable and $this->isClickable != "false" and $this->styleUsed != "snap") {
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
		if ($wrappedInHtml) {
			return '<pre><code class="json"' . $this->jsonString . '</code></pre>';
		}
		return $this->jsonString;
	}
	
	public function getHTML() {
		return $this->cvBuildHtmlMarkup();
	}  	 
	
	 

} // End of Class
	 
