<?php

namespace App\Config;

/**
 * This interface used for db connection class
 */
interface DBInterface
{
    public function connect();
    public function query(string $query);
}