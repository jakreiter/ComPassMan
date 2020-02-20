<?php

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class ComPassManWebTestCase extends WebTestCase
{
    /** @var Client $client */
    protected $client = null;

    /** @var EntityManager $entityManager */
    protected $entityManager;

    /** @var Session $session */
    protected $session;

    protected $parameters = [
        'test_user_id' => null,
        'firewall_name' => 'main',
        'firewall_context' => 'main'
    ];

    /** @var null|User  */
    protected $user = null;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
        $this->client->setMaxRedirects(10);
        $this->client->disableReboot();

        $this->session = $this->client->getContainer()->get('session');

        $this->entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        //dump($this->entityManager);
        $this->entityManager->beginTransaction();

        $this->parameters['test_user_id'] = static::$kernel->getContainer()->getParameter('test_user_id');
        $user = $this->entityManager->getRepository(User::class)->find($this->parameters['test_user_id']);
        $this->user = $user;
    }

    protected function logIn()
    {
        $session = $this->session;

        $userId = $this->parameters['test_user_id'];
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        $token = new UsernamePasswordToken($user, null, $this->parameters['firewall_name'], ['ROLE_SUPER_ADMIN']);
        $session->set('_security_' . $this->parameters['firewall_context'], serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        $this->user = $user;
    }

    protected function tearDown()
    {
        $this->entityManager->rollback();
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
