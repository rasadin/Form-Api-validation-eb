<?php
/**
 * Webalive functions and definitions
 *
 * @package Webalive
 */
/**
 * Enabling Session
 */
if (!session_id()) : session_start(); endif;


/**
 * Webalive only works in WordPress 4.7 or later.
 */
if (version_compare($GLOBALS['wp_version'], '4.7-alpha', '<')) {
    require get_template_directory() . '/inc/back-compat.php';
    return;
}

///////////START DEFINE API TOKEN AND URL////////////

define('url_api_organization_sign_up', 'http://api.gradpak.com/api/api_organization_sign_up.json');
define('token_api_organization_sign_up', '22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp');
define('api_url_country_states', 'http://api.gradpak.com/api/state_list_by_country_name');
define('api_token_country_states', '22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp');
define('url_url_verification', 'http://api.gradpak.com/api/is_url_exist.json');
define('token_url_verification', "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC");
define('url_sa_api', 'http://api.gradpak.com/api/is_already_registered_by_social_account.json');
define('token_social_api',"22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp");
define('url_email_api', 'http://api.gradpak.com/api/is_email_exist.json');
define('token_email_api', "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC");



// $api_url_country_states = 'http://api.gradpak.com/api/state_list_by_country_name';
// $api_token_country_states = "22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp";
// $url_url_verification = 'http://api.gradpak.com/api/is_url_exist.json';
// $token_url_verification = "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC";
// $url_sa_api = 'http://api.gradpak.com/api/is_already_registered_by_social_account.json';
// $token_social_api = "22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp";
// $url_email_api = 'http://api.gradpak.com/api/is_email_exist.json';
// $token_email_api = "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC";
// $url_sa_api = 'http://api.gradpak.com/api/is_already_registered_by_social_account.json';
// $token_social_api = "22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp";

////////////END DEFINE API TOKEN AND URL////////////


/**
 *
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function webalive_setup()
{
    /*
     * Make theme available for translation.
     * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentyseventeen
     * If you're building a theme based on Webalive, use a find and replace
     * to change 'twentyseventeen' to the name of your theme in all the template files.
     */
    load_theme_textdomain('webalive');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');
    add_image_size('webalive_event_type', 210, 268, true);
    add_image_size('webalive_event_case', 732, 430, true);

    // This theme uses wp_nav_menu() in two locations.
    register_nav_menus(array(
        'top' => __('Top Menu', 'webalive')
    ));

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', array(
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    /*
     * Enable support for Post Formats.
     *
     * See: https://codex.wordpress.org/Post_Formats
     */
    add_theme_support('post-formats', array());

    /*
     * This theme styles the visual editor to resemble the theme style,
     * specifically font, colors, and column width.
      */
    add_editor_style(array('assets/css/editor-style.css', webalive_fonts_url()));
}

add_action('after_setup_theme', 'webalive_setup');


/**
 * Register custom fonts.
 */
