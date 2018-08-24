<?php

class Persistents_AutoCards extends Persistents_Core {
    
    private $id           = 0;
    public $time          = '';
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
    
    function getTime() {
        return $this->time;
    }
    
    function setTime($time) {
        $this->time = $time;
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