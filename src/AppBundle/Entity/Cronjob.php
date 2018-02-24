<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Validator\Constraints as Assert;

/**
 * Cronjob
 *
 * @ORM\Table(name="cronjob")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CronjobRepository")
 */
class Cronjob
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="cron_expression", type="string", length=100)
     *
     * @Assert\CronExpression()
     */
    private $cronExpression;

    /**
     * @var string|null
     *
     * @ORM\Column(name="command", type="string", length=255, nullable=true)
     */
    private $command;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var Machine
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Machine", inversedBy="cronjobs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $machine;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CronjobSchedule", mappedBy="cronjob")
     */
    private $schedules;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->schedules = new ArrayCollection();
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
     * @return Cronjob
     */
    public function setName(string $name): Cronjob
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
     * Set cronExpression.
     *
     * @param string $cronExpression
     *
     * @return Cronjob
     */
    public function setCronExpression($cronExpression): Cronjob
    {
        $this->cronExpression = $cronExpression;

        return $this;
    }

    /**
     * Get cronExpression.
     *
     * @return string
     */
    public function getCronExpression(): ?string
    {
        return $this->cronExpression;
    }

    /**
     * Set command.
     *
     * @param string|null $command
     *
     * @return Cronjob
     */
    public function setCommand(?string $command): Cronjob
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command.
     *
     * @return string|null
     */
    public function getCommand(): ?string
    {
        return $this->command;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Cronjob
     */
    public function setCreatedAt(\DateTime $createdAt): Cronjob
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
     * Get machine.
     *
     * @return Machine
     */
    public function getMachine(): ?Machine
    {
        return $this->machine;
    }

    /**
     * Set machine.
     *
     * @param Machine $machine
     *
     * @return Cronjob
     */
    public function setMachine(?Machine $machine): Cronjob
    {
        $this->machine = $machine;

        return $this;
    }

    /**
     * Add schedule.
     *
     * @param CronjobSchedule $schedule
     */
    public function addSchedule(CronjobSchedule $schedule): void
    {
        if ($this->schedules->contains($schedule)) {
            return;
        }

        $this->schedules[] = $schedule;
        $schedule->setMachine($this);
    }

    /**
     * Remove schedule.
     *
     * @param CronjobSchedule $schedule
     */
    public function removeCronjob(CronjobSchedule $schedule)
    {
        $this->schedules->removeElement($schedule);
        $schedule->setCronjob(null);
    }

    /**
     * Get schedules.
     *
     * @return Collection|Cronjob[]
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }
}
