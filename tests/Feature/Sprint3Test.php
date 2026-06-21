<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Review;
use App\Models\District;
use App\Models\Department;
use App\Models\Province;
use App\Livewire\Dashboard\ProviderDashboard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class Sprint3Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Seed single Ubigeo location manually for speed
        $dep = Department::create(['id' => '15', 'name' => 'Lima']);
        $prov = Province::create(['id' => '1501', 'name' => 'Lima', 'department_id' => '15']);
        District::create(['id' => '150101', 'name' => 'Lima', 'province_id' => '1501', 'department_id' => '15']);
    }

    public function test_provider_can_reply_to_review_and_delete_reply()
    {
        $provider = User::factory()->create();
        $provider->assignRole('walker');
        $profile = $provider->walkerProfile()->create([
            'experience' => '3 years',
            'hourly_rate' => 30.00,
            'district_id' => '150101',
        ]);

        $client = User::factory()->create();
        $review = Review::create([
            'provider_id' => $provider->id,
            'user_id' => $client->id,
            'rating' => 5,
            'comment' => 'Excelente servicio paseando a mi perrito.',
        ]);

        Livewire::actingAs($provider)
            ->test(ProviderDashboard::class)
            ->set('replyText.' . $review->id, 'Muchas gracias por la confianza, un gusto!')
            ->call('submitReply', $review->id);

        $review->refresh();
        $this->assertEquals('Muchas gracias por la confianza, un gusto!', $review->provider_response);
        $this->assertNotNull($review->replied_at);

        // Test delete reply
        Livewire::actingAs($provider)
            ->test(ProviderDashboard::class)
            ->call('deleteReply', $review->id);

        $review->refresh();
        $this->assertNull($review->provider_response);
        $this->assertNull($review->replied_at);
    }

    public function test_provider_profile_completeness_calculation()
    {
        $provider = User::factory()->create();
        $provider->assignRole('walker');
        $profile = $provider->walkerProfile()->create([
            'experience' => '3 years',
            'hourly_rate' => 30.00,
        ]);

        // Start from empty profile: completeness should be calculated
        // Let's test using the component directly
        Livewire::actingAs($provider)
            ->test(ProviderDashboard::class)
            ->assertSet('completenessScore', 0) // No photo, no location, no verification, no services, no payment, no portfolio
            
            // 1. Add Location (District) (+20%)
            ->set('district_id', '150101')
            ->call('save')
            ->assertSet('completenessScore', 20)

            // 2. Add Photo (+20%)
            ->tap(function($component) use ($provider) {
                $provider->update(['profile_photo_path' => 'profile-photos/test.jpg']);
            })
            ->call('calculateCompleteness')
            ->assertSet('completenessScore', 40)

            // 3. Add verification document (+20%)
            ->tap(function($component) use ($profile) {
                $profile->update(['verification_document_path' => 'verification-docs/test.pdf']);
            })
            ->call('calculateCompleteness')
            ->assertSet('completenessScore', 60)

            // 4. Add catalog service (+20%)
            ->tap(function($component) use ($provider) {
                $provider->services()->create([
                    'name' => 'Corte de Pelo',
                    'price' => 45.00
                ]);
            })
            ->call('calculateCompleteness')
            ->assertSet('completenessScore', 80)

            // 5. Add payment details (yape/plin) (+10%)
            ->set('yape_number', '999888777')
            ->call('save')
            ->assertSet('completenessScore', 90)

            // 6. Add portfolio photo (+10%)
            ->tap(function($component) use ($provider) {
                $provider->portfolio()->create([
                    'image_path' => 'portfolio/test.jpg',
                    'title' => 'Test image'
                ]);
            })
            ->call('calculateCompleteness')
            ->assertSet('completenessScore', 100);
    }
}
