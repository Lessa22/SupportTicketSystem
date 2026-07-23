<?php

namespace Tests\Feature;

use App\Exceptions\InvalidTicketTransitionException;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketService;
use App\Services\TicketStateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketStateAndStrategyTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $agent1;
    protected User $agent2;
    protected Category $category;
    protected Priority $lowPriority;
    protected Priority $mediumPriority;
    protected Priority $highPriority;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create(['role' => 'customer']);
        $this->agent1 = User::factory()->create(['role' => 'agent', 'name' => 'Agent Alpha']);
        $this->agent2 = User::factory()->create(['role' => 'agent', 'name' => 'Agent Beta']);

        $this->category = Category::create(['name' => 'General Support']);

        $this->lowPriority = Priority::firstOrCreate(['id' => 1], ['name' => 'Low', 'color' => 'green']);
        $this->mediumPriority = Priority::firstOrCreate(['id' => 2], ['name' => 'Medium', 'color' => 'yellow']);
        $this->highPriority = Priority::firstOrCreate(['id' => 3], ['name' => 'High', 'color' => 'red']);
    }

    public function test_state_pattern_valid_transitions(): void
    {
        $ticket = Ticket::create([
            'title' => 'State Test Ticket',
            'description' => 'Testing state pattern transitions',
            'customer_id' => $this->customer->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->lowPriority->id,
            'status' => 'open',
        ]);

        $stateService = app(TicketStateService::class);

        // Open -> Assigned
        $stateService->changeStatus($ticket, 'assigned');
        $this->assertEquals('assigned', $ticket->fresh()->status);

        // Assigned -> In Progress
        $stateService->changeStatus($ticket, 'in_progress');
        $this->assertEquals('in_progress', $ticket->fresh()->status);

        // In Progress -> Resolved
        $stateService->changeStatus($ticket, 'resolved');
        $this->assertEquals('resolved', $ticket->fresh()->status);

        // Resolved -> Closed
        $stateService->changeStatus($ticket, 'closed');
        $this->assertEquals('closed', $ticket->fresh()->status);

        // Closed -> Reopened
        $stateService->changeStatus($ticket, 'reopened');
        $this->assertEquals('reopened', $ticket->fresh()->status);
    }

    public function test_state_pattern_invalid_transition_throws_exception(): void
    {
        $ticket = Ticket::create([
            'title' => 'Invalid State Transition Ticket',
            'description' => 'Testing invalid transition exception',
            'customer_id' => $this->customer->id,
            'category_id' => $this->category->id,
            'priority_id' => $this->lowPriority->id,
            'status' => 'open',
        ]);

        $stateService = app(TicketStateService::class);

        $this->expectException(InvalidTicketTransitionException::class);

        // Open -> Resolved is invalid
        $stateService->changeStatus($ticket, 'resolved');
    }

    public function test_sla_strategy_calculates_correct_deadlines(): void
    {
        $this->actingAs($this->customer);

        $ticketService = app(TicketService::class);

        $lowTicket = $ticketService->create([
            'title' => 'Low Priority Ticket',
            'description' => 'Checking SLA hours calculation',
            'category_id' => $this->category->id,
            'priority_id' => $this->lowPriority->id,
        ]);

        // Low priority SLA is 72 hours
        $this->assertNotNull($lowTicket->sla_deadline);
        $this->assertEquals(72, round(now()->diffInHours($lowTicket->sla_deadline)));

        $highTicket = $ticketService->create([
            'title' => 'High Priority Ticket',
            'description' => 'Checking SLA hours calculation',
            'category_id' => $this->category->id,
            'priority_id' => $this->highPriority->id,
        ]);

        // High priority SLA is 24 hours
        $this->assertNotNull($highTicket->sla_deadline);
        $this->assertEquals(24, round(now()->diffInHours($highTicket->sla_deadline)));
    }

    public function test_round_robin_assignment_strategy(): void
    {
        Setting::set('active_assignment_strategy', 'round_robin');
        $this->actingAs($this->customer);

        $ticketService = app(TicketService::class);

        $ticket1 = $ticketService->create([
            'title' => 'Ticket 1',
            'description' => 'Testing round robin',
            'category_id' => $this->category->id,
            'priority_id' => $this->lowPriority->id,
        ]);

        $ticket2 = $ticketService->create([
            'title' => 'Ticket 2',
            'description' => 'Testing round robin',
            'category_id' => $this->category->id,
            'priority_id' => $this->lowPriority->id,
        ]);

        $this->assertNotNull($ticket1->agent_id);
        $this->assertNotNull($ticket2->agent_id);
        $this->assertNotEquals($ticket1->agent_id, $ticket2->agent_id);
    }
}
