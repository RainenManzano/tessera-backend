<?php

/**
 * Class for secure and easy query transactions
 */
class Query
{
    private $conn;
    private $stmt;

    /**
     * Constructor
     *
     * @param object $conn  database connection
     * @param string $sql   query string
     */
    public function __construct($conn, $sql)
    {
        $this->conn = $conn;
        $this->stmt = $this->conn->prepare($sql);
    }

    /**
     * Select function
     *
     * @param array ...$val
     * @return array
     */
    public function select($val)
    {
        $rows = array();
        try {
            $this->stmt->execute($val);
            while ($row = $this->stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
        } catch (\Throwable $th) {
            // error message
        }

        return $rows;
    }

    /**
     * Insert function
     *
     * @param array $val    array or array of array
     * @return array
     */
    public function insert($val)
    {
        $insertInfo = array('insert_id' => false);
        try {
            if (\is_array($val[0])) {
                // if multiple value
                foreach ($val as $v) {
                    $this->stmt->execute($v);
                    $insertInfo['insert_id'][] = $this->conn->lastInsertId();
                }
            } else {
                // if not multiple
                $this->stmt->execute($val);
                $insertInfo['insert_id'] = $this->conn->lastInsertId();
            }
        } catch (\Throwable $th) {
            // error message
        }

        return $insertInfo;
    }

    /**
     * Execute function
     * use this for single execute with no any return exept affected_rows
     *
     * @param array $val
     * @return array        key = affected_rows
     */
    public function execute($val = null)
    {
        $info = array('affected_rows' => false);
        try {
            $this->stmt->execute($val);
            $info['affected_rows'] = $this->stmt->rowCount();
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $info;
    }

    /**
     * Update function
     *
     * @param array $val
     * @return array        key = affected_rows
     */
    public function update($val)
    {
        return self::execute($val);
    }

    /**
     * Delete function
     *
     * @param array $val
     * @return array        key = affected_rows
     */
    public function delete($val)
    {
        return self::execute($val);
    }
}
