<?php

use PHPUnit\Framework\TestCase;
include( __DIR__ . '/requirements.php');
include( __DIR__ . '/helpers.php' );
include( __DIR__ . '/../src/Conversation.php' );


/**
 * PHPUnit Test class for ConversationViewer Plugin
 */
class ConversationViewerTest extends TestCase {

    /**
     * Tests shortcode content is as expected for particular attributes
     *
     * @return void
     */
    public function testShortCodeRendering() {
        $atts = array(
            'conversation' => '', 
            'style' => 'messenger',
            'delimiter' => '//', 		
            'json' => false,
            'background' => 'default',
            'clickable' => false,
            'defaultname' => null,
            'width' => '600',
            'padding' => '25',
		);
        $content = "// Me: This is test content";
        $conversation = new CVConversation($atts, $content);
        $results = $conversation->getHTML(true);
        $expected = getDataFile('shortcode_rendering.txt');
        $this->assertSame($results, $expected);
    }
}