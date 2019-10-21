<?php
get_header();


//gmail information//
$client_id = '661086014255-59fldjo3ob0svde43iqadisoejv08ob0.apps.googleusercontent.com';
$client_secret = 'O_VjDn8Y4Y8B1KtnVESDN8O0';
$redirect_url = home_url('first-signup/');
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

                      <div class="container">     
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
                                <center> <p class= "or"> or </p> </center>
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
</div>
<?php get_footer(); ?>
