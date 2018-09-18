<?php

namespace AppBundle\Controller;

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
 * @Route("/job")
 */
class JobController extends Controller
{
    use ApiControllerHelperTrait;

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        try {
            // get the employee list
            $em      = $this->getDoctrine()->getManager();
            $jobRepo = $em->getRepository('AppBundle:Job');
            $jobList = $jobRepo->findAll();

            // build an array with the employee data, such as it can be converted to JSON
            $returnData = [];
            foreach ($jobList as $job) {
                array_push($returnData, $this->getJobArray($job));
            }

            return new JsonResponse($returnData, 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Something bad happened when retrieving jobs -> ' . $e], 500);
        }
    }

    /**
    * Undocumented function
    *
    * @param Job $job
    * @return array
    */
    protected function getJobArray(Job $job): array
    {
        return [
            'id'          => $job->getId(),
            'title'       => $job->getTitle(),
            'monthly_pay' => $job->getMonthlyPay(),
        ];
    }
}
