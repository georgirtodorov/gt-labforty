<?php
declare(strict_types=1);

namespace App\Services\Notifiers;

use App\Services\Drivers\DriverFactory;

abstract class BaseNotifier
{
    public function __construct(
        protected DriverFactory $factory
    ) {}
}
