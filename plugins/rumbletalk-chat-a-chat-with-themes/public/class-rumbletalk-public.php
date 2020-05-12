<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    RumbleTalk
 * @subpackage RumbleTalk/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    RumbleTalk
 * @subpackage RumbleTalk/public
 * @author     Your Name <email@example.com>
 */
class RumbleTalk_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_shortcode('rumbletalk-chat', array('RumbleTalk', 'embed'));
        add_action('wp_head', array(&$this, 'hook_javascript'));
    }

    /**
     * add the Login SDK script to the <head>
     */
    public function hook_javascript()
    {
        $code = get_option('rumbletalk_chat_chats');
        $current_user = wp_get_current_user();

        if (!empty($code) && !empty($current_user->display_name)) {
            ?>
            <script>
            (function (g, v, w, d, s, a, b) {
            w['rumbleTalkMessageQueueName'] = g;
            w[g]=w[g]||function(){(w[g].q = w[g].q || []).push(arguments)};
            a = d.createElement(s);
            b = d.getElementsByTagName(s)[0];
            a.async = 1;
            a.src = '<?= RumbleTalk::$cdn ?>api/' + v + '/sdk.js';
            b.parentNode.insertBefore(a, b)
            })('rtmq', 'v0.41', window, document, 'script');
            </script>
            <?php
        }
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

//        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/rumbletalk-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

//        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/rumbletalk-public.js', array('jquery'), $this->version, false);

    }

}
