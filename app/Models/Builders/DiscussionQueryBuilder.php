<?php

declare(strict_types=1);

namespace App\Models\Builders;

use App\Models\Discussion;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of Discussion
 */
final class DiscussionQueryBuilder extends Builder
{
    public function pinned(): self
    {
        return $this->where('is_pinned', true);
    }

    public function notPinned(): self
    {
        return $this->where('is_pinned', false);
    }

    public function recent(): self
    {
        return $this->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');
    }

    public function popular(): self
    {
        return $this->withCount('reactions')
            ->orderBy('reactions_count', 'desc');
    }

    public function active(): self
    {
        return $this->withCount(['replies' => function ($query): void {
            $query->where('created_at', '>=', now()->subWeek());
        }])
            ->orderBy('replies_count', 'desc');
    }

    public function noComments(): self
    {
        return $this->whereDoesntHave('replies')
            ->orderByDesc('created_at');
    }
}
