<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Group;
use App\Models\User;

class GroupUserControllerTest extends TestCase
{
    use RefreshDatabase;


    public function user_can_attach_a_student_to_a_group()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();

        $data = ['group_id' => $group->id, 'user_id' => $user->id];

        $response = $this->postJson('/api/group-students', $data);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Student attached to group successfully']);

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
    }

    public function user_can_update_a_group_user()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $group->students()->attach($user->id);

        $data = ['user_id' => $user->id];
        $response = $this->putJson("/api/group-students/{$group->id}", $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Student update from group successfully']);

        $this->assertDatabaseMissing('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
    }


    public function user_can_detach_a_student_from_a_group()
    {
        $group = Group::factory()->create();
        $user = User::factory()->create();
        $group->students()->attach($user->id);

        $response = $this->deleteJson("/api/group-students/{$user->id}", [
            'group_id' => $group->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Student  detached from group successfully']);

        $this->assertDatabaseMissing('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id
        ]);
    }
}
