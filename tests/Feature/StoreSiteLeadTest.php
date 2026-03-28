<?php

use App\Models\Lead;
use App\Models\Site;

test('contact form stores a lead and redirects to preview with fragment', function () {
    $site = Site::factory()->create(['slug' => 'gym-demo']);

    $response = $this->from(route('sites.preview', $site))
        ->post(route('sites.contact.store', $site), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
            'phone' => '555-0100',
            'message' => 'Interested in training.',
        ]);

    $response->assertRedirect(route('sites.preview', $site).'#contact');
    $response->assertSessionHas('lead_submitted', true);

    $this->assertDatabaseHas('leads', [
        'site_id' => $site->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
        'phone' => '555-0100',
        'message' => 'Interested in training.',
    ]);
});

test('contact form rejects honeypot field when filled', function () {
    $site = Site::factory()->create();

    $response = $this->postJson(route('sites.contact.store', $site), [
        'fax_extension' => 'bot-fill',
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'email' => 'jane@example.com',
    ]);

    $response->assertUnprocessable();
    $response->assertJsonValidationErrors(['fax_extension']);
    expect(Lead::query()->count())->toBe(0);
});

test('contact form accepts json requests without full page redirect', function () {
    $site = Site::factory()->create(['slug' => 'json-demo']);

    $response = $this->postJson(route('sites.contact.store', $site), [
        'first_name' => 'Alex',
        'last_name' => 'Kim',
        'email' => 'alex@example.com',
        'phone' => null,
        'message' => 'Hello',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['message']);

    $this->assertDatabaseHas('leads', [
        'site_id' => $site->id,
        'email' => 'alex@example.com',
    ]);
});

test('leads index page lists leads', function () {
    $site = Site::factory()->create(['company_name' => 'Acme']);
    Lead::factory()->for($site)->create([
        'first_name' => 'Pat',
        'last_name' => 'Lee',
        'email' => 'pat@example.com',
    ]);

    $this->get(route('leads.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('leads/index')
            ->has('leads', 1)
            ->where('leads.0.email', 'pat@example.com'));
});
