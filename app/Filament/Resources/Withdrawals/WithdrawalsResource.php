<?php

namespace App\Filament\Resources\Withdrawals;

use App\Filament\Resources\Withdrawals\Pages\CreateWithdrawals;
use App\Filament\Resources\Withdrawals\Pages\EditWithdrawals;
use App\Filament\Resources\Withdrawals\Pages\ListWithdrawals;
use App\Filament\Resources\Withdrawals\Pages\ViewWithdrawals;
use App\Filament\Resources\Withdrawals\Schemas\WithdrawalsForm;
use App\Filament\Resources\Withdrawals\Schemas\WithdrawalsInfolist;
use App\Filament\Resources\Withdrawals\Tables\WithdrawalsTable;
use App\Models\Withdrawal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;

use Filament\Tables\Table;

class WithdrawalsResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'عمليات السحب';

    protected static ?string $modelLabel = 'سحبة';

    protected static ?string $pluralModelLabel = 'عمليات السحب';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return WithdrawalsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WithdrawalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWithdrawals::route('/'),
        ];
    }
}
