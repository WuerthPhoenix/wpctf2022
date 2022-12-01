<?php

namespace Wp\Sfb\Model;

use Wp\Sfb\Auth\AuthenticatedUser;

class User extends AuthenticatedUser
{
    private ?int $id;
    private string $name;
    private string $full_name;
    private string $group_id;
    private ?string $password;

    /**
     * @param ?int $id
     * @param string $name
     * @param string $group_id
     * @param ?string $password
     */
    public function __construct(?int $id, string $name, string $full_name, string $group_id, ?string $password, array $permissions = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->full_name = $full_name;
        $this->group_id = $group_id;
        $this->password = $password;

        parent::__construct($permissions);
    }

    public static function fromRow(array $row): User {
        $perms = explode('|', $row['permissions']);
        return new self(
            $row['id'],
            $row['user'],
            $row['full_name'],
            $row['group_id'],
            $row['pass'],
            $perms
        );
    }

    public function toArray(): Array {
        return [
            'user_id' => $this->id,
            'user' => $this->name,
            'pass' => $this->password,
            'full_name' => $this->full_name,
            'group_id' => $this->group_id,
            'permissions' => $this->permissions
        ];
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->group_id;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }


}