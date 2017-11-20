# ConversationViewer WordPress Plugin
A plugin for displaying conversations in Wordpress, like in their original messaging apps.

## Shortcode
Here's a proposed layout for the shortcode.
`````

[conversationViewer style="messenger" conversation="

 
//Me: Hello!

//Friend: How are you?

// Me: I am fine.

//friend: Names are not case sensitive

// Other Friend: Also, if you forget to put a space before hand, that's okay too.

// A different friend: 

// friend: If nothing is said, no message will be written?

//other friend: yeah okay

//command: Anything put in here will appear as a message command or tiny centered text in the middle of a conversation.

"]

`````

## TODO
* figure out a way to ignore any additional colons after the first delimiter :

* More colour based, less platform based;
* sanitize variables
* Build out other themes
* reorganize and refactor code into different subclasses and methods
* build out extra params; post names
* build out js
* Write readme
* Post