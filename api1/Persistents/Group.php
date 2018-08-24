<?php

class Persistents_Group extends Persistents_Core {
    
    private $id           = 0;
    public $name          = '';
    public $orders        = 0;
    public $status        = 0; 
    
    
    /**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}
    
    function setId($id) {
        $this->id = $id;
    }
    
    function getName() {
        return $this->name;
    }
    
    function setName($name) {
        $this->name = $name;
    }


	/**
	 * @return the $orders
	 */
	public function getOrders() {
		return $this->orders;
	}

	/**
	 * @return the $status
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param field_type $orders
	 */
	public function setOrders($orders) {
		$this->orders = $orders;
	}

	/**
	 * @param field_type $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}
    
    function getClassName() {
        return __CLASS__;
    }
}