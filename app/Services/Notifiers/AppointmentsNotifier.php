<?php
declare(strict_types=1);

namespace App\Services\Notifiers;

class AppointmentsNotifier extends BaseNotifier implements NotifierInterface
{

    public function notify(string $driver, $data): void
    {
        $driver = $this->factory->make($driver);
        $driver->send($data);
        // TODO: Implement notify() method.
    }
}
