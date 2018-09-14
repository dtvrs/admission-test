<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Employee;
use AppBundle\Entity\Job;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Employees Controller
 *
 * @package     AppBundle
 * @copyright   Copyright (c) 2018 EYESO (https://eyeso.co)
 * @uses        \Symfony\Bundle\FrameworkBundle\Controller\Controller
 *
 * @Route("/employees")
 */
class EmployeesController extends Controller {

    /**
     * List all available employees
     *
     * @Route("/list", name="employees_list")
     * @Method({"GET"})
     */
    public function listAction(Request $request) {
        /** @var Employee $employee */

        // EXERCISE: add the employee job details to the output

        try {
            // get the employee list
            $em           = $this->getDoctrine()->getManager();
            $employeeRepo = $em->getRepository('AppBundle:Employee');
            $employeeList = $employeeRepo->findAll();

            // build an array with the employee data, such as it can be converted to JSON
            $returnData = [];
            foreach($employeeList as $employee) {
                $job = $employee->getJob();
                $job = $job === null ?: [
                    'id'          => $job->getId(),
                    'title'       => $job->getTitle(),
                    'monthly_pay' => $job->getMonthlyPay(),
                ];

                $returnData[] = [
                    'id'        => $employee->getId(),
                    'created'   => $employee->getCreated(),
                    'updated'   => $employee->getUpdated(),
                    'name'      => $employee->getName(),
                    'gender'    => $employee->getGender(),
                    'age'       => $employee->getAge(),
                    'job'       => $job,
                ];
            }

            return new JsonResponse($returnData, 200);
        } catch(\Exception $e) {
            return new JsonResponse(['error' => 'Something bad happened when retrieving the employees -> ' . $e], 500);
        }
    }
}