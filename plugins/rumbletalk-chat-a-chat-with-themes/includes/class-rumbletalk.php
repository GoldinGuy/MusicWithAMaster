<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    RumbleTalk
 * @subpackage RumbleTalk/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    RumbleTalk
 * @subpackage RumbleTalk/includes
 * @author     Your Name <email@example.com>
 */
class RumbleTalk
{
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * @var string  URL to the CDN base address
     */
    public static $cdn = 'https://d1pfint8izqszg.cloudfront.net/';

    /**
     * @var RumbleTalk_Admin
     */
    protected static $plugin_admin;

    /**
     * @var RumbleTalk_Public
     */
    protected static $plugin_public;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('RUMBLETALK_VERSION')) {
            $this->version = RUMBLETALK_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'rumbletalk';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - RumbleTalk_Loader. Orchestrates the hooks of the plugin.
     * - RumbleTalk_i18n. Defines internationalization functionality.
     * - RumbleTalk_Admin. Defines all hooks for the admin area.
     * - RumbleTalk_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-rumbletalk-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-rumbletalk-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-rumbletalk-public.php';

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the RumbleTalk_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new RumbleTalk_i18n();

        add_action('plugins_loaded', array(&$plugin_i18n, 'load_plugin_textdomain'));
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        self::$plugin_admin = new RumbleTalk_Admin($this->get_plugin_name(), $this->get_version());
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        self::$plugin_public = new RumbleTalk_Public($this->get_plugin_name(), $this->get_version());
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     * @param int $size - the user supplied size
     * @param bool $force - if set to true, will return default value on invalid user dimensions
     * @return string
     */
    private static function parseDimension($size, $force = false)
    {
        $matches = array();
        if (preg_match('/^(\d+)(%|px)?$/', $size, $matches)) {
            return $matches[1] . (isset($matches[2]) ? $matches[2] : 'px');
        } else {
            return $force
                ? '500px'
                : '';
        }
    }

    /**
     * fetches the login name from a user based on RumbleTalk's attribute names
     * @param WP_User $user - the user
     * @param string $key - the key set in the chat settings
     * @return string|array - the attribute name\s in "WP_User" class
     */
    private static function getLoginName($user, $key)
    {
        if ($key == 'nickname') {
            $loginName = get_the_author_meta('nickname', $user->id);
        } elseif ($key) {
            $key = explode(' ', $key);
            if (count($key) == 2) {
                $loginName = trim($user->{$key[0]} . ' ' . $user->{$key[1]});
            } else {
                $loginName = $user->{$key[0]};
            }
        }

        return @$loginName
            ? $loginName
            : $user->display_name;
    }

    /**
     * overwrites attributes of the chat options from the [shortcode]
     * @param array $chatOptions - the saved chat options
     * @param array $attr - the chat options set in the [shortcode]
     */
    private static function overwriteAttributes(&$chatOptions, $attr)
    {
        foreach (array('height', 'width', 'floating', 'members') as $value) {
            if (isset($attr[$value])) {
                switch ($attr[$value]) {
                    case 'true':
                        $attr[$value] = true;
                        break;

                    case 'false':
                        $attr[$value] = false;
                        break;
                }

                # [shortcode] turns attributes to lowercase
                if ($value == 'members') {
                    $chatOptions['membersOnly'] = $attr['members'];
                }
                $chatOptions[$value] = $attr[$value];
            }
        }
    }

    /**
     * @param array $attr
     * @return string
     */
    public static function embed($attr = array())
    {
        $issueMessage = 'Your RumbleTalk plug-in is not connected to your RumbleTalk account. ' .
            'Go to the plug-in\'s settings page to connect your account.';

        $chats = RumbleTalk_Admin::getChats();

        if (!$chats) {
            if (!get_option('rumbletalk_chat_token_key') || !get_option('rumbletalk_chat_token_secret')) {
                return $issueMessage;
            }

            self::$plugin_admin->ajaxHandler->setToken(
                get_option('rumbletalk_chat_token_key'),
                get_option('rumbletalk_chat_token_secret')
            );

            if (!self::$plugin_admin->ajaxHandler->updateAccessToken()) {
                return $issueMessage;
            }

            $chats = self::$plugin_admin->ajaxHandler->reloadChats(true);
        }

        # get the chat's hash
        $hash = isset($attr['hash'])
            ? $attr['hash']
            : null;

        if (!$hash && is_array($chats)) {
            $hash = current(array_keys($chats));
        }

        if (empty($hash)) {
            return $issueMessage;
        }

        # default options
        $chatOptions = array(
            'height' => '',
            'width' => '',
            'floating' => false,
            'membersOnly' => false
        );

        if (isset($chats[$hash])) {
            $chatOptions = $chats[$hash];

            // legacy support
        } elseif (get_option('rumbletalk_chat_member')) {
            $chatOptions['membersOnly'] = true;
        }
        self::overwriteAttributes($chatOptions, $attr);

        $str = '';
        if ($chatOptions['membersOnly'] && $attr !== false) {
            $current_user = wp_get_current_user();
            if ($current_user->display_name) {
                $loginInfo = array(
                    'username' => self::getLoginName($current_user, $chatOptions['loginName']),
                    'forceLogin' => true,
                    'hash' => $hash
                );
                $loginInfo = apply_filters('rumbletalk_login_args' , $loginInfo);
                $loginInfo = json_encode($loginInfo);

                $str = "<script>rtmq('login', {$loginInfo});</script>";
            }
        }

        $url = "https://www.rumbletalk.com/client/?{$hash}";
        if ($chatOptions['floating']) {
            $str .= '<div class="rumbletalk-handle">';
            $url .= '&1';
        } else {
            $width = self::parseDimension($chatOptions['width']);
            if ($width) {
                $width = "max-width: {$width};";
            }
            $height = 'height: ' . self::parseDimension($chatOptions['height'], true) . ';';
            $str .= '<div class="rumbletalk-handle" style="' . $height . $width . '">';
        }

        $divId = 'rt-' . md5($hash);
        $str .= '<div id="' . $divId . '"></div>';
        $str .= '<script src="' . $url . '"></script>';
        $str .= '</div>';

        return $str;
    }

}
