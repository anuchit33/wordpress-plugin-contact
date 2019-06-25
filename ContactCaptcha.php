<?php
/*
  Plugin Name: Contact & Captcha
  Plugin URI:
  Description: ระบบรับข้อความผู้ติดต่อและแสดงรายการผู้ติดต่อ
  Version:     1.0.0
  Author:      Intelligent Millionaire
  Author URI:  https://www.the-im.com
 */

class Contactcaptcha {

    // Constructor
    function __construct() {

        add_action('admin_menu', array($this, 'wpq_add_menu'));

        register_activation_hook(__FILE__, array($this, 'wpq_install'));
        register_deactivation_hook(__FILE__, array($this, 'wpq_uninstall'));

        // add shortcode
        add_shortcode('contact-captcha-page', array($this, 'shortcode_page_contact'));

        // add action for Ajax
        add_action('wp_ajax_post_contact', array($this, 'post_contact'));
        add_action('wp_ajax_nopriv_post_contact', array($this, 'post_contact'));

        add_action('wp_ajax_get_captcha_image', array($this, 'get_captcha_image'));
        add_action('wp_ajax_nopriv_get_captcha_image', array($this, 'get_captcha_image'));

    }

    function get_captcha_image() {

        $_SESSION = array();

        require_once( dirname(__FILE__) . '/libs/php-captcha/simple-php-captcha.php');
        $_SESSION['captcha'] = simple_php_captcha();
        echo json_encode(array('image_src'=>$_SESSION['captcha']['image_src']));
        die();
    }

    function post_contact() {

        check_ajax_referer('equiz_security_9$tu_8K!', 'security');
        global $wpdb; // this is how you get access to the database

        $data = $_POST;
        $error = array();
        if ($_SESSION['captcha']['code'] != $data['contact_captcha']) {
            echo json_encode(array('error' => array('contact_captcha' => '*โค้ดไม่ถูกต้อง'), 'status' => false));
            die();
        }

        if (empty($data['name'])) {
            echo json_encode(array('error' => array('name' => '*กรุณาใส่ซื่อ'), 'status' => false));
            die();
        }

        if (empty($data['email'])) {
            echo json_encode(array('error' => array('email' => '*กรุณาใส่อีเมล'), 'status' => false));
            die();
        } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array('error' => array('email' => '*รุปแบบอีเมลไม่ถูกต้อง'), 'status' => false));
            die();
        }

        if (empty($data['phone'])) {
            echo json_encode(array('error' => array('phone' => '*กรุณาใส่เบอร์โทร'), 'status' => false));
            die();
        } else if (!preg_match("/^[0]{1}[0-9]{9}$/", $data['phone']) && !preg_match("/^[0]{1}[0-9]{2}-[0-9]{7}$/", $data['phone']) && !preg_match("/^[0]{1}[0-9]{8}$/", $data['phone']) && !preg_match("/^[0]{1}[0-9]{2}-[0-9]{6}$/", $data['phone'])) {
            echo json_encode(array('error' => array('phone' => '*รุปแบบเบอร์โทรไม่ถูกต้อง'), 'status' => false));
            die();
        }


        if (empty($data['message'])) {
            echo json_encode(array('error' => array('message' => '*กรุณาใส่ข้อความ'), 'status' => false));
            die();
        }


        // send email
        $tablename = $wpdb->prefix . 'elixir_contact_email';
        $results = $wpdb->get_results("SELECT * FROM " . $tablename . " ", OBJECT);
        $to = array();
        foreach ($results as $v) {
            $to[] = $v->email;
        }
        $subject = 'Contact – elixir';
        $message = "Name: " . $data["name"] . " \n Email: " . $data["email"] . " \n Phone: " . $data["phone"] . " \n Message: \n " . $data["message"] . "";
        $headers = array('From: '.$data['name'].' <'.$data['email'].'>');

        wp_mail($to, $subject, $message, $headers);

        // add row contact
        $data = $_POST;
        $tablename = $wpdb->prefix . 'elixir_contact';
        $wpdb->insert($tablename, array(
            'name' => sanitize_text_field($data['name']),
            'email' => sanitize_text_field($data['email']),
            'phone' => sanitize_text_field($data['phone']),
            'message' => sanitize_text_field($data['message']),
            'created_datetime' => date('Y-m-d H:i:s')
                )
        );
        $record_id = $wpdb->insert_id;

        echo json_encode(array('status' => true));
        require_once( dirname(__FILE__) . '/libs/php-captcha/simple-php-captcha.php');
        $_SESSION['captcha'] = simple_php_captcha();
        die();
    }

    function shortcode_page_contact($atts) {

        extract(shortcode_atts(array('post_type' => 'post'), $atts));

        ob_start();
        require_once( dirname(__FILE__) . '/libs/php-captcha/simple-php-captcha.php');
        require_once( dirname(__FILE__) . '/templates/frontend/page-contact.php');
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /*
     * Actions perform on activation of plugin
     */

    function wpq_install() {

        $this->create_page("Cantact US", '[contact-captcha-page]');
        
    }

    function wpq_uninstall() {
        /*
         * Actions perform on de-activation of plugin
         */
    }

    /*
     * Actions perform at loading of admin menu
     */

    function wpq_add_menu() {
        add_menu_page('Elixir Contact', 'Contact US', 'manage_options', 'elixir-contact', '', 'dashicons-email', '2.2.10');
        add_submenu_page('elixir-contact', 'Elixir Contact', 'รายชื่อผู้ติดต่อ', 'manage_options', 'elixir-contact', array(__CLASS__, 'wpq_page_file_path'));
        add_submenu_page('elixir-contact', 'Elixir Email', 'ผู้รับอีเมล', 'manage_options', 'elixir-email', array(__CLASS__, 'wpq_page_file_path'));
    }

    /*
     * Actions perform on loading of menu pages
     */

    static function wpq_page_file_path() {
        $screen = get_current_screen();
        if (strpos($screen->base, 'elixir-email') !== false) {
            include( dirname(__FILE__) . '/templates/admin/email.php' );
        } else {
            include( dirname(__FILE__) . '/templates/admin/contact.php' );
        }
    }

    function create_page($page_title, $post_content = '', $post_type = 'page') {
        $page_id = wp_insert_post(array(
            'post_title' => $page_title,
            'post_type' => $post_type,
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'post_content' => $post_content
        ));

        if ($page_id && !is_wp_error($page_id)) {
            return $page_id;
        }

        return false;
    }

}

new Contactcaptcha();
?>