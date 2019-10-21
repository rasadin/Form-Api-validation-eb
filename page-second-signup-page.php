<?php
get_header();
?>
    <!--js-->
    <script src='https://www.google.com/recaptcha/api.js'></script>
<?php
/**
 * Timezones list with GMT offset
 *
 * @return array
 * @link http://stackoverflow.com/a/9328760
 */


            // Function For TimeZone by curl
            function jwt_request($token, $post=null) {
            $ch = curl_init('http://api.gradpak.com/api/timezone_list.json'); // Initialise cURL
            // $post = json_encode($post); // Encode the data array into a JSON string
            $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 0); // Specify the request method as POST
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
            $result = curl_exec($ch); // Execute the cURL statement
            curl_close($ch); // Close the cURL connection
            return json_decode($result); // Return the received data
            }
            $token = "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC"; // Get your token from a cookie or database
            // $post = array('some_trigger'=>'Austria','some_values'=>'Australia'); // Array of data with a trigger
            $request = jwt_request($token,$post=null); // Send or retrieve data
            // var_dump($request->timezone_list);



            //Function for Country by Curl 
            function country_request($token2, $post2=null) {
            $ch2 = curl_init('http://api.gradpak.com/api/country_state_list.json'); // Initialise cURL
            $authorization2 = "Authorization: Bearer ".$token2; // Prepare the authorisation token
            curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization2 )); // Inject the token into the header
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_POST, 0); // Specify the request method as POST //0 for get//1 for post//
            curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
            $result2 = curl_exec($ch2); // Execute the cURL statement
            curl_close($ch2); // Close the cURL connection
            return json_decode($result2); // Return the received data
            }
            $token2 = "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC"; // Get your token from a cookie or database
            $request2 = country_request($token2,$post2=null); // Send or retrieve data
            // var_dump($request2->countries);



            //Function for Country by Curl 
            function state_request($token3, $post3=null) {
            $ch3 = curl_init('http://api.gradpak.com/api/country_state_list.json'); // Initialise cURL
            $authorization3 = "Authorization: Bearer ".$token3; // Prepare the authorisation token
            curl_setopt($ch3, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization3 )); // Inject the token into the header
            curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch3, CURLOPT_POST, 0); // Specify the request method as POST //0 for get//1 for post//
            curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
            $result3 = curl_exec($ch3); // Execute the cURL statement
            curl_close($ch3); // Close the cURL connection
            return json_decode($result3); // Return the received data
            }
            $token3 = "tFYCwHkuaDrk7gVSdjPoRmesCs8nK7UQLrEYVkfC"; // Get your token from a cookie or database
            $request3 = state_request($token3,$post3=null); // Send or retrieve data
            // var_dump($request3->states);
            ?>
            <div id="result"></div>
            <?php

            //get 1st from set values
            $firstname_tr_var = get_transient('organiser_sign_up'); 
            //  echo $firstname_tr_var['firstname'];

            $name1 = $firstname_tr_var['firstname'];
            set_transient('sign_up_name1', $name1);

            $name2 = $firstname_tr_var['lastname'];
            set_transient('sign_up_name2', $name2);

            $sign_email = $firstname_tr_var['email'];
            set_transient('sign_up_email', $sign_email);


            $sign_identifier = $firstname_tr_var['identifier1'];
            set_transient('sign_up_identifier', $sign_identifier);

            $sign_provider = $firstname_tr_var['provider1'];
            set_transient('sign_up_provider', $sign_provider);



            //  var_dump($firstname_tr_var);
            //  var_dump($firstname_tr_var['firstname']);
            delete_transient('organiser_sign_up');
            ?>
              <form id="custom-form-two" method="post" class="signup_two">
                <div class="sign-up-email">
                      <div class="form-group col-md-12 ">
                            <input type="hidden" 
                            name="identifier" 
                            id="identifier"
                            placeholder="Your identifier"
                            value="<?php  echo $firstname_tr_var['identifier1']; ?>" 
                            required="required"
                            >
                      </div>
                </div>

                <div class="sign-up-email">
                      <div class="form-group col-md-12 ">
                            <input type="hidden" 
                            name="provider" 
                            id="provider"
                            placeholder="Your provider"
                            value="<?php  echo $firstname_tr_var['provider1']; ?>" 
                            required="required"
                            >
                      </div>
                </div>


                <div class="sign-up-email">
                      <div class="form-group col-md-12 ">
                            <input type="hidden" 
                            name="first_name" 
                            id="first_name" 
                            placeholder="Your first name"
                            value="<?php  echo $firstname_tr_var['firstname']; ?>" 
                            required="required"
                            >
                      </div>
                </div>



                <div class="sign-up-email">
                      <div class="form-group col-md-12 ">
                      <input type="hidden" 
                            name="last_name"
                            id="last_name"
                            placeholder="Your last name"
                            value="<?php  echo $firstname_tr_var['lastname']; ?>" 
                            required="required"
                            >
                      </div>
                </div>


                <div class="sign-up-email">
                      <div class="form-group col-md-12 ">
                      <input type="hidden" 
                            name="email" 
                            id="email" 
                            placeholder="Your email"
                            value="<?php  echo $firstname_tr_var['email']; ?>" 
                            required="required"
                            >
                      </div>
                </div>


                <div class="form-row">
                  <div class="sign-up-email">
                    <div class="form-group col-md-12 ">
                        <label for="inputOrganisationName"></label>
                        <input type="text"
                          class="form-control"
                          id="name" 
                          name="name"
                          placeholder="Organisation Name">
                    </div>
                  </div>


                <div class="sign-up-email">
                      <div class="form-group col-md-12 ">
                            <div class="url-prefix">
                                  <div class="input text required" aria-required="true">
                                      <input type="text" 
                                        name="url" 
                                        class="form-control required url_input" 
                                        id="url_input"
                                        required="required"
                                        placeholder="Your desired URL" 
                                        maxlength="200" 
                                        autocomplete="off"
                                        aria-required="true">
                                  </div>
                            </div>
                      <div class="url-suffix"><?php echo WA_MAIN_SITE_VIEW; ?></div>
                    <div class="url_msg">
                        <span class="note" id="messageurl"></span>
                        <span class="note" id="messageurl2"></span>
                    </div>
                      </div> 
                      </div>

                <div class="sign-up-email">
                      <div class="form-group col-md-6">
                          <label for="inputPassword"></label>
                          <input type="password" 
                          class="form-control" 
                          name="password" 
                          id="password" 
                          placeholder="Password">
                      </div>
                </div>

                <div class="sign-up-email">
                      <div class="form-group col-md-6">
                          <label for="inputPassword2"></label>
                          <input type="password" 
                          name="password_again"
                          class="form-control" 
                          id="password_again" 
                          placeholder="Confirm Password">
                    </div>
                </div>

                <div class="sign-up-email">
                      <div class="form-group col-md-6">
                          <label for="Address1"></label>
                          <input type="text" 
                          name= "address_line_1"
                          class="form-control" 
                          id="address1" 
                          placeholder="Address Line 1">
                      </div>
                </div>

                <div class="sign-up-email">
                      <div class="form-group col-md-6">
                        <label for="Address2"></label>
                        <input type="text" 
                        name="address_line_2"
                        class="form-control" 
                        id="address2" 
                        placeholder="Address Line 2">
                      </div>
                </div>


              <div class="sign-up-email">  
                    <div class="form-group col-md-6">
                        <label for="city"></label>
                        <input type="city" 
                        name="city"
                        class="form-control" 
                        id="city" 
                        placeholder="City">
                    </div>
              </div>


                    <div class="sign-up-email">
                        <div class="form-group col-md-6">
                              <label for="postcode"></label>
                              <input type="text" 
                              name="postcode"
                              class="form-control" 
                              id="postcode" 
                              placeholder="Post Code">
                        </div>
                    </div>

                    <div class="sign-up-email">
                        <div class="form-group col-md-6">
                            <label for="states"></label>
                            <select id="states"
                            name="state" 
                            class="form-control">
                            <option value="">States </option>
                            </select>
                        </div>
                    </div>

                    <div class="sign-up-email">
                        <div class="form-group col-md-6">
                            <label for="country"></label>
                            <select id="country" 
                            name ="country" 
                            class="form-control">
                            <option value="">Country</option>
                            <?php
                            foreach($request2->countries as $key2=>$cn) {
                            ?>
                                <option value="<?php echo $key2; ?>"><?php echo $cn; ?></option>
                            <?php
                            } ?>
                            </select>
                        </div>
                    </div>

                    <div class="sign-up-email">
                        <div class="form-group col-md-6">
                          <label for="phone"></label>
                          <input type="text" 
                          name="phone_no_2"
                          class="form-control" 
                          id="phone" 
                          placeholder="Phone">
                        </div>
                    </div>

                    <div class="sign-up-email">
                        <div class="form-group col-md-6">
                            <label for="mobile"></label>
                            <input type="text" 
                            name="phone_no_1"
                            class="form-control" 
                            id="mobile" 
                            placeholder="Mobile">
                        </div>
                    </div>

                    <div class="sign-up-email">
                        <div class="form-group col-md-6">
                            <label for="inputTimezone"></label>
                            <select id="inputTimezone" 
                            name="timezone" 
                            class="form-control">
                            <option value="">Timezone</option>
                            <?php
                            foreach($request->timezone_list as $key=>$tz) {
                            ?>
                            <option value="<?php echo $key; ?>"><?php echo $tz; ?></option>
                            <?php
                            } ?>
                            </select>
                        </div>
                    </div>

                    <div class="sign-up-email">
                        <div class="form-group col-md-5">
                          <label for="affiliate"></label>
                          <input type="text" 
                          name="refid"
                          class="form-control" 
                          id="affiliate" 
                          placeholder="Affiliate"> 
                        </div>

                            <div class="form-group col-md-1">
                            <a href="#" class="trigger"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a>
                            </div>
                            <div id="pop-up">
                                <p>
                                If you're subscribed through our affiliate program and have an affiliate code, then please enter here.
                                </p>
                            </div>

                        </div>

                        <div class="sign-up-email">
                            <div class="form-group col-md-6">
                                <div class="g-recaptcha" data-sitekey="6LcePAATAAAAAGPRWgx90814DTjgt5sXnNbV5WaW"></div>
                            </div>
                        </div>

                        <div class="sign-up-email">
                            <div class="form-group col-md-6">
                                <button type="submit" name="second-submit" id="js-second-submit" class="js-second-submit btn btn-primary btn-lg" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing...">CONFIRM</button>
                            </div>
                        </div>

                        <div class="sign-up-email">
                            <div class="form-group col-md-12">
                        <div class="second_error_msg" id="result2"></div>
                        <div class="second_error_msg" id="result3"></div>
                        </div>
                        </div>

                        <div class="sign-up-email">
                            <div class="form-group col-md-6">
                            <p class="back_button"><a href="<?php echo home_url(); ?>">Back</a></p>
                            </div>
                        </div>
                        </form>
                                    
                        
      <?php get_footer(); ?>