function webalive_fonts_url()
{
    $fonts_url = '';

    /*
     * Translators: If there are characters in your language that are not
     * supported by Libre Franklin, translate this to 'off'. Do not translate
     * into your own language.
     */
    $libre_franklin = _x('on', 'Libre Franklin font: on or off', 'webalive');

    if ('off' !== $libre_franklin) {
        $font_families = array();

        $font_families[] = 'Libre Franklin:300,300i,400,400i,600,600i,800,800i';

        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
            'subset' => urlencode('latin,latin-ext'),
        );

        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }

    return esc_url_raw($fonts_url);
}


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function webalive_widgets_init()
{
    register_sidebar(array(
        'name' => __('Blog Sidebar', 'webalive'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'webalive'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'webalive_widgets_init');

function webalive_excerpt_more($link)
{
    if (is_admin()) {
        return $link;
    }

    $link = sprintf('<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
        esc_url(get_permalink(get_the_ID())),
        /* translators: %s: Name of current post */
        sprintf(__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'webalive'), get_the_title(get_the_ID()))
    );
    return ' &hellip; ' . $link;
}

add_filter('excerpt_more', 'webalive_excerpt_more');

/**
 * Enqueue scripts and styles.
 */
function webalive_scripts()
{
    // Add custom fonts, used in the main stylesheet.
    wp_enqueue_style('webalive-fonts', webalive_fonts_url(), array(), null);


    if (has_nav_menu('top')) {
        $webalive_l10n['expand'] = __('Expand child menu', 'webalive');
        $webalive_l10n['collapse'] = __('Collapse child menu', 'webalive');
        $webalive_l10n['icon'] = twentyseventeen_get_svg(array('icon' => 'angle-down', 'fallback' => true));
    }

    wp_enqueue_script('webalive-global', get_theme_file_uri('/assets/js/global.js'), array('jquery'), '1.0', true);

    wp_enqueue_script('jquery-scrollto', get_theme_file_uri('/assets/js/jquery.scrollTo.js'), array('jquery'), '2.1.2', true);

    wp_localize_script('webalive-global', 'webaliveScreenReaderText', $webalive_l10n);
    //  wp_enqueue_script('webalive-navigation', get_theme_file_uri('/assets/js/navigation.js'), array('jquery'), '1.0', true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    //BOOTSTRAP
    wp_enqueue_style('bootstrap.min', get_template_directory_uri() . '/assets/additional-lib/bootstrap/3.3.6/css/bootstrap.min.css', array(), false, 'all');
    wp_enqueue_script('bootstrap.min', get_template_directory_uri() . '/assets/additional-lib/bootstrap/3.3.6/js/bootstrap.min.js', array('jquery'), '', true);

    //animate On Scroll
    wp_enqueue_script('aos', get_template_directory_uri() . '/assets/js/aos.js', array('jquery'), '', true);
    wp_enqueue_style('aos', get_template_directory_uri() . '/assets/css/aos.css', array(), false, 'all');

    //BXSLIDER
    wp_enqueue_style('jquery.fancybox.min', get_template_directory_uri() . '/assets/css/jquery.fancybox.min.css', array(), false, 'all');
    wp_enqueue_script('jquery.fancybox.min', get_template_directory_uri() . '/assets/js/jquery.fancybox.min.js', array('jquery'), '', true);
    wp_enqueue_script('iframeResizer.min', get_template_directory_uri() . '/assets/js/iframeResizer.min.js', array('jquery'), '', true);
//    wp_enqueue_script('iframeResizer.contentWindow.min', get_template_directory_uri() . '/assets/js/iframeResizer.contentWindow.min.js', array('jquery'), '', true);
    wp_enqueue_style('bxslider.css', get_template_directory_uri() . '/assets/additional-lib/jquery.bxslider/jquery.bxslider.css', array(), false, 'all');
    wp_enqueue_script('bxslider.min', get_template_directory_uri() . '/assets/additional-lib/jquery.bxslider/jquery.bxslider.min.js', array('jquery'), '', true);

    wp_enqueue_script('jquery.cookie', get_template_directory_uri() . '/assets/js/jquery.cookie.js', array('jquery'), '', true);
    wp_enqueue_script('math.min', get_template_directory_uri() . '/assets/js/math.min.js', array('jquery'), '', true);
    wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery', 'jquery.cookie', 'jquery.fancybox.min', 'math.min'), '20190423001', true);
    // Theme stylesheet.
    wp_enqueue_style('webalive-style', get_stylesheet_uri());
    wp_enqueue_style('custom-css', get_template_directory_uri() . '/assets/css/custom.css', array(), false, 'all');
    wp_enqueue_style('responsive-css', get_template_directory_uri() . '/assets/css/responsive.css', array(), false, 'all');

    $options = array(
        'admin_url'         => admin_url(''),
        'ajax_url'          => admin_url('admin-ajax.php'),
        'ajax_nonce'        => wp_create_nonce('ah3jhlk(765%^&ksk!@45'),
        'home_url'          => home_url('/'),
    );
    wp_localize_script('webalive-global', 'public_localizer', $options);
}

add_action('wp_enqueue_scripts', 'webalive_scripts');

function add_async_attribute($tag, $handle) {
    // add script handles to the array below
    $scripts_to_async = array(
        'bootstrap.min', 'jquery.fancybox.min', 'iframeResizer.min', 'bxslider.min', 'jquery.cookie', 'math.min', 'custom'
    );
    
    foreach($scripts_to_async as $async_script) {
       if ($async_script === $handle) {
          return str_replace(' src', ' async="async" src', $tag);
       }
    }
    return $tag;
 }

 add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

/**
 * Implement the Custom Header feature.
 */
require get_parent_theme_file_path('/inc/custom-header.php');

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path('/inc/template-tags.php');

/**
 * Additional features to allow styling of the templates.
 */
require get_parent_theme_file_path('/inc/template-functions.php');

/**
 * Customizer additions.
 */
require get_parent_theme_file_path('/inc/customizer.php');

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path('/inc/icon-functions.php');

//add_filter('show_admin_bar', '__return_false');

// add a favicon to frontend & backend
function blog_favicon()
{
    echo '<link rel="Shortcut Icon" type="image/x-icon" href="' . get_template_directory_uri() . '/images/favicon.ico" />';
}

add_action('wp_head', 'blog_favicon');
add_action('admin_head', 'blog_favicon');
add_filter('login_headerurl', 'blog_favicon');

function custom_login_logo()
{
    ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-color: transparent;
            background-image: url("<?php echo get_template_directory_uri(); ?>/images/admin-logo.png");
            background-position: center center;
            background-size: initial;
            height: 100px;
            width: auto;
            padding: 0;
        }
    </style>
    <?php
}

add_action('login_enqueue_scripts', 'custom_login_logo');

function readMore($content, $word_limit = 50)
{
    $content = strip_tags($content);
    $explode_content = explode(" ", $content);
    if (sizeof($explode_content) > $word_limit) {
        $slice_content = array_slice($explode_content, 0, $word_limit);
        $content = implode(" ", $slice_content);
    }
    return $content;
}

//initialize at header
function init_header()
{
    ?>
    <script type="text/javascript">
        ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
        home_url = '<?php echo home_url(); ?>';
        directory_url = '<?php echo get_template_directory_uri(); ?>';
        document.addEventListener('wpcf7mailsent', function (event) {
            location = '<?php echo home_url('/') ?>thank-you';
        }, false);
    </script>
    <?php
}

add_action('wp_head', 'init_header', 1);

add_action('wp_ajax_upload_mailchimp_subscriber', 'upload_mailchimp_subscriber');
add_action('wp_ajax_nopriv_upload_mailchimp_subscriber', 'upload_mailchimp_subscriber');
function upload_mailchimp_subscriber()
{
    $email = $_POST['email'];

    //CakeLog::write('debug', $user);
    $result = call('lists/subscribe', array(
        'id' => '3f6098aeb3',
        'email' => array('email' => $email),
        'double_optin' => false,
        'update_existing' => true,
        'replace_interests' => false,
        'send_welcome' => false,
    ));

    if (!empty($result["leid"]))
        wp_send_json($result);
    else if ($result["error"])
        wp_send_json($result);
    exit();

}

// function call($method, $args = array())
// {
//     $api_endpoint = 'https://<dc>.api.mailchimp.com/2.0';
//     $api_key = 'bcc534a46d190974ae4d8310b47a763d-us9'; //live

//     list(, $datacentre) = explode('-', $api_key);
//     $api_endpoint = str_replace('<dc>', $datacentre, $api_endpoint);
//     $args['apikey'] = $api_key;
//     $url = $api_endpoint . '/' . $method . '.json';

//     if (function_exists('curl_init') && function_exists('curl_setopt')) {
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $url);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//         curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
//         $result = curl_exec($ch);
//         curl_close($ch);
//     } else {
//         $json_data = json_encode($args);
//         $result = file_get_contents($url, null, stream_context_create(array(
//             'http' => array(
//                 'protocol_version' => 1.1,
//                 'user_agent' => 'PHP-MCAPI/2.0',
//                 'method' => 'POST',
//                 'header' => "Content-type: application/json\r\n" .
//                     "Connection: close\r\n" .
//                     "Content-length: " . strlen($json_data) . "\r\n",
//                 'content' => $json_data,
//             ),
//         )));
//     }
//     //CakeLog::write('debug', $result);
//     return $result ? json_decode($result, true) : false;
// }

