<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\ScheduleRepositoryInterface;
use App\Infrastructure\Eloquents\EloquentTimetreeLogin;
use App\Infrastructure\Eloquents\EloquentUser;
use Illuminate\Support\Facades\Log;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    private $user;
    private $userTimetreeLogin;

    /**
     * PostedReviewRepository constructor.
     *
     * @param Client $client
     */
    public function __construct(
        EloquentUser $user,
        EloquentTimetreeLogin $userTimetreeLogin
    ) {
        $this->user = $user;
        $this->userTimetreeLogin = $userTimetreeLogin;
    }

    /**
     * getUserInfo
     * ユーザー情報を取得
     *
     * @param int $userId
     * @return array
     */
    public function getUserInfo(int $userId) : array
    {
        $userInfo = $this->user->newQuery()
            ->where('user_id', $userId)
            ->first();

        return $userInfo->toArray();
    }

    /**
     * getUserTimetreeInfo
     * タイムツリー
     * ユーザー情報を取得
     *
     * @param array $userInfo
     * @return array
     */
    public function getUserTimetreeInfo(array $userInfo) : array
    {
        $userTimetreeLoginInfo = $this->userTimetreeLogin->newQuery()
            ->where('user_id', $userInfo['user_id'])
            ->first();

        return $userTimetreeLoginInfo->toArray();
    }

    /**
     * getTimetreeCalendersInfo
     * タイムツリー
     * 全カレンダーを取得
     *
     * @param
     * @return array
     */
    public function getTimetreeCalendersInfo(array $userTimetreeInfo) : ?array
    {
        $getCalenderUrl = "https://timetreeapis.com/calendars/".decrypt($userTimetreeInfo['calender_id'])."?include=labels,members";
        $headers = [
            "Accept: application/vnd.timetree.v1+json",
            "Authorization: Bearer ".decrypt($userTimetreeInfo['calender_token'])
        ];

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curlHandle, CURLOPT_URL, $getCalenderUrl);
        // curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true); // curl_exec()の結果を文字列にする
        $json_response = curl_exec($curlHandle);
        curl_close($curlHandle);

        $calenderInfo = json_decode($json_response, true);

        return $calenderInfo;
    }

    /**
     * getTimetreeScheduleList
     * タイムツリー
     * 予約リストを取得
     *
     * @param array $userTimetreeInfo
     * @return array
     */
    public function getTimetreeScheduleList(array $userTimetreeInfo) : ?array
    {
        $getCalenderUrl = "https://timetreeapis.com/calendars/".decrypt($userTimetreeInfo['calender_id'])."/upcoming_events?timezone=Asia/Tokyo&days=3&include=creator,label,attendees";

        $headers = [
            "Accept: application/vnd.timetree.v1+json",
            "Authorization: Bearer ".decrypt($userTimetreeInfo['calender_token'])
        ];

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curlHandle, CURLOPT_URL, $getCalenderUrl);
        // curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true); // curl_exec()の結果を文字列にする
        $json_response = curl_exec($curlHandle);
        curl_close($curlHandle);

        $calenderInfo = json_decode($json_response, true);

        return empty($calenderInfo) ? [] : $calenderInfo['data'];
    }

    /**
     * getTimetreeEventInfo
     * 予定を取得
     *
     * @param string $eventId 予定ID
     * @param array $userTimetreeInfo
     * @return array
     */
    public function getTimetreeEventInfo(string $eventId, array $userTimetreeInfo) : ?array
    {

        $getCalenderUrl = "https://timetreeapis.com/calendars/".decrypt($userTimetreeInfo['calender_id'])."/events/".decrypt($userTimetreeInfo['calender_id'])."?include=creator,label,attendees";
        $headers = [
            "Accept: application/vnd.timetree.v1+json",
            "Authorization: Bearer ".decrypt($userTimetreeInfo['calender_token'])
        ];

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curlHandle, CURLOPT_URL, $getCalenderUrl);
        // curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true); // curl_exec()の結果を文字列にする
        $json_response = curl_exec($curlHandle);
        curl_close($curlHandle);

        $calenderInfo = json_decode($json_response, true);

        return $calenderInfo;
    }

    /**
     * getTimetreeEventInfo
     * 予定を取得
     *
     * @param array $userTimetreeInfo
     * @param array $eventId 予定ID
     * @param array $hitosaraEvent
     * @param array $addUrlList 追加するURLのリスト
     * @return array
     */
    public function addCommentHitosaraEvent(array $userTimetreeInfo, array $scheduleInfo, array $hitosaraEvent, array $addUrlList = []) : array
    {
        $locat = '';
        if(!empty($scheduleInfo["attributes"]['location'])) {
            $locat = "【 ".$scheduleInfo["attributes"]["location"]." 】付近で\n";
        } else if (!empty($addUrlList['prefecture_url']) || !empty($addUrlList['main_url'])) {
            $timetreeDescription = preg_split('/\s/', $scheduleInfo["attributes"]["description"]);
            $locat = "【 ".$timetreeDescription[1]." 】付近の【 ".$timetreeDescription[2]." 】で\n";
        }

        $addUrl = '';
        if(!empty($addUrlList)) $addUrl = implode("/", $addUrlList)."/";

        $comment = [
            "data" => [
                "attributes" => [
                    "content" => "+*●○ ヒトサラ ○●*+\nこんにちはぁ〜٩(ˊᗜˋ*)وｨ\n\n".$locat."【 ".$hitosaraEvent["event_name"]." 】におすすめのお店を\n↓ここから予約できます\n\n-- >> Check!! & Reserve!! << ---\n--------------------------------\n\n".$hitosaraEvent["event_url"].$addUrl."\n\n--------------------------------\n--------------------------------"
                ]
            ]
        ];

        $getCalenderUrl = "https://timetreeapis.com/calendars/".decrypt($userTimetreeInfo['calender_id'])."/events/".$scheduleInfo['id']."/activities";

        $headers = [
            "Content-Type: application/json",
            "Accept: application/vnd.timetree.v1+json",
            "Authorization: Bearer ".decrypt($userTimetreeInfo['calender_token'])
        ];

        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curlHandle, CURLOPT_URL, $getCalenderUrl);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode($comment));
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true); // curl_exec()の結果を文字列にする
        $json_response = curl_exec($curlHandle);
        curl_close($curlHandle);

        $calenderInfo = json_decode($json_response, true);

        // Log::debug(print_r($calenderInfo, true));

        return empty($calenderInfo) && empty($calenderInfo['data']) ? [] : $calenderInfo;
    }

}
