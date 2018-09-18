<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Employee;

class EmployeesControllerTest extends AbstractControllerTest
{
    protected $employeeRepo;
    protected $jobRepo;

    protected function setUp()
    {
        parent::setUp();

        $this->employeeRepo = $this->entityManager->getRepository('AppBundle:Employee');
        $this->jobRepo      = $this->entityManager->getRepository('AppBundle:Job');
    }

    public function testListSuccess()
    {
        $this->client->request('GET', '/employees/list');

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent(), true);
        
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());

        $total = count($this->employeeRepo->findAll());

        $this->assertEquals((int)$total, count($jsonContent));

        $this->assertValidateEmployeeEntityStruct($jsonContent[array_rand($jsonContent)]);
    }

    // TODO: Implement testListErrorXX Methods

    public function testShowSuccess()
    {
        $this->client->request('GET', '/employees/1');

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent(), true);

        $this->assertEquals(JSON_ERROR_NONE, json_last_error());

        $this->assertValidateEmployeeEntityStruct($jsonContent);
    }

    // TODO: Implement testShowErrorXX Methods

    public function testCreateSuccess()
    {
        $jobs = $this->jobRepo->findAll();
        $job  = $jobs[array_rand($jobs)];

        $employee = [
            'name'   => 'Rosie Rice',
            'age'    => 49,
            'gender' => 'male',
            'jobId'  => $job->getId(),
        ];

        $this->client->request('POST', '/employees', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($employee));

        $response = $this->client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());

        $jsonContent = json_decode($response->getContent(), true);

        $this->assertValidateEmployeeEntityStruct($jsonContent);

        $this->assertEquals($employee['name'], $jsonContent['name']);
        $this->assertEquals($employee['age'], $jsonContent['age']);
        $this->assertEquals($employee['gender'], $jsonContent['gender']);
        $this->assertEquals($employee['jobId'], $jsonContent['job']['id']);
    }

    // TODO: Implement testCreateErrorXX Methods

    public function testUpdateSuccess()
    {
        $job = $this->jobRepo->find(5);

        $employee = new Employee();

        $employee->setName('Olivia Reid')
            ->setAge(47)
            ->setGender('male')
            ->setJob($job);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        $employeeEditedData = [
            'name'   => 'name edited',
            'age'    => 18,
            'gender' => 'female',
            'jobId'  => 1
        ];

        $this->client->request('PUT', sprintf('/employees/%s', $employee->getId()), [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode($employeeEditedData));

        $response = $this->client->getResponse();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEmpty($response->getContent());

        $editedEmployee = $this->employeeRepo->find($employee->getId());

        $this->assertEquals($employee->getId(), $editedEmployee->getId());
        $this->assertEquals($employeeEditedData['name'], $editedEmployee->getName());
        $this->assertEquals($employeeEditedData['age'], $editedEmployee->getAge());
        $this->assertEquals($employeeEditedData['gender'], $editedEmployee->getGender());
        $this->assertEquals($employeeEditedData['jobId'], (int) $editedEmployee->getJob()->getId());

        $this->assertNotEquals($employee->getName(), $editedEmployee->getName());
        $this->assertNotEquals($employee->getAge(), $editedEmployee->getAge());
        $this->assertNotEquals($employee->getGender(), $editedEmployee->getGender());
        $this->assertNotEquals((int) $employee->getJob()->getId(), (int) $editedEmployee->getJob()->getId());
    }

    // TODO: Implement testUpdateErrorXX Methods

   public function testDeleteSuccess()
   {
       $employeeId = 1;

       $employee = $this->employeeRepo->find($employeeId);

       $this->client->request('DELETE', sprintf('/employees/%d', $employeeId));

       $response = $this->client->getResponse();

       $this->assertEquals(204, $response->getStatusCode());
       $this->assertEmpty($response->getContent());

       $this->client->request('DELETE', sprintf('/employees/%d', $employeeId));

       $response     = $this->client->getResponse();
       $jsonContent  = json_decode($response->getContent(), true);

       $this->assertEquals(404, $response->getStatusCode());
       $this->assertArrayHasKey('error', $jsonContent);
       $this->assertSame('Employee not found', $jsonContent['error']);

   }

    /**
     * Undocumented function
     *
     * @param array $employee
     * @return void
     */
    protected function assertValidateEmployeeEntityStruct(array $employee)
    {
        $this->assertArrayHasKey('id', $employee);
        $this->assertInternalType('string', $employee['id']);
        
        $this->assertArrayHasKey('name', $employee);
        $this->assertInternalType('string', $employee['name']);
        
        $this->assertArrayHasKey('age', $employee);
        $this->assertInternalType('integer', $employee['age']);
        
        $this->assertArrayHasKey('gender', $employee);
        $this->assertInternalType('string', $employee['gender']);
        
        $this->assertArrayHasKey('job', $employee);
        $this->assertInternalType('array', $employee['job']);
        
        $this->assertArrayHasKey('id', $employee['job']);
        $this->assertInternalType('string', $employee['job']['id']);
        
        $this->assertArrayHasKey('title', $employee['job']);
        $this->assertInternalType('string', $employee['job']['title']);
        
        $this->assertArrayHasKey('monthly_pay', $employee['job']);
        $this->assertInternalType('integer', $employee['job']['monthly_pay']);
    }
}
