<?php

include( 'linkpress-controller.php' );

$content = <<< CONTENT
As of last standup I worked on:
[wpdt-2343] - Some title (see [rdnap_pr-626] for details).
[wpdt_pr-20] - The PR for this plugin.

The following are blocked:
[rpwo-872] - blocked because of the financial layer.

Next I plan to work on:
[WPDT-3139] - Migrate some stuff

CONTENT;

print( 'The original content:' . PHP_EOL . $content . PHP_EOL );

$lpc = new LinkPress_Controller();

$output = $lpc->content_filter( $content );
print( 'The updated content:' . PHP_EOL . $output . PHP_EOL );






