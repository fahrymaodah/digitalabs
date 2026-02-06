<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected string $view = 'filament.pages.settings';

    protected static ?string $slug = 'settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        // Provide default values for new notification settings
        $defaults = [
            'admin_email' => config('mail.from.address', ''),
            'admin_notify_payout' => true,
            'admin_notify_payment_success' => true,
            'admin_notify_payment_failed' => true,
            'admin_notify_new_affiliate' => true,
        ];
        
        $settings = array_merge($defaults, $settings);
        $this->form->fill($settings);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Section::make('Site Information')
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Site Name')
                                            ->required(),
                                        TextInput::make('site_tagline')
                                            ->label('Tagline'),
                                        Textarea::make('site_description')
                                            ->label('Description')
                                            ->rows(3),
                                    ])
                                    ->columns(2),

                                Section::make('Contact Information')
                                    ->schema([
                                        TextInput::make('contact_email')
                                            ->label('Email')
                                            ->email(),
                                        TextInput::make('contact_phone')
                                            ->label('Phone'),
                                        Textarea::make('contact_address')
                                            ->label('Address')
                                            ->rows(2),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Social Links')
                                    ->schema([
                                        TextInput::make('instagram')
                                            ->label('Instagram URL')
                                            ->url(),
                                        TextInput::make('youtube')
                                            ->label('YouTube URL')
                                            ->url(),
                                        TextInput::make('tiktok')
                                            ->label('TikTok URL')
                                            ->url(),
                                        TextInput::make('twitter')
                                            ->label('Twitter/X URL')
                                            ->url(),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Payment')
                            ->icon('heroicon-o-credit-card')
                            ->schema([
                                Section::make('Duitku Configuration')
                                    ->schema([
                                        TextInput::make('duitku_merchant_code')
                                            ->label('Merchant Code')
                                            ->password()
                                            ->revealable(),
                                        TextInput::make('duitku_api_key')
                                            ->label('API Key')
                                            ->password()
                                            ->revealable(),
                                        Toggle::make('duitku_sandbox')
                                            ->label('Sandbox Mode')
                                            ->helperText('Enable for testing, disable for production'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Affiliate')
                            ->icon('heroicon-o-user-group')
                            ->schema([
                                Section::make('Affiliate Settings')
                                    ->schema([
                                        Toggle::make('affiliate_enabled')
                                            ->label('Enable Affiliate Program'),
                                        TextInput::make('default_commission_rate')
                                            ->label('Default Commission (%)')
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%'),
                                        TextInput::make('minimum_payout')
                                            ->label('Minimum Payout (Rp)')
                                            ->numeric()
                                            ->prefix('Rp'),
                                        TextInput::make('cookie_days')
                                            ->label('Cookie Duration (Days)')
                                            ->numeric()
                                            ->helperText('Referral tracking duration'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Homepage')
                            ->icon('heroicon-o-home')
                            ->schema([
                                Section::make('Hero Section')
                                    ->schema([
                                        TextInput::make('hero_title')
                                            ->label('Hero Title'),
                                        TextInput::make('hero_subtitle')
                                            ->label('Hero Subtitle'),
                                        TextInput::make('hero_cta_text')
                                            ->label('CTA Button Text'),
                                        TextInput::make('hero_cta_url')
                                            ->label('CTA Button URL'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Notifications')
                            ->icon('heroicon-o-bell')
                            ->schema([
                                Section::make('Admin Email Notifications')
                                    ->description('Configure which notifications the admin should receive.')
                                    ->schema([
                                        TextInput::make('admin_email')
                                            ->label('Admin Email')
                                            ->email()
                                            ->helperText('All admin notifications will be sent to this email'),
                                        Toggle::make('admin_notify_payout')
                                            ->label('New Payout Requests')
                                            ->helperText('Get notified when an affiliate requests a payout'),
                                        Toggle::make('admin_notify_payment_success')
                                            ->label('Successful Payments')
                                            ->helperText('Get notified when a payment is successful'),
                                        Toggle::make('admin_notify_payment_failed')
                                            ->label('Failed/Expired Payments')
                                            ->helperText('Get notified when a payment fails or expires'),
                                        Toggle::make('admin_notify_new_affiliate')
                                            ->label('New Affiliate Registrations')
                                            ->helperText('Get notified when a new affiliate registers'),
                                    ])
                                    ->columns(1),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                // Update existing setting
                Setting::set($key, $value, $setting->type, $setting->group);
            } else {
                // Create new setting - determine type from value
                $type = match (gettype($value)) {
                    'boolean' => 'boolean',
                    'integer' => 'integer',
                    'double' => 'float',
                    default => 'text',
                };
                Setting::set($key, $value, $type, 'general');
            }
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
