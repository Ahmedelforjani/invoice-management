<?php

namespace App\Filament\Resources\Expenses\Schemas;

use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Employees\RelationManagers\ExpensesRelationManager;
use App\Filament\Resources\Employees\Schemas\EmployeeForm;
use App\Models\Customer;
use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label('صرف إلى')
                    ->relationship('employee',)
                    ->getOptionLabelFromRecordUsing(fn(Employee $record) => "{$record->name} ({$record->phone})")
                    ->createOptionForm(fn(Schema $schema) => EmployeeForm::configure($schema))
                    ->required()
                    ->searchable(['name', 'phone'])
                    ->hiddenOn(ExpensesRelationManager::class)
                    ->preload(),

                Textarea::make('description')
                    ->label('وصف المصروف')
                    ->required()
                    ->rows(3),


                TextInput::make('amount')
                    ->label('القيمة')
                    ->numeric()
                    ->suffix('د.ل')
                    ->required()
                    ->minValue(0.01),

                DatePicker::make('expense_date')
                    ->label('تاريخ المصروف')
                    ->default(now())
                    ->required(),
            ]);
    }
}