function editors_picks()
{
    $items = get_posts(array(
            'order' => 'asc',
            'orderby' => 'menu_order',
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => 3,
            'meta_key' => 'editor_picks',
            'meta_value' => 'Yes',
        )
    );

    if (count($items) > 0) {

        $html = '<ul>';
        foreach ($items as $item) {
            $html .= '<li>
						<a href="' . get_permalink($item->ID) . '">
							' . $item->post_title . '
						</a>
					</li>';
        }
        $html .= '</ul>';

        return $html;
    }
}

add_shortcode("editors_picks", "editors_picks");


if (!function_exists('write_log')) {
    function write_log($log)
    {
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}


add_action('wp_ajax_manager_login', 'manager_login');
add_action('wp_ajax_nopriv_manager_login', 'manager_login');
function manager_login()
{
   $subdomain = $_POST['subdomain'];
   $data = file_get_contents('http://facebookshop.info/universities/getorgbySubdomain?subdomain='.$subdomain);
   echo $data;
   exit;
}

require(__DIR__ . '/inc/ZohoDeskComponent.php');
// define the wpcf7_submit callback


// add the action
add_action( 'wpcf7_mail_sent', 'mrks_wpcf7_mail_sent', 10, 1 );
function mrks_wpcf7_mail_sent($WPCF7_ContactForm) {
    zoho_token($WPCF7_ContactForm);
};

// add the action
add_action('wpcf7_mail_failed', 'mrks_wpcf7_mail_failed', 10, 1);
function mrks_wpcf7_mail_failed($WPCF7_ContactForm)
{
//    zoho_token($WPCF7_ContactForm);
}

function zoho_token($WPCF7_ContactForm){
//    write_log("mizan");
    $response = array();
    // get current SUBMISSION instance
    $submission = WPCF7_Submission::get_instance();

    // Ok go forward
    if ($submission) {

        // get submission data
        $data = $submission->get_posted_data();

        // nothing's here... do nothing...
        if (empty($data))
            return;

        $subject = 'Contact Us ticket';
        $firstName = $data['first-name'];
        $lastName = $data['last-name'];
        $email = $data['your-email'];
        $message = $data['your-message'];
        $ZohoDesk = new ZohoDeskComponent();
        $response = $ZohoDesk->createTicketByRefreshToken($firstName, $lastName, $subject, $email, $message);
        // Save the email body
    }
//    write_log($response);
}

//this is ajax function for first page to second page
function my_user_ajax() {
    if( isset($_POST['fields']) ) {
        parse_str($_POST['fields'], $fields);
        set_transient('organiser_sign_up', $fields);
        wp_die();
    }
}
add_action("wp_ajax_my_user_ajax", "my_user_ajax");
add_action("wp_ajax_nopriv_my_user_ajax", "my_user_ajax");  



//this is ajax function for second page to third page
function my_second_user_ajax() {
    if( isset($_POST['fields']) ) {
        parse_str($_POST['fields'], $fields);
        if( $fields['identifier'] == "" &&  $fields['provider'] == ""){
        // set_transient('organiser_second_sign_up', $fields);
        $_SESSION['organiser_second_sign_up'] = $fields;
        ver_dump($_SESSION['organiser_second_sign_up']);
        wp_die();
        }

        if( $fields['identifier'] != ""){
            $_SESSION['organiser_second_sign_up'] = $fields;
            // $url_sa_api = 'http://api.gradpak.com/api/is_already_registered_by_social_account.json';
            $data6 = array("id_token"=> $fields['identifier'], "provider"=> $fields['provider'] ,'submit' => 'submit' );
            $postdata6 = json_encode($data6);
            // $token_social_api = "22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp";
            $ch6 = curl_init(url_sa_api);
            $authorization6 = "Authorization: Bearer ".token_social_api; 
            curl_setopt($ch6, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch6, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch6, CURLOPT_POST, 1);
            curl_setopt($ch6, CURLOPT_POSTFIELDS, $postdata6);
            curl_setopt($ch6, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch6, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization6 ));
            $result6 = curl_exec($ch6);
            curl_close($ch6);
            echo wp_send_json($result6);
            // echo json_encode($result6);
            wp_die();
        }
        // wp_die();
    }
}
add_action("wp_ajax_my_second_user_ajax", "my_second_user_ajax");
add_action("wp_ajax_nopriv_my_second_user_ajax", "my_second_user_ajax"); 



