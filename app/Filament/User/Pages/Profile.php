<?php

namespace App\Filament\User\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Profile extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Profile';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Profile Settings';
    protected string $view = 'filament.user.pages.profile';

    // Profile fields
    public string $name = '';
    public string $email = '';
    public ?string $phone = '';

    // Password fields
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
    }

    public function updateProfile(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $user->update($validated);

        Notification::make()
            ->title('Profile Updated')
            ->body('Your profile has been updated successfully.')
            ->success()
            ->send();
    }

    public function updatePassword(): void
    {
        $user = Auth::user();
        $rules = [
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ];

        // Jika user punya password (tidak dari Google OAuth), perlu current_password
        if ($user->password) {
            $rules['current_password'] = 'required|current_password';
        }

        $this->validate($rules);

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        Notification::make()
            ->title('Password Changed')
            ->body('Your password has been changed successfully.')
            ->success()
            ->send();
    }

    protected function getViewData(): array
    {
        return [
            'user' => Auth::user(),
        ];
    }
}
