<?php

namespace Sigma\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table(name="vote", indexes={@ORM\Index(name="fk_emission_has_user_user1_idx", columns={"iduser"}), @ORM\Index(name="fk_emission_has_user_emission1_idx", columns={"emission_id"})})
 * @ORM\Entity
 */
class Vote
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
     * @var integer
     *
     * @ORM\Column(name="vote", type="integer", nullable=true)
     */
    private $vote;
    /**
     * @var \Sigma\AdminBundle\Entity\Emission
     *
     * @ORM\ManyToOne(targetEntity="Sigma\AdminBundle\Entity\Emission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="emission_id", referencedColumnName="id")
     * })
     */
    private $emission;

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
     * @return Emission
     */
    public function getEmission()
    {
        return $this->emission;
    }

    /**
     * @param Emission $emission
     */
    public function setEmission($emission)
    {
        $this->emission = $emission;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * @param int $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }



}

