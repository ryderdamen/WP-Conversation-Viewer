[![CircleCI](https://circleci.com/gh/ryderdamen/WP-Conversation-Viewer.svg?style=shield)](https://circleci.com/gh/ryderdamen/WP-Conversation-Viewer)
# ConversationViewer WordPress Plugin
A plugin for displaying conversations in Wordpress, like in their original messaging apps.

[![A Screenshot of the plugin](http://www.ryderdamen.com/wp-content/uploads/2017/11/Screen-Shot-2017-11-19-at-11.13.22-PM.png)](http://ryderdamen.com/conversation-viewer)


## Setup
Install either from the WordPress Plugin directory, or by downloading a zip of this repository and uploading it to your site. After installing and activating, paste the following short code onto one of your pages, and the plugin will do it's magic.

`````

[conversationViewer]

//Me: Let me explain how it works.

//You: Okay, sounds good! 

// Me: I type up a conversation between the two of us in plain text in the regular WordPress editor, and the plugin converts it to look like a messaging service.

//You: Wow, that's really cool!

// Command: Someone Else has joined the conversation.

// Someone Else: Hey, more than one person can join?

// Me: Absolutely! As many people as you want, though "me" as the message-sender will always keep the right side of the conversation.

// You: That's cool!

[/conversationViewer]

`````

Note: This plugin uses enclosing shortcodes like this: `````[conversationViewer] [/conversationViewer]````` While it will work to put your conversation within a conversation="" tag, it's not recommended; just stick to the format above.

## How To Use It

### Getting Started

When writing a WordPress post or plugin, simply add this little bit of shortcode to display it as a conversation.

`````
[conversationViewer]

// Me: Hello!

// You: Hi!

[/conversationViewer]

`````

### Adding a line

There are three parts to adding a new line or speech bubble:

* The Delimiter (//)
* The Name
* The Message

First, start off with the delimiter, which is two forward slashes by default. After that, write the name of the person sending the message. Then, a colon `````:````` to indicate the message has started, and then, your message.

````` // Name : Message `````

### The "Me" Tag (Outgoing Messages)
For a chat bubble to appear to be an "outgoing" message (usually on the right side of the conversation), you will need to use the "Me" tag (not case sensitive). When you use this tag, it will appear as though you sent the message.

`````
[conversationViewer]

// Me: This is a message that I'm sending!

// Me: This message is also sent by me.

[/conversationViewer]

`````

### Messages from other people (Incoming Messages)
For anyone else, simply use the same procedure, and choose any name you want. Keep in mind, for clickable functionality to work, you will need to use the exact same spelling every time for each person. So don't be calling someone Jim in one place, and James in another. 

`````
[conversationViewer clickable="true" ]

// Me: Hey Guys, it's me!

// Jim: Hey, this is Jim!

// Sally: This is Sally!

// Jim: This is also Jim. When you click me, all of my messages will light up.

[/conversationViewer]

`````

### Commands
Sometimes in chats, the service indicates that someone has left, joined, changed the name of the conversation, etc. You can replicate this functionality with a command. It works like this:

 `````
[conversationViewer clickable="true" ]

// Me: Hey Guys, it's me!

// Jim: Hey, this is Jim!

// Command: Sally has joined the conversation.

// Sally: Hello!

[/conversationViewer]

`````

That's all you should need to get started. For more customizable options, keep on reading!


## Customizable Options
To make this a little more useful, I built a few customizable options you can play with.

### Styles
Within the shortcode, using the style="" selector, you can change the style to any of the following parameters:

* 	messenger (this is the default)
* 	whatsapp
* 	ios
* 	android
* 	snapchat

### Profile photos
Profile photos for each user can now be included on every style except snapchat. By default, profile photos will NOT be shown. To include them, simply provide the following image command for each particular person:

`````
// Image: [Name Of Person] [https://example.com/url-to-image/image.jpg]

`````

Now that an image is registered for that particular person, it will be shown for only them. If there are multiple other people in the conversation, their images will not be shown until they are registered. Image tags can go anywhere within the conversation, but I recommend putting them at the beginning like so:

`````
// Image: [George] [https://example.com/images/george.jpg]
// Image: [Kathy] [https://example.com/images/kathy.jpg]

// George: Hey there!
// Kathy: Hello, wow, look at our profile pictures.
// James: Awe, I don't have one, nobody registered one for me.


`````
	
### Clickability
To help your readers identify who's speaking, you can enable JavaScript clickability. When they click on a person, all messages sent by them will highlight. Just set the clickable="" selector to anything you want. Clickability is not available with the snapchat style (since the colours make it pretty clear who's speaking), and only extends to the current shortcode, not others on the page. 

`````

[conversationViewer style="whatsapp" clickable="yeaaa" ]

//Me: Hey!

//You: Hi!

[/conversationViewer]

`````

### Custom Background
The background is normally transparent, but if you need to set it to a specific colour, include the hex code with the # within the background="" tag.

`````

[conversationViewer style="snapchat" background="#cc0000" ]

//Me: Hey!

//You: Hi!

[/conversationViewer]


`````

### Custom Max Width
The maximum width is set at 600px with an auto margin; but the box is responsive for when you resize to smaller screens. To make the chat box larger, you can override the max width with the width="" selector. Enter a value in pixels (without the px suffix). 

`````

[conversationViewer style="ios" width="900" ]

//Me: Hey!

//You: Hi!

[/conversationViewer]


`````

### Custom Padding
The default padding of the main container is set to 25 pixels, but you can set it to something else with the padding="" selector. Enter the value in pixels without the px suffix.

`````

[conversationViewer style="ios" padding="100" ]

//Me: Hey!

//You: Hi!

[/conversationViewer]


`````

### Custom Delimiter
For some reason you or one of your friends uses double slashes within a conversation. Weird, but alright. You can set the delimiter to something else using the delimiter="" selector.

`````

[conversationViewer style="snapchat" delimiter="||" ]

||Me: The delimiter is now two pipes.

||You: Weird.

[/conversationViewer]


`````


### JSON Mode (Deprecated)
This one's weird and I'm not maintaining it, but I built it because I'm a big JSON fan. If you put something in the json="" selector, the plugin will print JSON of your conversation wrapped in a code (with a json class, wrapped in a pre, and not output the stylized conversation.

`````

[conversationViewer style="messenger" json="surewhynot" ]

//Me: Hey!

//You: Hi!

[/conversationViewer]


`````

The JSON Output of this function is as follows:

`````json

{
    "meta": {
        "people": [
            "me",
            "you",
            "command",
            "someone else"
        ],
        "numberOfMessages": 8,
        "style": "messenger",
        "clickable": "",
        "mainContainerWidth": "600px",
        "mainContainerHex": "default",
        "mainContainerPadding": 25
    },
    "data": [
        {
            "person": "me",
            "message": " Let me explain how it works.\n",
            "uniqueColor": "#EB984E"
        },
        {
            "person": "you",
            "message": " Okay, sounds good! \n",
            "uniqueColor": "#48C9B0"
        },
        {
            "person": "me",
            "message": " I type up a conversation between the two of us in plain text in the regular WordPress editor, and the plugin converts it to look like a messaging service.\n",
            "uniqueColor": "#EB984E"
        },
        {
            "person": "you",
            "message": " Wow, that's really cool!\n",
            "uniqueColor": "#48C9B0"
        },
        {
            "person": "command",
            "message": " Someone Else has joined the conversation.\n",
            "uniqueColor": "#EB984E"
        },
        {
            "person": "someone else",
            "message": " Hey, more than one person can join?\n",
            "uniqueColor": "#1A5276"
        },
        {
            "person": "me",
            "message": " Absolutely! As many people as you want, though you as the message-sender will always keep the right side of the conversation.\n",
            "uniqueColor": "#EB984E"
        },
        {
            "person": "you",
            "message": " That's cool!\n",
            "uniqueColor": "#48C9B0"
        }
    ]
}

`````



## Questions?
If you have any questions or feature requests, please feel free to send me a message.

## TODO (For Ryder)
* build out timestamp options
* add in ability to select a default highlighted person