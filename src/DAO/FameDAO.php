<?php 

namespace Src\DAO;
use Src\Utils\DB;
use Src\Beans\UserBean;
use Src\Utils\Crypt;
use Src\DAO\UserDAO;
use Src\Beans\Bean;
use Src\Beans\FameBean;

class FameDAO extends DB {

    public static function getFame(UserBean $rating): Array {
        $fame = parent::selectQuery("SELECT * FROM users WHERE fame_rating = ?", UserBean::class, [$rating->getFame_rating()]);
        return $fame;
    }

    public static function addFame(UserBean $rating) {
        $fame = $rating->getFame_rating();
        $fame = $fame + 10;
        $data = [];
        $data['fame_rating'] = $fame;
        UserDAO::updateOne($data, $rating);
    }

    public static function removeFame(UserBean $rating) {
        $fame = $rating->getFame_rating();
        $fame = $fame - 10;
        $data = [];
        $data['fame_rating'] = $fame;
        UserDAO::updateOne($data, $rating);
    }

}