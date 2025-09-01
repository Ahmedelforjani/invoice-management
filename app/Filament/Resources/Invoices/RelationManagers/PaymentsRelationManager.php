<?php

namespace App\Filament\Resources\Invoices\RelationManagers;

use App\Filament\Resources\Payments\PaymentResource;
use App\Filament\Resources\Payments\Schemas\PaymentForm;
use App\Filament\Resources\Payments\Tables\PaymentsTable;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $relatedResource = PaymentResource::class;

    public function form(Schema $schema): Schema
    {
        $invoice = $this->getOwnerRecord();
        return PaymentForm::configure($schema, $invoice);
    }

    public function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
