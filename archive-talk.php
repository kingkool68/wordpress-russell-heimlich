<?php
$context = array(
    'talks' => RH_Talk_Teaser_Block::render_from_wp_query(),
);
Sprig::out( 'archive-talk.twig', $context );
