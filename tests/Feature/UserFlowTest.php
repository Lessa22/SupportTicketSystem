<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Priority;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_end_to_end_user_journey(): void
    {
        // 1. Setup seed data
        $customer = User::factory()->create(['name' => 'John Customer', 'email' => 'customer@test.com', 'role' => 'customer']);
        $agent = User::factory()->create(['name' => 'Alice Agent', 'email' => 'agent@test.com', 'role' => 'agent']);
        $admin = User::factory()->create(['name' => 'Bob Admin', 'email' => 'admin@test.com', 'role' => 'admin']);

        $category = Category::create(['name' => 'Technical Support']);
        $highPriority = Priority::create(['id' => 3, 'name' => 'High', 'color' => 'red']);

        // 2. Customer logs in and creates a ticket
        $this->actingAs($customer);

        $response = $this->post(route('tickets.store'), [
            'title' => 'Urgent Network Outage',
            'description' => 'The server is unreachable for all department members.',
            'category_id' => $category->id,
            'priority_id' => $highPriority->id,
        ]);

        $response->assertRedirect(route('tickets.index'));
        $this->assertDatabaseHas('tickets', [
            'title' => 'Urgent Network Outage',
            'customer_id' => $customer->id,
        ]);

        $ticket = Ticket::where('title', 'Urgent Network Outage')->first();
        $this->assertNotNull($ticket);

        // 3. Admin updates active strategy in settings
        $this->actingAs($admin);
        $settingsResponse = $this->post(route('dashboard.settings'), [
            'active_assignment_strategy' => 'least_loaded',
        ]);
        $settingsResponse->assertSessionHasNoErrors();
        $this->assertEquals('least_loaded', Setting::get('active_assignment_strategy'));

        // 4. Agent updates ticket status via State Pattern
        $this->actingAs($agent);
        // First assign agent to ticket
        $ticket->agent_id = $agent->id;
        $ticket->status = 'assigned';
        $ticket->save();

        $statusResponse = $this->patch(route('tickets.changeStatus', $ticket), [
            'status' => 'in_progress',
        ]);
        $statusResponse->assertSessionHasNoErrors();

        $this->assertEquals('in_progress', $ticket->fresh()->status);

        // 5. Verify notification generated
        $this->assertDatabaseHas('notifications', [
            'user_id' => $customer->id,
            'ticket_id' => $ticket->id,
        ]);
    }
}
