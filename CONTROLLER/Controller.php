<?php

/**
 * Created by PhpStorm.
 * User: kevinhuron
 * Date: 18/12/2015
 * Time: 09:46
 */
class Controller
{

    //KEVIN - La méthode getModel est en faite un Singleton
    //C'est à dire que cette méthode va s'assurer que il y'a une seule instance de Model!
    // Donc quand tu dois accéder à la couche Model pour faire des requêtes
    // Toujours utiliser cette méthode !!!
    static private $modal_instance = NULL;

    /**
     * First Page of App
     */
    public function indexAction()
    {
        $model = $this->getModel();
        $databases = $model->get_all_db()->fetchAll();
        $version = $model->get_server_version()->fetchColumn();
        $us = $model->get_user_co()->fetchColumn();
        $charset = $model->get_charset()->fetchColumn();
        $log = $this->getLogs();
        echo $_SESSION['twig']->render("index.html.twig",
            array("databases"=>$databases, "ip"=>$_SERVER['SERVER_NAME'],
                "version"=>$version,"user"=>$us,
                "charset"=>$charset,
                "os"=>php_uname(),
                "server_type"=>$_SERVER["SERVER_SOFTWARE"],
                "phpv"=>phpversion(),
                "logs"=>$log));
        unset($model);
    }

    /** Get log informations
     * @return array Log info
     */
    public function getLogs()
    {
        $file = fopen("CONTROLLER/LOG/log.txt","r");
        $log = array();
        while ($read = fgets($file, 8192))
            array_push($log, $read);
        fclose($file);
        return $log;
    }

    /** This is a Singleton
     * Get an instance of model
     * @return Model Instance of Model Class
     */
    private function getModel()
    {
        return (self::$modal_instance == NULL)? self::$modal_instance = new Model(): self::$modal_instance;
    }

    public function showDB($dbname)
    {
        echo "Welcome to show DB : $dbname";
        // A toi de faire le boulot ;)
        // enlève le echo bien sur
    }

}