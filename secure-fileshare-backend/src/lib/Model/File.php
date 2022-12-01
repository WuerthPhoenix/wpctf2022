<?php
namespace Wp\Sfb\Model;


class File
{

    public ?int $id;
    public string $name;
    public int $size;
    public string $path;
    protected int $user_id;
    private $group_id;

    public static function fromRow(Array $row): self {
        return new self(
            $row['id'],
            $row['name'],
            $row['size'],
            $row['path'],
            $row['user_id'],
            $row['group_id']
        );
    }

    /**
     * @param int $id
     * @param string $name
     * @param int $size
     * @param string $path
     * @param int $user_id
     * @param $group_id
     */
    public function __construct(?int $id, string $name, int $size, string $path, int $user_id, $group_id)
    {
        $this->id = $id;
        $this->name = $name;
        $this->size = $size;
        $this->path = $path;
        $this->user_id = $user_id;
        $this->group_id = $group_id;
    }

    /**
     * @return int|null
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
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }


}