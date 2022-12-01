<?php

include_once "init.php";

use Wp\Sfb\Auth\PermissionHandler;

$user = PermissionHandler::getAuthenticatedUser();
if (!$user->canDownload()) {
    http_response_code(403);
    exit();
}
$tmp_f_name = tempnam("/tmp", $user->getGroupId());
$cmd_compress = "tar czf $tmp_f_name " . UPLOAD_PATH . "/" . $user->getGroupId();
exec($cmd_compress, $output, $retval);

if ($retval === 0) {
    header("Content-Type: application/octet-stream", true);
    print file_get_contents($tmp_f_name);
    unlink($tmp_f_name);
} else {
    http_response_code(500);
    exit();
}
