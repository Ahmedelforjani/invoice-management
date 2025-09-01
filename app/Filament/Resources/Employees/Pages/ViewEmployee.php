<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\Employee;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->before(function (Employee $record, DeleteAction $action) {
                    if ($record->expenses()->count() > 0) {
                        Notification::make()
                            ->title('خطأ')
                            ->body('لا يمكن حذف هذا الموظف لأنه يمتلك مصروفات')
                            ->status('danger')
                            ->send();
                        $action->cancel();
                    }
                }),

        ];
    }
}
