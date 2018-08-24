<?php

class Library_Validation {
    
    static function reCaptcha($g_res) {
        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        $ip = $_SERVER['REMOTE_ADDR'];
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        $fields = array(
            'secret' => '6LcxHhwUAAAAAJqWlrIbutoxP1GEMn-qWdMx3OCf',
            'response' => $g_res,
            'remoteip'=> $ip
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        $result = json_decode($result);
        
        return $result->success;
    }
    
    static function clean_value($val){
        if ($val != ""){
            $val = trim($val);
            $val = str_replace( "&#032;", " ", $val );
            $val = str_replace( chr(0xCA), "", $val );  //Remove sneaky spaces
            $val = str_replace(array("<!--", "-->", "/<script/i", ">", "<", '"', "/\\\$/", "/\r/", "!", "'"), array("","","&#60;script","&gt;", "&lt;","&quot;","&#036;","","&#33;","&#39;"), $val);
            $get_magic_quotes = @get_magic_quotes_gpc();
            if ( $get_magic_quotes ){
                    $val = stripslashes($val);
            }
            $val = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $val );
        }
        return $val;
    }
    
    static function antiSql($value) { 
        $value = self::clean_value($value);
        $banlist = array(
            'insert', 'select', 'update', 'delete', 'distinct', 'having', 'where', 'substring(', '(update',
            'truncate', 'replace', 'handler', 'ascii(', 'set', '\'or', 'shutdown)','%0A','%0a',
            'procedure', 'limit', 'order by', 'group by', 'asc', 'desc', 'show',
            'concat(', '(select', '/insert', '/select', '/update', '/delete', '/distinct', '/having',
            '/truncate', '/replace', '/handler', '/like', '/as', '/or', '/procedure', '/limit',
            '/order by', '/group by', '/asc', '/desc',
            'chr(', 'chr=', 'chr%20', '%20chr', 'wget%20', '%20wget', 'wget(',
            'cmd=', '%20cmd', 'cmd%20', 'rush=', '%20rush', 'rush%20',
            'union%20', '%20union', 'union(', 'union=', 'echr(', '%20echr', 'echr%20', 'echr=',
            'esystem(', 'esystem%20', 'cp%20', '%20cp', 'cp(', 'mdir%20', '%20mdir', 'mdir(',
            'mcd%20', 'mrd%20', 'rm%20', '%20mcd', '%20mrd', '%20rm',
            'mcd(', 'mrd(', 'rm(', 'mcd=', 'mrd=', 'mv%20', 'rmdir%20', 'mv(', 'rmdir(',
            'chmod(', 'chmod%20', '%20chmod', 'chmod(', 'chmod=', 'chown%20', 'chgrp%20', 'chown(', 'chgrp(',
            'locate%20', 'grep%20', 'locate(', 'grep(', 'diff%20', 'kill%20', 'kill(', 'killall',
            'passwd%20', '%20passwd', 'passwd(', 'telnet%20', 'vi(', 'vi%20',
            'insert%20into', 'select%20', 'nigga(', '%20nigga', 'nigga%20', 'fopen', 'fwrite', '%20like', 'like%20',
            '$_request', '$_get', '$request', '$get', '.system', 'HTTP_PHP', '&aim', '%20getenv', 'getenv%20',
            'new_password', '&icq','/etc/password','/etc/shadow', '/etc/groups', '/etc/gshadow',
            'HTTP_USER_AGENT', 'HTTP_HOST', '/bin/ps', 'wget%20', 'uname\x20-a', '/usr/bin/id',
            '/bin/echo', '/bin/kill', '/bin/', '/chgrp', '/chown', '/usr/bin', 'g\+\+', 'bin/python',
            'bin/tclsh', 'bin/nasm', 'perl%20', 'traceroute%20', 'ping%20', '.pl', '/usr/X11R6/bin/xterm', 'lsof%20',
            '/bin/mail', '.conf', 'motd%20', 'HTTP/1.', '.inc.php', 'config.php', 'cgi-', '.eml',
            'file\://', 'window.open', '<SCRIPT>', 'javascript\://','img src', 'img%20src','.jsp','ftp.exe',
            'xp_enumdsn', 'xp_availablemedia', 'xp_filelist', 'xp_cmdshell', 'nc.exe', '.htpasswd',
            'servlet', '/etc/passwd', 'wwwacl', '~root', '~ftp', '.js', '.jsp', 'admin_', '.history',
            'bash_history', '.bash_history', '~nobody', 'server-info', 'server-status', 'reboot%20', 'halt%20',
            'powerdown%20', '/home/ftp', '/home/www', 'secure_site, ok', 'chunked', 'org.apache', '/servlet/con',
            '<script', '/robot.txt' ,'/perl' ,'mod_gzip_status', 'db_mysql.inc', '.inc', 'select%20from',
            'select * from', 'drop%20', '.system', 'getenv', 'http_', '_php', 'php_', 'phpinfo()', '<?php', '?>', 'sql=',
        );
        
        $value = strtolower(trim($value));
        $return_value = str_replace( $banlist, '', $value);

        if($value !== $return_value) {
            Library_Log::writeOpenTable("hack.html");
            Library_Log::writeHtml("Before : " . $value . "<br/>After : " . $return_value, "hack.html");
            Library_Log::writeCloseTable("hack.html");
            return false;
        }
        return true;
    }
    
