<?php

class Query extends Database
{
    public function __construct()
    {
        parent::__construct();
        parent::createTablesIfNotExist();
    }

    // Create a new record
    public function insertData($table, $data)
    {
        $keys = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($keys) VALUES ($placeholders)";
        $this->query($sql);
        $this->bindValues(array_values($data));

        $this->execute();

        return $this->lastInsertId();
    }

    // Read records
    public function getData($table, $conditions = [], $columns = '*', $join = [], $groupBy = '', $orderBy = '', $limit = '')
    {
        $sql = "SELECT $columns FROM $table";

        // Add JOINs to the query
        if (!empty($join)) {
            foreach ($join as $key => $value) {
                $sql .= " JOIN $key ON $value";
            }
        }

        // Prepare to build the WHERE clause with AND/OR conditions
        $whereClauses = [];
        $parameters = [];

        // Loop through conditions and build the WHERE clause
        foreach ($conditions as $key => $value) {
            // Determine if the condition should use OR or AND
            if (strpos($key, 'OR ') === 0) {
                $conditionKey = substr($key, 3); // Remove "OR " from the key
                $whereClauses[] = "( $conditionKey = ? ) OR";
            } elseif (preg_match('/\s*(>=|<=|!=|<>|>|<|=)\s*$/', $key, $matches)) {
                // Handle other operators (>, <, >=, etc.)
                $whereClauses[] = "( $key ? ) AND";
            } else {
                // Default to AND condition
                $whereClauses[] = "( $key = ? ) AND";
            }
            $parameters[] = $value;
        }

        // Remove the trailing AND/OR from the last condition
        if (!empty($whereClauses)) {
            $whereClauses[count($whereClauses) - 1] = rtrim($whereClauses[count($whereClauses) - 1], ' AND');
            $whereClauses[count($whereClauses) - 1] = rtrim($whereClauses[count($whereClauses) - 1], ' OR');
        }

        // If there are conditions, append the WHERE clause to the query
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' ', $whereClauses);
        }

        // Add GROUP BY clause if provided
        if (!empty($groupBy)) {
            $sql .= " GROUP BY $groupBy";
        }

        // Add ORDER BY clause if provided
        if (!empty($orderBy)) {
            $sql .= " ORDER BY $orderBy";
        }

        // Add LIMIT clause if provided
        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }

        // Execute the query and bind the values
        $this->query($sql);
        $this->bindValues($parameters);

        // Return the result set
        return $this->resultSet();
    }


    // Read a single record
    public function getSingleData($table, $conditions = [], $columns = '*', $join = [])
    {
        $sql = "SELECT $columns FROM $table";

        if (!empty($join)) {
            foreach ($join as $key => $value) {
                $sql .= " JOIN $key ON $value";
            }
        }

        if (!empty($conditions))
            $sql .= " WHERE " . implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));

        $this->query($sql);
        $this->bindValues(array_values($conditions));

        return $this->single();
    }

    // Update a record
    public function updateData($table, $data, $conditions)
    {
        $sql = "UPDATE $table SET " . implode(', ', array_map(fn($key) => "$key = ?", array_keys($data))) .
            " WHERE " . implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));

        $this->query($sql);
        $this->bindValues(array_merge(array_values($data), array_values($conditions)));

        return $this->execute();
    }

    // Delete a record
    public function deleteData($table, $conditions)
    {
        $sql = "DELETE FROM $table WHERE " . implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));

        $this->query($sql);
        $this->bindValues(array_values($conditions));

        return $this->execute();
    }

    // Custom query
    public function customQuery($sql, $values = [])
    {
        $this->query($sql);
        $this->bindValues($values);

        return $this->resultSet();
    }
}
