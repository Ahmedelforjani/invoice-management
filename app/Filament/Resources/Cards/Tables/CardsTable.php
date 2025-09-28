<?php

namespace App\Filament\Resources\Cards\Tables;

use App\Enums\CardStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),

                TextColumn::make('balance')
                    ->label('الرصيد')
                    ->suffix('$')
                    ->formatStateUsing(fn($state) => number_format($state, 2)),

                TextColumn::make('dues_amount')
                    ->label('المستحقات')
                    ->suffix('$')
                    ->formatStateUsing(fn($state) => number_format($state, 2)),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->multiple()
                    ->options(CardStatus::class)
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
