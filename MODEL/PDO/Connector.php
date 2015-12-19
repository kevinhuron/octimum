<?php

/**
 * Created by PhpStorm.
 * User: DanyRafina
 * Date: 18/12/2015
 * Time: 14:16
 */

class Connector {

    static private $DSN ;
    static private $USERNAME ;
    static private $USERPASSWORD ;
    static private $instance = null;
    static private $errorMessage = null;
    
    /** Create a connection
     * (Ensure to change the password Database)
     * @throws PDOException Error of creation
     */
    private function __construct() {
        try {
            self::$DSN = "mysql:host=localhost";
            self::$USERNAME = "root";
            self::$USERPASSWORD = "mysql";
            self::$instance = new PDO(self::$DSN, self::$USERNAME, self::$USERPASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
        } catch (PDOException $event) {
            throw new PDOException($event->getMessage());
        }
    }
    
    /** Execute a prepare query
     *
     * @param string $query Query to execute
     * @param array $array Query options
     * @return PDOStatement Query result
     */
    static public function prepare($query, $array = NULL) {
        $prepare = NULL;
        if (self::$errorMessage == NULL) {
            $prepare = self::getInstance()->prepare($query);
            if ($array == NULL) {

                $prepare->execute();
            } else {

                $prepare->execute($array);
            }
        }
        return $prepare;
    }

    /** Get connection
     *
     * @return PDO The connection
     * @throws PDOException Error of connection or query execution
     */
    static private function getInstance() {
        try {
            if (self::$instance == NULL) {
                new Connector();
            }
        } catch (PDOException $exc) {
            throw new PDOException($exc->getMessage());
        }
        return self::$instance;
    }
    
    /** To get an error
     * 
     * @return string The error
     */
    static public function getErrorMessage() {
        return self::$errorMessage;
    }
    
    /** Drop connection
     * 
     * @return boolean True if the connection is removinf
     * @throws PDOException Error of drop
     */
    static public function destroyConnection() {
        try {
            if (self::$instance != null) {
                self::$instance = NULL;
            }
            return true;
        } catch (PDOException $event) {
            throw new PDOException($event->getMessage());
        }
    }
}