<?php

namespace App\Application\Services;


interface ScheduleServiceInterface
{
    public function getTimetreeCalenders(): array;

    public function getTimetreeSchedules(): array;

    public function addTimetreeEventRecommendComment(): array;

    public function addTimetreeEventGeoRecommendComment(): array;

    public function addTimetreeEventDescriptionRecommendComment(): array;
}
