<?php

use App\Jobs\RecordClick;
use App\Models\Link;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

test('click tracking returns link URL', function () {
    Queue::fake();
    $user = User::factory()->create(['username' => 'clickuser']);
    $link = Link::factory()->for($user)->create(['url' => 'https://example.com']);

    $this->post(route('profile.click', ['username' => 'clickuser', 'link' => $link->id]))
        ->assertOk()
        ->assertJson(['url' => 'https://example.com']);
});

test('click tracking dispatches RecordClick job', function () {
    Queue::fake();
    $user = User::factory()->create(['username' => 'clickjob']);
    $link = Link::factory()->for($user)->create(['url' => 'https://example.com']);

    $this->post(route('profile.click', ['username' => 'clickjob', 'link' => $link->id]));

    Queue::assertPushed(RecordClick::class, function (RecordClick $job) use ($link) {
        return $job->linkId === $link->id;
    });
});

test('click tracking works for valid link', function () {
    Queue::fake();
    $user = User::factory()->create(['username' => 'validclick']);
    $link = Link::factory()->for($user)->create(['url' => 'https://valid-link.com']);

    $response = $this->post(route('profile.click', ['username' => 'validclick', 'link' => $link->id]));

    $response->assertOk();
    $response->assertJson(['url' => 'https://valid-link.com']);
    Queue::assertPushed(RecordClick::class);
});
