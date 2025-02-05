<?php

namespace WalkerChiu\DeviceModbus;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\DeviceModbus\Models\Entities\Channel;
use WalkerChiu\DeviceModbus\Models\Entities\Main;
use WalkerChiu\DeviceModbus\Models\Entities\Setting;
use WalkerChiu\DeviceModbus\Models\Entities\SettingState;
use WalkerChiu\DeviceModbus\Models\Entities\SettingStateLang;

class SettingStateTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');
    }

    /**
     * To load your package service provider, override the getPackageProviders.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return Array
     */
    protected function getPackageProviders($app)
    {
        return [\WalkerChiu\Core\CoreServiceProvider::class,
                \WalkerChiu\DeviceModbus\DeviceModbusServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * A basic functional test on SettingState.
     *
     * For WalkerChiu\DeviceModbus\Models\Entities\SettingState
     * 
     * @return void
     */
    public function testSettingState()
    {
        // Config
        Config::set('wk-core.onoff.core-lang_core', 0);
        Config::set('wk-device-modbus.onoff.core-lang_core', 0);
        Config::set('wk-core.lang_log', 1);
        Config::set('wk-device-modbus.lang_log', 1);
        Config::set('wk-core.soft_delete', 1);
        Config::set('wk-device-modbus.soft_delete', 1);

        // Give
        factory(Channel::class)->create();
        factory(Main::class)->create();
        factory(Setting::class)->create();
        $record_1 = factory(SettingState::class)->create();
        $record_2 = factory(SettingState::class)->create();
        $record_3 = factory(SettingState::class)->create(['is_enabled' => 1]);

        // Get records after creation
            // When
            $records = SettingState::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $record_2->delete();
            $records = SettingState::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            SettingState::withTrashed()
                       ->find(2)
                       ->restore();
            $record_2 = SettingState::find(2);
            $records = SettingState::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);

        // Return Lang class
            // When
            $class = $record_2->lang();
            // Then
            $this->assertEquals($class, SettingStateLang::class);

        // Scope query on enabled records
            // When
            $records = SettingState::ofEnabled()
                                  ->get();
            // Then
            $this->assertCount(1, $records);

        // Scope query on disabled records
            // When
            $records = SettingState::ofDisabled()
                                  ->get();
            // Then
            $this->assertCount(2, $records);
    }
}
