<?php

namespace Wp\Sfb\Util;

use Wp\Sfb\Model\File;

class FileRepository extends BaseRepository
{
    public function getAllOfGroup(int $group_id): array {
        $files = array();
        $sql_query = "SELECT * FROM files WHERE group_id=?";
        $stmt = $this->mysqli->prepare($sql_query);
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row) {
            $files[] = File::fromRow($row);
        }
        return $files;
    }

    public function getAll(): array {
        $files = array();
        $sql_query = "SELECT * FROM files";
        $result = $this->mysqli->query($sql_query);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row) {
            $files[] = File::fromRow($row);
        }
        return $files;
    }

    public function get(int $id): File {
        $files = array();
        $sql_query = "SELECT * FROM files WHERE id=?";
        $stmt = $this->mysqli->prepare($sql_query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $fileArray = $result->fetch_array(MYSQLI_ASSOC);
        $stmt->close();
        return File::fromRow($fileArray);
    }

    public function delete(int $id): ?File {
        if ($file = $this->get($id)) {
            $sql_query = "DELETE FROM files WHERE id=?";
            $stmt = $this->mysqli->prepare($sql_query);
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                return $file;
            };
        }
        return null;
    }

    public function put(File $file): bool {
        $sql_query = "INSERT INTO files VALUES (NULL, ?, ?, ?, ?, ?, NULL)";
        $stmt = $this->mysqli->prepare($sql_query);

        $name = $file->getName();
        $size = $file->getSize();
        $path = $file->getPath();
        $user_id = $file->getUserId();
        $group_id = $file->getGroupId();

        $stmt->bind_param("sisss", $name, $size, $path, $user_id, $group_id);
        if ($stmt->execute()) {
            return true;
        };
        return false;
    }

}