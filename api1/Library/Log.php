<?php

class Library_Log {
    
    static function writeLog($message, $file = "log.log") {
        global $root_logs;
        global $date_format;
        $body = $message . "\n";
        $day = date("d_m_Y");
        $file = $root_logs . "/" . $day . "_" . $file;
        $fp = fopen($file, 'a+');
        fwrite($fp, $body);
        fclose($fp);
    }
    
    static function writeLogHeader($header, $file = 'log.log') {
        global $date_format;
        $time = date($date_format);
        $body = '';
        $body .= "#######################################\n";
        $body .= "#                                     #\n";
        $body .= "      $header\n";
        $body .= "#                                     #\n";
        $body .= "#######################################\n";
        $body .= 'Time ' . $time . "\n";
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $body .= "HTTP_X_FORWARDED_FOR : " . $_SERVER['HTTP_X_FORWARDED_FOR'] . "\n";
      }
        $body .= "From IP : " . $_SERVER['REMOTE_ADDR'] . "\n";
                      if (isset($_SERVER['REMOTE_HOST'])) {
        $body .= "From host : " . $_SERVER['REMOTE_HOST'] . "\n";
   
          
      }
        $body .= "From port : " . $_SERVER['REMOTE_PORT'] . "\n";
        $body .= "From user agent : " . $_SERVER['HTTP_USER_AGENT'] . "\n";
        $body .= "Script file name : " . $_SERVER['SCRIPT_FILENAME'] . "\n";
        self::writeLog($body, $file);
    }
    
    static function writeLogFooter($footer, $file = 'log.log') {
        $body = '';
        $body .= "#######################################\n";
        $body .= "#                                     #\n";
        $body .= "      $footer\n";
        $body .= "#                                     #\n";
        $body .= "#######################################\n";
        $body .= "\n\n\n";
        self::writeLog($body, $file);
    }
    
    static function writeHtml($message, $file) {
        $body = "";

        $body .= "<td><pre>";
        $body .= $message;
        $body .= "</td>";     
        self::writeLog($body, $file);   
    }
    
    static function writeOpenTable($file) {
        global $date_format;
        $body = '';
        $body .= "<table class='adminlist'>";
        $body .= "<tr>";
        $body .= "<td><pre>";
        $time = date($date_format);
        $body .= 'Time : ' . $time . "<br/>";
           if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $body .= "HTTP_X_FORWARDED_FOR : " . $_SERVER['HTTP_X_FORWARDED_FOR'] . "\n";
          
      }
        $body .= "From IP : " . $_SERVER['REMOTE_ADDR'] . "<br/>";
               if (isset($_SERVER['REMOTE_HOST'])) {
         $body .= "From host : " . $_SERVER['REMOTE_HOST'] . "<br/>";
          
      }
      
        $body .= "From port : " . $_SERVER['REMOTE_PORT'] . "<br/>";
        $body .= "From user agent : " . $_SERVER['HTTP_USER_AGENT'] . "<br/>";
        $body .= "Script file name : " . $_SERVER['SCRIPT_FILENAME'] . "<br/>";
        $body .= "</td>";
        self::writeLog($body, $file);
    }
    
    static function writeCloseTable($file) {
        $body = "</tr>";
        $body .= "</table>";
        self::writeLog($body, $file);
    }
}