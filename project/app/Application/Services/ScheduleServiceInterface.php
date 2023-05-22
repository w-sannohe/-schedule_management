<?php

namespace App\Application\Services;


interface ScheduleServiceInterface
{
    public function getTimetreeCalenders(): array;

    public function getTimetreeSchedules(): array;

    public function addTimetreeEventRecommendComment(): array;

}
