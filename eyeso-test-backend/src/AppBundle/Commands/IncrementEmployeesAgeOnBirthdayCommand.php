<?php

namespace AppBundle\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class IncrementEmployeesAgeOnBirthdayCommand extends Command
{
    protected function configure()
    {
        $this->setName('app:employee-birthday-age-increment')
            ->setDescription('Increments the age of any employee who celebrates his/her birthday when this is executed')
            ->setHelp('Increments the age of any employee who celebrates his/her birthday when this is executed');
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityManager = $this->getApplication()->getKernel()->getContainer()->get('doctrine.orm.entity_manager');

        $employeeRepo = $entityManager->getRepository('AppBundle:Employee');

        $employees = $employeeRepo->findAll();

        $today = date('m-d', strtotime('now'));

        foreach ($employees as $key => $employee) {
            $birthday = $employee->getBirthday()->format('m-d');
            
            if ($birthday === $today) {
               $employee->setAge($employee->getAge() + 1);
            }

            $entityManager->persist($employee);
        }

        $entityManager->flush();
    }
}