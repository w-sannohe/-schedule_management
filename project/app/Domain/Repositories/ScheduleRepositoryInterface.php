<?php

namespace App\Domain\Repositories;

interface ScheduleRepositoryInterface
{

    public function getUserInfo(int $userId): array;

    public function getUserTimetreeInfo(array $userInfo): array;

    public function getTimetreeCalendersInfo(array $userTimetreeInfo) : ?array;

    public function getTimetreeScheduleList(array $userTimetreeInfo) : ?array;

    public function getTimetreeEventInfo(string $eventId, array $userTimetreeInfo) : ?array;

    public function addCommentHitosaraEvent(array $userTimetreeInfo, array $scheduleInfo, array $hitosaraEvent) : array;

}
