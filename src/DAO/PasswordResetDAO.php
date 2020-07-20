<?php 

namespace Src\DAO;
use Src\Utils\DB;
use Src\Beans\PasswordResetsBean;

class PasswordResetDAO extends DB {

    public static function getAll(): Array {
        $passResets = parent::selectQuery("SELECT * FROM password_resets", PasswordResetsBean::class);
        return $passResets;
    }

    public static function fetch(Array $param, String $field): ?PasswordResetsBean {
        
        $sql = "SELECT * FROM password_resets WHERE $field = ?";
        $passResets = parent::selectQuery($sql, PasswordResetsBean::class, $param);
        if(count($passResets) > 0 ) {
            return $passResets[0];
        }
        return null;
    }

    public static function getAllWhere(String $whereClause): Array {
        $passResets = parent::selectQuery("SELECT * FROM password_resets WHERE $whereClause", PasswordResetsBean::class);
        return $passResets;
    }

    public static function insert(String $token, String $email) {
        //PDO execute returns true or false, extra check for exception does not hurt
        try {
            $query = "INSERT INTO password_resets (email, token) VALUES (:email, :token)";
            $values = [
                'email' => $email,
                'token' => $token
            ];
            parent::execute($query, $values);
            return true;
        } catch (Exception $e) {
            //TODO: set up logger and log the exceptions (file or db)
            return false;
        }
    }

    public static function delete(String $token, String $email) {
        $query = "DELETE FROM password_resets WHERE email = :email AND token = :token";
        $values = [
            'email' => $email,
            'token' => $token
        ];

        parent::execute($query, $values);
    }
}