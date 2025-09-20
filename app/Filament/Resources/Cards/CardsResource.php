<?php

namespace App\Filament\Resources\Cards;

use App\Filament\Resources\Cards\Pages\ListCards;
use App\Filament\Resources\Cards\Pages\ViewCards;
use App\Filament\Resources\Cards\RelationManagers\PaymentsHistoryRelationManager;
use App\Filament\Resources\Cards\Schemas\CardsForm;
use App\Filament\Resources\Cards\Tables\CardsTable;
use App\Models\Card;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CardsResource extends Resource
{
    protected static ?string $model = Card::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $navigationLabel = 'البطاقات';

    protected static ?string $modelLabel = 'بطاقة';

    protected static ?string $pluralModelLabel = 'البطاقات';

    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'Cards';

    public static function form(Schema $schema): Schema
    {
        return CardsForm::configure($schema);
    }



    public static function table(Table $table): Table
    {
        return CardsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PaymentsHistoryRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCards::route('/'),
            'view' => ViewCards::route('/{record}'),
        ];
    }
}
