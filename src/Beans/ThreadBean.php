<?php 

namespace Src\Beans;

class ThreadBean {

    private $id;
    private $origin;
    private $target;

    public function getId(): ?int {
		return $this->id;
	}

	public function setId(int $id){
		$this->id = $id;
	}

	public function getOrigin(): ?UserBean {
		return $this->origin;
	}

	public function setOrigin(UserBean $origin){
		$this->origin = $origin;
	}

	public function getTarget(): ?UserBean {
		return $this->target;
	}

	public function setTarget(UserBean $target){
		$this->target = $target;
	}
    

}
