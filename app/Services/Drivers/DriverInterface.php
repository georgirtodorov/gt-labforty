<?php
declare(strict_types=1);

namespace App\Services\Drivers;

interface DriverInterface
{
    public function send($data): void;
}
