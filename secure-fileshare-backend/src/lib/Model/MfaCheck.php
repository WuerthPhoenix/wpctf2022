<?php

namespace Wp\Sfb\Model;

use Wp\Sfb\Util\UserRepository;

class MfaCheck
{
    private ?int $id;
    private bool $passed;
    private int $user_id;

    /**
     * @param ?int $id
     * @param bool $passed
     * @param User $user
     */
    public function __construct(?int $id, bool $passed, int $user_id)
    {
        $this->id = $id;
        $this->passed = $passed;
        $this->user_id = $user_id;
    }

    public static function fromRow(array $row) {
        return new self(
            $row['id'],
            $row['passed'],
            $row['user_id']
        );
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isPassed(): bool
    {
        return $this->passed;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

}