<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
set_include_path('./includes/');
require_once('mysqli.php');
require_once('check_file.php');
require_once('PHPMailer/Exception.php');
require_once('PHPMailer/PHPMailer.php');


//    ~~Declare/recieve data~~
$name = '';
$org_name = '';
$email = '';
$description = '';
$name = mysqli_real_escape_string($mysqli, strip_tags(trim($_POST["nameText"])));
$org_name = mysqli_real_escape_string($mysqli, strip_tags(trim($_POST["orgNameText"])));
$email = mysqli_real_escape_string($mysqli, strip_tags(trim($_POST["emailText"])));
$description = mysqli_real_escape_string($mysqli, strip_tags(trim($_POST["descriptionText"])));
$slide_image = $_FILES['imageFile'];

//    ~~Validate data~~
DEFINE('SLIDE_IMAGE_MAX_FILE_SIZE', 2);
$slide_image_mime_types = array(
    'png' => 'image/png',
    'jpeg' => 'image/jpeg',
    'jpg' => 'image/jpeg'
);

//create error message in case a check fails
if (!isset($result_data)) 
    $result_data = new stdClass();
$result_data->status = 'error';
$result_data->message = '';

//check slide submission image
$slide_image_check_result = checkFile($slide_image, SLIDE_IMAGE_MAX_FILE_SIZE, $slide_image_mime_types);


if (!$slide_image_check_result->file_safe) {
    $result_data->message = $slide_image_check_result->message;
    echo json_encode($result_data);
    die();
}

//check name
if (!preg_match("/^[\w\ \'\.]{1,256}$/", $name)) {
    $result_data->message = 'Your name, ' . $name . ', is invalid. Please only use latin characters a-z or '
        . 'one of the following characters: -,@,.,#,&,!. Your name is also limited to 256 characters.';
    echo json_encode($result_data);
    die();
}

//check organization name
if (!preg_match("/^([a-zA-Z0-9]|[- @\.#&!,])*$/", $org_name)) {
    $result_data->message = 'Your organizaiton name, ' . $org_name . ', is invalid. Please only use numbers, latin characters a-z, or '
        . 'one of the following characters: -,@,.,#,&,!. Your organization name is also limited to 256 characters.';
    echo json_encode($result_data);
    die();
}

//check email
if(!preg_match('/^[\w\W]+@[\w\W\d]{1,256}$/', $email)) {
    $result_data->message = 'Your email, ' . $email . ', is invalid. Please use an email in the following format: <>@<>. '
        . 'Your email is also limited to 256 characters.';
    echo json_encode($result_data);
    die();
}

// Get admin name and email
$admin_name = '';
$admin_email = '';
$super_email = '';

$sql = 'SELECT admin_name, admin_email, super_email FROM tv_submission_info';
$result = $mysqli->query($sql);

if ($result) {
	while ($row = $result->fetch_assoc()) {
        $admin_name = $row['admin_name'];
        $admin_email = $row['admin_email'];
        $super_email = $row['super_email'];
    }
}

if ($admin_email === '' || $admin_name === '' || $super_email === '') {
    $result_data->message = 'Error occurred while retrieving admin information. Please try again. '
        . 'If the error persists, email the admin in the description.';
    echo json_encode($result_data);
    die();
}

// Create SQL command/Update table~
$sql = 'INSERT IGNORE INTO tv_submissions (name, org_name, email, description) ' 
    . "VALUES ('".$name."','".$org_name."','".$email."','".$description."')";
$result = $mysqli->query($sql);
if (!$result) {
    $result_data->message = 'Error occurred while submitting your information. Please try again. '
    . 'If the error persists, email the admin.';
    echo json_encode($result_data);
    die();
}

// Email user
$mail = new PHPMailer(true);

try {
    $mail->Subject = "CEAS TV Advertisement Submitted";

    $email_msg = "Hello " . $name . ", \n \n";
    $email_msg .= "This email is to confirm we have recieved your request on the behalf of " . $org_name . " to submit an advertisement to be displayed on the TVs in CEAS. ";
    $email_msg .= "Your advertisement will be evaluated and if we require any further information, we will contact you. \n \n";
    $email_msg .= "If you have any questions, feel free to reply back to this email. \n \n";
    $email_msg .= "Best regards, \n";
    $email_msg .= $admin_name;

    $mail->Body = $email_msg;
    $mail->setFrom($admin_email, $admin_name);
    $mail->addAddress($email, $name);
    $mail->send();
} catch (Exception $e) {
    $result_data->message = 'Error occurred while sending your confirmation email. Please email the admin in the footer notifying of this error.';
    echo json_encode($result_data);
    die();
}

// Email admin
$mail_admin = new PHPMailer(true);
try {
    $mail_admin->Subject = "TV Ad Submission - CEAS Tribunal";
    $email_msg = "Hello " . $admin_name . ", \n \n";
    $email_msg .= "TV ad has been submitted with the following information: \n";
    $email_msg .= "Name: " . $name . " \n";
    $email_msg .= "Organization Name: " . $org_name . " \n";
    $email_msg .= "Email: " . $email . " \n";
    $email_msg .= "Description: " . $description . " \n \n";
    $email_msg .= "The slide submission is attached to this email. ";
    $email_msg .= "Please review this slide and then approve or deny the submission by replying to " . $email . ". \n \n";
    $email_msg .= "Best regards, \n";
    $email_msg .= $super_email;
    $mail_admin->Body = $email_msg;
    $mail_admin->setFrom($super_email);
    $mail_admin->addAddress($admin_email, $admin_name);
    // Attach files
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $slide_image['tmp_name']);
    $mail_admin->AddAttachment($slide_image['tmp_name'], $slide_image['name'], 'base64', $mime);
    $mail_admin->send();
} catch (Exception $e) {
    $result_data->message = 'Error occurred while sending the admin the submission email. Please email the admin in the footer to notify them of this error.';
    echo json_encode($result_data);
    die();
}

$result_data->status = 'success';
echo json_encode($result_data);
mysqli_close($mysqli);
exit();
?>