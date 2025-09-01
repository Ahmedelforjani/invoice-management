<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\Customer;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewCustomer extends ViewRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->before(function (Customer $record, DeleteAction $action) {
                    if ($record->invoices()->count() > 0) {
                        Notification::make()
                            ->title('خطأ')
                            ->body('لا يمكن حذف هذا الزبون لأنه يمتلك فواتير')
                            ->status('danger')
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->schema([
                    TextEntry::make('name')
                        ->label('الاسم'),
                    TextEntry::make('phone')
                        ->label('الهاتف')
                        ->copyable(),
                    TextEntry::make('address')
                        ->label('العنوان')
                ])
                ->columns()
                ->columnSpanFull()
        ]);
    }
}
