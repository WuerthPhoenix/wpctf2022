<?php

include_once "init.php";

use Wp\Sfb\Util\UserRepository;
use Wp\Sfb\Auth\PermissionHandler;

// check authentication / permission
$user = PermissionHandler::getAuthenticatedUser();
if (!$user->canListUsers()) {
    http_response_code(403);
    exit();
}

header("Content-Type: application/json");

$result = [
    'success' => false
];

if (isset($_GET['id'])) {
    $uRepo = new UserRepository();
    if ($user = $uRepo->getById($_GET['id'])) {
        $result['success'] = true;
        $result['user'] = [
            $user->toArray()
        ];
    }
} else if (isset($_GET['name']) && isset($_GET['full_name'])) {
    $uRepo = new UserRepository();
    if ($users = $uRepo->getByNameAndFullname($_GET['name'], $_GET['full_name'])) {
        $result['success'] = true;
        foreach ($users as $user) {
            $result['user'][] = $user->toArray();
        }
    }
}

print json_encode($result);
