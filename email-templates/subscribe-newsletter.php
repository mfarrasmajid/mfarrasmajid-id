<?php
/**
 * Secure Newsletter Subscription Handler
 * Implementasi security measures: input validation, sanitization, CSRF protection, rate limiting
 */

// Error handling configuration
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Load security functions
require_once 'security-functions.php';

// Load configuration if exists
if (file_exists(__DIR__ . '/config.php')) {
	require_once __DIR__ . '/config.php';
}

// Set default values if config not loaded
if (!defined('RECEIVER_EMAIL')) {
	define('RECEIVER_EMAIL', 'info@yourdomain.com');
}
if (!defined('RECEIVER_NAME')) {
	define('RECEIVER_NAME', 'Your Name');
}

// Check request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	sendJsonResponse('alert-danger', 'Invalid request method!');
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
	logError('CSRF token validation failed');
	sendJsonResponse('alert-danger', 'Security validation failed. Please refresh the page and try again.');
}

// Check rate limiting
if (!checkRateLimit(3, 3600)) { // More restrictive for newsletter
	logError('Rate limit exceeded for IP: ' . $_SERVER['REMOTE_ADDR']);
	sendJsonResponse('alert-danger', 'Too many requests. Please try again later.');
}

// Validate and sanitize inputs
if (empty($_POST['email'])) {
	sendJsonResponse('alert-danger', 'Please add an email address!');
}

$email = validateEmail($_POST['email']);
if (!$email) {
	logError('Invalid email attempt: ' . sanitizeInput($_POST['email']));
	sendJsonResponse('alert-danger', 'Invalid email address!');
}

// Enable / Disable Mailchimp
$enable_mailchimp = 'no'; // yes OR no

// Enable / Disable SMTP
$enable_smtp = 'no'; // yes OR no

