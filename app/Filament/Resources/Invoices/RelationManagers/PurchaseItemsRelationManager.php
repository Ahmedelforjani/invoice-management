<?php

namespace App\Filament\Resources\Invoices\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchaseItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'purchaseItems';

    protected static ?string $title = 'مشتريات الفاتورة';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('description')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('unit_price')
                    ->required()
                    ->numeric(),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('description')
                    ->label('الصنف')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('الكمية')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label('سعر الوحدة')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('السعر الكلي')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ]);
    }
}
