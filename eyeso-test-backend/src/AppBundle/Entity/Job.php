<?php namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Job entity.
 *
 * @package     AppBundle
 * @copyright   Copyright (c) 2018 EYESO (https://eyeso.co)
 *
 * @ORM\Entity()
 * @ORM\Table(name="jobs")
 */
class Job {

    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * @ORM\Column(type="string")
    */
    protected $title;

    /**
     * @ORM\Column(type="integer")
     */
    protected $monthlyPay;

    /**
     * @ORM\OneToMany(targetEntity="Employee", mappedBy="job")
     */
    protected $employees;

    /**
     * Constructor
     */
    public function __construct() {
        $this->employees = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getMonthlyPay() {
        return $this->monthlyPay;
    }

    public function setMonthlyPay($monthlyPay) {
        $this->monthlyPay = $monthlyPay;
    }

    public function getEmployees() {
        return $this->employees;
    }

    /**
     * Add employee
     *
     * @param Employee $employee
     * @return Job
     */
    public function addEmployee(Employee $employee) {
        $this->employees[] = $employee;
        return $this;
    }

    /**
     * Remove employee
     *
     * @param Employee $employee
     */
    public function removeEmployee(Employee $employee) {
        $this->employees->removeElement($employee);
    }

    /**
     * End Getters/Setters
     */
}
