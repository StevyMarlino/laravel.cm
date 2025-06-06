<?php

declare(strict_types=1);

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

/**
 * @var \Tests\TestCase $this
 */
beforeEach(function (): void {
    Role::query()->create(['name' => 'admin']);

    $this->user = $this->login(['email' => 'joe@laravel.cm']);
    $this->user->assignRole('admin');

    $this->articles = Article::factory()->count(10)->create([
        'submitted_at' => now(),
    ]);
});

describe(ArticleResource::class, function (): void {
    it('page can display table with records', function (): void {
        Livewire::test(ArticleResource\Pages\ListArticles::class)
            ->assertCanSeeTableRecords($this->articles);
    })->group('articles');

    it('admin user can approved article', function (): void {
        $article = $this->articles->first();

        Livewire::test(ArticleResource\Pages\ListArticles::class)
            ->callTableAction('approved', $article);

        $article->refresh();

        expect($article->approved_at)->toBeInstanceOf(\Carbon\Carbon::class)
            ->and($article->declined_at)->toBeNull();

        Livewire::test(ArticleResource\Pages\ListArticles::class)
            ->assertTableActionHidden('approved', $article)
            ->assertTableActionHidden('declined', $article);
    })->group('articles');

    it('admin user can declined article', function (): void {
        $article = $this->articles->first();

        Livewire::test(ArticleResource\Pages\ListArticles::class)
            ->callTableAction('declined', $article, data: [
                'reason' => 'Ce contenu ne respecte pas nos règles éditoriales.',
            ]);

        $article->refresh();

        expect($article->declined_at)->toBeInstanceOf(\Carbon\Carbon::class)
            ->and($article->approved_at)->toBeNull();

        Livewire::test(ArticleResource\Pages\ListArticles::class)
            ->assertTableActionHidden('approved', $article)
            ->assertTableActionHidden('declined', $article);

    })->group('articles');
});
