<?php

namespace App\Tests;

use App\Entity\User;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

class RoutingACLTest extends WebTestCase
{
    /**
     * @dataProvider urlsThatNeedAValidLogin
     * @param $url
     * @param $method
     */
    public function testIfAnonIsRedirected($url, $method): void
    {
        $client = static::createClient();
        $client->request($method, $url);
        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider urlsThatNeedAValidLogin
     * @param $url
     * @param $method
     */
    public function testIfUrlsAreCallableWithRoleUser($url, $method): void
    {
        $client = $this->createLoggedInClient();
        $client->request($method, $url);
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @param $url
     * @param $method
     * @dataProvider urlsThatNeedAValidLogin
     */
    public function testIfUserWhoLogsInAndOutCannotCallSitesWithoutBeingRedirected($url, $method): void
    {
        $client = $this->createLoggedInClient();
        $client->request('GET', '/');
        $this->assertSame(200, $client->getResponse()->getStatusCode());
        // logout
        $client->request('GET', '/logout');
        //try url
        $client->request($method, $url);
        // should be 302 because no valid session is around
        $this->assertSame(302, $client->getResponse()->getStatusCode());

    }

    public function urlsThatNeedAValidLogin(): array
    {
        return [
            'manual upload' => ['manualUpload', 'GET'],
            'dropzone upload' => ['dropzoneUpload', 'GET'],
            'xhr upload' => ['xhrUpload', 'POST'],
        ];
    }

    /**
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    private function createLoggedInClient(): \Symfony\Bundle\FrameworkBundle\Client
    {
        $client = static::createClient();
        $session = $client->getContainer()->get('session');
        $firewallName = 'main';
        $firewallContext = 'main';
        $token = new UsernamePasswordToken('SomeUserWithRoleUser', null, $firewallName, ['ROLE_USER']);
        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
        return $client;
    }
}
