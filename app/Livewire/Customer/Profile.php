<?php

namespace App\Livewire\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $phone;
    public $profile_picture;
    public $new_profile_picture;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $showPasswordForm = false;

    public $temp_profile_picture_preview = null;
    public $uploadSuccess = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->profile_picture = $this->user->profile_picture;
    }

    public function updatedNewProfilePicture()
    {
        $this->validate([
            'new_profile_picture' => 'image|max:2048',
        ]);

        $this->temp_profile_picture_preview = $this->new_profile_picture->temporaryUrl();
        $this->uploadSuccess = false;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|max:255|unique:users,email,' . $this->user->id,
            'phone' => [
                'required',
                'string',
                'regex:/^09\d{9}$/', // Removed size:11
            ],
            'new_profile_picture' => 'nullable|image|max:2048',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address (e.g., name@domain.com).',
            'email.dns' => 'The email domain does not appear to exist. Please check your email address.',
            'email.unique' => 'This email is already taken by another user.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid Philippine mobile number (e.g., 09123456789).',
        ]);

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        // Handle profile picture upload
        if ($this->new_profile_picture) {
            // Delete old profile picture if exists
            if ($this->user->profile_picture) {
                Storage::disk('public')->delete($this->user->profile_picture);
            }

            // Store the new image
            $path = $this->new_profile_picture->store('profile-pictures', 'public');
            $updateData['profile_picture'] = $path;
            $this->profile_picture = $path;

            // Clear the temporary preview
            $this->temp_profile_picture_preview = null;
            $this->uploadSuccess = true;
        }

        // Update user
        $this->user->update($updateData);

        // Refresh user data
        $this->user = Auth::user();
        $this->profile_picture = $this->user->profile_picture;

        session()->flash('message', 'Profile updated successfully!');

        return redirect()->route('livewire.customer.profile');
    }

    public function removeProfilePicture()
    {
        if ($this->user->profile_picture) {
            Storage::disk('public')->delete($this->user->profile_picture);
            $this->user->update(['profile_picture' => null]);
            $this->profile_picture = null;
            $this->new_profile_picture = null;
            $this->temp_profile_picture_preview = null;

            session()->flash('message', 'Profile picture removed successfully!');
            return redirect()->route('livewire.customer.profile');
        }
    }

    public function togglePasswordForm()
    {
        $this->showPasswordForm = !$this->showPasswordForm;
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $this->user->update([
            'password' => Hash::make($this->new_password),
        ]);

        session()->flash('message', 'Password updated successfully!');
        $this->togglePasswordForm();
    }

    public function render()
    {
        return view('livewire.customer.profile')
            ->layout('components.layouts.customer');
    }
}
