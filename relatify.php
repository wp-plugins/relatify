<?php
/*
  Plugin Name: Relatify - Most Effective Related Contents
  Plugin URI: http://relatify.co/go/main
  Description: The best & smartest related contents/posts plugin for WordPress. The only Related Contents Framework in the industry.
  Version: 0.9.5
  Author: WP Dev Team
  Author URI: http://wpdevteam.com
  Text Domain: relatify
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( !defined( 'ABSPATH' ) ) exit;  // if direct access

if ( !class_exists( "Relatify" ) ) {

    class Relatify {

        const REVIEWLINK = 'http://relatify.co/go/OrgReview';
        const SUPPORTLINK = 'http://relatify.co/go/OrgSupport';
        const AUTHORURL = 'http://relatify.co/go/main';

        //-----------------------------------------
        // Options
        //-----------------------------------------
        var $options = 'RelatifyContent';
        //-----------------------------------------
        // Paths
        //-----------------------------------------
        var $pluginURL = '';
        var $pluginPath = '';
        //-----------------------------------------
        // Options page
        //-----------------------------------------
        var $optionsPageTitle = '';
        var $optionsMenuTitle = '';
        var $themes = array();

        public function __construct() {
            $this->pluginURL = plugin_dir_url( __FILE__ );
            $this->pluginPath = plugin_dir_path( __FILE__ );
            $this->optionsPageTitle = __( 'Relatify - Ultimate Related Contents Plugin', 'relatify' );
            $this->optionsMenuTitle = __( 'Relatify', 'relatify' );

            add_filter( 'the_content', array($this, 'relatify_show_content') );
            add_shortcode( 'related_content', array($this, 'relatify_content_display') );
            add_action( 'admin_menu', array($this, 'relatify_admin_menu') );
            add_action( 'wp_enqueue_scripts', array($this, 'relatify_scripts') );
            add_filter( 'the_posts', array($this, 'relatify_custom_the_post') );
            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'relatify_action_links' ) );
            add_action( 'init', array($this, 'plugin_init') );
            if ( is_admin() ) {
                add_action( 'admin_footer', array($this, 'add_footer_script') );
            }

            add_action( 'admin_enqueue_scripts', array( $this, 'relatify_admin_script' ) );

        }

        public function relatify_admin_script() {
            wp_enqueue_media();
            wp_register_style( 'custom_relatify_admin_css', plugins_url( '/css/admin.css', __FILE__ ), false, '1.0.0' );
            wp_enqueue_style( 'custom_relatify_admin_css' );
            wp_enqueue_script( 'admin-js', plugins_url( '/js/admin.js', __FILE__ ), array('jquery'), '1.0.0', true );
        }

        public function plugin_init() {
            $temp = array();
            $temp['name'] = $temp['src'] = array();
            if ( $handle = opendir( $this->pluginPath . 'themes/' ) ) {
                while ( false !== ($entry = readdir( $handle )) ) {
                    if ( $entry != "." && $entry != ".." && pathinfo( $entry, PATHINFO_EXTENSION ) == 'php' ) {
                        $temp['src'] = $entry;
                        $entry = explode( '-', $entry );
                        $entry = str_replace( '_', ' ', $entry[0] );
                        $temp['name'] = $entry;
                        array_push( $this->themes, $temp );
                    }
                }
                closedir( $handle );
            }
            $upload_dir = wp_upload_dir();
            if ( is_dir( $upload_dir['basedir'] . '/src_theme/' ) ) {
                if ( $handle = opendir( $upload_dir['basedir'] . '/src_theme/' ) ) {
                    while ( false !== ($entry = readdir( $handle )) ) {
                        if ( $entry != "." && $entry != ".." && pathinfo( $entry, PATHINFO_EXTENSION ) == 'php' ) {
                            $temp['src'] = $entry;
                            $entry = explode( '-', $entry );
                            $entry = str_replace( '_', ' ', $entry[0] );
                            $temp['name'] = $entry;
                            array_push( $this->themes, $temp );
                        }
                    }
                    closedir( $handle );
                }
            }
        }

        public function relatify_scripts() {
            wp_enqueue_script( 'underscore-js', '//documentcloud.github.io/underscore/underscore-min.js', array('jquery'), '1.0.0', true );
            wp_enqueue_script( 'relate-script', plugins_url( '/js/plugin.js', __FILE__ ), array('jquery'), '1.0.0', true );
        }

        public function relatify_show_content( $content ) {
            if ( is_single() ) {
                $options = get_option( $this->options, true );
                if ( $options['auto_inject'] == 'yes' ) {
                    if ( $options['content_pos'] == 'top' ) {
                        return do_shortcode( '[related_content title="' . $options['title'] . '" number="' . $options['number'] . '"]' ) . $content;
                    } else {
                        return $content . do_shortcode( '[related_content title="' . $options['title'] . '" number="' . $options['number'] . '"]' );
                    }
                }
                return $content;
            }
            return $content;
        }
        
        public function relatify_action_links( $links ){
            $mylinks = array(
                '<a href="' . admin_url( 'admin.php?page=relatify.php' ) . '">Settings</a>',
                );
            return array_merge( $links, $mylinks );
        }

        public function optionsPage() {
            if ( isset( $_POST['src_nonce_box_nonce'] ) && wp_verify_nonce( $_POST['src_nonce_box_nonce'], 'src_nonce_box' ) ) {
                if ( isset( $_POST['update_options'] ) ) {
                    if ( get_magic_quotes_gpc() ) {
                        $_POST = array_map( 'stripslashes_deep', $_POST );
                    }

                    $options = $_POST['options'];
                    if ( update_option( $this->options, $options ) ) {
                        do_action( 'src_option_saved' );
                    }
                    wp_redirect( admin_url( 'admin.php?page=relatify.php&msg=' . __( 'Options+saved.', 'relatify' ) ) );
                }
            }

            if ( isset( $_POST['src_upload_nonce_box_nonce'] ) && wp_verify_nonce( $_POST['src_upload_nonce_box_nonce'], 'src_upload_nonce_box' ) ) {
                if ( isset( $_POST['upload_theme'] ) ) {

                    if ( !isset( $_FILES['src_pro_theme'] ) || $_FILES['src_pro_theme']['name'] == '' ) {
                        wp_redirect( admin_url( 'admin.php?page=relatify.php&msg=' . __( 'Please+select+a+file.', 'relatify' ) ) );
                    } else {
                        $allow_types = array
                        (
                            'application/zip',
                            'application/octet-stream',
                            );
                        if ( !in_array( $_FILES['src_pro_theme']['type'], $allow_types ) ) {
                            wp_redirect( admin_url( 'admin.php?page=relatify.php&msg=' . __( 'Invalide+file+type.', 'relatify' ) ) );
                        }
                        $upload_dir = wp_upload_dir();
                        if ( !is_dir( $upload_dir['basedir'] . '/src_theme' ) ) {
                            mkdir( $upload_dir['basedir'] . '/src_theme' );
                        }

                        //if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

                        $target_dir = $upload_dir['basedir'] . '/src_theme/';
                        $target_file = $target_dir . basename( $_FILES["src_pro_theme"]["name"] );
                        if ( move_uploaded_file( $_FILES["src_pro_theme"]["tmp_name"], $target_file ) ) {
                            $zip = new ZipArchive;
                            $zip->open( $target_file );
                            $zip->extractTo( $target_dir . '/' );
                            $zip->close();
                            unlink( $target_file );
                            wp_redirect( admin_url( 'admin.php?page=relatify.php&msg=' . __( 'The+template+is+uploaded.', 'relatify' ) ) );
                        } else {
                            wp_redirect( admin_url( 'admin.php?page=relatify.php&msg=' . __( 'There+is+an+issue+with+the+upload+process.+Please+contact+support.', 'relatify' ) ) );
                        }
                    }
                }
            }

            $options = get_option( $this->options, true );

            if ( isset( $_REQUEST['msg'] ) && !empty( $_REQUEST['msg'] ) ) {
                ?>
                <div class="updated">
                    <p><strong><?php echo str_replace( '+', ' ', $_REQUEST['msg'] ); ?></strong></p>
                </div>
                <?php
            }

            // Display options form
            ?>
            <div class="wrap">
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <form method="post" action="<?php echo admin_url( 'admin.php?page=relatify.php&noheader=true' ); ?>" class="relatify_form">
                                <h2><?php echo $this->optionsPageTitle; ?></h2>
                                <p>
                                    <img src="<?php echo plugins_url( 'images/banner6.png', __FILE__ ) ?>" style="width: 100%;">
                                </p>
                                <div class="postbox">
                                    <h3 class="hndle"><span><?php _e( 'General Settings', 'relatify' ) ?></span></h3>
                                    <div class="inside">
                                        <table class="form-table">
                                            <tr valign="top">
                                                <th style="width: 40%" scope="row"><?php _e( 'Do you want to auto inject the related content?' ) ?></th>
                                                <td>
                                                    <label style="margin-right: 10px;">
                                                        <input <?php echo isset( $options['auto_inject'] ) && $options['auto_inject'] == 'yes' ? 'checked' : '' ?> type="radio" name="options[auto_inject]" value="yes"><?php _e( 'Yes', 'relatify' ) ?>
                                                    </label>
                                                    <label style="margin-right: 10px;">
                                                        <input <?php echo isset( $options['auto_inject'] ) && $options['auto_inject'] == 'no' ? 'checked' : (!isset( $options['auto_inject'] ) ? 'checked' : '' ) ?> type="radio" name="options[auto_inject]" value="no"><?php _e( 'No', 'relatify' ) ?>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="autoExtra">
                                                <th scope="row"><?php _e( 'Title', 'relatify' ) ?></th>
                                                <td>
                                                    <input type="text" name="options[title]" value="<?php echo isset( $options['title'] ) ? $options['title'] : '' ?>" style="width:50%;"/>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="autoExtra">
                                                <th scope="row"><?php _e( 'Number of posts', 'relatify' ) ?></th>
                                                <td>
                                                    <input type="text" name="options[number]" value="<?php echo isset( $options['number'] ) ? $options['number'] : '' ?>" style="width:50%;"/>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="autoExtra">
                                                <th scope="row"><?php _e( 'Where to show', 'relatify' ) ?></th>
                                                <td>
                                                    <label style="margin-right: 10px;">
                                                        <input <?php echo isset( $options['content_pos'] ) && $options['content_pos'] == 'top' ? 'checked' : '' ?> type="radio" name="options[content_pos]" value="top"><?php _e( 'Top of the content', 'relatify' ) ?>
                                                    </label>
                                                    <label style="margin-right: 10px;">
                                                        <input <?php echo isset( $options['content_pos'] ) && $options['content_pos'] == 'bottom' ? 'checked' : (!isset( $options['content_pos'] ) ? 'checked' : '' ) ?> type="radio" name="options[content_pos]" value="bottom"><?php _e( 'Bottom of the content', 'relatify' ) ?>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="autoExtra">
                                                <th scope="row"><?php _e( 'Do you want to show image in related post list?', 'relatify' ); ?></th>
                                                <td>
                                                    <label style="margin-right: 10px;">
                                                        <input <?php echo isset( $options['show_image'] ) && $options['show_image'] == 'yes' ? 'checked' : '' ?> type="radio" name="options[show_image]" onclick="show_image_type_row()"  value="yes" /><?php _e( 'Yes', 'relatify' ) ?>
                                                    </label>
                                                    <label style="margin-right: 10px;">
                                                        <input <?php echo isset( $options['show_image'] ) && $options['show_image'] == 'no' ? 'checked' : (!isset( $options['show_image'] ) ? 'checked' : '' ) ?> type="radio" name="options[show_image]" onclick="hide_image_type_row()" value="no" /><?php _e( 'No', 'relatify' ) ?>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr id="image_size" valign="top" class="autoExtra" <?php echo isset( $options['show_image'] ) && $options['show_image'] == 'yes' ? '' : 'style="display: none;"' ?>>
                                                <th scope="row">
                                                    Image Height & Width?
                                                </th>
                                                <th scope="row">
                                                    <div style="margin-left: 10px;">
                                                        <label style="margin-right: 10px;">Height: </label> <input type="text" name="options[image_height]" value="<?php echo isset( $options['image_height'] ) && $options['image_height'] != '' ? $options['image_height'] : '' ?>" size="10" />
                                                        <label style="margin-right: 10px;">Width: </label> <input type="text" name="options[image_width]" value="<?php echo isset( $options['image_width'] ) && $options['image_width'] != '' ? $options['image_width'] : '' ?>"  size="10" />
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr id="use_image" valign="top" class="autoExtra" <?php echo isset( $options['show_image'] ) && $options['show_image'] == 'yes' ? '' : 'style="display: none;"' ?>>
                                                <th scope="row">
                                                    What type of image do you want to show?
                                                </th>
                                                <th scope="row">
                                                    <div style="margin-left: 10px;">
                                                        <label id="featured_img" style="margin-right: 10px;">Featured image:</label> <input <?php echo isset( $options['image_type'] ) && $options['image_type'] == 'featured' ? 'checked' : (!isset( $options['image_type'] ) ? 'checked' : '' ) ?> type="radio" name="options[image_type]" onclick="hide_custom_field_row()" value="featured"/><br><label style="font-weight: 300;">If there is no featured images,<br> first image will be used, otherwise a default image.)</label>
                                                        <br><br>
                                                        <label id="custom_img" style="margin-right: 10px;">Custom image field:</label> <input <?php echo isset( $options['image_type'] ) && $options['image_type'] == 'custom' ? 'checked' : '' ?> type="radio" name="options[image_type]" onclick="show_custom_field_row()" value="custom"/><br><label style="font-weight: 300;">If there is no image in custom field,<br> first image will be used, otherwise a default image.</label>
                                                    </div>
                                                </th>
                                            </tr>
                                            <tr id="custom_image" valign="top" class="autoExtra" <?php echo !isset( $options['image_type'] ) || $options['image_type'] == 'featured' ? 'style="display: none;"' : '' ?>>
                                                <th scope="row"><label id="image_custom_lable">Custom image field:</label></th>
                                                <td><input type="text" id="image_custom_field" name="options[custom_field]" value="<?php echo isset( $options['custom_field'] ) ? $options['custom_field'] : '' ?>" size="20" /></td>
                                            </tr>
                                            <tr>
                                                <th><?php _e( 'Exclude Categories?', 'relatify' ) ?></th>
                                                <td>
                                                    <button class="button button-secondary button-long toggle-items"><?php _e( 'Load all categories' ) ?></button>
                                                    <br><br>
                                                    <div class="all_items">
                                                        <?php $categories = $this->get_all_categories(); foreach( $categories as $category ) { ?>
                                                        <label class="ex_cat">
                                                            <input <?php echo isset( $options['exclude_categories'] ) && in_array( $category->term_id, $options['exclude_categories'] ) ? 'checked' : '' ?> type="checkbox" name="options[exclude_categories][]" value="<?php echo $category->term_id ?>">
                                                            <?php echo $category->name ?>
                                                        </label>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php _e( 'Exclude Tags?', 'relatify' ) ?></th>
                                                <td>
                                                    <button class="button button-secondary button-long toggle-items"><?php _e( 'Load all tags' ) ?></button>
                                                    <br><br>
                                                    <div class="all_items">
                                                        <?php $tags = $this->get_all_tags(); foreach( $tags as $tag ) { ?>
                                                        <label class="ex_cat">
                                                            <input <?php echo isset( $options['exclude_tags'] ) && in_array( $tag->term_id, $options['exclude_tags'] ) ? 'checked' : '' ?> type="checkbox" name="options[exclude_tags][]" value="<?php echo $tag->term_id ?>">
                                                            <?php echo $tag->name ?>
                                                        </label>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr valign="top" class="autoExtra">
                                                <th scope="row"><?php _e( 'Select a theme', 'relatify' ) ?></th>
                                                <td>
                                                    <select id="rel_theme" name="options[src]" style="width: 50%;">
                                                        <?php sort( $this->themes ); ?>
                                                        <?php foreach ( $this->themes as $theme ) { ?>
                                                        <option <?php echo isset( $options['src'] ) && $options['src'] == $theme['src'] ? 'selected="selected"' : ''; ?> value="<?php echo $theme['src'] ?>"><?php echo $theme['name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <br><br>
                                                    <?php
                                                    if( ! isset( $options['src'] ) ){
                                                        $img_url = $this->pluginURL . 'images/demo/Template_1-simple.php.png';
                                                    }else{
                                                        $img_url = $this->pluginURL . 'images/demo/' . $options['src'] . '.png';
                                                    }
                                                    $upload_dir = wp_upload_dir();
                                                    if( ! file_exists( plugin_dir_path( __FILE__ ) . 'images/demo/' . $options['src'] . '.png' ) ){
                                                        $img_url = $upload_dir['baseurl'] . '/src_theme/' . $options['src'] . '.png';
                                                    }

                                                    ?>
                                                    <img style="width: 100%" src="<?php echo $img_url; ?>" class="theme_demo">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php _e( 'Give Relatify some Love?', 'relatify' ) ?></th>
                                                <td>
                                                    <label>
                                                        <input <?php echo ! isset( $options['love'] ) || $options['love'] == 1 ? 'checked' : '' ?> type="radio" value="1" name="options[love]">
                                                        <?php _e( 'Yes', 'relatify' ) ?>
                                                    </label>
                                                    <label>
                                                        <input <?php echo isset( $options['love'] ) && $options['love'] == 0 ? 'checked' : '' ?> type="radio" value="0" name="options[love]">
                                                        <?php _e( 'No', 'relatify' ) ?>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php _e( 'Default Image (this image will be shown if there is no featured image and custom field data)', 'relatify' ) ?></th>
                                                <td>
                                                    <input type="button" name="gallery_meta_upload" class="gallery_meta_upload button button-secondary button-long" value="Upload">
                                                    <input class="default_image_src" type="hidden" name="options[default_image_src]" value="<?php echo isset( $options['default_image_src'] ) ? $options['default_image_src'] : '' ?>">
                                                    <input class="default_image_id" type="hidden" name="options[default_image_id]" value="<?php echo isset( $options['default_image_id'] ) ? $options['default_image_id'] : '' ?>"><br><br>
                                                    <?php if (!empty($options['default_image_src'])){
                                                        echo '<img src="'. $options['default_image_src'].'" style="width: 200px">';
                                                    }else{
                                                        echo 'No Default Image is set yet!';
                                                    }?>
                                                </td>
                                            </tr>
                                        </table>
                                        <?php wp_nonce_field( 'src_nonce_box', 'src_nonce_box_nonce' ); ?>
                                        <p class="submit">
                                            <input type="submit" class="button-primary button-semi-long" name="update_options" value="<?php _e( 'Update Settings', 'relatify' ) ?>"/>
                                        </p>
                                    </div>
                                </div>
                            </form>
                            <div class="postbox">
                                <h3 class="hndle"><span><?php _e( 'Upload New Theme', 'relatify' ) ?></span></h3>
                                <div class="inside">
                                    <form action="<?php echo admin_url( 'admin.php?page=relatify.php&noheader=true' ) ?>" method="post" enctype="multipart/form-data" class="relatify_form">
                                        <table class="form-table">
                                            <tr>
                                                <th valign="top"><?php _e( 'Upload zip file of new theme', 'relatify' ) ?></th>
                                                <td valign="top">
                                                    <input type="file" name="src_pro_theme">
                                                </td>
                                            </tr>
                                        </table>
                                        <?php wp_nonce_field( 'src_upload_nonce_box', 'src_upload_nonce_box_nonce' ); ?>
                                        <p>
                                            <input name="upload_theme" type="submit" class="button button-primary button-semi-long" value="<?php _e( 'Upload', 'relatify' ) ?>">
                                        </p>
                                    </form>
                                </div>
                            </div>

                            <div class="postbox">
                                <h3 class="hndle"><span><?php _e( 'Download Premium Themes', 'relatify' ) ?></span></h3>
                                <div class="inside">
                                    <a href="http://relatify.co/go/Buy-Modern-Pro" title="Modern Pro" target="_blank"><img src="<?php echo plugins_url( 'images/premium_demo_one.png', __FILE__ ) ?>" style="width: 48%; padding: 1%;"/></a><a href="http://relatify.co/go/Buy-Elegant-Pro" target="_blank"><img src="<?php echo plugins_url( 'images/premium_demo_two.png', __FILE__ ) ?>" style="width: 48%; padding: 1%;" /></a>
                                </div>
                            </div>

                        </div>
                        <div class="postbox-container" id="postbox-container-1">
                            <div class="relatify_sidebar">

                                <div class="relatify_panel postbox ">
                                    <div class="inside">
                                        <center>
                                            <img src="http://relatify.co/images/relatify_logo.png" style="width: 100%; padding-top: 5px;"/>
                                            <br />
                                            <b>Relatify</b> by <b><a href="<?php echo self::AUTHORURL; ?>" title="WPDevTeam" target="_blank"><?php _e( 'WPDevTeam', 'relatify' ) ?></a></b><br />
                                            <a href="<?php echo self::REVIEWLINK; ?>" title="Rate Relatify" target="_blank"><?php _e( 'Rate & Review', 'relatify' ) ?></a> |
                                            <a href="<?php echo self::SUPPORTLINK ?>" title="Support For Relatify" target="_blank"><?php _e( 'Get Support', 'relatify' ) ?></a>
                                        </center>
                                    </div>
                                </div>

                                <div class="relatify_panel postbox ">
                                    <h3 class="hndle"><span><?php _e( 'Support' ) ?></span></h3>
                                    <div class="inside">
                                        <?php _e( 'Please email to <a href="mailto:info@relatify.co">info@relatify.co</a> for any type of query, support and feedback.' ) ?>
                                    </div>
                                </div>

                                <div class="relatify_panel postbox ">
                                    <h3 class="hndle"><span><?php _e( 'Connect with us', 'relatify' ) ?></span></h3>
                                    <div class="inside">
                                        <ul class="rel_social">
                                            <li><a class="fb" href="https://www.facebook.com/pages/Relatify/355786654626599" target="_blank">facebook</a></li>
                                            <li><a class="twt" href="https://twitter.com/RelatifyWP" target="_blank">twitter</a></li>
                                            <li><a class="gplus" href="https://plus.google.com/b/114328735736896910748/114328735736896910748" target="_blank">google-plus</a></li>
                                            <li><a class="mail" href="mailto:info@relatify.co" target="_blank">mail</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="relatify_panel postbox ">
                                    <h3 class="hndle"><span><?php _e( 'More Premium Themes' ) ?></span></h3>
                                    <div class="inside">
                                        <img src="http://relatify.co/images/premium_themes.png" class="sidebar_promo"/>
                                    </div>
                                </div>

                                <div class="relatify_panel postbox ">
                                    <h3 class="hndle"><span><?php _e( 'Subscribe to our newsletter' ) ?></span></h3>
                                    <div class="inside">
                                        <!-- Begin MailChimp Signup Form -->
                                        <link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
                                        <style type="text/css">
                                            #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
                                            /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                                            We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                                        </style>
                                        <div id="mc_embed_signup">
                                            <form action="//relatify.us10.list-manage.com/subscribe/post?u=c3d9e29480593f3bd38a3968f&amp;id=a0727e4ba5" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate relatify_form" target="_blank" novalidate>
                                                <div id="mc_embed_signup_scroll">
                                                    <div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
                                                    <div class="mc-field-group">
                                                        <label for="mce-EMAIL">Email Address  <span class="asterisk">*</span>
                                                        </label>
                                                        <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                                                    </div>
                                                    <div class="mc-field-group">
                                                        <label for="mce-FNAME">First Name </label>
                                                        <input type="text" value="" name="FNAME" class="" id="mce-FNAME">
                                                    </div>
                                                    <div class="mc-field-group">
                                                        <label for="mce-LNAME">Last Name </label>
                                                        <input type="text" value="" name="LNAME" class="" id="mce-LNAME">
                                                    </div>
                                                    <div id="mce-responses" class="clear">
                                                        <div class="response" id="mce-error-response" style="display:none"></div>
                                                        <div class="response" id="mce-success-response" style="display:none"></div>
                                                    </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                                    <div style="position: absolute; left: -5000px;"><input type="text" name="b_c3d9e29480593f3bd38a3968f_a0727e4ba5" tabindex="-1" value=""></div>
                                                    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button button-long"></div>
                                                </div>
                                            </form>
                                        </div>
                                        <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                                        <!--End mc_embed_signup-->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        public function relatify_custom_the_post( $posts ) {
            $options = get_option( $this->options, true );
            for ( $i = 0; $i < count( $posts ); $i++ ) {
                if ( isset( $options['show_image'] ) && $options['show_image'] == 'yes' ) {
                    if ( isset( $options['image_type'] ) && $options['image_type'] == 'featured' ) {
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $posts[$i]->ID ), 'full' );
                        $image = $image[0];
                        if ( $image == "" ) {
                            if(!empty($options['default_image_id'])){
                                $image =  wp_get_attachment_image_src( $options['default_image_id'], 'full' );
                                $image = $image[0];
                            }else{
                                $image = $this->pluginURL . 'images/no_image.png';
                            }
                        }
                    } else {
                        $image = get_post_meta( $posts[$i]->ID, $options['custom_field'], true );
                        if ( $image == "" ) {
                            $image = $this->pluginURL . 'images/no_image.png';
                        }
                    }
                } else {
                    $image = '';
                }
                $permalink = get_permalink( $posts[$i]->ID );
                $posts[$i]->permalink = $permalink;
                $posts[$i]->image = $image;
                $posts[$i]->width = $options['image_width'];
                $posts[$i]->height = $options['image_height'];
            }
            //var_dump($options);
            return $posts;
        }

        //Generate related content from shortcode
        public function relatify_content_generate( $title = "", $number = 4, $widget = false ) {
            global $post;
            if ( $title == "" ) {
                $title = __( 'Simple Related Content', 'relatify' );
            } elseif ( $title == "n/a" ) {
                $title = '';
            }
            $related_content_title = $title;
            $related_content_number = $number;

            $post_id = $post->ID;

            $options = get_option( $this->options, true );

            $options['exclude_categories'] = isset( $options['exclude_categories'] ) ? $options['exclude_categories'] : array();
            $options['exclude_tags'] = isset( $options['exclude_tags'] ) ? $options['exclude_tags'] : array();

            $excluded_cat = '-' . implode( ',-',  $options['exclude_categories'] );

            if ( has_tag() ) {
                $tags = wp_get_post_tags( $post_id );
                $tagIDs = array();
                if ( $tags ) {
                    $tagcount = count( $tags );
                    for ( $i = 0; $i < $tagcount; $i++ ) {
                        $tagIDs[$i] = $tags[$i]->term_id;
                    }

                    $args = array('tag__in' => $tagIDs, 'post__not_in' => array($post_id), 'showposts' => $related_content_number, 'ignore_sticky_posts' => 1, 'cat' => $excluded_cat, 'tag__not_in'=> $options['exclude_tags'] );
                    $my_query = new WP_Query( $args );
                }
            } else {
                $cat = get_the_category();
                $category = $cat[0]->cat_ID;
                $args = array('cat' => $category, 'posts_per_page' => $related_content_number, 'post__not_in' => array($post_id), 'cat' => $excluded_cat, 'tag__not_in'=> $options['exclude_tags'] );
                $my_query = new WP_Query( $args );

                wp_reset_query();
            }

            if ( $widget ) {
                $html = '<div class="relatify_widget">';
                $html .= '<ul>';
                foreach ( $my_query->posts as $post ) {
                    $html .= '<li>';
                    $html .= '<a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '<a/>';
                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</div>';
                return $html;
            } else {
                wp_localize_script( 'relate-script', 'rc_object', array(
                    'posts' => $my_query->posts,
                    'title' => $related_content_title,
                    'love' => isset( $options['love'] ) ? $options['love'] : 1,
                    'love_url' => 'http://relatify.co'
                    ) );
                wp_enqueue_script( 'relate-script' );
                ob_start();

                $upload_dir = wp_upload_dir();

                if ( file_exists( $this->pluginPath . 'themes/' . $options['src'] ) ) {
                    require $this->pluginPath . 'themes/' . $options['src'];
                } elseif ( file_exists( $upload_dir['basedir'] . '/src_theme/' . $options['src'] ) ) {
                    require $upload_dir['basedir'] . '/src_theme/' . $options['src'];
                }
                $output = ob_get_contents();
                ob_end_clean();

                return apply_filters( 'relatify_content_generate', $output, $my_query->posts, $related_content_title );
            }
        }

        //Shortcode for related content
        public function relatify_content_display( $atts ) {
            $atts = shortcode_atts(
                array(
                'title' => __( "Simple Related Content", 'relatify' ), //post title
                'number' => 4, //post number
                'widget' => false
                ), $atts );

            $html = '';
            $html .= $this->relatify_content_generate( $atts['title'], $atts['number'], $atts['widget'] );

            return apply_filters( 'relatify_content_html', $html, $atts['title'], $atts['number'], $atts['widget'] );
        }

        public function relatify_admin_menu() {
            add_menu_page( $this->optionsPageTitle, $this->optionsMenuTitle, 'manage_options', basename( __FILE__ ), array($this, 'optionsPage') );
        }

        public function add_footer_script() {
            $options = get_option( $this->options, true );
            $upload_dir = wp_upload_dir();
            ?>
            <script type="text/javascript">
                function show_image_type_row() {
                    document.getElementById('use_image').style.display = '';
                    document.getElementById('image_size').style.display = '';
                }
                function hide_image_type_row() {
                    document.getElementById('use_image').style.display = 'none';
                    document.getElementById('custom_image').style.display = 'none';
                    document.getElementById('image_size').style.display = 'none';
                }
                function show_custom_field_row() {
                    document.getElementById('custom_image').style.display = '';
                }
                function hide_custom_field_row() {
                    document.getElementById('custom_image').style.display = 'none';
                }
                jQuery(function ($) {
                    $('#rel_theme').change(function () {

                        var _url = '<?php echo plugins_url( '/images/demo/', __FILE__ ) ?>';
                        var val = $(this).val();


                        $.ajax({
                            url: _url + $(this).val() + '.png',
                            type:'HEAD',
                            error: function()
                            {
                                $('.theme_demo').attr('src', '<?php echo $upload_dir['baseurl'] ?>/src_theme/' + val + '.png');
                            },
                            success: function()
                            {
                                $('.theme_demo').attr('src', _url + val + '.png');
                            }
                        });

                    });
                });
            </script>
            <?php
        }

        public function get_all_tags() {
            return get_tags();
        }

        public function get_all_categories() {
            return get_categories();
        }

    }


    $relatify_class_instance = new Relatify();

    require_once( $relatify_class_instance->pluginPath . 'widgets.php' );
}
//Class closed