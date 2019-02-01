// ConversationViewer
// Author: Ryder Damen (http://ryderdamen.com/conversation-viewer)
// Version: 1.0

var $ = jQuery;
	
function highlightPersonOnClick(className){
	// When a name is clicked, highlight all members with the same class
	
	// Highlight the clicked class	
	$(className).click(function(e) {
		
		if ($(className).css("box-shadow") !=  "none" ) {
			// If the element already has a box shadow, let's remove it with another click
			$(className).css( "box-shadow", "none" );
		}
		else {
			// Remove shadow from all elements
			$(".CV-message").css( "box-shadow", "none" );
		
			// Place shadow on highlighted element
			$(className).css( "box-shadow", "2px 2px 10px #888888" );	
		}
	});
	
}

    
