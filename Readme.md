# ConversationViewer WordPress Plugin
A plugin for displaying conversations in Wordpress, like in their original messaging apps.

[![A Screenshot of the plugin](http://www.ryderdamen.com/wp-content/uploads/2017/11/Screen-Shot-2017-11-19-at-11.13.22-PM.png)](http://ryderdamen.com/conversation-viewer)


## Setup
Install either from the WordPress Plugin directory, or by downloading a zip of this repository and uploading it to your site. After installing and activating, paste the following short code onto one of your pages, and the plugin will do it's magic.

`````

[conversationViewer style="messenger" conversation="

//Me: Let me explain how it works.

//You: Okay, sounds good! 

// Me: I type up a conversation between the two of us in plain text in the regular WordPress editor, and the plugin converts it to look like a messaging service.

//You: Wow, that's really cool!

// Command: Someone Else has joined the conversation.

// Someone Else: Hey, more than one person can join?

// Me: Absolutely! As many people as you want, though you as the message-sender will always keep the right side of the conversation.

// You: That's cool!

"]

`````

Note: You cannot use quotations ````` "Hello" ````` within these conversations due to the limitations of the shortcode. If you do need to use quotations, simply use this override: ````` &rdquo;Hello&rdquo; ````` . Please note, you must input this override within the TEXT tab, not the visual tab, as WordPress will replace the first ampersand with code.

## Customizable Options
To make this a little more useful, I built a few customizable options you can play with.

### Styles
Within the shortcode, using the style="" selector, you can change the style to any of the following parameters:

* 	messenger (this is the default)
* 	whatsapp
* 	ios
* 	android
* 	snapchat
	
### Clickability
To help your readers identify who's speaking, you can enable JavaScript clickability. When they click on a person, all messages sent by them will highlight. Just set the clickable="" selector to anything you want. Clickability is not available with the snapchat style (since the colours make it pretty clear who's speaking), and only extends to the current shortcode, not others on the page. 

`````

[conversationViewer style="whatsapp" clickable="yeaaa" conversation="

//Me: Hey!

//You: Hi!

"]


`````

### Custom Background
The background is normally transparent, but if you need to set it to a specific colour, include the hex code with the # within the background="" tag.

`````

[conversationViewer style="snapchat" background="#cc0000" conversation="

//Me: Hey!

//You: Hi!

"]


`````

### Custom Max Width
The maximum width is set at 600px with an auto margin; but the box is responsive for when you resize to smaller screens. To make the chat box larger, you can override the max width with the width="" selector. Enter a value in pixels (without the px suffix). 

`````

[conversationViewer style="ios" width="900" conversation="

//Me: Hey!

//You: Hi!

"]


`````

### Custom Delimiter
For some reason you or one of your friends uses double slashes within a conversation. Weird, but alright. You can set the delimiter to something else using the delimiter="" selector.

`````

[conversationViewer style="snapchat" delimiter="||" conversation="

||Me: The delimiter is now two pipes.

||You: Weird.

"]


`````


### JSON Mode (Deprecated)
This one's weird and I'm not maintaining it, but I built it because I'm a big JSON fan. If you put something in the json="" selector, the plugin will print JSON of your conversation wrapped in a code (with a json class, wrapped in a pre, and not output the stylized conversation.

`````

[conversationViewer style="messenger" json="surewhynot" conversation="

//Me: Hey!

//You: Hi!

"]


`````
JSON Output:

`````json

{
  "meta": {
    "people": {
      "0": "me",
      "1": "you",
      "4": "command",
      "5": "professor oak"
    },
    "numberOfMessages": 8
  },
  "data": [
    {
      "person": "me",
      "message": " Hello\n",
      "snapColor": "#895725;"
    },
    {
      "person": "you",
      "message": " Hi! \ud83d\ude00\n",
      "snapColor": "#007e9a;"
    },
    {
      "person": "me",
      "message": " What's up?\n",
      "snapColor": "#895725;"
    },
    {
      "person": "you",
      "message": " Not much\n",
      "snapColor": "#007e9a;"
    },
    {
      "person": "command",
      "message": " Professor Oak has joined the conversation.\n",
      "snapColor": "#0614e3;"
    },
    {
      "person": "professor oak",
      "message": " Hey guys!\n",
      "snapColor": "#441861;"
    },
    {
      "person": "me",
      "message": " Hi Professor.\n",
      "snapColor": "#895725;"
    },
    {
      "person": "you",
      "message": " Hello.\n",
      "snapColor": "#007e9a;"
    }
  ]
}

`````


## Ryder's TODO

* Sanitize!
* Build more themes
* Refactor this hot mess
* Deal with the clickable problem (assign a unique var for each instance)
* Upload to WordPress


## Questions?
If you have any questions or feature requests, please feel free to send me a message.