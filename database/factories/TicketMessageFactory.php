<?php

namespace Database\Factories;

use App\Enums\TicketMessageDirection;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketMessageFactory extends Factory
{
    protected $model = TicketMessage::class;

    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'from_email' => $this->faker->safeEmail(),
            'from_name' => $this->faker->name(),
            'direction' => $this->faker->randomElement(TicketMessageDirection::cases()),
            'subject' => $this->faker->sentence(),
            'body_text' => $this->faker->paragraph(),
            'sent_at' => now(),
        ];
    }
}
