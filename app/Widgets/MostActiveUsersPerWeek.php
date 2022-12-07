<?php

namespace App\Widgets;

use App\Models\User;
use Arrilot\Widgets\AbstractWidget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class MostActiveUsersPerWeek extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The number of seconds before each reload.
     *
     * @var int|float
     */
    public $reloadTimeout = 60 * 60 * 24 * 2; // 2 days

    /**
     * The number of minutes before cache expires.
     * False means no caching at all.
     *
     * @var int|float|bool
     */
    public $cacheTime = 0;

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run(): View
    {
        $users = User::with('activities')
            ->withCount('activities')
            ->verifiedUsers()
            ->whereHas('activities', function (Builder $query) {
                return $query->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
            })
            ->orderByDesc('activities_count')
            ->limit(5)
            ->get();

        return view('widgets.most_active_users_per_week', [
            'config' => $this->config,
            'users' => $users,
        ]);
    }
}