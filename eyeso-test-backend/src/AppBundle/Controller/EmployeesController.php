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
class EmployeesController extends Controller
{
    use ApiControllerHelperTrait;

    /**
     * List all available employees
     *
     * @Route("/list", name="employees_list")
     * @Method({"GET"})
     */
    public function listAction(Request $request)
    {
        /** @var Employee $employee */

        // EXERCISE: add the employee job details to the output

        try {
            // get the employee list
            $em           = $this->getDoctrine()->getManager();
            $employeeRepo = $em->getRepository('AppBundle:Employee');
            $employeeList = $employeeRepo->findAll();

            // build an array with the employee data, such as it can be converted to JSON
            $returnData = [];
            foreach ($employeeList as $employee) {
                array_push($returnData, $this->getEmployeeArray($employee));
            }

            return new JsonResponse($returnData, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Something bad happened when retrieving the employees -> ' . $e], 500);
        }
    }

    /**
     * Create new employee
     *
     * @Route("", name="employees_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        try {
            $body = json_decode($request->getContent(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return new JsonResponse(['error' => sprintf('Error interpreting JSON Request Body. Message: "%s"', json_last_error_msg())], 400);
            }

            $name   = isset($body['name']) ? $body['name'] : null;
            $age    = isset($body['age']) ? $body['age'] : null;
            $gender = isset($body['gender']) ? $body['gender'] : null;
            $jobId  = isset($body['jobId']) ? $body['jobId'] : '';
            
            $em  = $this->getDoctrine()->getManager();
            $job = $em->getRepository('AppBundle:Job')->find($jobId);

            $employee = new Employee();
            $employee->setName($name);
            $employee->setAge($age);
            $employee->setGender($gender);
            $employee->setJob($job);
            
            $errors = $this->get('validator')->validate($employee);
            $errors = $this->buildApiErrors($errors);
            
            if (!$job) {
                $errors['jobId'] = [
                    'messages' => [
                        'Employee\'s job must not be blank and must be valid',
                    ],
                ];
            }
            
            if (count($errors) > 0) {
                return new JsonResponse(['errors' => $errors], 422);
            }

            $em->persist($employee);
            $em->flush();

            return new JsonResponse($this->getEmployeeArray($employee), 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Something bad happened when trying to create a new the employee -> ' . $e], 500);
        }
    }

    /**
     * Create new employee
     *
     * @Route("/{employeeId}", name="employees_show")
     * @Method({"GET"})
     */
    public function actionShow($employeeId)
    {
        try {
            $em       = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('AppBundle:Employee')->find($employeeId);

            if (!$employee) {
                return new JsonResponse(['error' => 'Employee not found'], 404);
            }
            
            return new JsonResponse($this->getEmployeeArray($employee), 200);
        } catch(\Exception $e) {
            return new JsonResponse(['error' => 'Something bad happened when trying to create a new the employee -> ' . $e], 500);
        }
    }

    /**
     * Create new employee
     *
     * @Route("/{employeeId}", name="employees_update")
     * @Method({"PUT"})
     */
    public function updateAction(Request $request, $employeeId)
    {
        $em       = $this->getDoctrine()->getManager();
        $employee = $em->getRepository('AppBundle:Employee')->find($employeeId);

        if (!$employee) {
            return new JsonResponse(['error' => 'Employee not found'], 404);
        }

        $body = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => sprintf('Error interpreting JSON Request Body. Message: "%s"', json_last_error_msg())], 400);
        }

        if (isset($body['name'])) {
            $employee->setName($body['name']);
        }

        if (isset($body['age'])) {
            $employee->setAge($body['age']);
        }
        if (isset($body['gender'])) {
            $employee->setGender($body['gender']);
        }

            $errors = $this->get('validator')->validate($employee);
        $errors     = $this->buildApiErrors($errors);

        if (isset($body['jobId'])) {
            $job = $em->getRepository('AppBundle:Job')->find($body['jobId']);

            if (!$job) {
                $errors['jobId'] = [
                    'messages' => [
                        'Employee\'s job must not be blank and must be valid',
                    ],
                ];
            }

            $employee->setJob($job);
        }

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], 422);
        }

        $employee->setUpdated(date('Y-m-d H:i:s'));

        $em->persist($employee);
        $em->flush();

        return new JsonResponse(null, 204);
    }

    /**
     * Create new employee
     *
     * @Route("/{employeeId}", name="employees_delete")
     * @Method({"DELETE"})
     */
    public function actionDelete($employeeId)
    {
        try {
            $em       = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('AppBundle:Employee')->find($employeeId);
    
            if (!$employee) {
                return new JsonResponse(['error' => 'Employee not found'], 404);
            }
    
            $em->remove($employee);
            $em->flush();
    
            return new JsonResponse(null, 204);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Something bad happened when trying to create a new the employee -> ' . $e], 500);
        }
    }

    /**
     * Undocumented function
     *
     * @param Employee $employee
     * @return array
     */
    protected function getEmployeeArray(Employee $employee): array
    {
        $job = $employee->getJob();
        $job = $job === null ? : [
            'id'          => $job->getId(),
            'title'       => $job->getTitle(),
            'monthly_pay' => $job->getMonthlyPay(),
        ];

        return [
            'id'      => $employee->getId(),
            'created' => $employee->getCreated(),
            'updated' => $employee->getUpdated(),
            'name'    => $employee->getName(),
            'gender'  => $employee->getGender(),
            'age'     => $employee->getAge(),
            'job'     => $job,
        ];
    }
}