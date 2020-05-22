<?php
include '../../config/Database.php';

class Priority{

        public function getPriority()
        {
            $sql = " SELECT * FROM priorities order by Level asc";
            $prioQuery = (new Database())->query($sql);

            return $prioQuery;
        }

        public function updatePriority($id, $data)
        {
            $sql = "UPDATE `priorities` SET Level = ?, Days = ?, Hours = ?, Minutes = ?, Label = ?  WHERE Priority_Id = ?";
            $prioQuery = (new Database())->query(
                $sql,
                [$data['Level'], $data['Days'],$data['Hours'],$data['Minutes'], $data['Label'], $id],
                'update'
            );
            return $prioQuery;
        }

        public function addPriority($data)
        {
            $sql = "INSERT INTO priorities(Level, Days, Hours, Minutes, Label) VALUE(?, ?, ?, ?, ? )";
            $prioQuery = (new Database())->query(
                $sql,
                [ $data['Level'],$data['Days'],$data['Hours'],$data['Minutes'],$data['Label'] ],
                'insert'
            );
            return $prioQuery;
        }


        public function deletePriority($id)
        {
            $sql = "DELETE FROM priorities WHERE Priority_Id = $id";
            $prioQuery = (new Database())->query($sql,[$id],'delete');
            return $prioQuery;
        }

        public function getSinglePriority($id)
        {
            $sql = " SELECT * FROM priorities WHERE Priority_Id = $id";
            $prioQuery = (new Database())->query($sql, [$id],'select');
            return $prioQuery;
        }

        public function doesLevelExists($level, $id) {
            $result = [];
            $sql = "SELECT * FROM priorities where Level = ? ";
            if($id!=0) {
                $sql .= "AND Priority_Id <> ?";
                $res = (new Database())->query($sql, [$level, $id], 'select');
            } else {
                $res = (new Database())->query($sql, [$level], 'select');
            }
            if(count($res) > 0) {    
                return true;
            } else {
                return false;
            }
        }

        public function doesLabelExists($label, $id) {
            $result = [];
            $sql = "SELECT * FROM priorities where Label = ? ";
            if($id!=0) {
                $sql .= "AND Priority_Id <> ?";
                $res = (new Database())->query($sql, [$label, $id], 'select');
            } else {
                $res = (new Database())->query($sql, [$label], 'select');
            }
            if(count($res) > 0) {    
                return true;
            } else {
                return false;
            }
        }




}