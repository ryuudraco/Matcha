<?php 

namespace Src\DAO;
use Src\Utils\DB;
use Src\Beans\UserBean;
use Src\Utils\Crypt;
use Src\DAO\UserDAO;
use Src\DAO\HistoryDAO;
use Src\Beans\HistoryBean;
use Src\Beans\HistoryUserBean;


class HistoryUserDAO extends DB {

    public static function getHistory(UserBean $user): ?Array {

        $results = [];
        //this will get us an array of history
        $history = HistoryDAO::getHistory($user);

        foreach($history as $record) {
            $historyUser = new HistoryUserBean();
            $historyUser->setHistory($record);
            //user bean of  the one who liked us
            $user = UserDAO::fetch([$record->getOrigin_id()], 'ID');
            $historyUser->setUser($user);
            array_push($results, $historyUser);
        }

        // echo "<pre>";
        // print_r($results);
        // echo "</pre>";
        return $results;
    }

    public static function getUnseenHistory(UserBean $user): ?Array {

        $results = [];
        //this will get us an array of history
        $history = HistoryDAO::getUnseenHistory($user);

        foreach($history as $record) {
            $historyUser = new HistoryUserBean();
            $historyUser->setHistory($record);
            //user bean of  the one who liked us
            $user = UserDAO::fetch([$record->getOrigin_id()], 'ID');
            $historyUser->setUser($user);
            array_push($results, $historyUser);
        }

        // echo "<pre>";
        // print_r($results);
        // echo "</pre>";
        return $results;
    }

}