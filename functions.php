<?php
function mytheme_setup() {
  register_nav_menus([
    'primary' => __('Primary Menu', 'mytheme'),
  ]);
}
add_action('after_setup_theme', 'mytheme_setup');


