<?php

namespace WalkerChiu\DeviceModbus\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class SettingLang extends Lang
{
    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.device-modbus.settings_lang');

        parent::__construct($attributes);
    }
}
