<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\DepartmentSeeder']);
        $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\DocumentCategorySeeder']);
        
        Storage::fake('public');
    }

    public function test_manager_can_upload_document(): void
    {
        $manager = User::factory()->create(['department_id' => 1]);
        $manager->assignRole('manager');

        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $response = $this->actingAs($manager)
            ->postJson('/api/v1/documents', [
                'title' => 'Test Document',
                'description' => 'Test description',
                'file' => $file,
                'category_id' => 1,
                'department_id' => 1,
                'access_level' => 'public',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'document' => ['id', 'title', 'file_name'],
            ]);

        $this->assertDatabaseHas('documents', [
            'title' => 'Test Document',
        ]);
    }

    public function test_employee_cannot_upload_document(): void
    {
        $employee = User::factory()->create(['department_id' => 1]);
        $employee->assignRole('employee');

        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $response = $this->actingAs($employee)
            ->postJson('/api/v1/documents', [
                'title' => 'Test Document',
                'description' => 'Test description',
                'file' => $file,
                'category_id' => 1,
                'department_id' => 1,
                'access_level' => 'public',
            ]);

        $response->assertStatus(403);
    }

    public function test_user_can_search_documents(): void
    {
        $user = User::factory()->create(['department_id' => 1]);
        $user->assignRole('employee');

        Document::factory()->create([
            'title' => 'Annual Report',
            'description' => 'Financial data',
            'department_id' => 1,
            'uploaded_by' => $user->id,
            'category_id' => 1,
            'access_level' => 'public',
        ]);

        Document::factory()->create([
            'title' => 'Employee Handbook',
            'description' => 'Company policies',
            'department_id' => 1,
            'uploaded_by' => $user->id,
            'category_id' => 1,
            'access_level' => 'public',
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/documents?search=Annual');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Annual Report'])
            ->assertJsonMissing(['title' => 'Employee Handbook']);
    }

    public function test_user_can_filter_documents_by_category(): void
    {
        $user = User::factory()->create(['department_id' => 1]);
        $user->assignRole('employee');

        Document::factory()->create([
            'title' => 'Policy Document',
            'department_id' => 1,
            'uploaded_by' => $user->id,
            'category_id' => 1,
            'access_level' => 'public',
        ]);

        Document::factory()->create([
            'title' => 'Report Document',
            'department_id' => 1,
            'uploaded_by' => $user->id,
            'category_id' => 2,
            'access_level' => 'public',
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/documents?category_id=1');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Policy Document'])
            ->assertJsonMissing(['title' => 'Report Document']);
    }

    public function test_admin_can_delete_any_document(): void
    {
        $admin = User::factory()->create(['department_id' => 1]);
        $admin->assignRole('admin');

        $otherUser = User::factory()->create(['department_id' => 2]);
        $otherUser->assignRole('manager');

        $document = Document::factory()->create([
            'department_id' => 2,
            'uploaded_by' => $otherUser->id,
            'category_id' => 1,
            'access_level' => 'public',
        ]);

        $response = $this->actingAs($admin)
            ->deleteJson("/api/v1/documents/{$document->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
    }
}
