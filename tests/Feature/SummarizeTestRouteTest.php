<?php

use Laravel\Ai\Agents\SummarizeAgent;

test('summarize test route returns a summary as json', function () {
    SummarizeAgent::fake(['Laravel is a popular PHP web framework.']);

    $article = 'Laravel is a web application framework with expressive, elegant syntax.';

    $this->getJson(route('test.summarize', ['article' => $article]))
        ->assertOk()
        ->assertJson([
            'article' => $article,
            'summary' => 'Laravel is a popular PHP web framework.',
        ]);

    SummarizeAgent::assertPrompted($article);
});

test('summarize test route uses default article when none is provided as json', function () {
    SummarizeAgent::fake(['A PHP web framework summary.']);

    $this->getJson(route('test.summarize'))
        ->assertOk()
        ->assertJsonPath('summary', 'A PHP web framework summary.')
        ->assertJson(fn ($json) => $json->whereType('article', 'string')->etc());
});

test('summarize test page renders input form', function () {
    $this->get(route('test.summarize'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('test/summarize')
            ->has('defaultArticle')
            ->where('summary', null)
            ->has('provider')
            ->has('model'));
});

test('summarize test page returns summary on post', function () {
    SummarizeAgent::fake(['A concise PHP framework summary.']);

    $article = 'Laravel is a web application framework with expressive, elegant syntax.';

    $this->post(route('test.summarize.store'), ['article' => $article])
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('test/summarize')
            ->where('article', $article)
            ->where('summary', 'A concise PHP framework summary.'));

    SummarizeAgent::assertPrompted($article);
});

test('summarize test page validates article input', function () {
    $this->post(route('test.summarize.store'), ['article' => 'short'])
        ->assertSessionHasErrors('article');
});
