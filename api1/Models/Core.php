<?php

class Models_Core {
    
    protected $table;
    private   $sql;
    protected $persistents; 
    
    function __construct() {
    }
    
    function setPersistents($persistents) {
        $this->persistents = $persistents;
    }
    
    function getSql() {
        return $this->sql;
    }
	
    function getTotalRecord() {
        $db = Models_Db::getDBO();
        $sql = "SELECT COUNT(1) FROM " . $this->table;
        $result = $db->query($sql);
        $row_count = $result->fetchColumn();
        $this->sql = $sql;
        return $row_count;
    }
    
    function getTotalRecordByCondition($column, $condition = array()) {
        $db = Models_Db::getDBO();
        $sql = "SELECT COUNT(" . $column . ") FROM " . $this->table;
        if(!empty($condition)) {
            $sql .= " WHERE ";
            $total = count($condition);
            $count = 0;
            foreach($condition as $field => $value) {
                if(is_array($value)) {
                    $sql .= $field . " " . $value[1] . " " . "?";
                    $data[] = $value[0];
                }
                else {
                    $sql .= $field . " = ?";
                    $data[] = $value;
                }
                if(++$count != $total) {
                    $sql .= " AND ";
                }
            }
        }
        $stm = $db->prepare($sql);
        $stm->execute($data);
        $row_count = $stm->fetchColumn();
        $this->sql = $sql;
        return $row_count;
    }
    
    function getTotalRecordByCondition2($column, $condition = '') {
    	$db = Models_Db::getDBO();
    	$sql = "SELECT COUNT(" . $column . ") FROM " . $this->table;
    	if(!empty($condition)) {
            $sql .= " WHERE " . $condition;
    	}
    	$result = $db->query($sql);
    	$row_count = $result->fetchColumn();
    	$this->sql = $sql;
    	return $row_count;
    }
    
    function getSumByColumn($column, $condition = array()) {
        $db = Models_Db::getDBO();
        $sql = "SELECT sum($column) FROM " . $this->table;
        if(!empty($condition)) {
            $sql .= " WHERE ";
            $total = count($condition);
            $count = 0;
            foreach($condition as $field => $value) {
                if(is_array($value)) {
                    $sql .= $field . " " . $value[1] . " " . "?";
                    $data[] = $value[0];
                }
                else {
                    $sql .= $field . " = ?";
                    $data[] = $value;
                }
                
                if(++$count != $total) {
                    $sql .= " AND ";
                }
            }
        }
        
        $stm = $db->prepare($sql);
        $stm->execute($data);
        $sum = $stm->fetchColumn();
        $this->sql = $sql;
        return $sum;
    }
    
    function getSumByColumn2($column, $condition) {
        $db = Models_Db::getDBO();
        $sql = "SELECT sum($column) FROM " . $this->table . " WHERE " . $condition;
        $result = $db->query($sql);
        $sum = $result->fetchColumn();
        $this->sql = $sql;
        return $sum;
    }
    
    function getMaxByColumn($column, $condition) {
        $db = Models_Db::getDBO();
        $sql = "SELECT MAX($column) FROM " . $this->table;
        if(!empty($condition)) {
            $sql .= " WHERE ";
            $total = count($condition);
            $count = 0;
            foreach($condition as $field => $value) {
                if(is_array($value)) {
                    $sql .= $field . " " . $value[1] . " " . "?";
                    $data[] = $value[0];
                }
                else {
                    $sql .= $field . " = ?";
                    $data[] = $value;
                }
                
                if(++$count != $total) {
                    $sql .= " AND ";
                }
            }
        }
        
        $stm = $db->prepare($sql);
        $stm->execute($data);
        $sum = $stm->fetchColumn();
        $this->sql = $sql;
        return $sum;
    }
    
    function getList() {
        $db = Models_Db::getDBO();
        $sql = "SELECT * FROM " . $this->table . " ORDER BY orders ASC, id DESC";
        $result = $db->query($sql);
        $objs = $result->fetchAll(PDO::FETCH_CLASS, $this->persistents->getClassName());
        $this->sql = $sql;
        return $objs;
    }
    
