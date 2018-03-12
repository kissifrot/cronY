<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CronjobSchedule
 *
 * @ORM\Table(name="cronjob_schedule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CronjobScheduleRepository")
 */
class CronjobSchedule
{
    const STATUS_SCHEDULED = 1;
    const STATUS_STARTED = 2;
    const STATUS_ENDED = 3;
    const STATUS_ERROR = 4;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="scheduled_at", type="datetime")
     */
    private $scheduledAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="started_at", type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ended_at", type="datetime", nullable=true)
     */
    private $endedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var Cronjob
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Cronjob", inversedBy="schedules", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cronjob;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->status = self::STATUS_SCHEDULED;
    }

    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return CronjobSchedule
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set startedAt.
     *
     * @param \DateTime|null $startedAt
     *
     * @return CronjobSchedule
     */
    public function setStartedAt(?\DateTime $startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get startedAt.
     *
     * @return \DateTime|null
     */
    public function getStartedAt(): ?\DateTime
    {
        return $this->startedAt;
    }

    /**
     * Set endedAt.
     *
     * @param \DateTime|null $endedAt
     *
     * @return CronjobSchedule
     */
    public function setEndedAt(?\DateTime $endedAt)
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    /**
     * Get endedAt.
     *
     * @return \DateTime|null
     */
    public function getEndedAt(): ?\DateTime
    {
        return $this->endedAt;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return CronjobSchedule
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return CronjobSchedule
     */
    public function setCreatedAt(\DateTime $createdAt)
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
     * Get cronjob.
     *
     * @return Cronjob|null
     */
    public function getCronjob(): ?Cronjob
    {
        return $this->cronjob;
    }

    /**
     * Set cronjob.
     *
     * @param Cronjob $cronjob
     *
     * @return CronjobSchedule
     */
    public function setCronjob(?Cronjob $cronjob): CronjobSchedule
    {
        $this->cronjob = $cronjob;

        return $this;
    }

    /**
     * Set scheduledAt.
     *
     * @param \DateTime $scheduledAt
     *
     * @return CronjobSchedule
     */
    public function setScheduledAt(\DateTime $scheduledAt): CronjobSchedule
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }

    /**
     * Get scheduledAt.
     *
     * @return \DateTime
     */
    public function getScheduledAt(): \DateTime
    {
        return $this->scheduledAt;
    }
}
