<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Group;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase; // Test har safar yangidan ishlashi uchun


    public function user_can_get_list_of_groups()
    {
        Group::factory()->count(5)->create();

        $response = $this->getJson('/api/groups');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'created_at', 'updated_at']
                ]
            ]);
    }


    public function user_can_create_a_group()
    {
        $data = ['name' => 'Test Group'];

        $response = $this->postJson('/api/groups', $data);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Group created successfully!']);

        $this->assertDatabaseHas('groups', $data);
    }


    public function user_can_view_a_single_group()
    {
        $group = Group::factory()->create();

        $response = $this->getJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $group->id, 'name' => $group->name]);
    }


    public function user_can_update_a_group()
    {
        $group = Group::factory()->create();
        $updateData = ['name' => 'Updated Group Name'];

        $response = $this->putJson("/api/groups/{$group->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Group updated successfully!']);

        $this->assertDatabaseHas('groups', $updateData);
    }


    public function user_can_delete_a_group()
    {
        $group = Group::factory()->create();

        $response = $this->deleteJson("/api/groups/{$group->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Group deleted successfully!']);

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}
