<?php
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Origin: *');
header('Wp-Ctf: WP{h1d3_http_h34d3rz}');


const JWT_SECRET = "WP{fr0m_bl4ckb0x_2_wh1t3b0x}";
define("DB_HOST", getenv("DB_HOST"));
define("DB_USER", getenv("DB_USER"));
define("DB_PASS", getenv("DB_PASS"));
define("DB_NAME", getenv("DB_NAME"));
define("UPLOAD_PATH", getenv("UPLOAD_PATH"));
define("TEAM_NAME", getenv("TEAM_NAME"));

header('Wp-Ctf-Team-Name: ' . TEAM_NAME);

set_include_path(get_include_path() . PATH_SEPARATOR . dirname(getcwd()).'lib/');
