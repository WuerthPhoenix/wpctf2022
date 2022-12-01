<?php

include_once "init.php";

use Wp\Sfb\Util\UserRepository;
use \Wp\Sfb\Util\MfaCheckRepository;

$result = array(
    'success' => false,
    'error' => "invalid request"
);

$data = json_decode(file_get_contents('php://input'), true);
$user = $data["username"];
$pass = $data["password"];

if (!empty($user) && !empty($pass)) {
    $uRepo = new UserRepository();
    if ($user = $uRepo->getByLogin($user, $pass)) {
        $mfaRepo = new MfaCheckRepository();
//        print_r($user);
        $id = $mfaRepo->generate($user->getId())->getId();
        $result = array(
            'success' => true,
            'mfa_check' => $id
        );
    } else {
        $result['error'] = "wrong username or password";
    }
}

print json_encode($result);
