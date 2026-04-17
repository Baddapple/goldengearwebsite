<?php
// Who you want to receive the emails from the form.
$sendto = 'gear@goldengearconsulting.com';

// The subject you'll see in your inbox
$subject = 'A message from Golden Gear Consulting Website';

// Message for the user when he/she doesn't fill in the form correctly.
$errormessage = 'Whoops, Looks like you are missing some info. Try again.';

// Message for the user when he/she fills in the form correctly.
$thanks = "Thanks for the email. We'll get back to you as soon as we can.";

// Message for the bot when it fills in the honeypot field.
$honeypot = "You filled in the honeypot! If you're human, try again!";

// Various messages displayed when the fields are empty.
$emptyname    = 'Entering your name?';
$emptyemail   = 'Entering your email address?';
$emptymessage = 'Entering a message?';

// Various messages displayed when the fields are incorrectly formatted.
$alertname    = 'Entering your name using only the standard alphabet?';
$alertemail   = 'Entering your email in this format: name@example.com?';
$alertmessage = "Making sure you aren't using any parenthesis or other escaping characters in the message? Most URLs are fine though!";

$alert = '';
$pass  = 0;

// Strip HTML tags, slashes and surrounding whitespace from a value.
function clean_var($variable) {
    $variable = strip_tags(stripslashes(trim($variable)));
    return $variable;
}

// Remove any characters that could be used for email header injection.
function sanitize_header($value) {
    return preg_replace('/[\r\n\t]/', '', $value);
}

// Only accept POST requests.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

if ( empty($_POST['last']) ) {

    if ( empty($_POST['name']) ) {
        $pass = 1;
        $alert .= "<li>" . htmlspecialchars($emptyname) . "</li>";
        $alert .= "<script>jQuery(\"#name\").addClass(\"error\");</script>";
    } elseif ( preg_match('/[][{}()*+?.\\\^$|]/', $_POST['name']) ) {
        $pass = 1;
        $alert .= "<li>" . htmlspecialchars($alertname) . "</li>";
    }

    if ( empty($_POST['email']) ) {
        $pass = 1;
        $alert .= "<li>" . htmlspecialchars($emptyemail) . "</li>";
        $alert .= "<script>jQuery(\"#email\").addClass(\"error\");</script>";
    } elseif ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
        $pass = 1;
        $alert .= "<li>" . htmlspecialchars($alertemail) . "</li>";
    }

    if ( empty($_POST['message']) ) {
        $pass = 1;
        $alert .= "<li>" . htmlspecialchars($emptymessage) . "</li>";
        $alert .= "<script>jQuery(\"#message\").addClass(\"error\");</script>";
    } elseif ( preg_match('/[][{}()*+\\\^$|]/', $_POST['message']) ) {
        $pass = 1;
        $alert .= "<li>" . htmlspecialchars($alertmessage) . "</li>";
    }

    if ( $pass == 1 ) {

        echo "<script>$(\".message\").toggle();$(\".message\").toggle().hide(\"slow\").show(\"slow\"); </script>";
        echo "<script>$(\".alert\").addClass('alert-block alert-error').removeClass('alert-success'); </script>";
        echo htmlspecialchars($errormessage);
        echo $alert;

    } elseif ( isset($_POST['message']) ) {

        $clean_name    = clean_var($_POST['name']);
        $clean_email   = sanitize_header(clean_var($_POST['email']));
        $clean_message = clean_var($_POST['message']);

        $body   = "From: " . $clean_name . "\n";
        $body  .= "Email: " . $clean_email . "\n";
        $body  .= "Message:\n" . $clean_message;
        $header = 'From: noreply@goldengearconsulting.com' . "\r\n" .
                  'Reply-To: ' . $clean_email . "\r\n" .
                  'X-Mailer: PHP/' . phpversion();

        mail($sendto, $subject, $body, $header);
        echo "<script>$(\".message\").toggle();$(\".message\").toggle().hide(\"slow\").show(\"slow\");$('#contactForm')[0].reset();</script>";
        echo "<script>$(\".alert\").addClass('alert-block alert-success').removeClass('alert-error'); </script>";
        echo htmlspecialchars($thanks);
        echo "<script>jQuery(\"#name\").removeClass(\"error\");jQuery(\"#email\").removeClass(\"error\");jQuery(\"#message\").removeClass(\"error\");</script>";
        exit;

    }

} else {
    echo "<script>$(\".message\").toggle();$(\".message\").toggle().hide(\"slow\").show(\"slow\"); </script>";
    echo htmlspecialchars($honeypot);
}
?>