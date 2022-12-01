<?php

include_once "init.php";

use Wp\Sfb\Auth\PermissionHandler;
use Wp\Sfb\Util\MfaCheckRepository;

$result = array(
    'success' => false
);

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['username']) && isset($data['mfa_check'])) {
    $user = $data['username'];
    $id = $data['mfa_check'];
    $mfaRepo = new MfaCheckRepository();
    if ($mfaRepo->get($id)->isPassed()) {
        $jwt_token = PermissionHandler::generateJwtFromUserName($data['username']);
        if ($jwt_token !== null) {
            $result = array(
                'success' => true,
                'jwt' => $jwt_token
            );
        } else {
            $result['error'] = 'not valid user';
        }
    }
}

print json_encode($result);