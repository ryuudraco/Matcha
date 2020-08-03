<?php 

namespace Src\DAO;
use Src\Utils\DB;
use Src\Beans\UserBean;
use Src\Utils\Crypt;
use Src\Beans\LikesBean;
use Src\DAO\HistoryDAO;

class LikesDAO extends DB {

    public static function countAllUserReceivedLikes(UserBean $user): int {
        $count = parent::select("SELECT count(id) as count FROM likes WHERE target_id = ?", [$user->getId()]);
        return $count[0]['count'];
    }

    public static function getAllTotals() {
        $count = parent::selectQuery("SELECT count(target_id) as total, target_id from likes group by target_id", LikesBean::class);
        return $count;
    }

    public static function getLike(UserBean $target, UserBean $origin): Array {
        $likes = parent::select("SELECT * FROM likes WHERE target_id = ? AND origin_id = ?", [$target->getId(), $origin->getId()]);
        return $likes;
    }

    public static function likeUnlikeProfile(UserBean $target, UserBean $origin) {
        
        $like = self::getLike($target, $origin);

        //guess we didn't liked that user yet
        if(empty($like)) {
            $sql = "INSERT INTO likes (origin_id, target_id) VALUES (:origin, :target)";
            HistoryDAO::insertHistoryAction($target, $origin, 'liked');

            $likeBack = self::getLike($origin, $target);
            //we liked back someone who liked us before
            if(!empty($likeBack)) {
                //their profile
                HistoryDAO::insertHistoryAction($target, $origin, 'matched');
    
                //our profile
                HistoryDAO::insertHistoryAction($origin, $target, 'matched');
            }
            
        } else {
            $sql = "DELETE FROM likes WHERE origin_id = :origin AND target_id = :target";
            HistoryDAO::deleteHistoryAction($target, $origin, 'liked');
        }

        $values = [
            'origin' => $origin->getId(),
            'target' => $target->getId()
        ];
        parent::execute($sql, $values);
    }
}