//this part for Social Account and email verification
function email_validation(){
    if( isset($_POST['fields'])) {
        parse_str($_POST['fields'], $fields);


       //this part for email verification
        if( $fields['identifier1'] == "" && $fields['email'] != ""){
        // $url_email_api = 'http://api.gradpak.com/api/is_email_exist.json';
        $data6 = array("email"=> $fields['email'],'submit' => 'submit' );
        $postdata6 = json_encode($data6);
        // $token_email_api = "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC";
        $ch6 = curl_init(url_email_api);
        $authorization6 = "Authorization: Bearer ".token_email_api; 
        curl_setopt($ch6, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch6, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch6, CURLOPT_POST, 1);
        curl_setopt($ch6, CURLOPT_POSTFIELDS, $postdata6);
        curl_setopt($ch6, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch6, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization6 ));
        $result6 = curl_exec($ch6);
        curl_close($ch6);
        echo wp_send_json($result6);
        // echo json_encode($result6);
        }


        //this part for email verification
        if( $fields['identifier1'] == "" && $fields['email'] == ""){
            // $url_email_api = 'http://api.gradpak.com/api/is_email_exist.json';
            $data6 = array("email"=> $fields['email'],'submit' => 'submit' );
            $postdata6 = json_encode($data6);
            // $token_email_api = "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC";
            $ch6 = curl_init(url_email_api);
            $authorization6 = "Authorization: Bearer ".token_email_api; 
            curl_setopt($ch6, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch6, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch6, CURLOPT_POST, 1);
            curl_setopt($ch6, CURLOPT_POSTFIELDS, $postdata6);
            curl_setopt($ch6, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch6, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization6 ));
            $result6 = curl_exec($ch6);
            curl_close($ch6);
            echo wp_send_json($result6);
            // echo json_encode($result6);
            }
    

 //this part for Social Account verification
        // if( $fields['identifier1'] != ""){
        //     // $url_sa_api = 'http://api.gradpak.com/api/is_already_registered_by_social_account.json';
        //     $data6 = array("id_token"=> $fields['identifier1'], "provider"=> $fields['provider1'] ,'submit' => 'submit' );
        //     $postdata6 = json_encode($data6);
        //     // $token_social_api = "22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp";
        //     $ch6 = curl_init(url_sa_api);
        //     $authorization6 = "Authorization: Bearer ".token_social_api; 
        //     curl_setopt($ch6, CURLOPT_SSL_VERIFYHOST, 0);
        //     curl_setopt($ch6, CURLOPT_SSL_VERIFYPEER, 0);
        //     curl_setopt($ch6, CURLOPT_POST, 1);
        //     curl_setopt($ch6, CURLOPT_POSTFIELDS, $postdata6);
        //     curl_setopt($ch6, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch6, CURLOPT_FOLLOWLOCATION, 1);
        //     curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization6 ));
        //     $result6 = curl_exec($ch6);
        //     curl_close($ch6);
        //     echo wp_send_json($result6);
        //     // echo json_encode($result6);
        // }

      //this part for email verification
      if( $fields['identifier1'] != "" && $fields['email'] != ""){
                $url_email1_verification = 'http://api.gradpak.com/api/is_email_exist.json';
                $data6 = array("email"=> $fields['email'],'submit' => 'submit' );
                $postdata6 = json_encode($data6);
                $token_email1_verification = "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC";
                $ch6 = curl_init($url_email1_verification);
                $authorization6 = "Authorization: Bearer ".$token_email1_verification; 
                curl_setopt($ch6, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch6, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch6, CURLOPT_POST, 1);
                curl_setopt($ch6, CURLOPT_POSTFIELDS, $postdata6);
                curl_setopt($ch6, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch6, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization6 ));
                $result6 = curl_exec($ch6);
                echo wp_send_json($result6);
                curl_close($ch6);
               // echo wp_send_json($result6);
                }
}  
   wp_die();
}
add_action("wp_ajax_email_validation", "email_validation");
add_action("wp_ajax_nopriv_email_validation", "email_validation"); 




