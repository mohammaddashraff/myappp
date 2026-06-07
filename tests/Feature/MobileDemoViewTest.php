<?php

test('mobile demo page is displayed for stakeholders', function () {
    $response = $this->get(route('mobile.demo'));

    $response->assertOk()
        ->assertSee('Mobile Demo')
        ->assertSee('Stakeholder Preview')
        ->assertSee('Yamaha NMAX 2024');
});
