<?php
//gmail information//
$client_id = '661086014255-59fldjo3ob0svde43iqadisoejv08ob0.apps.googleusercontent.com';
$client_secret = 'O_VjDn8Y4Y8B1KtnVESDN8O0';
$redirect_url = home_url();
$login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode($redirect_url) . '&response_type=code&client_id=' . $client_id . '&access_type=online';
// end gmail information//

//function for gamil login//
class GoogleLoginApi
{
	public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
		$url = 'https://www.googleapis.com/oauth2/v4/token';			
		$curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_POST, 1);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);	
		$data = json_decode(curl_exec($ch), true);
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to receieve access token');
		return $data;
	}

	public function GetUserProfileInfo($access_token) {	
		$url = 'https://www.googleapis.com/plus/v1/people/me';			
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));
		$data = json_decode(curl_exec($ch), true);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to get user information');
		return $data;
	}
}
//end function for gamil login//

if( isset($_GET['code']) ) {
    try {
        $gapi = new GoogleLoginApi();
        //Get Access Token Data
        $data = $gapi->GetAccessToken($client_id, $redirect_url, $client_secret, $_GET['code']);
        // var_dump($data);
        // echo $data['token_type'];


        // Get User Information
        $user_info = $gapi->GetUserProfileInfo($data['access_token']);
        // echo '<pre>'; var_dump($user_info); echo '</pre>';
        $user_info = json_encode ( $user_info, true );
        //echo "user_info==== " . $user_info;
        $character = json_decode($user_info);
        // var_dump($character->emails); 
        foreach ($character->emails as $char) {
            // echo $char->value . '<br>';
        };
            // var_dump($character->name); 
            // echo $character->name->familyName . '<br>';  
            // echo $character->name->givenName . '<br>';
            // echo $character->displayName;
    }
    catch(Exception $e) {
        echo $e->getMessage();
        exit();
    }
}
/// end gmail information////


$home_video_title = get_post_meta(get_the_ID(), 'home_video_title', true);
$home_video_subtitle = get_post_meta(get_the_ID(), 'home_video_subtitle', true);
$home_video_banner_image_id = get_post_meta(get_the_ID(), 'home_video_banner_image', true);
$home_video_banner_image = get_template_directory_uri() . '/images/video.png';
if ($home_video_banner_image_id != '') {
    $home_video_banner_image_Array = wp_get_attachment_image_src($home_video_banner_image_id, 'full');
    if (isset($home_video_banner_image_Array[0])) {
        $home_video_banner_image = $home_video_banner_image_Array[0];
    }
}
$home_video_iframe_link = get_post_meta(get_the_ID(), 'home_video_iframe_link', true);
$event_booking_login_iframe_link = get_post_meta(get_the_ID(), 'event_booking_login_iframe_link', true);
$home_registration_title = get_post_meta(get_the_ID(), 'home_registration_title', true);
$home_registration_description = get_post_meta(get_the_ID(), 'home_registration_description', true);
$home_event_description = get_post_meta(get_the_ID(), 'home_event_description', true);
$home_event_create_procedure = get_post_meta(get_the_ID(), 'home_event_create_procedure', true);
$home_event_steps_title = get_post_meta(get_the_ID(), 'home_event_steps_title', true);
$home_event_title = get_post_meta(get_the_ID(), 'home_event_title', true);
?>

<!-- this is facebook information -->
<script>
  function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
    console.log('statusChangeCallback');
    console.log(response);                   // The current login status of the person.
    if (response.status === 'connected') {   // Logged into your webpage and Facebook.
      testAPI();  
    } else {                                 // Not logged into your webpage or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this webpage.';
    }
  }
  function checkLoginState() {               // Called when a person is finished with the Login Button.
    FB.getLoginStatus(function(response) {   // See the onlogin handler
      statusChangeCallback(response);
    });
  }
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '397258797864685',
      xfbml      : true,
      version    : 'v4.0',
      cookie     : true    
    });
    FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
      statusChangeCallback(response);        // Returns the login status.
    });
  };
  
  (function(d, s, id) {                      // Load the SDK asynchronously
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
 
  function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', 'get', { fields: 'id,first_name,last_name,email' }, function (result) {
            document.cookie = "fbdata = " + result.id + "," + result.first_name + "," + result.last_name + "," + result.email;
            console.log(document.cookie);
        });
  }
</script>

