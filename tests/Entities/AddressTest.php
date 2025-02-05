<?php

namespace WalkerChiu\DeviceModbus;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\DeviceModbus\Models\Entities\Channel;
use WalkerChiu\DeviceModbus\Models\Entities\Main;
use WalkerChiu\DeviceModbus\Models\Entities\Setting;
use WalkerChiu\DeviceModbus\Models\Entities\Address;
use WalkerChiu\DeviceModbus\Models\Entities\AddressLang;

class AddressTest extends \Orchestra\Testbench\TestCase
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
     * A basic functional test on Address.
     *
     * For WalkerChiu\DeviceModbus\Models\Entities\Address
     * 
     * @return void
     */
    public function testAddress()
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
        $record_1 = factory(Address::class)->create();
        $record_2 = factory(Address::class)->create();
        $record_3 = factory(Address::class)->create(['is_enabled' => 1]);

        // Get records after creation
            // When
            $records = Address::all();
            // Then
            $this->assertCount(3, $records);

        // Delete someone
            // When
            $record_2->delete();
            $records = Address::all();
            // Then
            $this->assertCount(2, $records);

        // Resotre someone
            // When
            Address::withTrashed()
                   ->find(2)
                   ->restore();
            $record_2 = Address::find(2);
            $records = Address::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(3, $records);

        // Return Lang class
            // When
            $class = $record_2->lang();
            // Then
            $this->assertEquals($class, AddressLang::class);

        // Scope query on enabled records
            // When
            $records = Address::ofEnabled()
                              ->get();
            // Then
            $this->assertCount(1, $records);

        // Scope query on disabled records
            // When
            $records = Address::ofDisabled()
                              ->get();
            // Then
            $this->assertCount(2, $records);
    }
}
