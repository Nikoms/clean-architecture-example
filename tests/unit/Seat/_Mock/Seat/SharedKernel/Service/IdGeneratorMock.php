<?php declare(strict_types=1);

namespace SeatTest\_Mock\Seat\SharedKernel\Service;

use Seat\SharedKernel\Service\IdGenerator;

class IdGeneratorMock extends IdGenerator
{
    public $id = 0;
    public $lastId = '';

    public function next()
    {
        $this->lastId = (string)++$this->id;

        return $this->lastId;
    }
}
