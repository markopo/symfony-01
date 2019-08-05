<?php


namespace App\Tests\Security;

use App\Security\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase {


    public function testTokenGeneration() {

            $tokenGenerator = new TokenGenerator();
            $token = $tokenGenerator->getRandomSecureToken(30);
            
            $tokenLen = strlen($token);
            $this->assertTrue($tokenLen === 30, 'Same size is not OK!');

            $this->assertTrue(1 === preg_match("/[A-Za-z0-9]/", $token));

            $this->assertTrue(ctype_alnum($token), 'Contains not alphanumeric characters!');

    }

}
