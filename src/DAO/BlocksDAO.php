<?php 

namespace Src\DAO;
use Src\Utils\DB;
use Src\Beans\UserBean;
use Src\Utils\Crypt;
use Src\DAO\UserDAO;
use Src\Beans\BlocksBean;

class BlocksDAO extends DB {

    public static function countAllUserReceivedBlocks(UserBean $user): int {
        $countBlocks = parent::select("SELECT count(id) as count FROM blocks WHERE target_id = ?", [$user->getId()]);
        return $countBlocks[0]['count'];
    }

    public static function getAllTotals() {
        $countBlocks = parent::selectQuery("SELECT count(target_id) as total, target_id from blocks group by target_id", BlocksBean::class);
        print_r($countBlocks);
        return $countBlocks;
    }

    public static function getAllBlockedUsersForOrigin(UserBean $user): ?Array {
        $blocks = parent::selectQuery("SELECT * FROM blocks WHERE origin_id = ?", BlocksBean::class, [$user->getId()]);
        return $blocks;
    }

    public static function getBlock(UserBean $target, UserBean $origin): Array {
        $blocks = parent::select("SELECT * FROM blocks WHERE target_id = ? AND origin_id = ?", [$target->getId(), $origin->getId()]);
        return $blocks;
    }

    public static function blockUnblockProfile(UserBean $target, UserBean $origin) {
        
        $block = self::getBlock($target, $origin);

        //guess we didn't liked that user yet
        if(empty($block)) {
            $sql = "INSERT INTO blocks (origin_id, target_id) VALUES (:origin, :target)";
        } else {
            $sql = "DELETE FROM blocks WHERE origin_id = :origin AND target_id = :target";
        }

        $values = [
            'origin' => $origin->getId(),
            'target' => $target->getId()
        ];
        parent::execute($sql, $values);
    }
}