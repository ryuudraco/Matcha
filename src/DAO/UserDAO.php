<?php 

namespace Src\DAO;
use Src\Utils\DB;
use Src\Beans\UserBean;
use Src\Utils\Crypt;
use Src\DAO\LikesDAO;
use Src\DAO\BlocksDAO;

class UserDAO extends DB {

    public static function getAll(): Array {
        $users = parent::selectQuery("SELECT u.*, count(l.target_id) AS likes FROM likes AS l RIGHT JOIN users AS u ON u.id = target_id GROUP BY u.id, l.target_id;", UserBean::class);
        return $users;
    }

    public static function fetch(Array $param, String $field): ?UserBean {
        
        $sql = "SELECT * FROM users WHERE $field = ?";
        $users = parent::selectQuery($sql, UserBean::class, $param);
        if(count($users) > 0 ) {
            $likes = LikesDAO::countAllUserReceivedLikes($users[0]);
            $users[0]->setLikes($likes);
            return $users[0];
        }
        return null;
    }

    public static function getAllWhere(String $whereClause): Array {
        $users = parent::selectQuery("SELECT * FROM users WHERE $whereClause", UserBean::class);
        return $users;
    }

    public static function update(Array $data) {
        //PDO execute returns true or false, extra check for exception does not hurt
        try {
            if(self::isDirty($data)) {
                $userId = $_SESSION['user_id'];
                $query = "UPDATE users SET";
                $values = [];
                foreach($data as $name => $value) {
                    //this will concatinate string and will produce 
                    //e.g. UPDATE users SET city = :city (:city is replaced by pdo on prepare statement)
                    $query .= ' ' .$name . ' = :' . $name . ',';
                    $values[':'.$name] = $value;
                }
                
                $query = substr($query, 0, -1).' ';
                // we are assuming that we are only updating our own profile
                $query .= "WHERE id = $userId;";
                parent::execute($query, $values);
                return true;
            }
        } catch (Exception $e) {
            //TODO: set up logger and log the exceptions (file or db)
            return false;
        }
    }

    public static function updateOne(Array $data, UserBean $user) {
        //PDO execute returns true or false, extra check for exception does not hurt
        try {
                $query = "UPDATE users SET";
                $values = [];
                foreach($data as $name => $value) {
                    //this will concatinate string and will produce 
                    //e.g. UPDATE users SET city = :city (:city is replaced by pdo on prepare statement)
                    $query .= ' ' .$name . ' = :' . $name . ',';
                    $values[':'.$name] = $value;
                }
                
                $query = substr($query, 0, -1).' ';
                $query .= "WHERE id = " . $user->getId() . ";";
                parent::execute($query, $values);
                return true;
        } catch (Exception $e) {
            //TODO: set up logger and log the exceptions (file or db)
            return false;
        }
    }

    //update password on separate function as it needs to be hashed than other data
    public static function updatePassword(UserBean $user, String $password) {
        $password = Crypt::hash($password);
        $query = "UPDATE users SET password = :password WHERE id = " . $user->getId();
        parent::execute($query, ['password' => $password]);
    }


	/**
     * Dirty compares if there are any changes to the object that are not yet saved
     * This is quite simplified solution
     */

	private static function isDirty(Array $data) {
        $usrId = $_SESSION['user_id'];
        $usr = self::fetch([$usrId], 'ID');

        foreach($data as $field => $value) {
            $get = "get" . ucfirst($field);
            if($usr->$get() !== $value) {
                return true;
            }
        }
        return false;
    }
}