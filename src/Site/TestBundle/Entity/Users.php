<?php

namespace Site\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Site\TestBundle\Repository\UsersRepository")
 * @UniqueEntity(fields="inn", message="Данный ИНН уже используется. Введите инной!", groups={"userunique"})
 * @UniqueEntity(fields="snils", message="Данный СНИЛС уже используется. Введите инной!", groups={"userunique"})
 */
class Users
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Organizations", inversedBy="users")
     * @ORM\JoinColumn(name="orgid", referencedColumnName="id", onDelete="CASCADE")
     */
    private $orgid;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="firstname", type="string", length=256)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 256,
     *      minMessage = "Имя должно содержать более {{ limit }} символов",
     *      maxMessage = "Имя должно содержать менее {{ limit }} символов"
     * )
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="middlename", type="string", length=256)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 256,
     *      minMessage = "Отчество должно содержать более {{ limit }} символов",
     *      maxMessage = "Отчество должно содержать менее {{ limit }} символов"
     * )
     */
    private $middlename;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 256,
     *      minMessage = "Фамилия должно содержать более {{ limit }} символов",
     *      maxMessage = "Фамилия должно содержать менее {{ limit }} символов"
     * )
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="inn", type="string", length=20, unique=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "ИНН должен содержать более {{ limit }} символов",
     *      maxMessage = "ИНН должен содержать менее {{ limit }} символов"
     * )
     */
    private $inn;

    /**
     * @var string
     *
     * @ORM\Column(name="snils", type="string", length=20, unique=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "СНИЛС должен содержать более {{ limit }} символов",
     *      maxMessage = "СНИЛС должен содержать менее {{ limit }} символов"
     * )
     */
    private $snils;

    /**
     * @var date
     *
     * @ORM\Column(name="datebirth", type="date")
     * @Assert\Date(
     *      message = "Дата рождения должна быть формата: ГГГГ-ММ-ДД",
     * )
     */
    private $datebirth;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Users
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set middlename
     *
     * @param string $middlename
     *
     * @return Users
     */
    public function setMiddlename($middlename)
    {
        $this->middlename = $middlename;

        return $this;
    }

    /**
     * Get middlename
     *
     * @return string
     */
    public function getMiddlename()
    {
        return $this->middlename;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Users
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set inn
     *
     * @param string $inn
     *
     * @return Users
     */
    public function setInn($inn)
    {
        $this->inn = $inn;

        return $this;
    }

    /**
     * Get inn
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Set snils
     *
     * @param string $snils
     *
     * @return Users
     */
    public function setSnils($snils)
    {
        $this->snils = $snils;

        return $this;
    }

    /**
     * Get snils
     *
     * @return string
     */
    public function getSnils()
    {
        return $this->snils;
    }

    /**
     * Set datebirth
     *
     * @param \DateTime $datebirth
     *
     * @return Users
     */
    public function setDatebirth($datebirth)
    {
        $this->datebirth = $datebirth;

        return $this;
    }

    /**
     * Get datebirth
     *
     * @return \DateTime
     */
    public function getDatebirth()
    {
        return $this->datebirth;
    }

    /**
     * Set orgid
     *
     * @param \Site\TestBundle\Entity\Organizations $orgid
     *
     * @return Users
     */
    public function setOrgid(\Site\TestBundle\Entity\Organizations $orgid = null)
    {
        $this->orgid = $orgid;

        return $this;
    }

    /**
     * Get orgid
     *
     * @return \Site\TestBundle\Entity\Organizations
     */
    public function getOrgid()
    {
        return $this->orgid;
    }
}
