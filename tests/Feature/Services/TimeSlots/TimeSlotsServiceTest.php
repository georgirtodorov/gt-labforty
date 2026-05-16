<?php
declare(strict_types=1);

namespace Tests\Feature\Services\TimeSlots;

use App\Models;
use App\Services\TimeSlots\TimeSlotsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;


class TimeSlotsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TimeSlotsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TimeSlotsService();
        Event::fake();
    }

    public function test_it_returns_available_slots_for_specific_date(): void
    {
        $today = '2026-06-01';
        Carbon::setTestNow(Carbon::parse("{$today} 12:00:00"));

        $seed = [
            ['start_at' => "{$today} 13:00:00", 'appointment_id' => null], // In the future , free
            ['start_at' => "{$today} 10:00:00", 'appointment_id' => null], // In the pass
            ['start_at' => "{$today} 14:00:00", 'appointment_id' =>
                Models\Appointment::factory()->create()->id
            ],
            ['start_at' => "2026-06-02 13:00:00", 'appointment_id' => null], // Not today
        ];

        collect($seed)->each(fn($data) => Models\TimeSlot::factory()->create($data));

        $results = $this->service->getAvailableByDate($today);

        $this->assertCount(1, $results);

        $this->assertEquals($seed[0]['start_at'], $results->first()->start_at->toDateTimeString());

        Carbon::setTestNow();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }
}
