<?php

namespace Site\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Organizations
 *
 * @ORM\Table(name="organizations")
 * @ORM\Entity(repositoryClass="Site\TestBundle\Repository\OrganizationsRepository")
 * @UniqueEntity(fields="ogrn", message="Данный ОГРН уже используется. Введите инной!", groups={"organizationunique"})
 * @UniqueEntity(fields="oktmo", message="Данный oktmo уже используется. Введите инной!", groups={"organizationunique"})
 */
class Organizations
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
     * @var string
     * 
     * @ORM\Column(name="display_name", type="string", length=256)
     * @Assert\Length(
     *      min = 3,
     *      max = 256,
     *      minMessage = "Название Организации должно содержать более {{ limit }} символов",
     *      maxMessage = "Название Организации должно содержать менее {{ limit }} символов"
     * )
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="ogrn", type="string", length=20, unique=true)

     */
    private $ogrn;

    /**
     * @var string
     *
     * @ORM\Column(name="oktmo", type="string", length=20, unique=true)
     */
    private $oktmo;

    /**
     * @ORM\OneToMany(targetEntity="Users", mappedBy="orgid")
     */
    protected $users;

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
     * Set displayName
     *
     * @param string $displayName
     *
     * @return Organizations
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set ogrn
     *
     * @param string $ogrn
     *
     * @return Organizations
     */
    public function setOgrn($ogrn)
    {
        $this->ogrn = $ogrn;

        return $this;
    }

    /**
     * Get ogrn
     *
     * @return string
     */
    public function getOgrn()
    {
        return $this->ogrn;
    }

    /**
     * Set oktmo
     *
     * @param string $oktmo
     *
     * @return Organizations
     */
    public function setOktmo($oktmo)
    {
        $this->oktmo = $oktmo;

        return $this;
    }

    /**
     * Get oktmo
     *
     * @return string
     */
    public function getOktmo()
    {
        return $this->oktmo;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Site\TestBundle\Entity\Users $user
     *
     * @return Organizations
     */
    public function addUser(\Site\TestBundle\Entity\Users $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Site\TestBundle\Entity\Users $user
     */
    public function removeUser(\Site\TestBundle\Entity\Users $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
