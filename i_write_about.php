<?php
/*
Plugin Name: I Write About
Plugin URI: https://github.com/mittmedia/i_write_about
Description: Adds user meta with users topic of choice.
Version: 1.0.0
Author: Fredrik Sundström
Author URI: https://github.com/fredriksundstrom
License: MIT
*/

/*
Copyright (c) 2012 Fredrik Sundström

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.
*/

require_once( 'wp_mvc/init.php' );

$i_write_about_app = new \WpMvc\Application();

$i_write_about_app->init( 'IWriteAbout', WP_PLUGIN_DIR . '/i_write_about' );

// WP: Add pages
add_action( 'network_admin_menu', 'i_write_about_add_pages' );
function i_write_about_add_pages()
{
  add_submenu_page( 'settings.php', 'I Write About Settings', 'I Write About', 'Super Admin', 'i_write_about_settings', 'i_write_about_settings_page');
}

function i_write_about_settings_page()
{
  global $i_write_about_app;

  $i_write_about_app->settings_controller->index();
}

add_filter('admin_head', 'i_write_about_add_scripts_and_styles');
function i_write_about_add_scripts_and_styles() {
  if (isset( $_GET['page'] ) && $_GET['page'] == 'i_write_about_settings') {
    echo '<link rel="stylesheet" type="text/css" href="' . WP_PLUGIN_URL . '/i_write_about/assets/build/stylesheets/settings.css' . '" />';
    echo '<script type="text/javascript" src="' . WP_PLUGIN_URL . '/i_write_about/assets/build/javascripts/settings.js' . '"></script>';
  }
}

if ( isset( $_GET['i_write_about_updated'] ) ) {
  add_action( 'network_admin_notices', 'i_write_about_updated_notice' );
}

function i_write_about_updated_notice()
{
  $html = \WpMvc\ViewHelper::admin_notice( __( 'Settings saved.' ) );

  echo $html;
}
