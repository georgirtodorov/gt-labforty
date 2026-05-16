<?php
declare(strict_types=1);

namespace App\Services\Notifiers;

interface NotifierInterface
{
    public function notify(string $driver, $data): void;
}
