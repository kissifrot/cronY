<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Machine
 *
 * @ORM\Table(name="machine")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MachineRepository")
 */
class Machine
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
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Cronjob", mappedBy="machine")
     */
    private $cronjobs;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->cronjobs = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Machine
     */
    public function setName($name): Machine
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Machine
     */
    public function setCreatedAt(\DateTime $createdAt): Machine
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Add cronjob.
     *
     * @param Cronjob $cronjob
     */
    public function addCronjob(Cronjob $cronjob): void
    {
        if ($this->cronjobs->contains($cronjob)) {
            return;
        }

        $this->cronjobs[] = $cronjob;
        $cronjob->setMachine($this);
    }

    /**
     * Remove cronjob.
     *
     * @param Cronjob $cronjob
     */
    public function removeCronjob(Cronjob $cronjob)
    {
        $this->cronjobs->removeElement($cronjob);
        $cronjob->setMachine(null);
    }

    /**
     * Get cronjobs.
     *
     * @return Collection|Cronjob[]
     */
    public function getCronjobs(): Collection
    {
        return $this->cronjobs;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Machine
     */
    public function setDescription(?string $description): Machine
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
