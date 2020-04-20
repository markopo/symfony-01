<?php


namespace App\Tests\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\BlogPost;
use App\Entity\User;
use App\EventSubscriber\AuthoredEntitySubscriber;
use Cocur\Slugify\Slugify;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthoredEntitySubscriberTest extends TestCase
{

    public function testConfiguration() {
        $result = AuthoredEntitySubscriber::getSubscribedEvents();
        $this->assertArrayHasKey(KernelEvents::VIEW, $result);
        $this->assertEquals('getAuthenticatedUser', $result["kernel.view"][0]);
        $this->assertEquals(EventPriorities::PRE_WRITE, $result["kernel.view"][1]);
    }

    /**
     * @@dataProvider providerSetAuthorCall
     */
    public function testSetAuthorCall(string $className, bool $shouldCallMethods, string $method) {

        $entityMock = $this->getEntityMock($className, $shouldCallMethods);

        $tokenStorageMock = $this->getTokenStorageMock();
        $slugifyMock = $this->getSlugifyMock($shouldCallMethods);

        $eventMock = $this->getMockObject($method, $entityMock);
        (new AuthoredEntitySubscriber($tokenStorageMock, $slugifyMock))->getAuthenticatedUser($eventMock);
    }

    public function testNoTokenPresent() {
        $tokenStorageMock = $this->getTokenStorageMock(false);
        $slugifyMock = $this->getSlugifyMock(false);

        $eventMock = $this->getMockObject('POST', new class {});
        (new AuthoredEntitySubscriber($tokenStorageMock, $slugifyMock))->getAuthenticatedUser($eventMock);
    }


    public function providerSetAuthorCall(): array
    {
        return [
            [BlogPost::class, true, 'POST'],
            [BlogPost::class, true, 'PUT'],
            [BlogPost::class, false, 'GET'],
            ['Something', false, 'POST'],
        ];
    }


    /**
     * @return MockObject
     */
    private function getSlugifyMock(bool $shouldCallMethods): MockObject {
        $slugifyMock = $this->getMockBuilder(Slugify::class)->getMock();

        $slugifyMock->expects($shouldCallMethods ? $this->once() : $this->never())
                    ->method('slugify')
                    ->willReturn('bla-bla');

        return $slugifyMock;
    }

    /**
     * @param $entityMock
     * @return MockObject
     */
    private function getTokenStorageMock(bool $hasToken = true): MockObject
    {
        $tokenMock = $this->getMockBuilder(TokenInterface::class)
            ->getMockForAbstractClass();

        $tokenMock->expects($hasToken ? $this->once() : $this->never())
            ->method('getUser')
            ->willReturn(new User());

        $tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
            ->getMockForAbstractClass();

        $tokenStorageMock->expects($this->once())
            ->method('getToken')
            ->willReturn($hasToken ? $tokenMock : null);
        return $tokenStorageMock;
    }

    /**
     * @param string $method
     * @param $controllerResult
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getMockObject(string $method, $controllerResult): MockObject
    {
        $requestMock = $this->getMockBuilder(Request::class)->getMock();

        $requestMock->expects($this->once())->method('getMethod')->willReturn($method);

        $eventMock = $this->getMockBuilder(ViewEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $eventMock->expects($this->once())
                  ->method('getControllerResult')
                  ->willReturn($controllerResult);

        $eventMock->expects($this->once())
                  ->method('getRequest')
                  ->willReturn($requestMock);

        return $eventMock;
    }

    /**
     * @param $className
     * @param bool $shouldCallMethods
     * @return MockObject
     */
    private function getEntityMock($className, bool $shouldCallMethods): MockObject
    {
        $entityMock = $this->getMockBuilder($className)
            ->setMethods(['setAuthor', 'setSlug', 'getTitle'])
            ->getMock();

        $entityMock->expects($shouldCallMethods ? $this->once() : $this->never())
            ->method('setAuthor');

        $entityMock->expects($shouldCallMethods ? $this->once() : $this->never())
            ->method('setSlug');

        $entityMock->expects($shouldCallMethods ? $this->once() : $this->never())
            ->method('getTitle');

        return $entityMock;
    }

}
