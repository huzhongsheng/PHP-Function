<?php
    /*
    *@function：检测字符串是否由纯英文，纯中文，中英文混合组成
    *@param $str string
    *@return en:纯英文;cn:纯中文;encn:中英文混合
    */
    function ischinese($str){
        if(trim($str)==''){
            return '';
        }
        $m=mb_strlen($str,'utf-8');
        $s=strlen($str);
        if($s==$m){
            return 'en';
        }
        if($s%$m==0&&$s%3==0){
            return 'cn';
        }
        return 'encn';
    }
    /*
    *@function：检测字符串中文长度
    *@param $str string
    *@return $count int
    */
    function chineseCount($str){
        $pa = '/[\x{4e00}-\x{9fa5}]/siu';
        preg_match_all($pa, $str, $r);
        $count = count($r[0]);
        return $count;
    }
    /*
    *@function：检测字符串长度,中文算1个长度
    *@param $str string
    *@return int
    */
    function utf8_strlen($string = null) {
        // 将字符串分解为单元
        preg_match_all("/./us", $string, $match);
        // 返回单元个数
        return count($match[0]);
    }
    /*
     * @function 过滤emoji表情
     * @param $str string
     * @return string
     * */
    function filterEmoji($str){
        return preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $str);
    }
    /*
     * @function 生成唯一字符串
     * 可用于文件名，用户token等
     * */
    function generateRandomString(){

        $charid = strtoupper(md5(uniqid(mt_rand(), true)));

        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
    }
    /*
     * @function 生成随机字符串
     *@param $length int 长度
     * 唯一性不如 ‘generateRandomString’方法
     * */
    function randString($length = 20){

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {

            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
?>