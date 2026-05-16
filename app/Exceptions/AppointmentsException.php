<?php
declare(strict_types=1);

namespace App\Exceptions;

class AppointmentsException extends \Exception
{
    public static function pastTimeDelete(): AppointmentsException
    {
        return new self('Не можете да изтриете час, който вече е започнал или е приключил.', 400);
    }

    public static function slotNotAvailable(): AppointmentsException
    {
        return new self('Избраният час не е наличен или вече е зает.', 400);
    }

    public static function pastTimeBook(): AppointmentsException
    {
        return new self('Не може да запазвате час, чието начало е в миналото', 400);
    }

    public static function slotAlreadyBooked(): AppointmentsException
    {
        return new self('Слотът е вече зает', 400);
    }

    public static function pastTimeUpdate(): AppointmentsException
    {
        return new self('Не можете да променяте час, който вече е започнал или е приключил.', 400);
    }

    public static function bookInPast(): AppointmentsException
    {
        return new self('Не можете да преместите час в миналото', 400);
    }

    public static function slotWithDatesDoNotExist(string $startAt, string $endAt): AppointmentsException
    {
        return new self(
            sprintf(
                'Времевият слот с начало %s и край %s не съществува в системата',
                $startAt,
                $endAt
            )
        );
    }

    public static function bookingNotFound(int $id): AppointmentsException
    {
        return new self(
            sprintf('Резервация с ID %d не съществува в системата.', $id)
        );
    }
}
