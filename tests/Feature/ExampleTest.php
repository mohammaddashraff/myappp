<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('صلاحية الاشتراك')
        ->assertSee('خطط واضحة للمشتري والبائع');
});
