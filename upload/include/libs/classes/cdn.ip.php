<?php
/**
 * CDN加速获取真实IP
 */
function cdn_get_ip()
{
    static $ip = NULL;
    if($ip !== NULL){
        return $ip;
    }
    if(isset($_SERVER))
        {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr AS $ip){
                $ip = trim($ip);
                if($ip != 'unknown'){
                    $ip = $ip;
                    break;
                }
            }
        }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr AS $ip){
                $ip = trim($ip);
                if($ip != 'unknown'){
                    $ip = $ip;
                    break;
                }
            }
        }elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }else{
            if(isset($_SERVER['REMOTE_ADDR'])){
                $ip = $_SERVER['REMOTE_ADDR'];
            }else{
                $ip = '0.0.0.0';
            }
        }
    }else{
        if(getenv('HTTP_X_FORWARDED_FOR')){
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        }elseif(getenv('HTTP_CLIENT_IP')){
            $ip = getenv('HTTP_CLIENT_IP');
        }else{
            $ip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $ip, $onlineip);
    $ip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

    return $ip;
}

?>