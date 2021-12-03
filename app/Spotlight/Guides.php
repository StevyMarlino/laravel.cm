<?php

namespace App\Spotlight;

use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;

class Guides extends SpotlightCommand
{
    protected string $name = 'Guides';

    protected string $description = 'aller à la page du code de conduite';

    protected array $synonyms = [
        'code',
        'conduite',
        'règles',
        'comportement',
    ];

    public function execute(Spotlight $spotlight)
    {
        $spotlight->redirectRoute('rules');
    }
}