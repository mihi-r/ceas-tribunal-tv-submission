<?php
//    ~~Settings~~
error_reporting(-1);

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
$org_name = mysqli_real_escape_string($mysqli, strip_tags(trim($_POST["org_nameText"])));
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
    $result_data->message = 'Your name, ' . $name . ', is invalid. Please only use latin characters a-z with an optional '
        . 'apostrophe or period. Your name is also limited to 128 characters.';
    echo json_encode($result_data);
    die();
}

//check organization name
if (!preg_match("/^[\w\ \'\.]{1,256}$/", $org_name)) {
    $result_data->message = 'Your organizaiton name, ' . $org_name . ', is invalid. Please only use latin characters a-z with an optional '
        . 'apostrophe or period. Your organization name is also limited to 128 characters.';
    echo json_encode($result_data);
    die();
}

//check email
if(!preg_match('/^[\w\W]+@[\w\W\d]{1,256}$/', $email)) {
    $result_data->message = 'Your email, ' . $email . ', is invalid. Please use an email in the following format: <>@<>. '
        . 'Your email is also limited to 128 characters.';
    echo json_encode($result_data);
    die();
}

//check description
if (!preg_match("/^[\w\ \'\.]{1,2000}$/", $description)) {
    $result_data->message = 'Your description, ' . $description . ', is invalid. Please only use latin characters a-z with an optional '
        . 'apostrophe or period. Your description is also limited to 128 characters.';
    echo json_encode($result_data);
    die();
}


//    ~~Create SQL command/Update table~~
$sql = 'INSERT IGNORE INTO tv_submissions (name, org_name, email, description) ' 
    . "VALUES ('".$name."','".$org_name."','".$email."','".$description."')";
$result = $mysqli->query($sql);
if (!$result) {
    $result_data->message = 'Error occurred while submitting your information. Please try again. '
    . 'If the error persists, email the admin.';
    echo json_encode($result_data);
    die();
}


//    ~~Email admin~~
// $mail_admin = new PHPMailer(true);
// try {
//     $mail_admin->Subject = "Baldwin TV - Slide Submitted";
//     $email_msg = "Hello " . $admin_name . ", \n \n";
//     $email_msg .= "Baldwin TV slides have been submitted with the following information: \n";
//     $email_msg .= "Name: " . $name . " \n";
//     $email_msg .= "Organization Name: " . $org_name . " \n";
//     $email_msg .= "Email: " . $email . " \n";
//     $email_msg .= "Description: " . $description . " \n";
//     $email_msg .= "The slide submission is attached to this email. ";
//     $email_msg .= "Please review this slide and then approve or deny the submission. \n \n";
//     $email_msg .= "Best regards, \n";
//     $email_msg .= $super_email;
//     $mail_admin->Body = $email_msg;
//     $mail_admin->setFrom($super_email);
//     $mail_admin->addAddress($admin_email, $admin_name);
//     // Attach files
//     $finfo = finfo_open(FILEINFO_MIME_TYPE);
//     $mime = finfo_file($finfo, $slide_image['tmp_name']);
//     $mail_admin->AddAttachment($slide_image['tmp_name'], $slide_image['name'], 'base64', $mime); //Image instead of form
//     $mail_admin->send();
// } catch (Exception $e) {
//     $result_data->message = 'Error occurred while sending the admin the submission email. Please email the admin to notify them of this error.';
//     echo json_encode($result_data);
//     die();
// }


//    ~~End~~
echo json_encode($resultData);
mysqli_close($mysqli);
exit();
?>