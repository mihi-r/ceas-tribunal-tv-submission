<?php
error_reporting(-1);

/** 
 * Check the file size and the file type to see if it's the expected format.
 * 
 * @param array $file The file to evaluate.
 * @param int $mb_size_limit The limit of the maximum size in megabytes a file can be.
 * @param array $expected_mime_types The allowed MIME types of the files.
 * 
 * @return object A $file_check_result which holds the status of the check and an optional message.
*/
function checkFile($file, $mb_size_limit, $expected_mime_types) {
    if (!isset($file_check_result)) 
        $file_check_result = new stdClass();
    $file_check_result->file_safe = true;
    $file_check_result->message = "";

    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            $file_check_result->file_safe = false;
            $file_check_result->message = 'No file sent.';
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $file_check_result->file_safe = false;
            $file_check_result->message = 'Exceeded resume filesize limit of ' . $mb_size_limit . "MB.";
        default:
            $file_check_result->file_safe = false;
            $file_check_result->message = 'Unknown errors. Try registering again. If the problem persists, select a different resume file or contact the number in the description.';
    }
    
    // Check file size
    $b_size_limit = (2**20) * $mb_size_limit;
    if ($file['size'] > $b_size_limit) {
        $file_check_result->file_safe = false;
        $file_check_result->message = 'Exceeded resume filesize limit of' . $mb_size_limit . "MB.";
    }
    
    // Check file type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $ext = array_search($finfo->file($file['tmp_name']), $expected_mime_types, true);
    if ($ext === false) {
        $file_check_result->file_safe = false;

        $file_types = array_keys($expected_mime_types);
        $file_types_str = "";

        foreach ($file_types as $file_type) {
            $file_types_str .= $file_type;
            $file_types_str .= ", ";
        }

        $file_types_str = substr($file_types_str, 0, -2);

        $file_check_result->message = $file['name'] . ' is an invalid file format. Please only use ' . $file_types_str . ' format(s).';
    }

    return $file_check_result;
}
?>