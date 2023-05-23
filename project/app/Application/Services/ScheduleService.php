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
        // $scheduleListCollection = new Collection($timetreeSchedulrList);

        $hitosaraEventList = $this->hitosaraEventRepo->getHitosaraAllEventList();
        // $hitosaraEventCollection = new Collection($hitosaraEventList);

        $resAddComment = [];
        foreach ($timetreeSchedulrList as $timetreeSchedule) {
            if (empty($timetreeSchedule["attributes"])) continue;

            foreach ($hitosaraEventList as $hitosaraEvent) {
                if (preg_match('/^(?=.*'.$hitosaraEvent["event_name"].').*$/', $timetreeSchedule["attributes"]["title"])) {
                    $resAddComment[] = $this->scheduleRepo->addCommentHitosaraEvent($userTimetreeLoginInfo, $timetreeSchedule, $hitosaraEvent);
                }
            }
        }

        return $resAddComment;

        // collectionのeach使ってきたいよね
        // $timetreeCalendersInfo = $scheduleListCollection->each(function ($scheduleInfo) use ($userTimetreeLoginInfo, $hitosaraEventCollection) {
        //     if (empty($scheduleInfo["attributes"])) return [];

        //     $addEvent = $hitosaraEventCollection->each(function ($hitosaraEvent) use ($userTimetreeLoginInfo, $scheduleInfo) {
        //         // Log::debug("event_name" . print_r($hitosaraEvent["event_name"], true));
        //         if (preg_match('/^(?=.*'.$hitosaraEvent["event_name"].').*$/', $scheduleInfo["attributes"]["title"])) {
        //             $resAddComment = $this->scheduleRepo->addCommentHitosaraEvent($userTimetreeLoginInfo, $scheduleInfo, $hitosaraEvent);
        //             // Log::debug(print_r($resAddComment, true));
        //             return $resAddComment;
        //         }
        //     });
        //     Log::debug(print_r($addEvent, true));
        //     return $addEvent;
        // });

        // Log::debug(print_r($timetreeCalendersInfo->toArray(), true));
        // return $timetreeCalendersInfo->toArray();
    }

    /**
     * addTimetreeEventGeoRecommendComment
     * タイムツリーに登録しているイベントからおすすめの特集を
     * GEO情報取得して
     * イベント登録をして
     * コメント追加する
     *
     * @param UserAuth $user
     * @param
     * @return array
     */
    public function addTimetreeEventGeoRecommendComment(): array
    {
        $userInfo = $this->scheduleRepo->getUserInfo(1);
        $userTimetreeLoginInfo = $this->scheduleRepo->getUserTimetreeInfo($userInfo);
        if (empty($userInfo) || empty($userTimetreeLoginInfo)) {
            return [];
        }

        $timetreeSchedulrList = $this->scheduleRepo->getTimetreeScheduleList($userTimetreeLoginInfo);
        // $scheduleListCollection = new Collection($timetreeSchedulrList);

        $hitosaraEventList = $this->hitosaraEventRepo->getHitosaraAllEventList();
        // $hitosaraEventCollection = new Collection($hitosaraEventList);

        $resAddComment = [];
        foreach ($timetreeSchedulrList as $timetreeSchedule) {
            if (empty($timetreeSchedule["attributes"])) continue;

            foreach ($hitosaraEventList as $hitosaraEvent) {
                if (preg_match('/^(?=.*'.$hitosaraEvent["event_name"].').*$/', $timetreeSchedule["attributes"]["title"])) {
                    // Log::debug("where: " . print_r($timetreeSchedule["attributes"]["location_lat"], true));
                    $hitosaraGeoUrl = [];
                    if(!empty($timetreeSchedule["attributes"]["location_lat"]) && !empty($timetreeSchedule["attributes"]['location_lon'])) {
                        // GEO ここをrepositoryに動かしたいよね -- start --
                        $uri = "https://map.yahooapis.jp/geocode/V1/geoCoder". "?lat=" . $timetreeSchedule["attributes"]["location_lat"] . "&lon=" . $timetreeSchedule["attributes"]["location_lon"] . "&appid=" . 'dj00aiZpPWV0NEIxZHc5ZHFvViZzPWNvbnN1bWVyc2VjcmV0Jng9N2U-' . "&output=json";

                        $curl = curl_init($uri);
                        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $res = json_decode($response,true);
                        // Log::debug("where: " . print_r($res['Feature'][0]['Property']['Address'], true));
                        // ここをrepositoryに動かしたいよね -- end --

                        if (!empty($res['Feature'][0]['Property']['Address'])) {
                            $hitosaraGeoUrl = $this->hitosaraEventRepo->getHitosaraGeoUrl($res['Feature'][0]['Property']['Address']);
                            // Log::debug("hitosaraGeoUrl: " . print_r($hitosaraGeoUrl, true));
                        }
                    }

                    $resAddComment[] = $this->scheduleRepo->addCommentHitosaraEvent($userTimetreeLoginInfo, $timetreeSchedule, $hitosaraEvent, $hitosaraGeoUrl);
                }
            }
        }

        return $resAddComment;

        // $timetreeCalendersInfo = $scheduleListCollection->each(function ($scheduleInfo) use ($userTimetreeLoginInfo, $hitosaraEventCollection) {
        //     if (empty($scheduleInfo["attributes"])) return [];

        //     $addEvent = $hitosaraEventCollection->each(function ($hitosaraEvent) use ($userTimetreeLoginInfo, $scheduleInfo) {
        //         Log::debug("event_name" . print_r($hitosaraEvent["event_name"], true));
        //         if (preg_match('/^(?=.*'.$hitosaraEvent["event_name"].').*$/', $scheduleInfo["attributes"]["title"])) {
        //             $resAddComment = $this->scheduleRepo->addCommentHitosaraEvent($userTimetreeLoginInfo, $scheduleInfo, $hitosaraEvent);
        //             Log::debug(print_r($resAddComment, true));
        //             return $resAddComment;
        //         }
        //     });
        //     // Log::debug(print_r($addEvent, true));

        //     return $addEvent;
        // });

        // return $timetreeCalendersInfo->toArray();
    }

    /**
     * addTimetreeEventDescriptionRecommendComment
     * タイムツリーに登録しているイベントからおすすめの特集を
     * Discriotion情報取得して
     * イベント登録をして
     * コメント追加する
     *
     * @param UserAuth $user
     * @param
     * @return array
     */
    public function addTimetreeEventDescriptionRecommendComment(): array
    {
        $userInfo = $this->scheduleRepo->getUserInfo(1);
        $userTimetreeLoginInfo = $this->scheduleRepo->getUserTimetreeInfo($userInfo);
        if (empty($userInfo) || empty($userTimetreeLoginInfo)) {
            return [];
        }

        $timetreeSchedulrList = $this->scheduleRepo->getTimetreeScheduleList($userTimetreeLoginInfo);
        // $scheduleListCollection = new Collection($timetreeSchedulrList);

        $hitosaraEventList = $this->hitosaraEventRepo->getHitosaraAllEventList();
        // $hitosaraEventCollection = new Collection($hitosaraEventList);

        $resAddComment = [];
        foreach ($timetreeSchedulrList as $timetreeSchedule) {
            if (empty($timetreeSchedule["attributes"])) continue;

            foreach ($hitosaraEventList as $hitosaraEvent) {
                if (preg_match('/^(?=.*'.$hitosaraEvent["event_name"].').*$/', $timetreeSchedule["attributes"]["title"])) {
                    // Log::debug("where: " . print_r($timetreeSchedule["attributes"]["location_lat"], true));
                    $conditions = [];
                    if (!empty($timetreeSchedule["attributes"]["description"]) && preg_match('/^ヒトサラ/', $timetreeSchedule["attributes"]["description"])) {
                        // Log::debug("desc: " . print_r($timetreeSchedule["attributes"]["description"], true));
                        $timetreeDescription = preg_split('/\s/', $timetreeSchedule["attributes"]["description"]);

                        //     // GEO ここをrepositoryに動かしたいよね -- start --
                        $uri = "https://map.yahooapis.jp/geocode/V1/geoCoder?appid=dj00aiZpPWV0NEIxZHc5ZHFvViZzPWNvbnN1bWVyc2VjcmV0Jng9N2U-&".http_build_query(['query' => $timetreeDescription[1], 'al' => 3, 'sort' => 'address2'])."&output=json";

                        $curl = curl_init($uri);
                        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $res = json_decode($response,true);
                        // Log::debug("where: " . print_r($res['Feature'][0]['Property']['Address'], true));
                    //     // ここをrepositoryに動かしたいよね -- end --
                        // $conditions[] = $this->hitosaraEventRepo->getHitosaraGeoUrl($timetreeDescription);
                        // Log::debug("conditions: " . print_r($res['Feature'][0]['Name'], true));

                        $addressUrl = [];
                        if (!empty($res['Feature'][0]['Name']) && preg_match('/.*区/', $res['Feature'][0]['Name'], $address)) {
                            // Log::debug("address: " . print_r($address, true));
                            $addressUrl = $this->hitosaraEventRepo->getHitosaraGeoUrl($address[0]);
                            // Log::debug("conditions: " . print_r($conditions, true));
                        }

                        $mainTypeUrl = $this->hitosaraEventRepo->getHitosaraMainTypeUrl($timetreeDescription);
                        $conditions = array_merge($addressUrl, $mainTypeUrl);
                        // Log::debug("conditions: " . print_r($conditions, true));
                    }

                    // $hitosaraGeoUrl = [];
                    // if(!empty($timetreeSchedule["attributes"]["location_lat"]) && !empty($timetreeSchedule["attributes"]['location_lon'])) {
                    //     // GEO ここをrepositoryに動かしたいよね -- start --
                    //     $uri = "https://map.yahooapis.jp/geocode/V1/geoCoder". "?lat=" . $timetreeSchedule["attributes"]["location_lat"] . "&lon=" . $timetreeSchedule["attributes"]["location_lon"] . "&appid=" . 'dj00aiZpPWV0NEIxZHc5ZHFvViZzPWNvbnN1bWVyc2VjcmV0Jng9N2U-' . "&output=json";

                    //     $curl = curl_init($uri);
                    //     curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                    //     $response = curl_exec($curl);
                    //     curl_close($curl);
                    //     $res = json_decode($response,true);
                    //     // Log::debug("where: " . print_r($res['Feature'][0]['Property']['Address'], true));
                    //     // ここをrepositoryに動かしたいよね -- end --

                    //     if (!empty($res['Feature'][0]['Property']['Address'])) {
                    //         $hitosaraGeoUrl = $this->hitosaraEventRepo->getHitosaraGeoUrl($res['Feature'][0]['Property']['Address']);
                    //         // Log::debug("hitosaraGeoUrl: " . print_r($hitosaraGeoUrl, true));
                    //     }
                    // }

                    $resAddComment[] = $this->scheduleRepo->addCommentHitosaraEvent($userTimetreeLoginInfo, $timetreeSchedule, $hitosaraEvent, $conditions);
                }
            }
        }

        return $resAddComment;
    }
}
