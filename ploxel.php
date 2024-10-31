<?php
/*
Plugin Name: Ticketmeo
Plugin URI: http://wordpress.org/plugins/ploxel/
Description: Sell tickets online your WordPress site with Ticketmeo, see https://www.ticketmeo.com/sell-tickets-on-wordpress for more information on how it works.
Version: 2.2.0
Author: Ploxel
Author URI: http://www.ticketmeo.com/sell-tickets-on-wordpress
License: GPLv3
*/

define('PLOXEL_PLUGIN_VERSION', '2.2.0');

register_activation_hook(__FILE__, 'ploxel_activate');
add_action('admin_init', 'ploxel_redirect');

function ploxel_activate() {
    add_option('ploxel_do_activation_redirect', true);
}

function ploxel_redirect() {
    if (get_option('ploxel_do_activation_redirect', false)) {
        delete_option('ploxel_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("options-general.php?page=ploxel-integration");
        }
    }
}

function init_ploxel_script() {
    wp_enqueue_style('ploxel-css', plugins_url('/css/ploxel.css', __FILE__ ));
    wp_enqueue_script('ploxel-jquery', plugins_url('/js/ploxel_jquery.js', __FILE__ ), array('jquery'));
}

add_action( 'wp_enqueue_scripts', 'init_ploxel_script' );

function ploxel_iframe_plugin($attrs) {
    $id = rand(0, 10);

    if(!isset($attrs['src'])) {
        return 'Sorry you have not set an url, please check your code again';
    }

    $html = '<iframe';

    foreach($attrs as $attr => $value) {
        if($attr != '') {
            $html .= ' ' . esc_attr($attr) . '="' . esc_attr($value) . '"';
        }
    }

    $html .= ' id="plox' . $id . '" class="iFrameResize"></iframe>';

    $html .= '<script>jQuery( document ).ready(function() { iFrameResize(\'\',\'.iFrameResize\'); });</script>';
    return $html;
}

add_shortcode('ticketmeo', 'ploxel_iframe_plugin');
add_shortcode('ploxel', 'ploxel_iframe_plugin');
add_shortcode('ticketix', 'ploxel_iframe_plugin');

add_action( 'admin_menu', 'ploxel_menu' );

function ploxel_menu() {
    add_options_page( 'Ticketmeo WordPress Plugin', 'Ticketmeo WordPress Plugin', 'manage_options', 'ploxel-integration', 'ploxel_menu_page' );
}


function ploxel_menu_page() {
    if(!current_user_can( 'manage_options' )){
        wp_die( __( 'Sorry you do not have the correct permissions to view this page.' ) );
    }
    ?>
    <div class="wrap">
        <h2>Ticketmeo WordPress Plugin</h2>
        <p>Ticketmeo is a cloud based ticketing solution allowing you to sell tickets direct on your WordPress website without the hassle of managing the ticketing data on your server giving you peace of mind for both security and loss of data.</p>
        <p>Our plugin is free to install and to use with a small booking fee taken for all paid events which is paid by the customer, all free events are free. </p>
        <p>To get started you will need to sign up to Ticketmeo by clicking <a href="https://www.ticketmeo.com" target="_blank">here</a> if you do not already have an account. Signing up for Ticketmeo is free and simple to do.</p>
        <h3>How to integrate your widget.</h3>
        <p>To create your widget just follow these simple steps. It shouldn't take long:
        <ol>
            <li><a href="https://www.ticketmeo.com/login" target="_blank">Login into your Ticketmeo account</a> or <a href="https://www.ticketmeo.com/sign-up" target="_blank">sign up</a></li>
            <li>Create your first event (if you haven't already)</li>
            <li>Once you have created your first event, visit your event page by clicking Events then click the event you have created.</li>
            <li>Next on the sub menu click <strong>Integrate</strong> then <strong>WordPress Widget</strong></li>
            <li>Complete the form with the settings you want to display on your widget and the widget type you want to show and submit.</li>
            <li>Copy the code which is created once submitting.</li>
            <li>Paste this code into your WordPress blog where you want it to appear.</li>
            <li>Open sales and sell your first ticket.</li>
        </ol></p>
    </div>
    <?php
}