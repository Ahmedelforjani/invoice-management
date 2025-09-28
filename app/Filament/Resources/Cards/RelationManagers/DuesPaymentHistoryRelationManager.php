<?php

namespace App\Filament\Resources\Cards\RelationManagers;

use App\Models\CardDuePayment;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class DuesPaymentHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'duesPayments';

    protected static ?string $title = 'سجل المستحقات المدفوعة';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->label('القيمة')
                    ->numeric()
                    ->suffix('$')
                    ->required()
                    ->minValue(0.01)
                    ->maxValue(function () {
                        $card = $this->getOwnerRecord();

                        $editingPayment = $this->getMountedAction()?->getRecord();

                        return $card->maxAllowedDuePayment($editingPayment);
                    }),

                DatePicker::make('payment_date')
                    ->label('تاريخ الدفع')
                    ->default(now())
                    ->required(),

                Textarea::make('notes')
                    ->label('ملاحظات')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                TextColumn::make('amount')
                    ->label('القيمة')
                    ->suffix('$')
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->sortable(),

                TextColumn::make('payment_date')
                    ->label('تاريخ الدفع')
                    ->date()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('notes')
                    ->label('ملاحظات')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })

            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('إضافة دفعة')
                    ->hidden(fn() => $this->getOwnerRecord()->balance <= 0)
                    ->after(function (CardDuePayment $record) {
                        $card = $this->getOwnerRecord();
                        $card->updateDueAmount(-$record->amount);
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->before(function (CardDuePayment $record, array $data) {
                    $oldAmount = $record->amount ?? 0;
                    $newAmount = $data['amount'] ?? 0;

                    $difference = $newAmount - $oldAmount;

                    $card = $this->getOwnerRecord();
                    $card->updateDueAmount(-$difference);
                }),

                DeleteAction::make()->after(function (CardDuePayment $record) {
                    $card = $this->getOwnerRecord();
                    $card->updateDueAmount($record->amount);

                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->after(function (Collection $records) {
                        $sumDeleted = $records->sum('amount');
                        $card = $this->getOwnerRecord();
                        $card->updateBalance($sumDeleted);
                    }),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        $card = $this->getOwnerRecord();
        return $card->dues_amount <= 0;
    }
}
