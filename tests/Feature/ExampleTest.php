<?php

test('root redirects to dashboard', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('modules.dashboard'));
});

test('unauthenticated user is redirected to login when visiting dashboard', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});
