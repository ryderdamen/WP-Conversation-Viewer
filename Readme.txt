=== Conversation Viewer - Display Chat Bubbles ===
Contributors: ryderdamen
Donate link: http://ryderdamen.com/buy-me-a-beer
Tags: Conversation Viewer, Chat bubble, speech bubble, chat bubbles, facebook messenger, messenger, whatsapp, texting, ios messages, snapchat, chat simulator
Requires at least: 3.8
Tested up to: 4.9
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A plugin for displaying chat bubbles on your site, like in their original messaging apps.

== Description ==

Conversation Viewer is a responsive WordPress plugin for displaying chat conversations between people as if in their native apps.

It allows you to write up conversations between two, or groups of more than two people, without having to take screenshots. These conversations can be easily switched into various messaging app styles. There are a few currently supported styles.

* Facebook Messenger (the default)
* Android Messages
* iOS Texting
* WhatsApp
* Snapchat

With responsive CSS, these conversations look great on all screens, are more responsive and accessible than uploading screenshots.  
  
== Installation ==
 
To get up and running at its most basic, follow these three steps:
  
1. Upload `ConversationViewer` to the `/wp-content/plugins/` directory (or download from the plugin directory)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `[conversationViewer] //Me: Hello! //You: Hi! [/conversationViewer]` in your page/post editor, and the plugin will work its magic.

== Getting Started & Customization == 

For the complete documentation on how to do everything including example code, visit the [GitHub Readme Page](https://github.com/ryderdamen/WP-Conversation-Viewer).
 
== Frequently Asked Questions ==
 
= Where can I find more info on this plugin? =
 
Check the [GitHub Repository](https://github.com/ryderdamen/WP-Conversation-Viewer "GitHub Repository") for more information on Conversation Viewer and how to use it.

 
= How do I change styles? =
 
To change styles, simply use the style selector in the opening tag of the shortcode, like this: `[conversationViewer style="snapchat"]`. The following styles are available:

* messenger (the default)
* whatsapp
* snapchat
* ios
* android

= How do I add profile images? = 

By default, profile images will not be displayed. In order to display them, include an image command for each person in the conversation. To do this, simply add the following command anywhere:

` // Image [Name Of Person] [https://example.com/link/to/image.jpg]`

For more information, visit the [Full Documentation](https://github.com/ryderdamen/WP-Conversation-Viewer "Full Documentation")

 
== Screenshots ==
 
1. An example of the plugin in action, using the default messenger style.
2. An example of the plugin using snapchat style.
3. An example of the plugin using whatsapp style.
 
== Changelog ==
 
= 1.1 =
* Adding support for profile images with the image command (See documentation)
* Fixing a URL parsing issue: https:// and http:// links no longer split conversations and mess everything up
 
 
= 1.0 =
* The initial version supporting Facebook messenger, iOS, Android, WhatsApp and Snapchat styling.
  
== Upgrade Notice ==
 
= 1.1 =
* This version provides support for including profile photos 
 
= 1.0 =
This is the initial version. 
  
 