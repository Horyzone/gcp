<?php

namespace Tests\Functional;

class BasicTest extends BaseTestCase
{
    public function testGetHome()
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/');
        $response = $app->handle($request);

        $this->assertEquals(301, $response->getStatusCode());
    }

    public function testGetLogin()
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/auth');
        $response = $app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Veuillez vous identifier', (string)$response->getBody());
    }
}
