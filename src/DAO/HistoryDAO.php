<?php 

namespace Src\DAO;
use Src\Utils\DB;
use Src\Beans\UserBean;
use Src\Utils\Crypt;
use Src\DAO\UserDAO;
use Src\Beans\HistoryBean;

class HistoryDAO extends DB {

    public static function insertHistoryAction(UserBean $target, UserBean $origin, String $action) {
        
        $sql = "INSERT INTO history (origin_id, target_id, action) VALUES (:origin, :target, :action)";

        $values = [
            'origin' => $origin->getId(),
            'target' => $target->getId(),
            'action' => $action
        ];
        parent::execute($sql, $values);
    }

    public static function deleteHistoryAction(UserBean $target, UserBean $origin, String $action) {
        
        $sql = "DELETE FROM history WHERE origin_id = :origin AND target_id = :target AND action = :action";

        $values = [
            'origin' => $origin->getId(),
            'target' => $target->getId(),
            'action' => $action
        ];
        parent::execute($sql, $values);
    }

    public static function updateHistory(UserBean $target, UserBean $origin, String $action) {
        
        $sql = "UPDATE history SET status = 1 WHERE origin_id = :origin AND target_id = :target AND action = :action";

        $values = [
            'origin' => $origin->getId(),
            'target' => $target->getId(),
            'action' => $action
        ];
        parent::execute($sql, $values);
    }


    public static function getHistory(UserBean $target): Array {
        $history = parent::selectQuery("SELECT * FROM history WHERE target_id = ?", HistoryBean::class, [$target->getId()]);
        return $history;
    }

    public static function getUnseenHistory(UserBean $target): Array {
        $history = parent::selectQuery("SELECT * FROM history WHERE target_id = ? AND status = 0", HistoryBean::class, [$target->getId()]);
        return $history;
    }


}