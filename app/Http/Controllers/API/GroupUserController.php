<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGroupUserRequest;
use App\Http\Requests\UpdateGroupUserRequest;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupUserController extends Controller
{
    public function store(StoreGroupUserRequest $request)
    {
        $validator = $request->validated();

        $group=Group::query()->findOrFail($validator['group_id']);
        $group->students()->attach($validator['user_id'], ['created_at' => now(), 'updated_at' => now()]);


        return response()->json(['message' => 'Student attached to group successfully'], 201);
    }
    public function update(string $id, UpdateGroupUserRequest $request)
    {
        $validator = $request->validated();
        $group=Group::query()->findOrFail($id);

        $group->students()->detach($validator['user_id']);

        return response()->json(['message'=>'Student update from group successfully'], 200);

    }
    public function destroy(string $id,Request $request)
    {
        $validator=$request->validate([
            'group_id'=>'required|exists:groups,id'
        ]);
        $group=Group::query()->findOrFail($validator['group_id']);
        $group->students()->detach($id);
        return response()->json(['message'=>'Student  detached from group successfully'], 200);
    }

}
