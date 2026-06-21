<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\ProviderService;
use App\Models\Payment;
use App\Livewire\Pages\Profile;
use App\Livewire\Dashboard\ClientAppointments;
use App\Livewire\Dashboard\ProviderAppointments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MonetizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Seed single Ubigeo location manually for speed
        $dep = \App\Models\Department::create(['id' => '15', 'name' => 'Lima']);
        $prov = \App\Models\Province::create(['id' => '1501', 'name' => 'Lima', 'department_id' => '15']);
        \App\Models\District::create(['id' => '150101', 'name' => 'Lima', 'province_id' => '1501', 'department_id' => '15']);
    }

    public function test_provider_can_manage_services_and_payment_config()
    {
        $provider = User::factory()->create();
        $provider->assignRole('walker');
        $provider->walkerProfile()->create([
            'experience' => '3 years',
            'hourly_rate' => 30.00,
            'district_id' => '150101',
        ]);

        $this->actingAs($provider);

        // Add a service
        $service = $provider->services()->create([
            'name' => 'Paseo Grupal',
            'description' => 'Un paseo divertido.',
            'price' => 20.00,
            'duration_minutes' => 60,
        ]);

        $this->assertDatabaseHas('provider_services', [
            'user_id' => $provider->id,
            'name' => 'Paseo Grupal',
            'price' => 20.00
        ]);

        // Update payment info on user
        $provider->update([
            'yape_number' => '999888777',
            'plin_number' => '999111222',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $provider->id,
            'yape_number' => '999888777',
            'plin_number' => '999111222',
        ]);
    }

    public function test_client_can_book_appointment_with_services()
    {
        $provider = User::factory()->create();
        $provider->assignRole('walker');
        $provider->walkerProfile()->create([
            'experience' => '3 years',
            'hourly_rate' => 30.00,
            'district_id' => '150101',
        ]);

        $service1 = $provider->services()->create(['name' => 'S1', 'price' => 15.00]);
        $service2 = $provider->services()->create(['name' => 'S2', 'price' => 25.00]);

        $client = User::factory()->create();
        $pet = Pet::create([
            'user_id' => $client->id,
            'name' => 'Fido',
            'species' => 'Dog',
            'breed' => 'Golden',
            'age_years' => 1,
            'weight' => 12.0,
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
        ]);

        Livewire::actingAs($client)
            ->test(Profile::class, ['id' => $provider->id])
            ->set('appointmentDate', now()->addDays(2)->format('Y-m-d'))
            ->set('appointmentTime', '10:00')
            ->set('selectedPetId', $pet->id)
            ->set('selectedServices', [$service1->id, $service2->id])
            ->call('bookAppointment');

        $appointment = Appointment::first();
        $this->assertNotNull($appointment);
        $this->assertCount(2, $appointment->services);
        
        $payment = Payment::where('appointment_id', $appointment->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals(40.00, $payment->amount);
        $this->assertEquals('pending', $payment->status);
    }

    public function test_client_can_pay_using_manual_upload_and_provider_approves()
    {
        $provider = User::factory()->create();
        $provider->assignRole('walker');
        $provider->walkerProfile()->create([
            'experience' => '3 years',
            'hourly_rate' => 30.00,
            'district_id' => '150101',
        ]);

        $client = User::factory()->create();
        $pet = Pet::create([
            'user_id' => $client->id,
            'name' => 'Fido',
            'species' => 'Dog',
            'breed' => 'Golden',
            'age_years' => 1,
            'weight' => 12.0,
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
        ]);

        $appointment = Appointment::create([
            'client_id' => $client->id,
            'provider_id' => $provider->id,
            'pet_id' => $pet->id,
            'scheduled_at' => now()->addDays(2),
            'status' => 'confirmed',
        ]);

        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => 50.00,
            'payment_method' => 'yape',
            'status' => 'pending',
        ]);

        // Mock upload receipt photo
        \Illuminate\Support\Facades\Storage::fake('public');
        $file = \Illuminate\Http\UploadedFile::fake()->image('receipt.jpg');

        Livewire::actingAs($client)
            ->test(ClientAppointments::class)
            ->set('selectedAppointmentId', $appointment->id)
            ->set('paymentMethod', 'yape')
            ->set('receiptPhoto', $file)
            ->set('operationCode', 'OP-99999')
            ->call('submitManualPayment');

        $payment->refresh();
        $this->assertEquals('under_review', $payment->status);
        $this->assertEquals('OP-99999', $payment->transaction_reference);
        $this->assertNotNull($payment->receipt_photo_path);

        // Provider approves payment
        Livewire::actingAs($provider)
            ->test(ProviderAppointments::class)
            ->call('approvePayment', $appointment->id);

        $payment->refresh();
        $this->assertEquals('completed', $payment->status);
    }
}
