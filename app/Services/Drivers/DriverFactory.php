<?php
declare(strict_types=1);

namespace App\Services\Drivers;

class DriverFactory
{
    public function make(string $type): DriverInterface
    {
        return match ($type) {
            'sms' => new SmsDriver(),
            'email' => new EmailDriver(),
            default => throw new \InvalidArgumentException("Неподдържан тип нотификация: {$type}"),
        };
    }
}
