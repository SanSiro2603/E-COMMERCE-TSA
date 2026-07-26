<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LandingSortOrderService
{
    public function insert(Builder $scope, int $requestedPosition): int
    {
        $position = $this->clamp($requestedPosition, (clone $scope)->count() + 1);

        (clone $scope)
            ->where('sort_order', '>=', $position)
            ->increment('sort_order');

        return $position;
    }

    public function move(Builder $scope, Model $item, int $requestedPosition): int
    {
        $position = $this->clamp($requestedPosition, max(1, (clone $scope)->count()));
        $currentPosition = max(1, (int) $item->getAttribute('sort_order'));

        if ($position < $currentPosition) {
            (clone $scope)
                ->where($item->getKeyName(), '!=', $item->getKey())
                ->whereBetween('sort_order', [$position, $currentPosition - 1])
                ->increment('sort_order');
        } elseif ($position > $currentPosition) {
            (clone $scope)
                ->where($item->getKeyName(), '!=', $item->getKey())
                ->whereBetween('sort_order', [$currentPosition + 1, $position])
                ->decrement('sort_order');
        }

        return $position;
    }

    public function remove(Builder $scope, int $removedPosition): void
    {
        (clone $scope)
            ->where('sort_order', '>', $removedPosition)
            ->decrement('sort_order');
    }

    private function clamp(int $requestedPosition, int $maximum): int
    {
        return min(max(1, $requestedPosition), max(1, $maximum));
    }
}
