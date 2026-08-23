<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('r2')->delete("artists/{$user->id}/{$user->avatar}");
            }
            $file = $request->file('avatar');
            $filename = 'avatar.'.$file->extension();
            Storage::disk('r2')->put("artists/{$user->id}/$filename", $file->get());
            $validated['avatar'] = $filename;
        }

        // Handle CV PDF upload
        if ($request->hasFile('cv_pdf')) {
            if ($user->cv_pdf) {
                Storage::disk('r2')->delete("artists/{$user->id}/{$user->cv_pdf}");
            }
            $file = $request->file('cv_pdf');
            $filename = 'cv.'.$file->extension();
            Storage::disk('r2')->put("artists/{$user->id}/$filename", $file->get());
            $validated['cv_pdf'] = $filename;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
