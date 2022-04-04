<?php
/*
 */
// MailChimp API credentials and Audience ID
$apiKey = '2e570349373fe99f345d538a534040cb-us10';
$audienceID = 'ce3cf21900';
// Message that will be displayed when everything is OK :)
$okMessage = 'You\'ve successfully signed up. Thank you!';
// If something goes wrong, display this message.
$errorMessage = 'There was an error while submitting the form. Please try again later';

try {
    if (!empty($_POST)) {
        //Grab post data from form
        $email = $_POST['MERGE0'];
        $firstName = $_POST['MERGE1'];
        $lastName = $_POST['MERGE2']; 
// Building the MailChimp API URL
        $memberID = md5(strtolower($email));
        var_dump($memberID); echo '------------';
        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
        var_dump($dataCenter); echo '------------';
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $audienceID . '/members/' . $memberID;
        var_dump($url); echo '------------';
        
        // User Information to sent to MailChimp
        $json = json_encode([
            'email_address' => $email,
            'status'        => 'subscribed',
            'merge_fields'  => [
                'FNAME'     => $firstName,
                'LNAME'     => $lastName //This is a CUSTOM MERGE field that you must configure in MailChimp in order for it to work - Make sure you change the name to match your configured MERGE field name in MailChimp
            ],
            
            'update_existing'   => true // YES, update existing subscribers!
        ]);
        var_dump($json);die;
        
        // send a HTTP POST request with curl to MailChimp
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        
       $responseArray = array('type' => 'success', 'message' => $okMessage);
    }
} catch (\Exception $e) {
    $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
}
// If requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
header('Content-Type: application/json');
echo $encoded;
}
// Else just display the message
else {
    echo $responseArray['message'];
}