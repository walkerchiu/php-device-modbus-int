<?php

namespace WalkerChiu\DeviceModbus\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Forms\FormTrait;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryTrait;
use WalkerChiu\Core\Models\Services\PackagingFactory;

class ChannelRepository extends Repository
{
    use FormTrait;
    use RepositoryTrait;

    protected $instance;



    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = App::make(config('wk-core.class.device-modbus.channel'));
    }

    /**
     * @param String  $code
     * @param Array   $data
     * @param Bool    $is_enabled
     * @param Bool    $auto_packing
     * @return Array|Collection|Eloquent
     */
    public function list(string $code, array $data, $is_enabled = null, $auto_packing = false)
    {
        $instance = $this->instance;
        if ($is_enabled === true)      $instance = $instance->ofEnabled();
        elseif ($is_enabled === false) $instance = $instance->ofDisabled();

        $data = array_map('trim', $data);
        $repository = $instance->with(['langs' => function ($query) use ($code) {
                                    $query->ofCurrent()
                                          ->ofCode($code);
                                }])
                                ->whereHas('langs', function ($query) use ($code) {
                                    return $query->ofCurrent()
                                                 ->ofCode($code);
                                })
                                ->unless(empty(config('wk-core.class.morph-tag.tag')), function ($query) {
                                    return $query->with(['tags', 'tags.langs']);
                                })
                                ->when($data, function ($query, $data) {
                                    return $query->unless(empty($data['id']), function ($query) use ($data) {
                                                return $query->where('id', $data['id']);
                                            })
                                            ->unless(empty($data['serial']), function ($query) use ($data) {
                                                return $query->where('serial', $data['serial']);
                                            })
                                            ->unless(empty($data['identifier']), function ($query) use ($data) {
                                                return $query->where('identifier', $data['identifier']);
                                            })
                                            ->unless(empty($data['order']), function ($query) use ($data) {
                                                return $query->where('order', $data['order']);
                                            })
                                            ->unless(empty($data['protocol']), function ($query) use ($data) {
                                                return $query->where('protocol', $data['protocol']);
                                            })
                                            ->unless(empty($data['interface']), function ($query) use ($data) {
                                                return $query->where('interface', $data['interface']);
                                            })
                                            ->unless(empty($data['scan_interval']), function ($query) use ($data) {
                                                return $query->where('scan_interval', $data['scan_interval']);
                                            })
                                            ->unless(empty($data['polling_timeout']), function ($query) use ($data) {
                                                return $query->where('polling_timeout', $data['polling_timeout']);
                                            })
                                            ->unless(empty($data['retry_interval']), function ($query) use ($data) {
                                                return $query->where('retry_interval', $data['retry_interval']);
                                            })
                                            ->unless(empty($data['baud']), function ($query) use ($data) {
                                                return $query->where('baud', $data['baud']);
                                            })
                                            ->unless(empty($data['parity']), function ($query) use ($data) {
                                                return $query->where('parity', $data['parity']);
                                            })
                                            ->unless(empty($data['stop_bit']), function ($query) use ($data) {
                                                return $query->where('stop_bit', $data['stop_bit']);
                                            })
                                            ->unless(empty($data['ip']), function ($query) use ($data) {
                                                return $query->where('ip', $data['ip']);
                                            })
                                            ->unless(empty($data['port']), function ($query) use ($data) {
                                                return $query->where('port', $data['port']);
                                            })
                                            ->unless(empty($data['name']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'name')
                                                          ->where('value', 'LIKE', "%".$data['name']."%");
                                                });
                                            })
                                            ->unless(empty($data['description']), function ($query) use ($data) {
                                                return $query->whereHas('langs', function ($query) use ($data) {
                                                    $query->ofCurrent()
                                                          ->where('key', 'description')
                                                          ->where('value', 'LIKE', "%".$data['description']."%");
                                                });
                                            })
                                            ->unless(empty($data['categories']), function ($query) use ($data) {
                                                return $query->whereHas('categories', function ($query) use ($data) {
                                                    $query->ofEnabled()
                                                          ->whereIn('id', $data['categories']);
                                                });
                                            })
                                            ->unless(empty($data['tags']), function ($query) use ($data) {
                                                return $query->whereHas('tags', function ($query) use ($data) {
                                                    $query->ofEnabled()
                                                          ->whereIn('id', $data['tags']);
                                                });
                                            });
                                })
                                ->orderBy('order', 'ASC');

        if ($auto_packing) {
            $factory = new PackagingFactory(config('wk-device-modbus.output_format'), config('wk-device-modbus.pagination.pageName'), config('wk-device-modbus.pagination.perPage'));
            $factory->setFieldsLang(['name', 'description']);
            return $factory->output($repository);
        }

        return $repository;
    }

    /**
     * @param Channel       $instance
     * @param Array|String  $code
     * @return Array
     */
    public function show($instance, $code): array
    {
    }
}