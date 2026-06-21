<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\BlockedDate;
use App\Models\Appointment;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Pet;
use App\Livewire\Dashboard\MessagesDashboard;
use App\Livewire\Dashboard\VisualCalendar;
use App\Livewire\Dashboard\NotificationBell;
use App\Livewire\Pages\Profile;
use App\Livewire\Dashboard\ProviderAppointments;
use App\Livewire\Dashboard\ClientAppointments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Carbon\Carbon;

class Sprint4Test extends TestCase
{
    use RefreshDatabase;

    protected $provider;
    protected $client;
    protected $pet;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Seed single Ubigeo location manually for speed
        $dep = \App\Models\Department::create(['id' => '15', 'name' => 'Lima']);
        $prov = \App\Models\Province::create(['id' => '1501', 'name' => 'Lima', 'department_id' => '15']);
        \App\Models\District::create(['id' => '150101', 'name' => 'Lima', 'province_id' => '1501', 'department_id' => '15']);

        // Create Users
        $this->provider = User::factory()->create();
        $this->provider->assignRole('walker');
        $this->provider->walkerProfile()->create([
            'experience' => '3 years',
            'hourly_rate' => 30.00,
            'district_id' => '150101',
        ]);

        $this->client = User::factory()->create();
        $this->client->assignRole('client');
        $this->pet = Pet::create([
            'user_id' => $this->client->id,
            'name' => 'Fido',
            'species' => 'dog',
            'breed' => 'Golden Retriever',
            'weight' => 25.5,
            'birth_date' => '2022-01-01',
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
        ]);
    }

    public function test_in_app_chat_functionality()
    {
        // 1. Client visits Messages Dashboard with provider's contactId
        // This should firstOrCreate a conversation
        Livewire::actingAs($this->client)
            ->test(MessagesDashboard::class, ['contactId' => $this->provider->id])
            ->assertSet('newMessageBody', '')
            ->set('newMessageBody', 'Hola, ¿estás disponible el lunes?')
            ->call('sendMessage')
            ->assertSet('newMessageBody', '');

        // Verify conversation and message exist
        $conversation = Conversation::where('client_id', $this->client->id)
            ->where('provider_id', $this->provider->id)
            ->first();

        $this->assertNotNull($conversation);
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $this->client->id,
            'body' => 'Hola, ¿estás disponible el lunes?',
            'is_read' => false,
        ]);

        // Verify provider received notification in database
        $this->assertEquals(1, $this->provider->unreadNotifications()->count());
        $notification = $this->provider->unreadNotifications()->first();
        $this->assertEquals('chat_message', $notification->data['type']);
        $this->assertEquals($this->client->name, $notification->data['sender_name']);

        // 2. Provider opens Messages Dashboard
        // Unread messages should be marked as read
        Livewire::actingAs($this->provider)
            ->test(MessagesDashboard::class, ['contactId' => $this->client->id])
            ->assertSet('activeConversationId', $conversation->id);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $this->client->id,
            'body' => 'Hola, ¿estás disponible el lunes?',
            'is_read' => true, // Marked as read
        ]);
    }

    public function test_provider_can_block_and_unblock_dates_via_visual_calendar()
    {
        $dateStr = Carbon::now()->addDays(2)->format('Y-m-d');

        // 1. Block date
        Livewire::actingAs($this->provider)
            ->test(VisualCalendar::class)
            ->set('selectedDate', $dateStr)
            ->set('blockNotes', 'Vacaciones')
            ->call('blockDate');

        $this->assertTrue(BlockedDate::where('provider_id', $this->provider->id)
            ->whereDate('blocked_date', $dateStr)
            ->where('notes', 'Vacaciones')
            ->exists());

        // 2. Unblock date
        Livewire::actingAs($this->provider)
            ->test(VisualCalendar::class)
            ->call('unblockDate', $dateStr);

        $this->assertFalse(BlockedDate::where('provider_id', $this->provider->id)
            ->whereDate('blocked_date', $dateStr)
            ->exists());
    }

    public function test_booking_on_blocked_date_is_prevented()
    {
        $blockedDateStr = Carbon::now()->addDays(2)->format('Y-m-d');

        // Block the date first
        BlockedDate::create([
            'provider_id' => $this->provider->id,
            'blocked_date' => $blockedDateStr,
            'notes' => 'Feriado personal',
        ]);

        // Client attempts to book on the blocked date
        Livewire::actingAs($this->client)
            ->test(Profile::class, ['id' => $this->provider->id])
            ->set('appointmentDate', $blockedDateStr)
            ->set('appointmentTime', '10:00')
            ->set('selectedPetId', $this->pet->id)
            ->set('appointmentNotes', 'Corte de uñas')
            ->call('bookAppointment')
            ->assertHasErrors(['appointmentDate']);

        // Assert no appointment was created
        $this->assertDatabaseMissing('appointments', [
            'client_id' => $this->client->id,
            'provider_id' => $this->provider->id,
        ]);
    }

    public function test_appointment_booking_and_status_changes_trigger_notifications()
    {
        $dateStr = Carbon::now()->addDays(3)->format('Y-m-d');

        // 1. Client books appointment: triggers AppointmentBooked notification
        Livewire::actingAs($this->client)
            ->test(Profile::class, ['id' => $this->provider->id])
            ->set('appointmentDate', $dateStr)
            ->set('appointmentTime', '14:00')
            ->set('selectedPetId', $this->pet->id)
            ->set('appointmentNotes', 'Paseo largo')
            ->call('bookAppointment')
            ->assertHasNoErrors();

        $appointment = Appointment::where('client_id', $this->client->id)
            ->where('provider_id', $this->provider->id)
            ->first();

        $this->assertNotNull($appointment);
        $this->assertEquals('pending', $appointment->status);

        // Verify provider received AppointmentBooked notification
        $this->assertEquals(1, $this->provider->unreadNotifications()->count());
        $notification = $this->provider->unreadNotifications()->first();
        $this->assertEquals('appointment_booked', $notification->data['type']);
        $this->assertEquals($this->client->name, $notification->data['client_name']);

        // 2. Provider confirms appointment: triggers AppointmentStatusChanged notification
        Livewire::actingAs($this->provider)
            ->test(ProviderAppointments::class)
            ->call('confirm', $appointment->id);

        $appointment->refresh();
        $this->assertEquals('confirmed', $appointment->status);

        // Verify client received AppointmentStatusChanged notification
        $this->assertEquals(1, $this->client->unreadNotifications()->count());
        $clientNotif = $this->client->unreadNotifications()->first();
        $this->assertEquals('appointment_status_changed', $clientNotif->data['type']);
        $this->assertEquals('confirmed', $clientNotif->data['status']);

        // 3. Client cancels appointment: triggers AppointmentStatusChanged notification for provider
        $this->provider->unreadNotifications()->first()->markAsRead(); // Clear provider notifications for testing
        
        Livewire::actingAs($this->client)
            ->test(ClientAppointments::class)
            ->call('cancelAppointment', $appointment->id);

        $appointment->refresh();
        $this->assertEquals('cancelled', $appointment->status);

        // Verify provider received cancel notification
        $this->assertEquals(1, $this->provider->unreadNotifications()->count());
        $providerCancelNotif = $this->provider->unreadNotifications()->first();
        $this->assertEquals('appointment_status_changed', $providerCancelNotif->data['type']);
        $this->assertEquals('cancelled', $providerCancelNotif->data['status']);
    }

    public function test_notification_bell_marks_read_and_routes()
    {
        // Setup a fake notification
        $dateStr = Carbon::now()->addDays(3)->format('Y-m-d');
        
        Livewire::actingAs($this->client)
            ->test(Profile::class, ['id' => $this->provider->id])
            ->set('appointmentDate', $dateStr)
            ->set('appointmentTime', '14:00')
            ->set('selectedPetId', $this->pet->id)
            ->set('appointmentNotes', 'Paseo')
            ->call('bookAppointment');

        $notif = $this->provider->unreadNotifications()->first();
        $this->assertNotNull($notif);

        // Clicking the notification in the bell should mark it read and return redirect to provider appointments
        Livewire::actingAs($this->provider)
            ->test(NotificationBell::class)
            ->assertViewHas('unreadCount', 1)
            ->call('handleNotificationClick', $notif->id)
            ->assertRedirect(route('dashboard.provider.appointments'));

        $notif->refresh();
        $this->assertNotNull($notif->read_at);
    }
}
