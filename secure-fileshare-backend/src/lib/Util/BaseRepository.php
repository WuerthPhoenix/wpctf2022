<?php

namespace Wp\Sfb\Util;

class BaseRepository
{
    protected $mysqli;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->mysqli = $db->getConnection();
    }
}