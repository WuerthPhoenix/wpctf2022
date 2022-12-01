<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");

include_once "init.php";

use Wp\Sfb\Util\UploadHelper;
use Wp\Sfb\Auth\PermissionHandler;

// check authentication / permission
$user = PermissionHandler::getAuthenticatedUser();
if (!$user->canUpload()) {
    http_response_code(403);
    exit();
}

// get group_id
$group_id = '';
if (isset($_POST['group_id'])) {
    $group_id = $_POST['group_id'];
}

$response = array(
    "success" => false
);
//$upload_dir = '../uploads/';
//$server_url = 'http://127.0.0.1:8000/api';

$uploaded_file = UploadHelper::getUploadedFile();

if ($uploaded_file && $group_id) {
    $uploadHelper = new UploadHelper();
    $file = $uploadHelper->storeUploadedFile($uploaded_file, $user, $group_id);
    if ($file) {
        $response['success'] = true;
        $response['file'] = [
            "id" => $file->getId(),
            "name" => $file->getName(),
            "size" => $file->getSize(),
            "path" => $file->getPath(),
            "user_id" => $file->getUserId(),
            "group_id" => $file->getGroupId(),
        ];
    }
}
if (!$response['success']) {
    http_response_code(500);
}
echo json_encode($response);

