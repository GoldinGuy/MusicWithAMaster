<?php

/**
 * Plugin Name: RumbleTalk Chat
 * Plugin URI: https://wordpress.org/plugins/rumbletalk-chat-a-chat-with-themes/
 * Description: Group chat room for WordPress and BuddyPress websites. Use one or many advanced stylish chat rooms for your community.
 * Tags: Group chat, BuddyPress
 * Version: 5.6.9
 * Author: RumbleTalk Ltd
 * Author URI: https://www.rumbletalk.com
 * License: GPL2
 *
 * Copyright 2012-2017 RumbleTalk Ltd (email : support@rumbletalk.com)
 *
 * This program is free trial software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Update this as you release new versions.
 */
define('RUMBLETALK_VERSION', '5.6.9');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rumbletalk-activator.php
 */
function activate_rumbletalk()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-rumbletalk-activator.php';
    RumbleTalk_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rumbletalk-deactivator.php
 */
function deactivate_rumbletalk()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-rumbletalk-activator.php';
    RumbleTalk_Activator::deactivate();
}

register_activation_hook(__FILE__, 'activate_rumbletalk');
register_deactivation_hook(__FILE__, 'deactivate_rumbletalk');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-rumbletalk.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rumbletalk()
{
    new RumbleTalk();
}

run_rumbletalk();
