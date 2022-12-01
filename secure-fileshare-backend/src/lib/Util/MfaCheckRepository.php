<?php

namespace Wp\Sfb\Util;

use Wp\Sfb\Model\MfaCheck;

class MfaCheckRepository extends BaseRepository
{
    public function get(int $id): ?MfaCheck {
        $sql_query = "SELECT * FROM mfa_checks WHERE id=?";
        $stmt = $this->mysqli->prepare($sql_query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $fileArray = $result->fetch_array(MYSQLI_ASSOC);
        $stmt->close();
        if ($fileArray) {
            return MfaCheck::fromRow($fileArray);
        }
        return null;
    }

    public function delete(int $id): ?MfaCheck {
        if ($mfaCheck = $this->get($id)) {
            $sql_query = "DELETE FROM mfa_checks WHERE id=?";
            $stmt = $this->mysqli->prepare($sql_query);
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                return $mfaCheck;
            };
        }
        return null;
    }

    private function put(MfaCheck $mfaCheck): ?MfaCheck {
        $sql_query = "INSERT INTO mfa_checks VALUES (NULL, ?, ?)";
        $stmt = $this->mysqli->prepare($sql_query);

        $passed = $mfaCheck->isPassed();
        $user_id = $mfaCheck->getUserId();

        $stmt->bind_param("bi", $passed, $user_id);
        if ($stmt->execute()) {
            $mfaCheck->setId($stmt->insert_id);
            return $mfaCheck;
        };
        return null;
    }

    public function generate(int $user_id): ?MfaCheck {
        $mfaCheck = new MfaCheck(
            NULL,
            false,
            $user_id
        );
        if ($mfaCheck = $this->put($mfaCheck)) {
            return $mfaCheck;
        }
        return null;
    }

    public function validate(int $id): ?MfaCheck {
        if ($mfaCheck = $this->get($id)) {
            $sql_query = "UPDATE mfa_checks SET passed=true WHERE id=?";
            $stmt = $this->mysqli->prepare($sql_query);
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                return $mfaCheck;
            };
        }
        return null;
    }
}