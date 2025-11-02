<?php
// Include the PHPMailer library files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// NOTE: You must have these files in your project directory
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. COLLECT FORM DATA
    $name = strip_tags(trim($_POST['cf-name']));
    $email = filter_var(trim($_POST['cf-email']), FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST['cf-number']);
    $message = trim($_POST['cf-message']);
    
    // Check for necessary data
    if (empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete the form and try again.";
        exit;
    }

    // 2. CONFIGURE PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings for SMTP (Gmail, Outlook, etc.)
        // This is where you configure the SMTP details
        $mail->isSMTP();                                       
        $mail->Host       = 'smtp.gmail.com'; // e.g., 'smtp.gmail.com' or 'mail.yourdomain.com'
        $mail->SMTPAuth   = true;                                 
        $mail->Username   = 'johnstonekipkosgei31@gmail.com'; // e.g., 'contact@yourdomain.com'
        $mail->Password   = 'imoomzklojkynvvf'; // Use an App Password for Gmail/Outlook
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // or ENCRYPTION_STARTTLS
        $mail->Port       = 465; // Use 587 for STARTTLS, 465 for SMTPS


        //Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('info@360irrigationhub.co.ke', 'Edwin Ngetich'); // Add a recipient
        
        //Content
        $mail->isHTML(true);                                  
        $mail->Subject = 'New Contact Form Submission from ' . $name;
        
        $email_content = "<h2>New Contact Request</h2>";
        $email_content .= "<p><strong>Name:</strong> {$name}</p>";
        $email_content .= "<p><strong>Email:</strong> {$email}</p>";
        $email_content .= "<p><strong>Phone:</strong> {$phone}</p>";
        $email_content .= "<p><strong>Message:</strong><br>{$message}</p>";
        
        $mail->Body    = $email_content;
        $mail->AltBody = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nMessage: {$message}";

        $mail->send();
        
        // Redirect upon success
        header("Location: index.php"); 
        exit;
        
    } catch (Exception $e) {
        http_response_code(500);
        echo "Oops! Something went wrong, and your message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>