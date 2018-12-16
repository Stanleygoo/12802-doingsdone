<?php

require_once('./bootstrap.php');

$guest_content = view(VIEWS_PATH . '/guest.php');

$full_page = build_layout([
    'show_bg' => true,
    'has_sidebar' => false,
    'user' => null,
    'content' => $guest_content
]);

echo $full_page;

