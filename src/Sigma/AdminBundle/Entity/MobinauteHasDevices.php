<?php

namespace Sigma\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MobinauteHasDevices
 *
 * @ORM\Table(name="mobinaute_has_devices", indexes={@ORM\Index(name="fk_user_has_devices_devices1_idx", columns={"devices_id"}), @ORM\Index(name="fk_user_has_devices_user1_idx", columns={"iduser"})})
 * @ORM\Entity
 */
class MobinauteHasDevices
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Sigma\AdminBundle\Entity\Devices
     *
     * @ORM\ManyToOne(targetEntity="Sigma\AdminBundle\Entity\Devices")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="devices_id", referencedColumnName="id")
     * })
     */
    private $devices;

    /**
     * @var \Sigma\AdminBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Sigma\AdminBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Devices
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param Devices $devices
     */
    public function setDevices($devices)
    {
        $this->devices = $devices;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Mobinaute $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }



}

