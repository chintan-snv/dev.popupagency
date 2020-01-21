<?php

// process emails first
$response = $this->niteo_subscribe( true );

$html = '';

// get current theme
$theme = $this->cmp_selectedTheme();

// get type of susbscribe
$subscribe_type = get_option('niteoCS_subscribe_type', '2');

// if subscribers is 3rd party plugin, render form by shortcode
switch ($subscribe_type) {
    // custom shortcode
    case '1':
        $replace  = array('<p>', '</p>' );
        $html =  str_replace($replace, '', do_shortcode( stripslashes( get_option('niteoCS_subscribe_code') ))) ; 
        break;
    // CMP subscribe form
    case '2':
        // get GDPR message
        $niteoCS_subscribe_label    = stripslashes( get_option('niteoCS_subscribe_label') );

        //  get translation if exists
        $translation            = json_decode( get_option('niteoCS_translation'), true );
        $placeholder            = isset($translation[4]['translation']) ? stripslashes( $translation[4]['translation'] ) : 'Insert your email address.';
        $placeholder_firstname  = isset($translation[10]['translation']) ? stripslashes( $translation[10]['translation'] ) : 'First Name';
        $placeholder_lastname   = isset($translation[11]['translation']) ? stripslashes( $translation[11]['translation'] ) : 'Last Name';
        $submit                 = isset($translation[8]['translation']) ? stripslashes( $translation[8]['translation'] ) : 'Submit';

        // overwrite it with theme specific requirements
        if ( $this->cmp_selectedTheme() == 'stylo' ) {
            $placeholder            =  '&#xf003;  ' . $placeholder;
            $placeholder_firstname  =  '&#xf2c0;  ' . $placeholder_firstname;
            $placeholder_lastname   =  '&#xf2c0;  ' . $placeholder_lastname;
        }

        // overwrite it with theme specific requirements
        if ( $this->cmp_selectedTheme() == 'pluto' ) {
            $placeholder            =  '&#xf003;  ' . $placeholder;
        }

        $submit = ( $this->cmp_selectedTheme() == 'postery' ) ? '&#xf1d9;' : $submit;
        $submit = ( $this->cmp_selectedTheme() == 'juno' ) ? '&#xf1d8;' : $submit;
        $submit = ( $this->cmp_selectedTheme() == 'agency' ) ? '&#xf105;' : $submit;
        $submit = ( $this->cmp_selectedTheme() == 'libra' ) ? '&#xf1d9;' : $submit;

        ?>
        
        <form id="subscribe-form" method="post" class="cmp-subscribe">
            <div class="cmp-form-inputs">

                <?php wp_nonce_field('save_options','save_options_field'); ?>
                <?php
                // display placeholders or labels
                switch ( $label ) {
                    case TRUE:
                        if ( $firstname === TRUE ) { ?>
                            <div class="firstname input-wrapper">
                                <label for="firstname-subscribe"><?php echo esc_attr( $placeholder_firstname );?></label>
                                <input type="text" id="firstname-subscribe" name="cmp_firstname">
                            </div>
                            <?php 
                        }

                        if ( $lastname === TRUE ) { ?>
                            <div class="lastname input-wrapper">
                                <label for="lastname-subscribe"><?php echo esc_attr( $placeholder_lastname );?></label>
                                <input type="text" id="lastname-subscribe" name="cmp_lastname">
                            </div>
                            <?php 
                        } ?>
                        <div class="email input-wrapper">
                            <label for="email-subscribe"><?php echo esc_attr( $placeholder );?></label>
                            <input type="email" id="email-subscribe" name="email" required>
                        </div>
                        <?php 
                        break;

                    case FALSE: 
                        if ( $firstname === TRUE ) { ?>
                            <input type="text" id="firstname-subscribe" name="cmp_firstname" placeholder="<?php echo esc_attr( $placeholder_firstname );?>">
                            <?php 
                        }

                        if ( $lastname === TRUE ) { ?>
                            <input type="text" id="lastname-subscribe" name="cmp_lastname" placeholder="<?php echo esc_attr( $placeholder_lastname );?>">
                            <?php 
                        } ?>

                        <input type="email" id="email-subscribe" name="email" placeholder="<?php echo esc_attr( $placeholder );?>" required> 
                        <?php 
                        break;

                    default:
                        break;
                } 

                if ( $this->cmp_selectedTheme() == 'mercury' ) { ?>
                    <button type="submit" id="submit-subscribe" value="<?php echo esc_attr( $submit );?>"><?php echo esc_attr( $submit );?></button>
                    <?php 
                } else { ?>
                    <input type="submit" id="submit-subscribe" value="<?php echo esc_attr( $submit );?>">
                    <?php 
                } ?>

                <div style="display: none;">
                    <input type="text" name="form_honeypot" value="" tabindex="-1" autocomplete="off">
                </div>

                <div id="subscribe-response"><?php echo isset( $response ) ? $response : '';?></div>

                <div id="subscribe-overlay"></div>
            </div>

            <?php 
            // render Subscribe form Message/GDPR
            if ( $niteoCS_subscribe_label != '' ) {

                $allowed_html = array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                );
                ?>

                <div class="cmp-form-notes">
                <?php echo wpautop(wp_kses( $niteoCS_subscribe_label, $allowed_html )); ?>
                </div>
                <?php 
            } ?>

        </form>

        <script>
            /* Subscribe form script */
            <?php 
            $url = parse_url( admin_url() );
            $path = isset($url['path']) ? $url['path'] : '/wp-admin/';
            ?>
            
            var ajaxurl = '<?php echo esc_attr($path);?>admin-ajax.php';
            var security = '<?php echo wp_create_nonce( 'cmp-subscribe-action' );?>';
            var msg = '';

            window.addEventListener('load',function(event) {
                subForm( 'subscribe-form', 'submit-subscribe', 'subscribe-response', 'email-subscribe', 'firstname-subscribe', 'lastname-subscribe' );
            });

            subForm = function(formID, buttonID, resultID, emailID, firstnameID, lastnameID) {

                let selectForm = document.getElementById(formID); // Select the form by ID.
                let selectButton = document.getElementById(buttonID); // Select the button by ID.
                let selectResult = document.getElementById(resultID); // Select result element by ID.
                let emailInput =  document.getElementById(emailID); // Select email input by ID.
                let firstnameInput =  document.getElementById(firstnameID); // Select firstname input by ID.
                let lastnameInput =  document.getElementById(lastnameID); // Select lastname input by ID.
                let firstname = '';
                let lastname = '';
                selectButton.onclick = function(e){ // If clicked on the button.

                    if ( emailInput.value !== '' ) {
                        firstname = firstnameInput === null ? '' : firstnameInput.value;
                        lastname = lastnameInput === null ? '' : lastnameInput.value;
                        
                        // I'm using the whatwg-fetch polyfill and a polyfill for Promises.
                        fetch(ajaxurl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
                                'Access-Control-Allow-Origin': '*',
                            },
                            body: `action=niteo_subscribe&ajax=true&form_honeypot=&email=${emailInput.value}&firstname=${firstname}&lastname=${lastname}&security=${security}`,
                            credentials: 'same-origin'
                        })
                        .then((res) => {
                            return res.json();
                        })
                        .then((data) => {
                            selectResult.innerHTML = data.message; // Display the result inside result element.
                            selectForm.classList.add('-subscribed');
                            if (data.status == 1) {
                                selectForm.classList.remove('-subscribe-failed');
                                selectForm.classList.add('-subscribe-successful');
                                emailInput.value = '';
                                firstnameInput ? firstnameInput.value = '' : null;
                                lastnameInput ? lastnameInput.value = '' : null;
                                <?php do_action('cmp-successfull-subscribe-action'); ?>

                            } else {
                                selectForm.classList.add('-subscribe-failed');
                            }
                        })
                        .catch(function(error) { console.log(error.message); });
                    }
                }

                selectForm.onsubmit = function(){ // Prevent page refresh
                    return false;
                }
            }
        </script>
        <?php 
        break;
    // MailOPtin
    case '3':

        if ( defined('MAILOPTIN_VERSION_NUMBER') ) {

            $campaign_id = get_option('niteoCS_mailoptin_selected');
            $campaign= MailOptin\Core\Repositories\OptinCampaignsRepository::get_optin_campaign_by_id($campaign_id);
            if ( $campaign['optin_type'] !== 'lightbox' ) {
                if ( !$this->jquery ) {
                    echo '<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" Crossorigin="anonymous"></script>';
                    $this->jquery = TRUE;
                }
                $html = do_shortcode( '[mo-optin-form id="'. get_option('niteoCS_mailoptin_selected') .'"]' );
            }
        }
        break;
    default:
        break;
}

return $html;