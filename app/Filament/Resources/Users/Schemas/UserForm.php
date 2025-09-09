<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('الاسم')
                    ->required(),
                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required(),

                TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => \Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->revealable(),

                TextInput::make('password')
                    ->label('تأكيد كلمة المرور')
                    ->password()
                    ->helperText('اترك فارغًا إذا كنت لا تريد تغيير كلمة المرور في التحديث.')
                    ->dehydrateStateUsing(fn($state) => \Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->revealable(),
            ]);
    }
}
