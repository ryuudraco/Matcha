<?php 

namespace Src\DAO;
use Src\Utils\DB;
use Src\Beans\UserBean;
use Src\DAO\UserDAO;
use Src\Beans\ThreadBean;
use Src\Beans\MessagesBean;


class MessagesDAO extends DB {

    public static function getThreads(UserBean $user): ?Array {
        $threads = parent::select("SELECT * FROM message_thread WHERE target_id = ? OR origin_id = ?", [$user->getId(), $user->getId()]);
        return $threads;
    }

    public static function getThreadsWithUserBeans(UserBean $user) {
        
        $threads = self::getThreads($user);

        $threadsBeansArray = [];
        foreach($threads as $thread) {
            $bean = new ThreadBean();
            $bean->setId($thread['id']);
            $bean->setOrigin(UserDAO::fetch([$thread['origin_id']], 'ID'));
            $bean->setTarget(UserDAO::fetch([$thread['target_id']], 'ID'));
            //print_r($thread);
            $threadsBeansArray[] = $bean;
        }

        echo "<pre>";
        //print_r($threadsBeansArray);
        echo "</pre>";

        return $threadsBeansArray;
    }

    public static function getMessagesForThread(UserBean $user, int $thread_id) {

        $threads = self::getThreadsWithUserBeans($user);
        $messages = [];
        $messageBeans = [];
        foreach($threads as $thread) {
            if($thread->getId() == $thread_id) {

                $messages = parent::select("SELECT * FROM messages WHERE thread_id = ?", [$thread_id]);

                if(!empty($messages)) {
                    foreach($messages as $message) {
                        $bean = new MessagesBean();
                        $bean->setThread_id($thread->getId());

                        $bean->setOrigin(UserDAO::fetch([$message['origin_id']], 'ID'));
                        $bean->setTarget(UserDAO::fetch([$message['target_id']], 'ID'));
                        
                        $bean->setMessage($message['message']);
                        $messageBeans[] = $bean;
                    }
                }
            }
        }



        echo "<pre>";
        //print_r($messageBeans);
        echo "</pre>";
        
        //                $bean = new MessagesBean()
        
        return $messageBeans;
    }


    public static function getNewMessages() {
        
    }

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