    static function blackList($value) {
        $value = trim($value);
        $arr_bl = array('fuck', 'shit', 'lồn', 'lôz', 'địt', 'cứt', 'buồi', 'đậu má', 'đụ má');
        $arr_rp = array('f**k', 'sh*t', 'l*n', 'l*z', 'd*t', 'c*t', 'b**i', 'd*u m*', 'd* m*');
        $value = str_replace($arr_bl, $arr_rp, $value);
        return $value;
    }
    
    static function escapeString($value) {
        $value = str_replace("\'", "'", $value);
        $value = str_replace("'", "''", $value);
        return $value;
    }
    
    static function isEmailValid($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
    
    static function isCardId($cardid) {
        if(preg_match('/^\d{9,12}$/', $cardid)) {
            return true;
        }
        return false;
    }
    
    static function isUsername($username) {
        if(preg_match('/^[A-Za-z]([A-Za-z0-9_]){5,14}$/', $username)) {
            return true;
        }
        return false;
    }
    
    static function isPositive($string) {
        if(is_numeric($string)) {
            if((int)$string > 0) {
                return true;
            }
        }
        return false;
    }
    
    static function isPhoneNumberViettel($phone) {
        $arr_ds = array('096', '097', '098', '0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169', '086');
        foreach($arr_ds as $value) {
            if(strpos($phone, $value) === 0) {
                // validate tiep
                if(preg_match("/^[0-9]{10,11}$/", $phone)) {
                    return true;
                }
            }
        }
        return false;
    }
    
    static function isPhoneNumber($phone) {
        $arr_ds = array('090', '093', '0120', '0121', '0122', '0126', '0128', '096', '097', '098', '0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169', '086', '091', '094', '0123', '0124', '0125', '0127', '0129', '092', '0188', '095', '0993', '0994', '0995', '0996', '0199', '0186', '0188', '0925', '086', '088', '089');
        foreach($arr_ds as $value) {
            if(strpos($phone, $value) === 0) {
                // validate tiep
                if(preg_match("/^[0-9]{10,11}$/", $phone)) {
                    return true;
                }
            }
        }
        return false;
    }
    
    static function isURL($url) {
        $arr_ds = array('.vn', '.net', '.com.vn', '.net.vn', '.com', '.org', '.edu.vn', '.biz.vn', '.gov.vn', '.org.vn', '.info.vn', '.pro.vn', '.health.vn', '.int.vn', '.ac.vn', '.pro.vn', '.name.vn', '.biz', '.info', '.cc', '.ws', '.tv', '.mobi', '.eu', '.asia', '.me', '.tel', '.co', '.com.co', '.net.co', '.nom.co', '.us');
        foreach($arr_ds as $value) {
            if(strstr($url, $value) !== false) {
                // validate tiep
                if(preg_match("/^https?:\/\/\w+\.\w+(\.\w+)?/", $url)) {
                    return true;
                }
            }
        }
        return false;
    }
    
    static function isIP($ip) {
        if(preg_match('/^([01]?\d\d?|2[0-4]\d|25[0-5])\.([01]?\d\d?|2[0-4]\d|25[0-5])\.([01]?\d\d?|2[0-4]\d|25[0-5])\.([01]?\d\d?|2[0-4]\d|25[0-5])$/', $ip)) {
            return true;
        }
        return false;
    }
    
    static function isNumber($number) {
        if(preg_match("/\D/", $number)) {
            return false;
        }
        return true;
    }
}