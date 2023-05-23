<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\ScheduleServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimetreeController extends Controller
{
    private $service;

    public function __construct(
        ScheduleServiceInterface $service
    )
    {
        $this->service = $service;
    }

    /**
     * カレンダー取得
     *
     * @param Request $request
     * @return void
     */
    public function getCalenders(Request $request) 
    {
        $scheduleInfo = $this->service->getTimetreeCalenders();

        return response()->json($scheduleInfo);
    }

    /**
     * 予定取得
     *
     * @param Request $request
     * @return void
     */
    public function getSchedules(Request $request) 
    {
        $scheduleInfo = $this->service->getTimetreeSchedules();

        return response()->json($scheduleInfo);

    }

    /**
     * おすすめコメント追加
     *
     * @param Request $request
     * @return void
     */
    public function addRecommend(Request $request) 
    {
        $recommendComment = $this->service->addTimetreeEventRecommendComment();

        return response()->json($recommendComment);
    }

    /**
     * おすすめコメント(GEO情報とスケジュールの登録)追加
     *
     * @param Request $request
     * @return void
     */
    public function addRecommendGeo(Request $request) 
    {
        $recommendComment = $this->service->addTimetreeEventGeoRecommendComment();

        return response()->json($recommendComment);
    }

    /**
     * おすすめコメント(description情報とスケジュールの登録)追加
     *
     * @param Request $request
     * @return void
     */
    public function addRecommendDescription(Request $request) 
    {
        $recommendComment = $this->service->addTimetreeEventDescriptionRecommendComment();

        return response()->json($recommendComment);
    }
}