// Email configuration
$receiver_email = defined('RECEIVER_EMAIL') ? RECEIVER_EMAIL : 'info@yourdomain.com';
$receiver_name 	= defined('RECEIVER_NAME') ? RECEIVER_NAME : 'Your Name';
$subject 	= 'Subscribe Newsletter form details';
if ($enable_mailchimp == 'no') { // Simple / SMTP Email

	$name 	= isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';

	$message = '
	<html>
	<head>
	<title>HTML email</title>
	</head>
	<body>
	<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
	<td colspan="2" align="center" valign="top"><img style=" margin-top: 15px; " src="http://www.yourdomain.com/images/logo-email.png" ></td>
	</tr>
	<tr>
	<td width="50%" align="right">&nbsp;</td>
	<td align="left">&nbsp;</td>
	</tr>';
	if (!empty($name)) {
		$message .= '<tr>
		<td align="right" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;">Name:</td>
		<td align="left" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;">' . $name . '</td>
		</tr>';
	}
	$message .= '<tr>
	<td align="right" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;">Email:</td>
	<td align="left" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;">' . $email . '</td>
	</tr>
	</table>
	</body>
	</html>
	';

	if ($enable_smtp == 'no') { // Simple Email

		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// More headers
		$headers .= 'From: <' . $email . '>' . "\r\n";
		$headers .= 'Reply-To: ' . $email . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		
		if (mail($receiver_email, $subject, $message, $headers)) {
			
			// Validate and sanitize redirect URL
			$redirect_page_url = !empty($_POST['redirect']) ? validateRedirectUrl($_POST['redirect']) : '';
			if (!empty($redirect_page_url)) {
				header("Location: " . $redirect_page_url);
				exit();
			}

		   	//Success Message
		  	sendJsonResponse('alert-success', 'Your message has been sent successfully subscribed to our email list!');
		} else {
			logError('Newsletter mail send failed for: ' . $email);
			//Fail Message
		  	sendJsonResponse('alert-danger', 'Your message could not been sent!');
		}

	} else { // SMTP
		// Email Receiver Addresses
		$toemailaddresses = array();
		$toemailaddresses[] = array(
			'email' => $receiver_email,
			'name' 	=> $receiver_name
		);

		require 'phpmailer/Exception.php';
		require 'phpmailer/PHPMailer.php';
		require 'phpmailer/SMTP.php';

		$mail = new PHPMailer\PHPMailer\PHPMailer();

		try {
			$mail->isSMTP();
			$mail->Host     = defined('SMTP_HOST') ? SMTP_HOST : 'YOUR_SMTP_HOST';
			$mail->SMTPAuth = true;
			$mail->Username = defined('SMTP_USERNAME') ? SMTP_USERNAME : 'YOUR_SMTP_USERNAME';
			$mail->Password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : 'YOUR_SMTP_PASSWORD';
			$mail->SMTPSecure = defined('SMTP_SECURE') ? SMTP_SECURE : 'ssl';
			$mail->Port     = defined('SMTP_PORT') ? SMTP_PORT : 465;
			
			// Use verified sender address for SMTP authentication
			$mail->setFrom($receiver_email, $receiver_name);
			// Set user's email as reply-to with name if available
			if (!empty($name)) {
				$mail->addReplyTo($email, $name);
			} else {
				$mail->addReplyTo($email);
			}
			
			foreach ($toemailaddresses as $toemailaddress) {
				$mail->AddAddress($toemailaddress['email'], $toemailaddress['name']);
			}

			$mail->Subject = $subject;
			$mail->isHTML(true);
			$mail->Body = $message;

			if ($mail->send()) {
				
				// Validate and sanitize redirect URL
				$redirect_page_url = !empty($_POST['redirect']) ? validateRedirectUrl($_POST['redirect']) : '';
				if (!empty($redirect_page_url)) {
					header("Location: " . $redirect_page_url);
					exit();
				}

			   	//Success Message
			  	sendJsonResponse('alert-success', 'Your message has been sent successfully subscribed to our email list!');
			} else {
				logError('Newsletter SMTP send failed: ' . $mail->ErrorInfo);
				//Fail Message
			  	sendJsonResponse('alert-danger', 'Your message could not been sent!');
			}
		} catch (Exception $e) {
			logError('Newsletter SMTP Exception: ' . $e->getMessage());
			sendJsonResponse('alert-danger', 'Your message could not been sent!');
		}
	}

} else { // Mailchimp

	$api_key 	= defined('MAILCHIMP_API_KEY') ? MAILCHIMP_API_KEY : 'YOUR_MAILCHIMP_API_KEY';
	$list_id 	= defined('MAILCHIMP_LIST_ID') ? MAILCHIMP_LIST_ID : 'YOUR_MAILCHIMP_LIST_ID';
	$status 	= 'subscribed';
	$f_name		= !empty($_POST['name']) ? sanitizeInput($_POST['name']) : substr($email, 0, strpos($email,'@'));

	$data = array(
		'apikey'        => $api_key,
    	'email_address' => $email,
		'status'        => $status,
		'merge_fields'  => array('FNAME' => $f_name)
	);
	$mch_api = curl_init(); // initialize cURL connection
 
	curl_setopt($mch_api, CURLOPT_URL, 'https://' . substr($api_key, strpos($api_key, '-') + 1) . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5(strtolower($data['email_address'])));
	curl_setopt($mch_api, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.base64_encode('user:' . $api_key)));
	curl_setopt($mch_api, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	curl_setopt($mch_api, CURLOPT_RETURNTRANSFER, true); // return the API response
	curl_setopt($mch_api, CURLOPT_CUSTOMREQUEST, 'PUT'); // method PUT
	curl_setopt($mch_api, CURLOPT_TIMEOUT, 10);
	curl_setopt($mch_api, CURLOPT_POST, true);
	curl_setopt($mch_api, CURLOPT_SSL_VERIFYPEER, true); // Changed to true for security
	curl_setopt($mch_api, CURLOPT_POSTFIELDS, json_encode($data)); // send data in json
 
	$result	= curl_exec($mch_api);
	$result = !empty($result) ? json_decode($result) : '';

	if (!empty($result->status) AND $result->status == 'subscribed') {
		
		// Validate and sanitize redirect URL
		$redirect_page_url = !empty($_POST['redirect']) ? validateRedirectUrl($_POST['redirect']) : '';
		if (!empty($redirect_page_url)) {
			header("Location: " . $redirect_page_url);
			exit();
		}

	   	//Success Message
		sendJsonResponse('alert-success', 'Your message has been sent successfully subscribed to our email list!');
	} else {
		logError('Mailchimp subscription failed for: ' . $email);
		//Fail Message
		sendJsonResponse('alert-danger', 'Your message could not been sent!');
	}
}