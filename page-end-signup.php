<?php
get_header();

            $getSignup_name1 = get_transient('sign_up_name1'); 
            // var_dump($getSignup_name1);
            // echo $getSignup_name1;
            delete_transient('sign_up_name1');


            $getSignup_name2 = get_transient('sign_up_name2'); 
            // var_dump($getSignup_name2);
            // echo $getSignup_name2;
            delete_transient('sign_up_name2');

            $getSignup_email = get_transient('sign_up_email'); 
            // var_dump($getSignup_email);
            // echo $getSignup_email;
            delete_transient('sign_up_email');




            //get 1st from set values
            $second_tr_var = $_SESSION['organiser_second_sign_up'];
            //  echo $second_tr_var['first_name'];
            //   var_dump($second_tr_var);
            //   echo $second_tr_var['url'];
            //  echo $second_tr_var['url'];
            //  var_dump($second_tr_var['first_name']);
            //  delete_transient('organiser_second_sign_up');


            // //Function for 2nd form Curl 
            // $url_api_organization_sign_up= 'http://api.gradpak.com/api/api_organization_sign_up.json';
            $data6 = array(
                "email"=> $getSignup_email,
                "url"=> $second_tr_var['url'],
                "name" => $second_tr_var['name'],
                "first_name" => $getSignup_name1,
                "last_name" => $getSignup_name2,
                "address_line_1"=>$second_tr_var['address_line_1'],
                "address_line_2"=>$second_tr_var['address_line_2'],
                "phone_no_1"=>$second_tr_var['phone_no_1'],
                "phone_no_2"=>$second_tr_var['phone_no_2'],
                "city"=>$second_tr_var['city'],
                "postcode"=>$second_tr_var['postcode'],
                "state"=>$second_tr_var['state'],
                "country"=>$second_tr_var['country'],
                "timezone"=>$second_tr_var['timezone'],
                "refid"=>$second_tr_var['refid'],
                "password"=>$second_tr_var['password'],
                "password_again"=>$second_tr_var['password_again'],
                "provider"=>$second_tr_var['provider'],
                "identifier"=>$second_tr_var['identifier']);

            // var_dump($data6);
            // var_dump($data6['email']);

            if($data6['email']!="" || $data6['first_name']!="" || $data6['last_name']!=""){

            $postdata6 = json_encode($data6);
            // $token_url_api_organization_sign_up = "22NYL9fy1OIsdAIEGD8f3XkMpudGFCS9hHFG7AWp";
            $ch6 = curl_init(url_api_organization_sign_up);
            $authorization6 = "Authorization: Bearer ".token_api_organization_sign_up; 
            curl_setopt($ch6, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch6, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch6, CURLOPT_POST, 1);
            curl_setopt($ch6, CURLOPT_POSTFIELDS, $postdata6);
            curl_setopt($ch6, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch6, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch6, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization6 ));
            $result6 = curl_exec($ch6);
            // var_dump($result6);

            
            $status = json_decode($result6)->status;
            $msg = json_decode($result6)->msg;
            // print_r($redirect_url);
            // print_r(json_decode($result6));


            //page redirect
            if($status == "success"){
                $redirect_url = json_decode($result6)->redirect_url;
            ?>
            <button class="go_to_dash"><a href= " <?php echo $redirect_url ?> " >GO TO DASHBOARD NOW</a></button>
            <?php
                // header('Location: ' . $redirect_url, true, 302);
                // exit();
            ?>
            <p class="dash_txt"> Your EventBookings account is ready. </p>
            <p class="dash_p"> We are redirecting you to your dashboard...in <div id="counter">10</div></p>
                <script>
                    setInterval(function() {
                        var div = document.querySelector("#counter");
                        var count = div.textContent * 1 - 1;
                        div.textContent = count;
                        if (count <= 0) {
                            window.location.replace(" <?php echo  $redirect_url ;?> ");
                        }
                    }, 1000);
                </script>
            <?php
            }

            if($status == "error"){
                ?>
                <button class="go_to_dash"><a href= " <?php echo home_url();?> " >GO TO HOME PAGE NOW</a></button>
                <?php
                    // header('Location: ' . $redirect_url, true, 302);
                    // exit();
                ?>
                <p class="dash_txt">ERROR!!! Your input email address and your social media(Gmail/Facebook) email address don't match.  </p>
                <p class="dash_p"><?php echo $msg ?></p>

                <?php
                }


            //page redirect end


            curl_close($ch6);
            // var_dump($result6);
            // echo json_encode($result6);
            // $obj= json_encode($result6);
            // echo  $obj->status;
            //  var_dump($obj);
            // if ($obj->status == "error"){
            //     echo $obj->msg;
            //     echo $obj->error_list->email->_empty;
            // }
            // if ($obj->status == "success"){
            //     echo "Your Organisation created";
            //   }
            // $data_last = print_r ($);
            // var_dump($data_last);
            // echo $data_last['status'];
            // var_dump($data_last['url']);
            }
?>
<?php get_footer(); ?>
