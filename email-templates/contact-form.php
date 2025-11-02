<?php
/**
 * Secure Contact Form Handler
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
if (!checkRateLimit(5, 3600)) {
	logError('Rate limit exceeded for IP: ' . $_SERVER['REMOTE_ADDR']);
	sendJsonResponse('alert-danger', 'Too many requests. Please try again later.');
}

// Validate and sanitize inputs
if (empty($_POST['email'])) {
	sendJsonResponse('alert-danger', 'Please add an email address!');
}

$from = validateEmail($_POST['email']);
if (!$from) {
	logError('Invalid email attempt: ' . sanitizeInput($_POST['email']));
	sendJsonResponse('alert-danger', 'Invalid email address!');
}

$name 	= isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
$phone 	= isset($_POST['phone']) ? validatePhone($_POST['phone']) : '';
$comment = isset($_POST['comment']) ? sanitizeInput($_POST['comment']) : '';

// Additional validation
if (empty($name) || empty($comment)) {
	sendJsonResponse('alert-danger', 'Please fill in all required fields!');
}

// Validate name length
if (strlen($name) > 100) {
	sendJsonResponse('alert-danger', 'Name is too long!');
}

// Validate comment length
if (strlen($comment) > 5000) {
	sendJsonResponse('alert-danger', 'Message is too long!');
}

// Enable / Disable SMTP
$enable_smtp = 'no'; // yes OR no

// Email configuration
$receiver_email = defined('RECEIVER_EMAIL') ? RECEIVER_EMAIL : 'info@yourdomain.com';
$receiver_name 	= defined('RECEIVER_NAME') ? RECEIVER_NAME : 'Your Name';
$subject = 'Contact form details';
	
$message = '
	<html>
	<head>
	<title>HTML email</title>
	</head>
	<body>
	<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
	<td colspan="2" align="center" valign="top"><img style="margin-top: 15px;" src="http://www.yourdomain.com/images/logo-email.png" ></td>
	</tr>
	<tr>
	<td width="50%" align="right">&nbsp;</td>
	<td align="left">&nbsp;</td>
	</tr>
	<tr>
	<td align="right" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;">Name:</td>
	<td align="left" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;">' . $name . '</td>
	</tr>
	<tr>
	<td align="right" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;">Email:</td>
	<td align="left" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;">' . $from . '</td>
	</tr>
	<tr>
	<td align="right" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;">Phone:</td>
	<td align="left" valign="top" style="border-top:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;">' . $phone . '</td>
	</tr>
	<tr>
	<td align="right" valign="top" style="border-top:1px solid #dfdfdf; border-bottom:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 5px 7px 0;">Message:</td>
	<td align="left" valign="top" style="border-top:1px solid #dfdfdf; border-bottom:1px solid #dfdfdf; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#000; padding:7px 0 7px 5px;">' . nl2br($comment) . '</td>
	</tr>
	</table>
	</body>
	</html>
	';

if ($enable_smtp == 'no') { // Simple Email

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	// More headers - Use safe from address
	$headers .= 'From: ' . $name . ' <' . $from . '>' . "\r\n";
	$headers .= 'Reply-To: ' . $from . "\r\n";
	$headers .= 'X-Mailer: PHP/' . phpversion();
	
	if (mail($receiver_email, $subject, $message, $headers)) {

		// Validate and sanitize redirect URL
		$redirect_page_url = !empty($_POST['redirect']) ? validateRedirectUrl($_POST['redirect']) : '';
		if (!empty($redirect_page_url)) {
			header("Location: " . $redirect_page_url);
			exit();
		}

	   	//Success Message
	  	sendJsonResponse('alert-success', 'Your message has been sent successfully!');
	} else {
		logError('Mail send failed for: ' . $from);
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
		$mail->setFrom($from, $name);
		$mail->addReplyTo($from, $name);
		
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
		  	sendJsonResponse('alert-success', 'Your message has been sent successfully!');
		} else {
			logError('SMTP send failed: ' . $mail->ErrorInfo);
			//Fail Message
		  	sendJsonResponse('alert-danger', 'Your message could not been sent!');
		}
	} catch (Exception $e) {
		logError('SMTP Exception: ' . $e->getMessage());
		sendJsonResponse('alert-danger', 'Your message could not been sent!');
	}
}