    function getObject($id, $lock = false) {
        $db = Models_Db::getDBO();
        if($lock) {
            $sql = "SELECT * FROM " . $this->table . " WHERE id = " . intval($id) . " FOR UPDATE";
        }
        else {
            $sql = "SELECT * FROM " . $this->table . " WHERE id = " . intval($id);
        }
        $result = $db->query($sql);
        $objs = $result->fetchAll(PDO::FETCH_CLASS, $this->persistents->getClassName());
        $this->sql = $sql;
        return $objs[0];
    }
    
    function getObjectByCondition($fields = '', $condition = array()) {
        $db = Models_Db::getDBO();
        $sql = "SELECT ";
        if(!empty($fields)) {
            $sql .= $fields;
        }
        else {
            $sql .= " * ";   
        }
        $sql .= " FROM " . $this->table;
        if(!empty($condition)) {
            $sql .= " WHERE ";
            $total = count($condition);
            $count = 0;
            foreach($condition as $field => $value) {
                if(is_array($value)) {
                    $sql .= $field . " " . $value[1] . " " . "?";
                    $data[] = $value[0];
                }
                else {
                    $sql .= $field . " = ?";
                    $data[] = $value;
                }
                
                if(++$count != $total) {
                    $sql .= " AND ";
                }
            }
        }
        $stm = $db->prepare($sql);
        $stm->execute($data);
        $objs = $stm->fetchAll(PDO::FETCH_CLASS, $this->persistents->getClassName());
        $this->sql = $sql;
        if(!empty($objs)) {
            return $objs[0];
        }
        else {
            return FALSE;
        }
    }
    
    function getLastObject() {
        $db = Models_Db::getDBO();
        $sql = "SELECT * FROM " . $this->table . " WHERE status = 1 ORDER BY id DESC LIMIT 0,1";
        $result = $db->query($sql);
        $objs = $result->fetchAll(PDO::FETCH_CLASS, $this->persistents->getClassName());
        if(!empty($objs)) {
            return $objs[0];
        }
        else {
            return FALSE;
        }
    }
    
    function getLastObjectByCondition($condition = array()) {
        $db = Models_Db::getDBO();
        $sql = "SELECT * FROM " . $this->table;
        if(!empty($condition)) {
            $sql .= " WHERE ";
            $total = count($condition);
            $count = 0;
            foreach($condition as $field => $value) {
                if(is_array($value)) {
                    $sql .= $field . " " . $value[1] . " " . "?";
                    $data[] = $value[0];
                }
                else {
                    $sql .= $field . " = ?";
                    $data[] = $value;
                }
                
                if(++$count != $total) {
                    $sql .= " AND ";
                }
            }
            $sql .= " ORDER BY id DESC LIMIT 0,1";
        }
        else {
            $sql .= " WHERE status = 1 ORDER BY id DESC LIMIT 0,1";
        }

        $stm = $db->prepare($sql);
        $stm->execute($data);
        $objs = $stm->fetchAll(PDO::FETCH_CLASS, $this->persistents->getClassName());
        $this->sql = $sql;
        if(!empty($objs)) {
            return $objs[0];
        }
        else {
            return FALSE;
        }
    }
    
    function customFilter($fields = '', $setting = array(), $ord = null, $limit = null) {
        $db = Models_Db::getDBO();
        $sql = "SELECT ";
        if(!empty($fields)) {
            $sql .= $fields;
        }
        else {
            $sql .= " * ";   
        }
        $sql .= " FROM " . $this->table;
        if(!empty($setting)) {
            $sql .= " WHERE ";
            $total = count($setting);
            $count = 0;
            foreach($setting as $field => $value) {
                if(is_array($value)) {
                    $sql .= $field . " " . $value[1] . " " . "?";
                    $data[] = $value[0];
                }
                else {
                    $sql .= $field . " = ?";
                    $data[] = $value;
                }
                
                if(++$count != $total) {
                    $sql .= " AND ";
                }
            }
        }
        if($ord != null) {
            $sql .= ' ORDER BY ' . $ord;
        }
        else {
            $sql .= " ORDER BY orders ASC, id DESC ";
        }
        
        if($limit != null) {
            $sql .= " LIMIT " . $limit;
        }
        
        $stm = $db->prepare($sql);
        $stm->execute($data);
        $objs = $stm->fetchAll(PDO::FETCH_CLASS, $this->persistents->getClassName());
        $this->sql = $sql;
        return $objs;
    }
    