//  function google_validation(){
//     if( isset($_POST['identifier1'])) {
//         parse_str($_POST['fields'], $fields);
//         $url6 = 'http://api.gradpak.com/api/is_already_registered_by_social_account.json';
//         $data6 = array("id_token"=> $fields['identifier1'], "provider"=> $fields['provider1'] ,'submit' => 'submit' );
//         $postdata6 = json_encode($data6);
//         $token6 = "22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp";
//         $ch6 = curl_init($url6);
//         $authorization6 = "Authorization: Bearer ".$token6; 
//         curl_setopt($ch6, CURLOPT_SSL_VERIFYHOST, 0);
//         curl_setopt($ch6, CURLOPT_SSL_VERIFYPEER, 0);
//         curl_setopt($ch6, CURLOPT_POST, 1);
//         curl_setopt($ch6, CURLOPT_POSTFIELDS, $postdata6);
//         curl_setopt($ch6, CURLOPT_RETURNTRANSFER, 1);
//         curl_setopt($ch6, CURLOPT_FOLLOWLOCATION, 1);
//         curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization6 ));
//         $result6 = curl_exec($ch6);
//         curl_close($ch6);
//         echo wp_send_json($result6);
//         // echo json_encode($result6);
//     } 
//    wp_die();
// }

