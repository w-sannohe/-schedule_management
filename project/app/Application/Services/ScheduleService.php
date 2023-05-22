<?php

namespace App\Application\Services;

use App\Domain\Repositories\HitosaraEventRepositoryInterface;
use App\Domain\Repositories\ScheduleRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ScheduleService implements ScheduleServiceInterface
{
    private $scheduleRepo;
    private $hitosaraEventRepo;

    public function __construct(
        ScheduleRepositoryInterface $scheduleRepo,
        HitosaraEventRepositoryInterface $hitosaraEventRepo
    ) {
        $this->scheduleRepo = $scheduleRepo;
        $this->hitosaraEventRepo = $hitosaraEventRepo;
    }

    /**
     * getTimetreeCalenders
     * タイムツリー
     * カレンダー取得
     *
     * @param UserAuth $user
     * @param
     * @return int
     */
    public function getTimetreeCalenders(): array
    {
        $userInfo = $this->scheduleRepo->getUserInfo(1);
        $userTimetreeLoginInfo = $this->scheduleRepo->getUserTimetreeInfo($userInfo);
        if (empty($userInfo) || empty($userTimetreeLoginInfo)) {
            return [];
        }

        $timetreeCalendersInfo = $this->scheduleRepo->getTimetreeCalendersInfo($userTimetreeLoginInfo);

        return $timetreeCalendersInfo;
    }

    /**
     * getTimetreeSchedules
     * タイムツリー
     * 予定取得
     *
     * @param UserAuth $user
     * @param
     * @return int
     */
    public function getTimetreeSchedules(): array
    {
        $userInfo = $this->scheduleRepo->getUserInfo(1);
        $userTimetreeLoginInfo = $this->scheduleRepo->getUserTimetreeInfo($userInfo);
        if (empty($userInfo) || empty($userTimetreeLoginInfo)) {
            return [];
        }

        $timetreeSchedulrList = $this->scheduleRepo->getTimetreeScheduleList($userTimetreeLoginInfo);
        $scheduleListCollection = new Collection($timetreeSchedulrList);

        $timetreeCalendersInfo = $scheduleListCollection->each(function ($scheduleInfo) use ($userTimetreeLoginInfo) {
            return $this->scheduleRepo->getTimetreeEventInfo($scheduleInfo['id'], $userTimetreeLoginInfo);
        });

        return $timetreeCalendersInfo->toArray();
    }

    /**
     * addTimetreeEventRecommendComment
     * タイムツリーに登録しているイベントからおすすめの特集をコメント追加する
     *
     * @param UserAuth $user
     * @param
     * @return array
     */
    public function addTimetreeEventRecommendComment(): array
    {
        $userInfo = $this->scheduleRepo->getUserInfo(1);
        $userTimetreeLoginInfo = $this->scheduleRepo->getUserTimetreeInfo($userInfo);
        if (empty($userInfo) || empty($userTimetreeLoginInfo)) {
            return [];
        }

        $timetreeSchedulrList = $this->scheduleRepo->getTimetreeScheduleList($userTimetreeLoginInfo);
        $scheduleListCollection = new Collection($timetreeSchedulrList);

        $hitosaraEventList = $this->hitosaraEventRepo->getHitosaraAllEventList();
        $hitosaraEventCollection = new Collection($hitosaraEventList);

        $timetreeCalendersInfo = $scheduleListCollection->each(function ($scheduleInfo) use ($userTimetreeLoginInfo, $hitosaraEventCollection) {
            if (empty($scheduleInfo["attributes"])) return [];

            $aa = $hitosaraEventCollection->each(function ($hitosaraEvent) use ($userTimetreeLoginInfo, $scheduleInfo) {
                Log::debug("event_name" . print_r($hitosaraEvent["event_name"], true));
                if (preg_match('/^(?=.*'.$hitosaraEvent["event_name"].').*$/', $scheduleInfo["attributes"]["title"])) {
                    $resAddComment = $this->scheduleRepo->addCommentHitosaraEvent($userTimetreeLoginInfo, $scheduleInfo, $hitosaraEvent);
                    // Log::debug(print_r($resAddComment, true));
                    return $resAddComment;
                }
            });

            return $aa;
        });

        return $timetreeCalendersInfo->toArray();
    }
}
