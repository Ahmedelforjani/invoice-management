<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'نظام الفوترة');
        $this->migrator->add('general.site_phone');
        $this->migrator->add('general.site_logo');
    }
};
