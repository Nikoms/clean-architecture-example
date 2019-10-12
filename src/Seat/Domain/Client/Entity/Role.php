<?php declare(strict_types=1);

namespace Seat\Domain\Client\Entity;

class Role
{
    static $client = 'client';
    static $sandwich = 'sandwich';
    static $admin = 'admin';

    static $all = [];
}

Role::$all = [Role::$client, Role::$sandwich, Role::$admin];
