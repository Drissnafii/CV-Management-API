<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachSkillRequest;
use App\Http\Requests\DetachSkillRequest;
use App\Http\Requests\SyncSkillsRequest;
use App\Models\User;

class SkillController extends Controller
{
    public function attachSkill(AttachSkillRequest $request, User $user)
    {
        $validated = $request->validated();

        if (!$user->skills()->where('skill_id', $validated['skill_id'])->exists()) {
            $user->skills()->attach($validated['skill_id']);
        }

        return response()->json([
            'message' => 'Compétence ajoutée avec succès',
            'user' => $user->load('skills')
        ]);
    }

    public function detachSkill(DetachSkillRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->skills()->detach($validated['skill_id']);

        return response()->json([
            'message' => 'Compétence supprimée avec succès',
            'user' => $user->load('skills')
        ]);
    }

    public function syncSkills(SyncSkillsRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->skills()->sync($validated['skills']);

        return response()->json([
            'message' => 'Compétences synchronisées avec succès',
            'user' => $user->load('skills')
        ]);
    }
}
