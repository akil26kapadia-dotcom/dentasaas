<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'patient_name' => fake()->name(),
            'service_name' => 'Consultation',
            'appt_date' => now()->format('Y-m-d'),
            'appt_time' => fake()->time('H:i'),
            'status' => 'pending',
        ];
    }
}
