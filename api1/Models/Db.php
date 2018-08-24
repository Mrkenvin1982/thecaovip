<?php
class Models_Db {
    
    private static $instance;
    private static $active_group;
    
    public static function getDBO($active_group = "default") {
        global $config;
        
        $host = $config[$active_group]['host'];
        $user = $config[$active_group]['user'];
        $pass = $config[$active_group]['pass'];
        $db = $config[$active_group]['db'];
        
        try {
            if(!isset(self::$instance) || self::$active_group != $active_group) {
                self::$active_group = $active_group;
                self::$instance = new PDO('mysql:host='.$host.';dbname='.$db.";charset=utf8", $user, $pass, array(
                    PDO::ATTR_PERSISTENT => false
                ));
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return self::$instance;
        }
        catch(PDOException $e){ 
            // ghi log error db
            $file_log = "connect_db_error.log";
            Library_Log::writeOpenTable($file_log);
            Library_Log::writeHtml("Error Msg : " . $e->getMessage(), $file_log);
            Library_Log::writeHtml("Input : " . print_r($config[$active_group], 1), $file_log);                        
            Library_Log::writeCloseTable($file_log);
            die("System busy!");
        }
    }
}