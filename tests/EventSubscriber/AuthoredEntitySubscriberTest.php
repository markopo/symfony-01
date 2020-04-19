<?php


namespace App\Tests\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\EventSubscriber\AuthoredEntitySubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelEvents;

class AuthoredEntitySubscriberTest extends TestCase
{

    public function testConfiguration() {
        $result = AuthoredEntitySubscriber::getSubscribedEvents();
        $this->assertArrayHasKey(KernelEvents::VIEW, $result);
        $this->assertEquals('getAuthenticatedUser', $result["kernel.view"][0]);
        $this->assertEquals(EventPriorities::PRE_WRITE, $result["kernel.view"][1]);
    }

}
