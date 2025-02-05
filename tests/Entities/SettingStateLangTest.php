<?php

namespace WalkerChiu\DeviceModbus;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use WalkerChiu\DeviceModbus\Models\Entities\Channel;
use WalkerChiu\DeviceModbus\Models\Entities\Main;
use WalkerChiu\DeviceModbus\Models\Entities\Setting;
use WalkerChiu\DeviceModbus\Models\Entities\SettingState;
use WalkerChiu\DeviceModbus\Models\Entities\SettingStateLang;

class SettingStateLangTest extends \Orchestra\Testbench\TestCase
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
     * A basic functional test on SettingStateLang.
     *
     * For WalkerChiu\Core\Models\Entities\Lang
     *     WalkerChiu\DeviceModbus\Models\Entities\SettingStateLang
     *
     * @return void
     */
    public function testSettingStateLang()
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
        factory(SettingState::class, 2)->create();
        factory(SettingStateLang::class)->create(['morph_id' => 1, 'morph_type' => SettingState::class, 'code' => 'en_us', 'key' => 'name', 'value' => 'Hello']);
        factory(SettingStateLang::class)->create(['morph_id' => 1, 'morph_type' => SettingState::class, 'code' => 'en_us', 'key' => 'description']);
        factory(SettingStateLang::class)->create(['morph_id' => 1, 'morph_type' => SettingState::class, 'code' => 'zh_tw', 'key' => 'description']);
        factory(SettingStateLang::class)->create(['morph_id' => 1, 'morph_type' => SettingState::class, 'code' => 'en_us', 'key' => 'name']);
        factory(SettingStateLang::class)->create(['morph_id' => 2, 'morph_type' => SettingState::class, 'code' => 'en_us', 'key' => 'name']);
        factory(SettingStateLang::class)->create(['morph_id' => 2, 'morph_type' => SettingState::class, 'code' => 'zh_tw', 'key' => 'description']);

        // Get records after creation
            // When
            $records = SettingStateLang::all();
            // Then
            $this->assertCount(6, $records);

        // Get record's morph
            // When
            $record = SettingStateLang::find(1);
            // Then
            $this->assertNotNull($record);
            $this->assertInstanceOf(SettingState::class, $record->morph);

        // Scope query on whereCode
            // When
            $records = SettingStateLang::ofCode('en_us')
                                      ->get();
            // Then
            $this->assertCount(4, $records);

        // Scope query on whereKey
            // When
            $records = SettingStateLang::ofKey('name')
                                      ->get();
            // Then
            $this->assertCount(3, $records);

        // Scope query on whereCodeAndKey
            // When
            $records = SettingStateLang::ofCodeAndKey('en_us', 'name')
                                      ->get();
            // Then
            $this->assertCount(3, $records);

        // Scope query on whereMatch
            // When
            $records = SettingStateLang::ofMatch('en_us', 'name', 'Hello')
                                      ->get();
            // Then
            $this->assertCount(1, $records);
            $this->assertTrue($records->contains('id', 1));
    }

    /**
     * A basic functional test on SettingStateLang.
     *
     * For WalkerChiu\Core\Models\Entities\LangTrait
     *     WalkerChiu\DeviceModbus\Models\Entities\SettingStateLang
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
        factory(SettingState::class, 2)->create();
        factory(SettingStateLang::class)->create(['morph_id' => 1, 'morph_type' => SettingState::class, 'code' => 'en_us', 'key' => 'name', 'value' => 'Hello']);
        factory(SettingStateLang::class)->create(['morph_id' => 1, 'morph_type' => SettingState::class, 'code' => 'en_us', 'key' => 'description']);
        factory(SettingStateLang::class)->create(['morph_id' => 1, 'morph_type' => SettingState::class, 'code' => 'zh_tw', 'key' => 'description']);
        factory(SettingStateLang::class)->create(['morph_id' => 1, 'morph_type' => SettingState::class, 'code' => 'en_us', 'key' => 'name']);
        factory(SettingStateLang::class)->create(['morph_id' => 2, 'morph_type' => SettingState::class, 'code' => 'en_us', 'key' => 'name']);
        factory(SettingStateLang::class)->create(['morph_id' => 2, 'morph_type' => SettingState::class, 'code' => 'zh_tw', 'key' => 'description']);

        // Get lang of record
            // When
            $record_1 = SettingState::find(1);
            $lang_1   = SettingStateLang::find(1);
            $lang_4   = SettingStateLang::find(4);
            // Then
            $this->assertNotNull($record_1);
            $this->assertTrue(!$lang_1->is_current);
            $this->assertTrue($lang_4->is_current);
            $this->assertCount(4, $record_1->langs);
            $this->assertInstanceOf(SettingStateLang::class, $record_1->findLang('en_us', 'name', 'entire'));
            $this->assertEquals(4, $record_1->findLang('en_us', 'name', 'entire')->id);
            $this->assertEquals(4, $record_1->findLangByKey('name', 'entire')->id);
            $this->assertEquals(2, $record_1->findLangByKey('description', 'entire')->id);

        // Get lang's histories of record
            // When
            $histories_1 = $record_1->getHistories('en_us', 'name');
            $record_2 = SettingState::find(2);
            $histories_2 = $record_2->getHistories('en_us', 'name');
            // Then
            $this->assertCount(1, $histories_1);
            $this->assertCount(0, $histories_2);
    }
}
