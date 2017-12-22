<?php

namespace Sigma\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DevicesHasNotification
 *
 * @ORM\Table(name="user_has_notification", indexes={@ORM\Index(name="fk_user_has_notification_notification1_idx", columns={"notification_id"}), @ORM\Index(name="fk_user_has_notification_user1_idx", columns={"user_id"}), @ORM\Index(name="fk_user_has_notification_emission1_idx", columns={"emission_id"})})
 * @ORM\Entity
 */
class UserHasNotification
{


    /**
     * @var \Sigma\AdminBundle\Entity\Emission
     *
     * @ORM\ManyToOne(targetEntity="Sigma\AdminBundle\Entity\Emission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="emission_id", referencedColumnName="id")
     * })
     */
    protected $emissions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vu", type="boolean", nullable=true)
     */
    private $vu;
    /**
     * @var boolean
     *
     * @ORM\Column(name="participation", type="boolean", nullable=true)
     */
    private $participation;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Sigma\AdminBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Sigma\AdminBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $users;

    /**
     * @var \Sigma\AdminBundle\Entity\Notification
     *
     * @ORM\ManyToOne(targetEntity="Sigma\AdminBundle\Entity\Notification")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     * })
     */
    private $notification;

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return bool
     */
    public function isVu()
    {
        return $this->vu;
    }

    /**
     * @param bool $vu
     */
    public function setVu($vu)
    {
        $this->vu = $vu;
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
     * @return User
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }



    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param Notification $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return bool
     */
    public function isParticipation()
    {
        return $this->participation;
    }

    /**
     * @param bool $participation
     */
    public function setParticipation($participation)
    {
        $this->participation = $participation;
    }

    /**
     * @return emissions
     */
    public function getEmissions()
    {
        return $this->emissions;
    }

    /**
     * @param mixed $emissions
     */
    public function setEmissions($emissions)
    {
        $this->emissions = $emissions;
    }

}

