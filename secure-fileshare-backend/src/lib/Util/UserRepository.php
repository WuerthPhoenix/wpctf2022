<?php

namespace Wp\Sfb\Util;

use Wp\Sfb\Model\User;

class UserRepository extends BaseRepository
{
    public function getById(int $id): ?User {
        $sql_query = "SELECT * FROM users WHERE id=?";
        $stmt = $this->mysqli->prepare($sql_query);

        /* bind parameters for markers */
        $stmt->bind_param("i", $id);
        return $this->getUserFromStatement($stmt);

    }

    public function getByName(string $user): ?User {
        $sql_query = "SELECT * FROM users WHERE user=?";
        $stmt = $this->mysqli->prepare($sql_query);

        /* bind parameters for markers */
        $stmt->bind_param("s", $user);
        return $this->getUserFromStatement($stmt);

    }

    public function getByLogin(string $user, string $pass): ?User {

        $sql_query = "SELECT * FROM users WHERE user=? AND pass=?";
        $stmt = $this->mysqli->prepare($sql_query);

        /* bind parameters for markers */
        $stmt->bind_param("ss", $user, $pass);

        /* execute query */
        return $this->getUserFromStatement($stmt);
    }

    public function getByNameAndFullname(string $name, string $full_name): array
    {
        $users = [];
        $name = Database::sanitizeString($name);
        $full_name = Database::sanitizeString($full_name);
        $sql_query = sprintf("SELECT * FROM users WHERE `user`='%s' AND full_name='%s'", $name, $full_name);
        $stmt = $this->mysqli->query($sql_query);
        if ($stmt) {
            $rows = $stmt->fetch_all(MYSQLI_ASSOC);
            foreach ($rows as $row) {
                $users[] = User::fromRow($row);
            }
        } else {
            print_r($this->mysqli->error);
        }
        return $users;
    }

    /**
     * @param $stmt
     * @return User|null
     */
    private function getUserFromStatement($stmt): ?User
    {
        /* execute query */
        $stmt->execute();

        /* instead of bind_result: */
        $result = $stmt->get_result();

        /* now you can fetch the results into an array - NICE */
        if ($result->num_rows === 1 && $row = $result->fetch_assoc()) {
            $perms = explode('|', $row['permissions']);
            return new User(
                $row['id'],
                $row['user'],
                $row['full_name'],
                $row['group_id'],
                $row['pass'],
                $perms
            );
        }

        return null;
    }
}