<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\HitosaraEventRepositoryInterface;
use App\Infrastructure\Eloquents\EloquentEvent;
use Illuminate\Support\Facades\Log;

class HitosaraEventRepository implements HitosaraEventRepositoryInterface
{

    private $hitosaraEvent;

    /**
     * HitosaraEventRepository constructor.
     *
     * @param Client $client
     */
    public function __construct(
        EloquentEvent $hitosaraEvent
    ) {
        $this->hitosaraEvent = $hitosaraEvent;
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

}
