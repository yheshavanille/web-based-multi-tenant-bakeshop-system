<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthPagesUiTest extends TestCase
{
    public function test_login_page_contains_updated_ui_sections(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSeeText('Welcome back to your bakery dashboard');
        $response->assertSeeText('Demo Credentials');
        $response->assertSeeText('Grow your bakeshop with one simple platform');
    }

    public function test_register_page_contains_updated_ui_sections(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSeeText('Create your bakeshop account');
        $response->assertSeeText('Create Account');
        $response->assertSeeText('Build your bakery brand online with confidence');
    }
}
