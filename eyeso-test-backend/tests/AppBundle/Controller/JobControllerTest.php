<?php

namespace Tests\AppBundle\Controller;

class JobControllerTest extends AbstractControllerTest
{
    protected function setUp()
    {
        parent::setUp();
        
        $this->jobRepo = $this->entityManager->getRepository('AppBundle:Job');
    }

    public function testListSuccess()
    {
        $this->client->request('GET', '/job/');

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent(), true);
        
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());

        $total = count($this->jobRepo->findAll());

        $this->assertEquals((int)$total, count($jsonContent));

        $this->assertValidateJobEntityStruct($jsonContent[array_rand($jsonContent)]);
    }

    // TODO: Implement testListErrorXX Methods

    protected function assertValidateJobEntityStruct(array $job)
    {
        $this->assertArrayHasKey('id', $job);
        $this->assertInternalType('string', $job['id']);
        
        $this->assertArrayHasKey('title', $job);
        $this->assertInternalType('string', $job['title']);
        
        $this->assertArrayHasKey('monthly_pay', $job);
        $this->assertInternalType('integer', $job['monthly_pay']);
    }
}
