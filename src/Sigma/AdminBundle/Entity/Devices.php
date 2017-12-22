<?php

namespace Sigma\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devices
 *
 * @ORM\Table(name="devices")
 * @ORM\Entity
 */
class Devices
{
    /**
     * @var string
     *
     * @ORM\Column(name="regid", type="string", nullable=true)
     */
    private $regid;

    /**
     * @var string
     *
     * @ORM\Column(name="deviceid", type="string", nullable=true)
     */
    private $deviceid;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=45, nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @return string
     */
    public function getRegid()
    {
        return $this->regid;
    }

    /**
     * @param string $regid
     */
    public function setRegid($regid)
    {
        $this->regid = $regid;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

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
     * @return string
     */
    public function getDeviceid()
    {
        return $this->deviceid;
    }

    /**
     * @param string $deviceid
     */
    public function setDeviceid($deviceid)
    {
        $this->deviceid = $deviceid;
    }


}

