<?php

namespace App\Filament\User\Pages;

use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Computed;

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
    public ?string $province_id = null;
    public ?string $city_id = null;
    public ?string $district_id = null;

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
        $this->province_id = $user->province_id;
        $this->city_id = $user->city_id;
        $this->district_id = $user->district_id;
    }

    public function updateProfile(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'required|string|max:20',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'district_id' => 'nullable|exists:districts,id',
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

    // Livewire lifecycle hooks for cascade select
    public function updatedProvinceId(): void
    {
        $this->city_id = null;
        $this->district_id = null;
    }

    public function updatedCityId(): void
    {
        $this->district_id = null;
    }

    // Computed properties with caching
    #[Computed]
    public function provinces()
    {
        return Province::orderBy('name')->get();
    }

    #[Computed]
    public function cities()
    {
        if (!$this->province_id) {
            return collect([]);
        }

        return City::where('province_id', $this->province_id)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function districts()
    {
        if (!$this->city_id) {
            return collect([]);
        }

        return District::where('city_id', $this->city_id)
            ->orderBy('name')
            ->get();
    }

    protected function getViewData(): array
    {
        return [
            'user' => Auth::user(),
        ];
    }
}
