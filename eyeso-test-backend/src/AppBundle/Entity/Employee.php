<?php namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Employee entity.
 *
 * @package     AppBundle
 * @copyright   Copyright (c) 2018 EYESO (https://eyeso.co)
 *
 * @ORM\Entity()
 * @ORM\Table(name="employees")
 * @ORM\HasLifecycleCallbacks
 */
class Employee {

    protected static $genderOptions = array(
        'eyeso.admissiontest.employee.male'   => 'male',
        'eyeso.admissiontest.employee.female' => 'female',
    );

    /**
     * @ORM\Id
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

    /**
    * @ORM\Column(type="string") 
    * @Assert\NotBlank()
    * @Assert\Length(
    *      min = 1,
    *      max = 255,
    *      minMessage = "Employee's name must be at least {{ limit }} characters long",
    *      maxMessage = "Employee's name cannot be longer than {{ limit }} characters"
    * )
    */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 18,
     *     message = "Employee's age must be greater or equal to {{ compared_value }}"
     * )
     */
    protected $age;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^(male|female)$/",
     *     message="Employee's gender must be either 'male' or 'female'"
     * )
     */
    protected $gender;

    /**
     * @ORM\ManyToOne(targetEntity="Job", inversedBy="employees")
     * @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     */
    protected $job;

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->created = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        $this->updated = new \DateTime('now');
    }

    /**
     * Begin Getters/Setters
     */

    public function getId() {
        return $this->id;
    }

    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    public function getUpdated() {
        return $this->updated;
    }

    public function setUpdated($updated) {
        $this->updated = $updated;
        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function getAge() {
        return $this->age;
    }

    public function setAge($age) {
        $this->age = $age;
        return $this;
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
        return $this;
    }

    public function getJob() {
        return $this->job;
    }

    public function setJob($job) {
        $this->job = $job;
        return $this;
    }

    /**
     * End Getters/Setters
     */

    /**
     * Returns the gender options
     *
     * @return array
     */
    public static function getGenderOptions($valuesOnly = true) {
        if($valuesOnly) {
            return array_values(self::$genderOptions);
        }

        return self::$genderOptions;
    }


}
