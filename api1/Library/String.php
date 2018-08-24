<?php

class Library_String {
    
    static function ucfirst($string, $e = 'utf-8') {
        if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
            $string = mb_strtolower($string, $e);
            $upper = mb_strtoupper($string, $e);
                preg_match('#(.)#us', $upper, $matches);
                $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
        }
        else {
            $string = ucfirst($string);
        }
        return $string;
    }
    
    static function removeAccent($string){
    	$marTViet=array(
    		// Chữ thường
    		"à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ","ặ","ẳ","ẵ",
    		"è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ",
    		"ì","í","ị","ỉ","ĩ",
    		"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ờ","ớ","ợ","ở","ỡ",
    		"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
    		"ỳ","ý","ỵ","ỷ","ỹ",
    		"đ","Đ","'",
    		// Chữ hoa
    		"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă","Ằ","Ắ","Ặ","Ẳ","Ẵ",
    		"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
    		"Ì","Í","Ị","Ỉ","Ĩ",
    		"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ",
    		"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
    		"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
    		"Đ","Đ","'"
    		);
    	$marKoDau=array(
    		/// Chữ thường
    		"a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a",
    		"e","e","e","e","e","e","e","e","e","e","e",
    		"i","i","i","i","i",
    		"o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o",
    		"u","u","u","u","u","u","u","u","u","u","u",
    		"y","y","y","y","y",
    		"d","D","",
    		//Chữ hoa
    		"A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A",
    		"E","E","E","E","E","E","E","E","E","E","E",
    		"I","I","I","I","I",
    		"O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O",
    		"U","U","U","U","U","U","U","U","U","U","U",
    		"Y","Y","Y","Y","Y",
    		"D","D","",
    		);
    	return str_replace($marTViet, $marKoDau, $string);
    }
    
    static function rename($string, $keyReplace = "/"){
        $string = self::removeAccent($string);
        $string = trim(preg_replace("/[^A-Za-z0-9]/i"," ",$string)); // khong dau
        $string = str_replace(" ","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace($keyReplace,"-",$string);
        return strtolower($string);
    }
    
    static function subString($str, $len) {
        if($str == "" || $str == NULL) {
            return $str;
        }
        if(is_array($str)) {
            return $str;    
        }
        $str = trim($str);
        if(strlen($str) <= $len) {
            return $str;    
        }
        return mb_substr($str, 0, $len, "UTF-8") . "...";
    }
    
    static function decryptstr($string, $password){
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $password, base64_decode($string), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
    
    static function encryptStr($string, $password){
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $password, $string, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
}