<?php namespace AppBundle\DataFixtures;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 *
 * Data seeder.
 *
 * @package     AppBundle
 * @copyright   Copyright (c) 2018 EYESO (https://eyeso.co)
 *
 */
class AppFixtures extends Fixture {

    // Sources for random data
    private $firstNames = ['Domenic','Darbie','Marj','Lizette','Maitilde','Windham','Corabella','Nance','Samson','Quintilla','Lance','Wynny','Avram','Annora','Fowler','Baron','Aldwin','Maud','Rosemonde','Marley'];
    private $lastNames = ['Dinsell','Bletsor','Waterworth','Stockwell','Corgenvin','Ephson','Cutchey','Pickering','Wenn','Penberthy','Lauret','Mumm','Brixham','Sannes','Rickerd','Isaksen','Paschek','Sheber','Regi','Tackell'];
    private $jobTitles = ['Project Manager','Librarian','Financial Analyst','Senior Developer','Clinical Specialist','Payment Adjustment Coordinator','Associate Professor','Geologist IV','Compensation Analyst','Teacher','Financial Advisor','Business Systems Development Analyst','Sales Representative','Budget/Accounting Analyst III','Desktop Support Technician','Financial Advisor','Teacher','Recruiter','Nuclear Power Engineer','Administrative Officer'];

    const EMPLOYEE_COUNT = 20;
    const JOB_COUNT = 5;

    public function load(ObjectManager $manager) {

        // create jobs
        $jobs = [];
        for ($i = 0; $i < self::JOB_COUNT; $i++) {
            /** @var Job $job */
            $job = new Job();
            $job->setTitle($this->jobTitles[mt_rand(0, count($this->jobTitles)-1)]);
            $job->setMonthlyPay(mt_rand(1000, 2000));
            $manager->persist($job);

            $jobs[] = $job;
        }

        // create employees
        $genders = Employee::getGenderOptions();
        for ($i = 0; $i < self::EMPLOYEE_COUNT; $i++) {
            /** @var Employee $employee */
            $employee = new Employee();
            $employee->setName($this->getRandomName());
            $employee->setAge(mt_rand(18,67));
            $employee->setGender($genders[mt_rand(0,1)]);
            $employee->setJob($jobs[mt_rand(0, count($jobs)-1)]);
            $manager->persist($employee);
        }

        $manager->flush();
    }

    private function getRandomName() {
        return $this->firstNames[mt_rand(0, count($this->firstNames)-1)] . " " . $this->lastNames[mt_rand(0, count($this->lastNames)-1)];
    }


}
