<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\HitosaraEventRepositoryInterface;
use App\Infrastructure\Eloquents\EloquentEvent;
use App\Infrastructure\Eloquents\EloquentGeo;
use App\Infrastructure\Eloquents\EloquentMainType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HitosaraEventRepository implements HitosaraEventRepositoryInterface
{

    private $hitosaraEvent;
    private $geoHitosaraUrl;
    private $mainTypeUrl;

    /**
     * HitosaraEventRepository constructor.
     *
     * @param Client $client
     */
    public function __construct(
        EloquentEvent $hitosaraEvent,
        EloquentGeo $geoHitosaraUrl,
        EloquentMainType $mainTypeUrl
    ) {
        $this->hitosaraEvent = $hitosaraEvent;
        $this->geoHitosaraUrl = $geoHitosaraUrl;
        $this->mainTypeUrl = $mainTypeUrl;
    }

    /**
     * getHitosaraAllEventList
     * ユーザー情報を取得
     *
     * @param int $userId
     * @return array
     */
    public function getHitosaraAllEventList() : array
    {
        $hitosaraAllEventList = $this->hitosaraEvent->newQuery()
            ->get();

        return $hitosaraAllEventList->toArray();
    }

    /**
     * getHitosaraGeoUrl
     * 場所の情報から地域URLを取得
     *
     * @param string $address
     * @return array
     */
    public function getHitosaraGeoUrl($address) : array
    {
        $urlList = $this->geoHitosaraUrl->newQuery()
        ->select(
            $this->geoHitosaraUrl->getTable().".prefecture_url",
            $this->geoHitosaraUrl->getTable().".city_url",
        )
        ->where(DB::raw('CONCAT(prefecture_name, city_name)'), 'like', '%'.$address.'%')
        ->first();

        return $urlList->toArray();
    }

    /**
     * getHitosaraMainTypeUrl
     * description情報からメインジャンルを取得
     *
     * @param array $description
     * @return array
     */
    public function getHitosaraMainTypeUrl(array $description): array
    {
        $urlList = $this->mainTypeUrl->newQuery()
        ->select(
            $this->mainTypeUrl->getTable().".main_url"
        )
        ->where($this->mainTypeUrl->getTable().".main_name", 'like', '%'.$description[0].'%')
        ->orwhere($this->mainTypeUrl->getTable().".main_name", 'like', '%'.$description[1].'%')
        ->orwhere($this->mainTypeUrl->getTable().".main_name", 'like', '%'.$description[2].'%')
        ->first();

        return $urlList->toArray();
    }
}
