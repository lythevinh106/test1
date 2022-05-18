<?php

require "config.php";

class DB
{

    public $conn;

    function __construct()
    {
        $this->connection();
    }

    function connection()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("ket noi khong thanh cong" . $this->conn->connect_error);
        }
        // echo "thanh cong";
    }

    /// hamf loc ma doc
    function escape_string($str)
    {

        return $this->conn->real_escape_string($str);
    }

    //ham db_query

    function db_query($sql)
    {
        return $this->conn->query($sql);
    }

    function insert($table, $data)
    {

        foreach ($data as $key => $value) {
            $list_field[] = "$key";
            $list_value[] = "'{$this->escape_string($value)}'";
        }

        // noi chuoi
        $list_field = implode(",", $list_field);
        $list_value = implode(",", $list_value);


        $sql = "INSERT INTO {$table} ({$list_field}) VALUES ({$list_value})";
        if ($this->db_query($sql)) {
            return  $this->conn->insert_id;
        } else {
            // echo "Loi" . $this->conn->error;
            echo $sql;
        }
    }




    function get($table, $field = array(), $where = "")
    {


        if (!empty($field)) {
            $field = implode(",", $field);
        } else {
            $field = "*";
        }

        if (!empty($where)) {
            $where = " WHERE {$where}";
        } else {
            $where = $where;
        }

        $sql = "SELECT {$field}
        FROM {$table}{$where}";

        $result = $this->db_query($sql);


        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            // echo $result->num_rows;
            if (count($data) > 1) {
                return $data;
            } else {
                return $data[0];
            }
        } else {
            echo "khong tim thay ban ghi nao";
            // echo $sql;
        }
    }



    function update($table, $data = [], $where = "")
    {

        $data_insert = [];
        foreach ($data as $key => $value) {
            $data_insert[] = "`$key`='$value'";
        }
        $data_insert = implode(",", $data_insert);

        if (!empty($where)) {
            $where = "WHERE $where";
        } else {
            $where = $where;
        }

        $sql = "UPDATE $table SET $data_insert  $where";
        if ($this->db_query($sql)) {
            echo "cap nhat thanh cong";
        } else {
            echo "cap nhat that bai" . $this->conn->error;
        }
    }


    function delete($table, $where = "")
    {
        if (!empty($where)) {
            $where = "WHERE $where";
        } else {
            $where = $where;
        }

        $sql = "DELETE FROM $table $where";
        if ($this->db_query($sql)) {
            echo "xoa thanh cong";
        } else {
            echo "xoa that bai" . $this->conn->error;
        }
    }
}


$db = new DB;
// $data = array(
//     'username' => 'thevinh6',
//     'password' => '123456'

// );

//--- insert
// $db->insert("user", $data);

//--- get
// $user = $db->get('user', [], "");

// print_r($user);


//--update
// $data = array(
//     'username' => 'nguyenvan1',
//     'password' => '12345677'

// );

// $db->update('user', $data, "id=6");



//---delete
$db->delete('user', "id=6");
