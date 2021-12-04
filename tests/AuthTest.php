<?php

namespace Enors\Siat;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testGetAccessTokenFail()
    {
        $nit = 999999999;
        $credentials = new \stdClass;
        $credentials->username = '';
        $credentials->password = '';
        $auth = new Auth($nit, $credentials);
        $token = $auth->getAccessToken();
        $this->assertEquals('', $token);
    }

    public function testGetAccessTokenSuccessful()
    {
        $nit = $_ENV['NIT'];
        $credentials = new \stdClass;
        $credentials->username = $_ENV['USERNAME'];
        $credentials->password = $_ENV['PASSWORD'];
        $auth = new Auth($nit, $credentials);
        $token = $auth->getAccessToken();
        $this->assertNotEmpty($token);
    }
}