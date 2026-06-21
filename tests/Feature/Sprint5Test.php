<?php
 
namespace Tests\Feature;
 
use App\Models\User;
use App\Models\District;
use App\Models\Department;
use App\Models\Province;
use App\Livewire\Pages\Profile;
use App\Livewire\Pages\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
 
class Sprint5Test extends TestCase
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
 
    public function test_user_gamification_levels()
    {
        $provider = User::factory()->create();
        $provider->assignRole('walker');
        $profile = $provider->walkerProfile()->create([
            'experience' => '3 years',
            'hourly_rate' => 30.00,
        ]);
 
        // Start from empty profile: 0 points = Bronce
        $this->assertEquals(0, $provider->getProfileCompleteness($profile));
        $this->assertEquals('bronce', $provider->getProfileLevel($profile)['name']);
 
        // 1. Add Location (District) (+20%)
        $profile->update(['district_id' => '150101']);
        $this->assertEquals(20, $provider->getProfileCompleteness($profile));
        $this->assertEquals('bronce', $provider->getProfileLevel($profile)['name']);
 
        // 2. Add Photo (+20%)
        $provider->update(['profile_photo_path' => 'profile-photos/test.jpg']);
        $this->assertEquals(40, $provider->getProfileCompleteness($profile));
        $this->assertEquals('bronce', $provider->getProfileLevel($profile)['name']);
 
        // 3. Add verification document (+20%)
        $profile->update(['verification_document_path' => 'verification-docs/test.pdf']);
        $this->assertEquals(60, $provider->getProfileCompleteness($profile));
        $this->assertEquals('plata', $provider->getProfileLevel($profile)['name']);
 
        // 4. Add catalog service (+20%)
        $provider->services()->create([
            'name' => 'Corte de Pelo',
            'price' => 45.00
        ]);
        $this->assertEquals(80, $provider->getProfileCompleteness($profile));
        $this->assertEquals('oro', $provider->getProfileLevel($profile)['name']);
 
        // 5. Add payment details (yape/plin) (+10%)
        $provider->update(['yape_number' => '999888777']);
        $this->assertEquals(90, $provider->getProfileCompleteness($profile));
        $this->assertEquals('oro', $provider->getProfileLevel($profile)['name']);
 
        // 6. Add portfolio photo (+10%)
        $provider->portfolio()->create([
            'image_path' => 'portfolio/test.jpg',
            'title' => 'Test image'
        ]);
        $this->assertEquals(100, $provider->getProfileCompleteness($profile));
        $this->assertEquals('diamante', $provider->getProfileLevel($profile)['name']);
    }
 
    public function test_profile_page_role_switching()
    {
        $provider = User::factory()->create();
        $provider->assignRole('walker');
        $provider->assignRole('groomer');
        
        $walkerProfile = $provider->walkerProfile()->create([
            'experience' => '3 years',
            'hourly_rate' => 30.00,
            'district_id' => '150101',
        ]);
        
        $groomerProfile = $provider->groomerProfile()->create([
            'bio' => 'Top Groomer',
            'district_id' => '150101',
        ]);
 
        // Default loads first checked role (walker)
        Livewire::test(Profile::class, ['id' => $provider->id])
            ->assertSet('selectedRole', 'walker')
            ->assertSet('profile.experience', '3 years');
 
        // Request role via URL parameter loads groomer
        Livewire::withQueryParams(['role' => 'groomer'])
            ->test(Profile::class, ['id' => $provider->id])
            ->assertSet('selectedRole', 'groomer')
            ->assertSet('profile.bio', 'Top Groomer')
            
            // Switch role dynamically
            ->call('switchProfileRole', 'walker')
            ->assertSet('selectedRole', 'walker')
            ->assertSet('profile.experience', '3 years');
    }
 
    public function test_seo_landing_pages_parameters()
    {
        // Request services/walker/lima -> pre-filters category and district
        Livewire::test(Search::class, [
            'serviceType' => 'walker',
            'districtName' => 'lima'
        ])
        ->assertSet('serviceType', 'walker')
        ->assertSet('district_id', '150101');
    }
}
