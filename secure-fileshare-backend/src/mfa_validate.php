<?php

include_once "init.php";


use Wp\Sfb\Util\MfaCheckRepository;

$result = array(
    'success' => false
);

if (isset($_GET['mfa_check_id'])) {
    $mfaRepo = new MfaCheckRepository();
    if ($mfaCheck = $mfaRepo->validate($_GET['mfa_check_id'])) {
        $result['success'] = true;
        $result['mfacheck'] = $mfaCheck;
    }
}

print json_encode($result);