// add_action("wp_ajax_google_validation", "google_validation");
// add_action("wp_ajax_nopriv_google_validation", "google_validation"); 


// this part for url verification
function url_validation(){
    if( isset($_POST['fields'])) {
        parse_str($_POST['fields'], $fields);
        // $url_url_verification = 'http://api.gradpak.com/api/is_url_exist.json';
        $data6 = array("url"=> $fields['url'],'submit' => 'submit' );
        $postdata6 = json_encode($data6);
        // $token_url_verification = "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC";
        $ch6 = curl_init(url_url_verification);
        $authorization6 = "Authorization: Bearer ".token_url_verification; 
        curl_setopt($ch6, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch6, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch6, CURLOPT_POST, 1);
        curl_setopt($ch6, CURLOPT_POSTFIELDS, $postdata6);
        curl_setopt($ch6, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch6, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization6 ));
        $result6 = curl_exec($ch6);
        curl_close($ch6);
        echo wp_send_json($result6);
        // echo json_encode($result6);
    }  
   wp_die();
}
add_action("wp_ajax_url_validation", "url_validation");
add_action("wp_ajax_nopriv_url_validation", "url_validation"); 


// this part for country states relation
function country_states(){
    if( isset($_POST['fields'])) {
        parse_str($_POST['fields'], $fields);
        // $api_url_country_states = 'http://api.gradpak.com/api/state_list_by_country_name';
        $data6 = array("country_name"=> $fields['country'],'submit' => 'submit' );
        $postdata6 = json_encode($data6);
        // $api_token_country_states = "22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp";
        $ch6 = curl_init(api_url_country_states);
        $authorization6 = "Authorization: Bearer ".api_token_country_states; 
        curl_setopt($ch6, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch6, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch6, CURLOPT_POST, 1);
        curl_setopt($ch6, CURLOPT_POSTFIELDS, $postdata6);
        curl_setopt($ch6, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch6, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization6 ));
        $result6 = curl_exec($ch6);
        curl_close($ch6);
        echo wp_send_json($result6);
        // echo json_encode($result6);
    }    
   wp_die();
}
add_action("wp_ajax_country_states", "country_states");
add_action("wp_ajax_nopriv_country_states", "country_states"); 
