<?php
/**
 * Created by PhpStorm.
 * User: markopoikkimaki
 * Date: 2018-12-01
 * Time: 08:39
 */

namespace App\Service;


use Psr\Log\LoggerInterface;

class Greeting
{

    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * Greeting constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function greet(string $name): string {
        $this->logger->info("Greeted $name");
        return "Hello $name";
    }

}