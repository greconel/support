<?php

namespace Database\Factories;

use App\Enums\TicketImpact;
use App\Enums\TicketSource;
use App\Enums\TicketStatus;
use App\Models\Client;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'ticket_number' => Ticket::generateTicketNumber(),
            'subject' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(TicketStatus::cases()),
            'impact' => $this->faker->randomElement(TicketImpact::cases()),
            'source' => TicketSource::Web,
            'client_id' => Client::factory(),
        ];
    }
}
