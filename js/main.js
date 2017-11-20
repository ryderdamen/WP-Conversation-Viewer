
var $ = jQuery;

	
function highlightPersonOnClick(className){
	// When a name is clicked, highlight all members with the same class
	
	
	// Highlight the clicked class	
	$(className).click(function(e) {
				
		// Remove shadow from all elements
		$(".CV-message").css( "box-shadow", "none" );
		
		// Place shadow on highlighted element
		$(className).css( "box-shadow", "2px 2px 1px #888888" );
	});
	
	
}

    
