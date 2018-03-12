<?php

namespace AppBundle\Twig;


use Hashids\Hashids;

class AppExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $salt;

    /**
     * @var int
     */
    private $length;

    public function __construct(string $salt, int $length)
    {
        $this->salt = $salt;
        $this->length = $length;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('hashid', [$this, 'hashidFilter']),
        ];
    }

    /**
     * @param int $id
     * @return string
     * @throws \Hashids\HashidsException
     */
    public function hashidFilter(int $id): string
    {
        $hashids = new Hashids($this->salt, $this->length);

        return $hashids->encode($id);
    }
}

