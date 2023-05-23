<?php

namespace App\Domain\Repositories;

interface HitosaraEventRepositoryInterface
{

    public function getHitosaraAllEventList(): array;

    public function getHitosaraGeoUrl(string $address): array;

    public function getHitosaraMainTypeUrl(array $description): array;
}
