<?php
declare(strict_types=1);

namespace App\Listeners\Appointments;

use App\Events\Appointments\AppointmentCreated;
use App\Events\Appointments\AppointmentDeleted;
use App\Services\Notifiers\NotifierInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentDeletedListener implements ShouldQueue
{
    private NotifierInterface $notifier;

    /**
     * Create the event listener.
     */
    public function __construct(NotifierInterface $notifier
    )
    {
        $this->notifier = $notifier;
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentDeleted $event): void
    {
        $appointment = $event->appointment;
        $this->notifier->notify($appointment->notification_type, $appointment);
    }
}
