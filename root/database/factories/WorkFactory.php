<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private int $i = 1;

    public function definition()
    {
        return [
            'work_content' => sprintf('業務%02d', $this->i),
            'date' => sprintf('2022-10-%02d', $this->i++),
            'work_start_time' => '09:30:00',
            'work_end_time' => '18:30:00',
            'break_time' => '01:00:00',
            'created_at' => '2022-09-30',
            'updated_at' => null,
        ];
    }
}
