<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Api\DataProcessor;


use App\Core\DataProcessor\Driver\SqliteDriver;

class UserDataProcessor extends SqliteDriver
{
    /**
     * @return array|bool
     */
    public function getAllUsers($field = null, $order = null)
    {
        $sql = "SELECT id, name, email FROM users";

        if ($field) {
            $sql .= ' ORDER BY `' . $field . '`';
            if ($order) {
                $sql .= ' ' . \strtoupper($order);
            }
        }

        $stmt = $this->dp->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|bool
     */
    public function getSomeUsers(int $limit, int $offset)
    {
        $sql = "SELECT id, name, email FROM users LIMIT $offset, $limit";

        $stmt = $this->dp->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @return array|bool
     */
    public function getUserById(int $id)
    {
        $sql = "SELECT id, name, email FROM users WHERE id=:id";

        $stmt = $this->dp->prepare($sql);

        $stmt->bindParam(':id', $id);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $string
     * @return mixed
     */
    public function getUserByString(string $string)
    {
        $sql = "SELECT id, name, email FROM users WHERE :parameter IN (name, email)";

        $stmt = $this->dp->prepare($sql);

        $stmt->bindParam(':parameter', $string);

        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id
     * @param array $updates
     * @return bool
     */
    public function updateUser(int $id, array $updates): bool
    {
        $sql = "UPDATE users SET %s WHERE id=:id";
        $update_string = [];
        $update_values = [':id' => $id];
        foreach ($updates as $field => $value) {
            $update_string[] = "`$field` = :$field";
            $update_values[":$field"] = $value;
        }
        try {
            $this->dp->beginTransaction();
            $stmt = $this->dp->prepare(\sprintf($sql, \implode(', ', $update_string)));
            $stmt->execute($update_values);
            $this->dp->commit();
            return is_int($stmt->rowCount());
        } catch (\PDOException $ex) {
            $this->dp->rollBack();
            return false;
        }
    }

    /**
     * @param array $insert
     * @return int|bool
     */
    public function createUser(array $insert)
    {
        $sql = "INSERT INTO users(%s) VALUES (%s)";
        $insert_string = [];
        $insert_values = [];
        foreach ($insert as $field => $value) {
            $insert_string["`$field`"] = ":$field";
            $insert_values[":$field"] = $value;
        }
        $this->dp->beginTransaction();
        try {
            $stmt = $this->dp->prepare(
                \sprintf(
                    $sql,
                    \implode(', ', \array_keys($insert_string)),
                    \implode(', ', $insert_string)
                )
            );
            $stmt->execute($insert_values);
            $this->dp->commit();
            $id = $this->dp->lastInsertId();
            return intval($id);
        } catch (\PDOException $ex) {
            $this->dp->rollBack();
            return false;
        }
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        try {
            $sql = "DELETE FROM users WHERE id=:id";
            $this->dp->beginTransaction();
            $stmt = $this->dp->prepare($sql);
            $stmt->execute([':id' => $id]);
            $this->dp->commit();
            return boolval($stmt->rowCount());
        } catch (\PDOException $ex) {
            $this->dp->rollBack();
            return false;
        }
    }
}
