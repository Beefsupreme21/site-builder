<?php

test('chat page renders', function () {
    $this->get(route('chat.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('chat/index')
            ->has('messages', 0));
});

test('chat requires message content', function () {
    $this->post(route('chat.store'), ['content' => ''])
        ->assertSessionHasErrors('content');
});

test('chat requires an api key', function () {
    config(['ai.default' => 'openai', 'ai.providers.openai.key' => null]);

    $this->post(route('chat.store'), ['content' => 'Hello'])
        ->assertSessionHasErrors('content');
});

test('clear chat empties the session', function () {
    session(['chat_messages' => [
        ['role' => 'user', 'content' => 'Hi'],
    ]]);

    $this->post(route('chat.clear'))->assertRedirect(route('chat.index'));

    $this->get(route('chat.index'))
        ->assertInertia(fn ($page) => $page->has('messages', 0));
});