    function customQuery($sql) {
        $db = Models_Db::getDBO();
        $result = $db->query($sql);
        $objs = $result->fetchAll(PDO::FETCH_CLASS, $this->persistents->getClassName());
        $this->sql = $sql;
        return $objs;
    }
    
    function execute($sql) {
        $db = Models_Db::getDBO();
        $result = $db->exec($sql);
        return $result;
    }
    
    function add($last_id = false) {
        $db = Models_Db::getDBO();
        $mark = '';
        foreach($this->persistents as $field => $value) {
            $data[] = $value;
            $mark.= ",?";
        }
        $sql = "INSERT " . $this->table . " VALUES(NULL" . $mark . ")";
        $result = $db->prepare($sql);
        try {
            $result->execute($data);
            if($result) {
                if($last_id) {
                    // return last id
                    $rs = $db->lastInsertId();
                }
                else {
                    // return row affect count
                    $rs = $result->rowCount();
                }
            }
            else {
                $rs = false;
            }
        }
        catch(PDOException $e) {
            $rs = false;
            // luu log
            $file_log = "add_error.log";
            Library_Log::writeOpenTable($file_log);
            Library_Log::writeHtml("Error Msg : " . $e->getMessage() . "<br/>Sql : " . $sql, $file_log);
            Library_Log::writeHtml("Data : " . print_r($data, 1), $file_log);                        
            Library_Log::writeCloseTable($file_log);
        }
        
        $this->sql = $sql;
        return $rs;
    }
    
    function edit($fields = array(), $is_edit = true) {

        $db = Models_Db::getDBO();
        $sql = "UPDATE " . $this->table . " SET ";
        foreach($this->persistents as $field => $value) {
            if(count($fields) > 0) {
                if($is_edit) {
                    foreach($fields as $field_edit) {
                        if($field_edit == $field) {
                            $sql .= $field . " = ?, ";
                            $data[] = $value;
                            break;
                        }
                    }
                }
                else {
                    $is_assign = true; 
                    foreach($fields as $field_edit) {
                        if($field_edit == $field) {
                            $is_assign = false;
                            break;
                        }             
                    }
                    if($is_assign) {
                        $sql .= $field . " = ?, ";
                        $data[] = $value;
                    }
                }
            }
            else {
                $sql .= $field . " = ?, ";
                $data[] = $value;
            }
        }
        $sql = substr($sql, 0, strlen($sql) - 2);
        $sql .= " WHERE id = "  . $this->persistents->getId();
        $result = $db->prepare($sql);
        try {
            $result->execute($data);
            if($result) {
                // return row affect count
                $rs = true;
            }
            else {
                $rs = false;
            }
        }
        catch(PDOException $e) {
            $rs = false;
            // luu log
            $file_log = "edit_error.log";
            Library_Log::writeOpenTable($file_log);
            Library_Log::writeHtml("Error Msg : " . $e->getMessage(), $file_log);
            Library_Log::writeHtml("Data : " . print_r($data, 1), $file_log);                        
            Library_Log::writeCloseTable($file_log);
        }
        
        $this->sql = $sql;
        return $rs;
    }
    
    function delete($id) {
        $db = Models_Db::getDBO();
        $sql = "DELETE FROM " . $this->table . " WHERE id = " . intval($id);
        $affect_row = $db->exec($sql);
        $this->sql = $sql;
        return $affect_row;
    }
	
    function truncateTable() {
        $db = Models_Db::getDBO();
        $sql = "TRUNCATE TABLE " . $this->table;
        $affect_row = $db->exec($sql);
        $this->sql = $sql;
        return $affect_row;
    }
    
    function customDelete($setting = array()) {
        $db = Models_Db::getDBO();
        $sql = "DELETE FROM " . $this->table;
        if(empty($setting)) {
            $sql .= " WHERE id = -1";
        }
        else {
            $sql .= " WHERE ";
            $total = count($setting);
            $count = 0;
            foreach($setting as $field => $value) {
                if(is_array($value)) {
                    $sql .= $field . " " . $value[1] . " " . "?";
                    $data[] = $value[0];
                }
                else {
                    $sql .= $field . " = ?";
                    $data[] = $value;
                }
                
                if(++$count != $total) {
                    $sql .= " AND ";
                }
            }
        }
        $stm = $db->prepare($sql);
        $stm->execute($data);
        $affect_row = $stm->rowCount();
        $this->sql = $sql;
        return $affect_row;
    }
}