<script>
    function onSuccess(googleUser) {
      console.log('Logged in as: ' + googleUser.getBasicProfile().getName());
    }
    function onFailure(error) {
      console.log(error);
    }
    function renderButton() {
      gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSuccess,
        'onfailure': onFailure
      });
    }
  </script>

  <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>

<!-- 
<script>
document.cookie="profile_viewer_uid= First Name";
</script> -->

<!-- end facebook information -->


<section class="home-banner home-content" id="home-banner"
         style="background: url(<?php echo get_template_directory_uri(); ?>/images/banner-one.jpg)">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1><?php echo esc_html($home_video_title); ?></h1>
                <p><?php echo esc_html($home_video_subtitle); ?></p>
                <div class="video-content-block mrks-iframe-wrapper">
                    <a href="javascript:void(0)" class="play-btn mrks-iframe-pb"></a>
                    <img src="<?php echo esc_url($home_video_banner_image); ?>" alt="video"
                         data-youtube-link="<?php echo esc_url($home_video_iframe_link) ?>"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="sign-up-form">
                    <?php
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    if ( is_plugin_active( 'wa-affiliate/wa-affiliate.php' ) ) {
                    ?>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#signup-content" aria-controls="signup-content" role="tab" data-toggle="tab">Organisers Sign Up</a>
                        </li>
                        <li role="presentation">
                            <a href="#affiliate-content" aria-controls="affiliate-content" role="tab" data-toggle="tab">Affiliate Sign Up</a>
                        </li>
                    </ul>
                    <?php } ?>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="signup-content">
                                <h3>Sign up for free and manage your <br> event with EventBookings!</h3>
                                <?php
                                $uriString = '?';
                                if(isset($_GET['refid']) && !empty($_GET['refid'])){
                                    $uriString .= 'refid='.urlencode($_GET['refid']);
                                }
                                ?>
                             
                    <form action="" id="custom-form" method="post" class="signup">
                         <div class="form-row">
                            <div class="form-group col-md-6">   
                                <div class="sign-up-email">
                                        <div class="input firstname required" aria-required="true">
                                            <input type="text" name="firstname"
                                                  id="firstname"
                                                  class="form-control required firstname_input"
                                                  required="required"
                                                  placeholder="First Name"
                                                  value="<?php if(isset($character) ): echo $character->name->givenName; endif; ?><?php if(isset($_COOKIE['fbdata'])) { echo "welcome ".$_COOKIE['fbdata']; } ?>"
                                                  maxlength="200"
                                                  autocomplete="off"
                                                  aria-required="true"
                                                  <?php if(isset($character) ) {echo "readonly=";}?>  <?php if(isset($character) ) {echo "readonly";}?>
                                                  >
                                        </div>
                                        <span class="note" id="message_firstname"></span>
                                 </div>
                            </div>  
                        
                            <div class="form-group col-md-6">
                                    <div class="sign-up-email">
                                        <div class="input lastname required" aria-required="true">
                                            <input type="text" name="lastname"
                                                  id="lastname"
                                                  class="form-control required lastname_input"
                                                  required="required"
                                                  placeholder="Last Name"
                                                  value="<?php if(isset($character) ): echo $character->name->familyName; endif; ?>"
                                                  maxlength="200"
                                                  autocomplete="off"
                                                  aria-required="true"
                                                  <?php if(isset($character) ) {echo "readonly=";}?>  <?php if(isset($character) ) {echo "readonly";}?>
                                                  >
                                        </div>
                                        <span class="note" id="message_lastname"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="sign-up-email">
                            <div class="form-group col-md-12">     
                            <div class="sign-up-email">
                                <div class="input email required" aria-required="true">
                                    <input type="email" name="email"
                                            id="email"
                                            class="form-control required email_input"
                                            required="required"
                                            value="<?php if(isset($character) ):  foreach ($character->emails as $char) {echo $char->value;}; endif; ?>"
                                            placeholder="Email Address"
                                            maxlength="200"
                                            autocomplete="off"
                                            aria-required="true"
                                            <?php if(isset($character) ) {echo "readonly=";}?>  <?php if(isset($character) ) {echo "readonly";}?>
                                            >
                                </div>
                                <span class="note" id="messageemail"></span>
                            </div>
                            </div>

                            <div id="exampleModal" class="modal fade">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button"
                                                    style="display: inline-block;z-index:99;position: relative;width: auto;height: auto;"
                                                    class="close"
                                                    data-dismiss="modal"
                                                    aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <h5 class="modal-title">Help</h5>
                                        </div>
                                        <div class="modal-body" >
                                            <p style="color:#000000;">This URL will be the link of your organisation.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
  </div>
                    <!-- hidden input fields  -->
                    
                                <input type="hidden" 
                                name="identifier1" 
                                id="identifier1" 
                                placeholder="Identifier"
                                value="<?php if(isset($data) ): echo $data['id_token']; endif; ?>" 
                                required="required"
                                >
                  
                   
                                <input type="hidden" 
                                name="provider1" 
                                id="provider1"
                                placeholder="Provider"
                                value="<?php if(isset($data) ): echo "Google"; endif; ?>" 
                                required="required" 
                                >
                  
                                <button type="submit" class="sign-up-submit" id="js-submit" name="submit_form">SIGN UP FOR FREE</button>
                                <p class= "or"> or </p>
                    </form> 
                    <div id="result"></div>
                    <div id="result2"></div>
                    <div class="form-group col-md-12">      
                        <div class="form-group col-md-6"> 
                                <!-- google sign up button -->
                                <?php if( !isset($_GET['code']) ) : ?>
                                <a href="<?php echo $login_url ?>"><img src="<?php echo home_url('wp-content/uploads/2019/10/Gsignin.png') ?>" alt="Smiley face" width="150" height="120"></a>
                                <?php endif; ?> 
                        </div>

                        <div class="form-group col-md-6"> 
                                <!-- facebook sigup button  -->
                                <div class="fb-login-button" data-width="150" data-height="120" data-size="small" data-button-type="continue_with" data-auto-logout-link="false" data-use-continue-as="true"></div>
                        </div>

                    </div>            
            </div>

                            <div role="tabpanel" class="tab-pane" id="affiliate-content">
                                <h3>Sign Up to our Affiliate Program today <br> and start earning $$ </h3> 
                                <h3>Annual Sign Up Fee</h3>
                                <div class="affiliate-price">
                                    <?php
                                    if(!isset($_COOKIE['mrksEventBookingSelectedCountry']) || (isset($_COOKIE['mrksEventBookingSelectedCountry']) && $_COOKIE['mrksEventBookingSelectedCountry']=="AUS")) {
                                        ?><?php echo AFFILIATE_CURRENCY .  '0' ?>
                                        <del><?php echo AFFILIATE_CURRENCY .  AFFILIATE_REGULAR_PRICE ?></del>
                                        <?php
                                    } else if (AFFILIATE_SALE_PRICE) {
                                        ?><?php echo AFFILIATE_CURRENCY .  AFFILIATE_SALE_PRICE ?>
                                        <del><?php echo AFFILIATE_CURRENCY .  AFFILIATE_REGULAR_PRICE ?></del>
                                        <?php
                                    } else {
                                        ?>
                                        <?php echo AFFILIATE_CURRENCY . AFFILIATE_REGULAR_PRICE ?>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <a href="<?php echo home_url('/affiliate-program'); ?>"> <button type="submit" class="sign-up-submit">Learn More</button></a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    
    <section class="home-navigation-area">
        <div class="home-icon-block">
        <span>
            <img src="<?php echo get_template_directory_uri(); ?>/images/icons/easy-to-use-icon.png" alt="Easy to Use"/>
            <p>Easy to Use</p>
        </span>
        </div>
        <div class="home-icon-block">
        <span>
            <img src="<?php echo get_template_directory_uri(); ?>/images/icons/no-hidden-fees-icon.png"
                 alt="No Hidden Fees"/>
            <p>No Hidden Fees</p>
        </span>
        </div>
        <div class="home-icon-block">
        <span>
            <img src="<?php echo get_template_directory_uri(); ?>/images/icons/full-customisation-icon.png"
                 alt="Full Customisation"/>
            <p>Full Customisation</p>
        </span>
        </div>
        <div class="home-icon-block">
        <span>
            <img src="<?php echo get_template_directory_uri(); ?>/images/icons/intuitive-reporting-icon.png"
                 alt="Intuitive Reporting"/>
            <p>Intuitive Reporting</p>
        </span>
        </div>
    </section>
</section>
<section class="home-text">
    <div class="container">
        <h2 class="heading-text"><?php echo esc_html(stripslashes($home_registration_title)); ?></h2>
        <p><?php echo esc_html(stripslashes($home_registration_description)); ?></p>
        <a href="<?php echo get_page_link(17); ?>" class="link-btn">Check Our Pricing</a>
    </div>
</section>

<section class="home-create-event text-align-center padding-top-100">
    <div class="container">
        <h2 class="heading-text"><?php echo esc_html(stripslashes($home_event_steps_title)); ?></h2>
        <div class="create-event-content">
            <?php
            echo wp_kses($home_event_create_procedure, array(
                'div' => array(
                    'class' => array(),
                    'id' => array()
                ),
                'h2' => array(),
                'h4' => array(),
                'h6' => array(),
                'p' => array(),
                'h3' => array(),
                'br' => array(),
                'span' => array()
            ));
            ?>
        </div>
        <a href="<?php echo get_page_link(15); ?>" class="link-btn">See How It Works</a>
    </div>
</section>
<section class="why-eventbooking-text text-align-center">
    <div class="container">
        <h2 class="heading-text"><?php echo esc_html(stripslashes($home_event_title)); ?></h2>
        <p><?php echo wp_kses($home_event_description, array(
                'strong' => array(),
                'br' => array(),
                'i' => array()
            )) ?></p>
        <div class="home-feature-content">
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/branded-customisable-icon.png"
                             alt="Branded, customisable"/>
                        <span>Branded, customisable <br> page</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/unique-url-icon.png"
                             alt="Unique event URL"/>
                        <span>Unique event URL</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/reserved-seating-icon.png"
                             alt="Reserved seating"/>
                        <span>Reserved seating</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/interactive-seating-icon.png"
                             alt="Interactive seating plans"/>
                        <span>Interactive seating plans</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/multiple-pricing-icon.png"
                             alt="Multiple pricing levels"/>
                        <span>Multiple pricing levels</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/early-bird-icon.png"
                             alt="Early bird and discounted tickets"/>
                        <span>Early bird and discounted<br>tickets</span>
                    </a>
                </div>
            </div>


            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/secure-payments-icon.png"
                             alt="Secure, direct payments"/>
                        <span>Secure, direct payments</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/mobile-scanning-icon.png"
                             alt="Free mobile scanning app"/>
                        <span>Free mobile scanning <br> app</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/analytic-traffic-icon.png"
                             alt="Analytic and traffic reports"/>
                        <span>Analytic and traffic <br>reports</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/customised-forms-icon.png"
                             alt="Customised forms"/>
                        <span>Customised forms</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/social-media-links-icon.png"
                             alt="Social media links"/>
                        <span>Social media links</span>
                    </a>
                </div>
            </div>
            <div class="home-feature-block">
                <div class="feature-block">
                    <a href="javascript:void(0)">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/phone-email-support-icon.png"
                             alt="Phone and email support"/>
                        <span>Phone and email support</span>
                    </a>
                </div>
            </div>
        </div>

        <a href="<?php echo get_page_link(19); ?>" class="link-btn">Browse All Features</a>

    </div>
</section>

<section class="suitable-all-event text-center">
    <div class="container">
        <?php
        $event_headings = get_post_meta(get_the_ID(), 'event_headings', true);
        if ($event_headings != '') {
            echo stripslashes($event_headings);
        } else {
            ?>
            <h2 class="heading-text">Suitable for All Event Types</h2>
            <p>EventBookings can be used to sell tickets to any kind of event by people in just about any industry.</p>
            <?php
        }
        ?>

        <div class="suitable-event-content row">
            <?php
            $eventArgs = array(
                'post_type' => 'wa_solution',
                'orderby' => 'meta_value_num',
                'meta_key' => 'sorting_position',
                'order' => 'ASC',
                'posts_per_page' => 6
            );
            $eventQuery = new WP_Query($eventArgs);
            if ($eventQuery->have_posts()) {
                while ($eventQuery->have_posts()) {
                    $eventQuery->the_post();
                    ?>
                    <div class="col-md-2 col-sm-3">
                        <a href="<?php the_permalink(); ?>" class="suitable-inner-block ">
                            <?php
                            if (has_post_thumbnail()) {
                                $imgUrl = get_the_post_thumbnail_url(get_the_ID(), 'webalive_event_type');
                            } else {
                                $imgUrl = get_template_directory_uri() . '/images/academic-event.jpg';
                            }
                            ?>
                            <img src="<?php echo esc_url($imgUrl); ?>" alt="<?php the_title(); ?>">
                            <h4><?php the_title(); ?></h4>
                        </a>
                    </div>

                    <?php
                }
                wp_reset_postdata();
            }
            ?>
        </div>
        <a href="<?php echo get_page_link(21); ?>" class="link-btn">Explore More Events</a>
    </div>
</section>
