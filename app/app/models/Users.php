<?php
class Users
{
    private $db;
    private $functions;

    public function __construct()
    {
        $this->db = new Query();
        $this->functions = new Functions();
    }

    public function getUser($query = [], $columns = '*', $joins = [], $groupBy = '', $orderBy = '', $limit = '')
    {
        return $this->db->getData('tbl_users', $query, $columns, $joins, $groupBy, $orderBy, $limit);
    }

    public function createUser($data)
    {
        return $this->db->insertData('tbl_users', $data);
    }

    public function updateUser($data, $query)
    {
        return $this->db->updateData('tbl_users', $data, $query);
    }

    public function deleteUser($query)
    {
        return $this->db->deleteData('tbl_users', $query);
    }
}
