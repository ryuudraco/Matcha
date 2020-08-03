<?php 

namespace Src\Beans;

use Src\Beans\UserBean;
use Src\Beans\HistoryBean;

class HistoryUserBean {

    private $history;
	private $user;

    public function getHistory(): ?HistoryBean {
		return $this->history;
	}

	public function setHistory(HistoryBean $history){
		$this->history = $history;
	}

	public function getUser(): ?UserBean {
		return $this->user;
	}

	public function setUser(UserBean $user){
		$this->user = $user;
	}

}
