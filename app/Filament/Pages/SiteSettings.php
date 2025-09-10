<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SiteSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = GeneralSettings::class;

    protected static string | UnitEnum | null $navigationGroup = "النظام";
    protected static ?string $navigationLabel = 'إعدادات النظام';

    protected static ?string $title = 'إعدادات النظام';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('site_name')
                            ->label('الأسم')
                            ->required(),
                        TextInput::make('site_phone')
                            ->label('رقم الهاتف')
                            ->tel(),
                        FileUpload::make('site_logo')
                            ->label('الشعار')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->visibility('public')
                    ])->columnSpanFull()
            ]);
    }
}
