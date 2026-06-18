<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function show(Request $request): JsonResponse
    {
        return $this->successResponse(
            $request->user()->load('profile', 'school', 'branch'),
            'Profile retrieved'
        );
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        if (isset($data['bio']) || isset($data['address'])) {
            $user->profile()->updateOrCreate(['user_id' => $user->id], [
                'bio' => $data['bio'] ?? null,
                'address' => $data['address'] ?? null,
            ]);
        }

        return $this->updatedResponse($user->fresh()->load('profile'), 'Profile updated');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated()['password']),
        ]);

        return $this->successResponse(null, 'Password changed successfully');
    }

    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate(['avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048']);
        $path = $request->file('avatar')->store('avatars', 'public');
        $request->user()->update(['avatar' => $path]);
        return $this->successResponse(['avatar' => $path], 'Avatar uploaded');
    }

    public function updateTheme(Request $request): JsonResponse
    {
        $validated = $request->validate(['theme' => 'required|string|in:light,dark,auto']);
        $request->user()->update(['theme_preference' => $validated['theme']]);
        return $this->successResponse(['theme' => $validated['theme']], 'Theme updated');
    }

    public function updateLanguage(Request $request): JsonResponse
    {
        $validated = $request->validate(['locale' => 'required|string|in:en,fr,es,ar,zh']);
        $request->user()->update(['locale' => $validated['locale']]);
        return $this->successResponse(['locale' => $validated['locale']], 'Language updated');
    }
}
