<?php
/**
* @package PoemhdPlugin
*/
/*
 * Plugin Name:       Poemhd Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Krithi Krishna
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       poemhd-plugin
 * Domain Path:       /languages
 */
 /*
 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

 Copyright 2005-2015 Automattic, Inc.
 */


defined( 'ABSPATH' ) or die('Hey,you can\t access this file!' );

function Poemhd_plugin_get_lyric() {
	/** These are the lyrics to Hello Dolly */
	$lyrics = "Go, lovely Rose —
Tell her that wastes her time and me,
That now she knows,
When I resemble her to thee,
How sweet and fair she seems to be.
Tell her that's young,
And shuns to have her graces spied,
That hadst thou sprung
In deserts where no men abide,
Thou must have uncommended died.
Small is the worth
Of beauty from the light retired:
Bid her come forth,
Suffer herself to be desired,
And not blush so to be admired.
Then die — that she
The common fate of all things rare
May read in thee;
How small a part of time they share
That are so wondrous sweet and fair!";

	// Here we split it into lines.
	$lyrics = explode( "\n", $lyrics );

	// And then randomly choose a line.
	return wptexturize( $lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later.
function poemhd_plugin() {
	$select = Poemhd_plugin_get_lyric();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		$lang,
	);
	retutrn $select
}

// Now we set that function up to execute when the admin_notices action is called.
add_shortcode( 'heaven', 'poemhd_plugin' );

if( !class_exists( 'PoemhdPlugin' ) ) {

  class PoemhdPlugin
  {

    function __construct() {
      add_action( 'init', array( $this,'custom_post_type') );
    }

    function register() {
      add_action( 'admin_enqueue_scripts', array( $this,'enqueue') );
    }

    function activate() {
      $this->custom_post_type();
      flush_rewrite_rules();
    }

    function deactivate() {
      flush_rewrite_rules();
    }

    function custom_post_type() {
      register_post_type( 'book', ['public' => true, 'label' => 'Books', 'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')] );
    }

    function  enqueue() {
      wp_enqueue_style( 'mypluginstyle', plugins_url( '/assets/mystyle.css', __FILE__) );
      wp_enqueue_script( 'mypluginscript', plugins_url( '/assets/myscript.js', __FILE__) );
    }
  }

  if ( class_exists( 'PoemhdPlugin' ) ) {
    $poemhdPlugin = new PoemhdPlugin();
    $poemhdPlugin->register();
  }

//plugin_activation

  register_activation_hook(__FILE__ , array( $poemhdPlugin, 'activate' ) );

//plugin_deactivation

  register_deactivation_hook(__FILE__ , array( $poemhdPlugin, 'deactivate' ) );

}
//uninstall
