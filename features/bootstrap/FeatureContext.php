<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext extends \Behatch\Context\RestContext
{
    const USERS = [
        'admin' => 'admin123'
    ];

    const AUTH_URL = '/api/login_check';

    const AUTH_JSON = '{
                "username": "%s",
                "password": "%s"
            }';

    /**
     * @var \App\DataFixtures\AppFixtures
     */
    private $fixtures;

    /**
     * @var \Coduo\PHPMatcher\Matcher
     */
    private $matcher;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private \Doctrine\ORM\EntityManagerInterface $em;

    public function __construct(
        \Behatch\HttpCall\Request $request,
        \App\DataFixtures\AppFixtures $fixtures,
        \Doctrine\ORM\EntityManagerInterface $em)
    {
        parent::__construct($request);
        $this->fixtures = $fixtures;
        $this->matcher = (new \Coduo\PHPMatcher\Factory\MatcherFactory)->createMatcher();
        $this->em = $em;
    }

    /**
     * @BeforeScenario @createSchema
     */
    public function createSchema() {

        // Get entity metadata
        $classes = $this->em->getMetadataFactory()->getAllMetadata();

        // Drop and create schema
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);

        // Load fixtures ..and execute
        $purger = new \Doctrine\Common\DataFixtures\Purger\ORMPurger($this->em);
        $fixturesExecutor = new \Doctrine\Common\DataFixtures\Executor\ORMExecutor($this->em, $purger);
        $fixturesExecutor->execute([
            $this->fixtures
        ]);
    }

    /**
     * @Given I am authenticated as :user
     */
    public function iAmAuthenticatedAs($user)
    {
       $this->request->setHttpHeader('Content-Type', 'application/ld+json');
       $this->request->send(
           'POST',
           $this->locatePath(self::AUTH_URL),
           [],
           [],
           sprintf(self::AUTH_JSON, $user, self::USERS[$user])
       );

       $json = json_decode($this->request->getContent(), true);
       // make sure the token was returned
       $this->assertTrue(isset($json['token']));

       $token = $json['token'];
       $this->request->setHttpHeader('Authorization', 'Bearer '.$token);
    }

//    /**
//     * @When a demo scenario sends a request to :arg1
//     */
//    public function aDemoScenarioSendsARequestTo($arg1)
//    {
//        throw new PendingException();
//    }
//
//    /**
//     * @Then the response should be received
//     */
//    public function theResponseShouldBeReceived()
//    {
//        throw new PendingException();
//    }
}
