<?php
/**
 * Created by PhpStorm.
 * function-contact-forms.php
 * Definitions for functions relating to contact forms
 * User: gilbert
 * Date: 5/18/2015
 * Time: 3:11 PM
 */

function k2_contact_form_use_dev_or_this($email){
  //Chris said to use my email address for now - Yossi
  return 'development' == SERVER_ENVIRONMENT ? 'yossi.wolfe+devtest@komando.com' : $email;
}

function k2_contact_form_newsletter_subscribe($email){
    $url = 'https:' . CLUB_BASE_URI . '/newsletters/subscribe.json?lists=alerts,kimsnewsletter,kimsdailynews,kimstipoftheday,kimsinsider';
    $email = "email=".urlencode($email);

//open connection
    $ch = curl_init();

//set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $email);

//execute post
    $result = curl_exec($ch);

//close connection
    curl_close($ch);

    return $result;
}

############
## Contact Form
############

function k2_contact_form() {

    // Setting the timezone
    date_default_timezone_set('America/Phoenix');

    // Form vars being cleaned
    $company_name = htmlspecialchars(stripslashes($_GET['contact-company-name']));
    $company_url = htmlspecialchars(stripslashes($_GET['contact-company-url']));
    $full_name = htmlspecialchars(stripslashes($_GET['contact-full-name']));
    $first_name = htmlspecialchars(stripslashes($_GET['contact-first-name']));
    $last_name = htmlspecialchars(stripslashes($_GET['contact-last-name']));
    $title = htmlspecialchars(stripslashes($_GET['contact-title']));
    $email = htmlspecialchars(stripslashes($_GET['contact-email']));
    $phone = htmlspecialchars(stripslashes($_GET['contact-phone']));

    // Form address being cleaned and combined
    $address = htmlspecialchars(stripslashes($_GET['contact-address']));
    $csp = htmlspecialchars(stripslashes($_GET['contact-city-state-postal']));
    $address = $address . ', ' . $csp;

    // Form vars continued
    $order_number = htmlspecialchars(stripslashes($_GET['contact-order-number']));
    $problem_page = htmlspecialchars(stripslashes($_GET['contact-problem-page']));
    $age = htmlspecialchars(stripslashes($_GET['contact-age']));
    $station = htmlspecialchars(stripslashes($_GET['contact-station']));
    $ad_name = htmlspecialchars(stripslashes($_GET['contact-advertiser-name']));
    $ad_location = htmlspecialchars(stripslashes($_GET['contact-advertiser-location']));
    $cool_site_url = htmlspecialchars(stripslashes($_GET['contact-cool-site-url']));
    $budget = $_GET['contact-budget'];
    $message = htmlspecialchars(stripslashes($_GET['contact-message']));
    $description = htmlspecialchars(stripslashes($_GET['contact-description']));
    $pr = htmlspecialchars(stripslashes($_GET['contact-pr']));
    $newsletter_subscribe = htmlspecialchars(stripslashes($_GET['newsletter-subscribe']));

    // Advertising specific form vars
    $advertiser_brand_recognition = htmlspecialchars(stripslashes($_GET['advertiser-brand-recognition']));
    $advertiser_b2b = htmlspecialchars(stripslashes($_GET['advertiser-b2b']));
    $advertiser_b2c = htmlspecialchars(stripslashes($_GET['advertiser-b2c']));
    $advertiser_customer_acquisition = htmlspecialchars(stripslashes($_GET['advertiser-customer-acquisition']));
    $advertiser_increase_exposure = htmlspecialchars(stripslashes($_GET['advertiser-increase-exposure']));
    $advertising_prev_spoken = htmlspecialchars(stripslashes($_GET['advertising-prev-spoken']));

    //Make an Appointment specific form vars
    $make_appointment_name = htmlspecialchars(stripslashes($_GET['contact-make-an-appointment-name']));
    $make_appointment_gender = ($_GET['contact-make-an-appointment-gender'] == "male" ? "Male" : "Female");
    $make_appointment_email = htmlspecialchars(stripslashes($_GET['contact-make-an-appointment-email']));
    $make_appointment_phone = htmlspecialchars(stripslashes($_GET['contact-make-an-appointment-phone']));
    $make_appointment_city = htmlspecialchars(stripslashes($_GET['contact-make-an-appointment-city']));
    $make_appointment_state = htmlspecialchars(stripslashes($_GET['contact-make-an-appointment-state']));
    $make_appointment_spoken_previously = ($_GET['contact-make-an-appointment-spoken-previously'] == "yes" ? "Yes" : "No");
    $make_appointment_date = htmlspecialchars(stripslashes($_GET['contact-make-an-appointment-date']));
    $make_appointment_time = htmlspecialchars(stripslashes($_GET['contact-make-an-appointment-time']));
    $make_appointment_question = htmlspecialchars(stripslashes($_GET['contact-make-an-appointment-question']));

    // Seperating the debug info - page-contact-form.php
    // info[0] = nonce
    // info[1] = IP
    // info[2] = referer
    // info[3] = timestamp
    // info[4] = useragent
    $info = explode('##', base64_decode($_GET['contact-info']));
    $start_time = $info[3] . ' (' . date('F j, Y @ H:i:s T', $info[3]) . ')';
    $cur_time = time();
    $sent_time = $cur_time . ' (' . date('F j, Y @ H:i:s T', $cur_time) . ')';
    $spent_time = round(($cur_time - $info[3]) / 60);
    $screen_resolution = htmlspecialchars(stripslashes($_GET['screen-resolution']));
    $form_type = htmlspecialchars(stripslashes($_GET['form-type']));

    // Before the form gets set lets check the IP for rate limiting
    $ip = str_replace('.', '', $info[1]);
    $rate_limit_check = get_transient('contact_rate_limit_' . $ip);

    if ($rate_limit_check) {
        http_response_code(403);
        die(json_encode(array('message' => 'There were too many request from your IP (' . $info[1] . '). Please try again in a few minutes.', 'code' => 'RATE LIMIT')));
    }

    set_transient('contact_rate_limit_' . $ip, '1', 60);

    // Pushing clean data into an array with a title
    $form_data[] = array('title' => 'Company Name:', 'value' => $company_name);
    $form_data[] = array('title' => 'Company URL:', 'value' => $company_url);
    $form_data[] = array('title' => 'Full Name:', 'value' => $full_name);
    $form_data[] = array('title' => 'First Name:', 'value' => $first_name);
    $form_data[] = array('title' => 'Last Name:', 'value' => $last_name);
    $form_data[] = array('title' => 'Title:', 'value' => $title);
    $form_data[] = array('title' => 'Email:', 'value' => $email);
    $form_data[] = array('title' => 'Phone:', 'value' => $phone);
    $form_data[] = array('title' => 'Address:', 'value' => $address);
    $form_data[] = array('title' => 'Order Number:', 'value' => $order_number);
    $form_data[] = array('title' => 'Problem Page:', 'value' => $problem_page);
    $form_data[] = array('title' => 'Age:', 'value' => $age);
    $form_data[] = array('title' => 'Station:', 'value' => $station);
    $form_data[] = array('title' => 'Company/Ad:', 'value' => $ad_name);
    $form_data[] = array('title' => 'Ad Location:', 'value' => $ad_location);
    $form_data[] = array('title' => 'Cool Site URL:', 'value' => $cool_site_url);
    $form_data[] = array('title' => 'Budget:', 'value' => $budget);
    $form_data[] = array('title' => 'Message:', 'value' => $message);
    $form_data[] = array('title' => 'Description:', 'value' => $description);
    $form_data[] = array('title' => 'Press Release:', 'value' => $pr);
    $form_data[] = array('title' => 'Strengthen Brand Recognition:', 'value' => $advertiser_brand_recognition);
    $form_data[] = array('title' => 'Business-to-Business Sales:', 'value' => $advertiser_b2b);
    $form_data[] = array('title' => 'Business-to-Consumer Sales:', 'value' => $advertiser_b2c);
    $form_data[] = array('title' => 'Customer Name Acquisition:', 'value' => $advertiser_customer_acquisition);
    $form_data[] = array('title' => 'Increase Product Exposure:', 'value' => $advertiser_increase_exposure);
    $form_data[] = array('title' => 'Spoken to Ad Rep Recently:', 'value' => $advertising_prev_spoken);
    $form_data[] = array('title' => 'Remote IP:', 'value' => $info[1]);
    $form_data[] = array('title' => 'Referer:', 'value' => $info[2]);
    $form_data[] = array('title' => 'Start Timestamp:', 'value' => $start_time);
    $form_data[] = array('title' => 'Sent Timestamp:', 'value' => $sent_time);
    $form_data[] = array('title' => 'Minutes Spent:', 'value' => $spent_time);
    $form_data[] = array('title' => 'Useragent:', 'value' => $info[4]);
    $form_data[] = array('title' => 'Screen Resolution:', 'value' => $screen_resolution);
    $form_data[] = array('title' => 'Subscribe to Newsletters:', 'value' => $newsletter_subscribe);

    if($form_type == "contact-make-an-appointment"){
        $form_data = array();
        $form_data[] = array('title' => 'Full Name:', 'value' => $make_appointment_name);
        $form_data[] = array('title' => 'Gender:', 'value' => $make_appointment_gender);
        $form_data[] = array('title' => 'Email Address:', 'value' => $make_appointment_email);
        $form_data[] = array('title' => 'Phone Number:', 'value' => $make_appointment_phone);
        $form_data[] = array('title' => 'City:', 'value' => $make_appointment_city);
        $form_data[] = array('title' => 'State or Province:', 'value' => $make_appointment_state);
        $form_data[] = array('title' => 'Has Spoken to Kim Previously:', 'value' => $make_appointment_spoken_previously);
        $form_data[] = array('title' => 'Appointment Date:', 'value' => $make_appointment_date);
        $form_data[] = array('title' => 'Appointment Time:', 'value' => $make_appointment_time);
        $form_data[] = array('title' => 'Question:', 'value' => $make_appointment_question);
    }

    $otrs_queue = '';

    switch($form_type) {
        case 'contact-ask-kim':
            // webpagemail2@komando.com
            $email_to = k2_contact_form_use_dev_or_this('webpagemail2@komando.com');
            $email_subject = 'Contact Form - Ask Kim a Question';
            break;

        case 'contact-newsletters':
            // support@komando.com
            $email_to = k2_contact_form_use_dev_or_this('support@komando.com');
            $email_subject = 'Contact Form - Newsletter Subscriptions';
            $otrs_queue = 'Komando Newsletter Support';
            break;

        case 'contact-cool-site':
            // webpagemail2@komando.com
            $email_to = k2_contact_form_use_dev_or_this('webpagemail2@komando.com');
            $email_subject = 'Contact Form - Cool Site Submission';
            break;

        case 'contact-make-an-appointment':
            // webpagemail2@komando.com
            $email_to = k2_contact_form_use_dev_or_this('webpagemail2@komando.com');
            $email_subject = 'Appointment Request Submission from Komando.com/contact-us';
            break;

        case 'contact-advertiser-feedback':
            // support@komando.com
            $email_to = k2_contact_form_use_dev_or_this('support@komando.com');
            $email_subject = 'Contact Form - Advertiser Feedback';
            $otrs_queue = 'Komando Website Support';
            break;

        case 'contact-website-feedback':
            // support@komando.com
            $email_to = k2_contact_form_use_dev_or_this('support@komando.com');
            $email_subject = 'Contact Form - Website Feedback';
            $otrs_queue = 'Komando Website Support';
            break;

        case 'contact-advertising':
            // ad-req@komando.com
            $email_to = k2_contact_form_use_dev_or_this('ad-req@komando.com');
            $email_subject = 'Contact Form - Advertising';
            break;

        case 'contact-pr':
            // pr@komando.com
            $email_to = k2_contact_form_use_dev_or_this('pr@komando.com');
            $email_subject = 'Contact Form - Press Releases';
            break;

        case 'contact-order-trouble':
            // support@komando.com
            $email_to = k2_contact_form_use_dev_or_this('support@komando.com');
            $email_subject = 'Contact Form - Placing an Order';
            $otrs_queue = 'Komando eStore Support';
            break;

        case 'contact-order-return':
            // support@komando.com
            $email_to = k2_contact_form_use_dev_or_this('support@komando.com');
            $email_subject = 'Contact Form - Kim\'s Store Products';
            $otrs_queue = 'Komando eStore Support::Returns';
            break;

        case 'contact-club-access':
            // support@komando.com
            $email_to = k2_contact_form_use_dev_or_this('support@komando.com');
            $email_subject = 'Contact Form - Kim\'s Club Access';
            $otrs_queue = 'Kim\'s Club Support';
            break;

        case 'contact-club-billing':
            // support@komando.com
            $email_to = k2_contact_form_use_dev_or_this('support@komando.com');
            $email_subject = 'Contact Form - Kim\'s Club Billing';
            $otrs_queue = 'Kim\'s Club Support';
            break;

        case 'contact-affiliates':
            // affiliates@weststar.com
            $email_to = k2_contact_form_use_dev_or_this('affiliates@weststar.com');
            $email_subject = 'Contact Form - Affiliates';
            break;
    }

    // Email headers
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8" . "\r\n";
    $headers .= "From: Komando.com <no-reply@komando.com>" . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";
    $headers .= "X-OTRS-Queue: $otrs_queue" . "\r\n";

    if(!isset($email_body)){
        $email_body = "";
    }

    $hide_advert = 0;
    // Looping through the above array and only emailing content that is not empty
    foreach ($form_data as $data) {
        if (!empty($data['value'])) {
            if ($data['value'] == '$30,000 - $59,999' && $form_type != 'contact-advertising') {
                // do nothing, set var to hide contacted advertiser previously
                $hide_advert = 1;
            } else if ($data['title'] == 'Spoken to Ad Rep Recently:') {
                if(!$hide_advert) {
                    $email_body .= '<strong>' . $data['title'] . '</strong> ' . $data['value'] . '<br />';
                }
            } else if ($data['title'] == 'Address:') {
                if (strlen($data['value']) > 4) {
                    $email_body .= '<strong>' . $data['title'] . '</strong> ' . $data['value'] . '<br />';
                }
            } else if ($data['title'] == 'Remote IP:') {
                $email_body .= '<br /><br /><strong>Debug Information</strong><br />';
                $email_body .= '<strong>' . $data['title'] . '</strong> ' . $data['value'] . '<br />';
            } else {
                $email_body .= '<strong>' . $data['title'] . '</strong> ' . $data['value'] . '<br />';
            }
        }
    }

    // Checking the nonce from the form to make sure it matches and sending the email
    if (wp_verify_nonce($info[0], 'security')) {
        if('on' == $newsletter_subscribe){
            k2_contact_form_newsletter_subscribe($email);
        }
        wp_mail($email_to, $email_subject, $email_body, $headers);
        http_response_code(200);
        die(json_encode(array('message' => 'Message sent successfully.', 'code' => 'SENT')));
    } else {
        http_response_code(403);
        die(json_encode(array('message' => 'There was a problem submitting your form. Please refresh and try again.', 'code' => 'NONCE FAILED')));
    }
}
add_action('wp_ajax_k2_contact_form', 'k2_contact_form'); // This handles the contact form
add_action('wp_ajax_nopriv_k2_contact_form', 'k2_contact_form'); // This handles the contact form for non-admin users

