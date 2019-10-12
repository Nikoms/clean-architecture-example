<?php declare(strict_types=1);

namespace Seat\Domain\Client\Entity;

class Store
{
    static $laHulpe = 'la-hulpe';
    static $waterloo = 'waterloo';

    static $all = [];
}

Store::$all = [Store::$laHulpe, Store::$waterloo];
