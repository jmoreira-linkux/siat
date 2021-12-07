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
        $nit = $_ENV['SIAT_NIT'];
        $credentials = new \stdClass;
<<<<<<< HEAD
        $credentials->username = $_ENV['SIAT_USER'];
        $credentials->password = $_ENV['SIAT_PASSWORD'];
=======
        $credentials->username = $_ENV['USER'];
        $credentials->password = $_ENV['PASSWORD'];
>>>>>>> 93a4328 (resolve #13 solicitud cufd)
        $auth = new Auth($nit, $credentials);
        $token = $auth->getAccessToken();
        $this->assertNotEmpty($token);
    }
}
