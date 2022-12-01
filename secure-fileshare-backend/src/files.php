<?php

include_once "init.php";

use Wp\Sfb\Model\File;
use Wp\Sfb\Util\FileRepository;
use Wp\Sfb\Auth\PermissionHandler;

header("Content-Type: application/json");

$user = PermissionHandler::getAuthenticatedUser();

$action = $_SERVER['REQUEST_METHOD'];
$action = $_REQUEST['action'] ?? $_SERVER['REQUEST_METHOD'];

$frepo = new FileRepository();

if ($action === 'GET') {

    if (isset($_GET['id'])) {
        if (!$user->canDownload()) {
            http_response_code(403);
            exit();
        }
        // download a file
        $file = $frepo->get($_GET['id']);
        $path = UPLOAD_PATH . "/" . $file->getPath();
        header("Content-Type: application/octet-stream", true);
        $content = file_get_contents($path);
        if ($content) {
            print $content;
        } else {
            http_response_code(500);
        }
        exit(0);
    } else {
        // get all files
        if (!$user->canListFiles()) {
            http_response_code(403);
            exit();
        }

        $files = $frepo->getAllOfGroup($user->getGroupId());

        print json_encode($files);
    }
} elseif ($action === 'DELETE') {
    if (!$user->canDeleteFiles()) {
        http_response_code(403);
        exit();
    }

    if (!isset($_REQUEST['id'])) {
        http_response_code(400);
        exit();
    }

    $result = [
        'success' => false
    ];
    if ($file = $frepo->delete($_REQUEST['id'])) {
        $result['file'] = $file;
        $result['success'] = true;
    }
    print json_encode($result);
}



