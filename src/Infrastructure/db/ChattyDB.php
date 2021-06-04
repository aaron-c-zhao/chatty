<?php

declare (strict_types = 1);

namespace App\Infrastructure\db;

use SQLite3;

class ChattyDB extends SQLite3 {
    function __construct () {
        $this -> open('chatty.db');
    }
}