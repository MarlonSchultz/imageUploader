<?php

namespace App\Tests;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class IndexControllerTest extends WebTestCase
{
    public function testIfAnonIsRedirected(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/createNewImage');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    public function testIfLoginSucceeds()
    {

        $client = static::createClient();

        $session = $client->getContainer()->get('session');

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'main';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken('SomeUserWithRoleUser', null, $firewallName, ['ROLE_USER']);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);

        $crawler = $client->request('GET', '/createNewImage');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