############
## Corvette Contact Form
############
function k2_corvette_form() {

    // Setting the timezone
    date_default_timezone_set('America/Phoenix');

    $name = htmlspecialchars(stripslashes($_GET['contact-name']));
    $phone = htmlspecialchars(stripslashes($_GET['contact-phone']));
    $email = htmlspecialchars(stripslashes($_GET['contact-email']));
    $enquiry = htmlspecialchars(stripslashes($_GET['contact-enquiry']));
    $hp = htmlspecialchars(stripslashes($_GET['contact-hp']));
    $info = htmlspecialchars(stripslashes($_GET['contact-info']));
    $email_to = 'webpagemail2@komando.com, tawnya.hines@komando.com';

    // Seperating the debug info - page-contact-form.php
    $info = explode('##', base64_decode($_GET['contact-info']));
    $ip = $info[1];
    $referer = $info[2];

    $email_body  = '<strong>Name:</strong> ' . $name . '<br />';
    $email_body .= '<strong>Phone:</strong> ' . $phone . '<br />';
    $email_body .= '<strong>Email:</strong> ' . $email . '<br />';
    $email_body .= '<strong>Enquiry:</strong> ' . $enquiry . '<br /><br />';
    $email_body .= '<strong>IP:</strong> ' . $ip . '<br />';
    $email_body .= '<strong>Referer:</strong> ' . $referer;

    // Email headers
    $headers  = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8" . "\r\n";
    $headers .= "From: Komando.com <no-reply@komando.com>" . "\r\n";

    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $headers .= "Reply-To: $email" . "\r\n";
    } else {
        $headers .= "Reply-To: Komando.com <no-reply@komando.com>" . "\r\n";
    }

    // Checking the nonce from the form to make sure it matches and sending the email
    if (wp_verify_nonce($info[0], 'security') && empty($hp)) {
        wp_mail($email_to, 'Corvette Contact Form', $email_body, $headers);
        return true;
    }

    die();
}
add_action('wp_ajax_k2_corvette_form', 'k2_corvette_form'); // This handles the contact form
add_action('wp_ajax_nopriv_k2_corvette_form', 'k2_corvette_form'); // This handles the contact form for non-admin users

