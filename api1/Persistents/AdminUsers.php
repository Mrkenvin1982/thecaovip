<?php

class Persistents_AdminUsers extends Persistents_Core {

    private $id            = 0;
    public $username       = '';
    public $password       = '';
    public $permission     = 1;
    public $orders         = 0;
    public $status         = 0;
    
    /**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
    
    /**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}
    
    /**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}
    
    /**
	 * @param field_type $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}
    
       
    function getOrders() {
        return $this->orders;
    }
    
    function setOrders($orders) {
        $this->orders = $orders;
    }

	/**
	 * @return the $username
	 */
	public function getUsername() {
		return $this->username;
	}
    
    /**
	 * @param field_type $username
	 */
	public function setUsername($username, $validate = false) {
		$this->username = $username;
        if($validate){
            if($username == '') {
                $this->isValidate = false;
		$this->errors[] = "Tài khoản là rỗng.";
            }
        }
	}

	/**
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param field_type $password
	 */
	public function setPassword($password, $confirm_password, $validate = false) {
            $this->password = md5($password);
            if($validate){
                if($password == "") {
                    $this->isValidate = false;
                    $this->errors[] = "Mật khẩu là rỗng.";
                }
                else {
                    if(md5($password) != md5($confirm_password)) {
                        $this->isValidate = false;
                        $this->errors[] = "Mật khẩu không khớp";
                    }
                }
            }
	}
    
    
        
        
    function getPermission() {
        return $this->permission;
    }
    
    function setPermission($permission) {
        $this->permission = $permission;
    }
    
    function getLastVisited() {
        return $this->last_visited;
    }
    
    function setLastVisited($last_visited, $validate = false) {
        $this->last_visited = $last_visited;
    }
    
    function getGroupId() {
        return $this->group_id;
    }
    
    function setGroupId($group_id, $validate = false) {
        $this->group_id = $group_id;
        if($validate){
            if(!Library_Validation::isPositive($group_id)) {
                $this->isValidate = false;
				$this->errors[] = "Please select group.";
            }
        }
    }
    
    function isValidate() {
        return $this->isValidate;
    }
    
    function getClassName() {
        return __CLASS__;
    }
}