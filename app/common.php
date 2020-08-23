<?php
// 应用公共文件

//获取用户真实ip
function clientIP()
{
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}
/**
 * 获取 IP  地理位置
 * 百度地图IP接口
 * @Return: array
 */
function getCity($ip)
{
    $ch = curl_init();

    $url = "http://api.map.baidu.com/location/ip?ip={$ip}&ak=kt8PsF07h0RG0oo7mT8Em0pURMpHUCPk";
    curl_setopt($ch, CURLOPT_URL, $url);

    //参数为1表示传输数据，为0表示直接输出显示。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //参数为0表示不带头文件，为1表示带头文件
    curl_setopt($ch, CURLOPT_HEADER,0);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
    $output = curl_exec($ch);

    curl_close($ch);

    $output = json_decode($output,true);

    if($output['status'] == 0){
        $data['status'] = 0;
        $data['city'] = $output['content']['address'];
    }elseif($output['status'] == 2){
        $data['status'] = 2;
        $data['city'] = "请求参数错误：ip非法";

    }elseif($output['status'] == 1){
        $data['status'] = 1;
        $data['city'] = "内部服务器ip";
    }

    return $data;

}

/**
 * 统计目录文件大小的函数
 * @param $dir 文件路径
 * @author 65420278@qq.com
 */
function dirsize($dir)
{
    @$dh = opendir($dir);
    $size = 0;
    while($file = @readdir($dh))
    {
        if($file != "." and $file != "..")
        {
            $path = $dir . "/" . $file;

            if(is_dir($path))
            {
                $size += dirsize($path);
            } elseif(is_file($path)) {
                $size += filesize($path);
            }
        }
    }

    @closedir($dh);
    return $size;
}

/**
 * 格式化字节大小*/
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

//防整型注入
function get_int($str){
    $str=intval($str);
    if (!is_numeric($str)){
        header("location:".$_SERVER['HTTP_REFERER']."");
        exit;
    }
    return $str;
}

//防字符串注入
function get_str($str,$chk=0) {
    if (ini_get('magic_quotes_gpc')) {
        if($chk==1){
            return $str;
        }else{
            return htmlspecialchars($str);
        }
    } else {
        if($chk==1){
            return addslashes($str);
        }else{
            return addslashes(htmlspecialchars($str));
        }
    }
}

/**
 * 递归删除缓存文件
 * @param $dir
 */
function delFileByDir($dir) {
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (is_dir($fullpath)) {
                delFileByDir($fullpath);
            } else {
                unlink($fullpath);
            }
        }
    }
    closedir($dh);
}

// 获取网址协议
function get_http_type()
{
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http_type;
}

//批量处理数据类型   整型转换
function handArray(array $data = [] , array $schema = [] , array $string)
{
    //需要转换的字段
    if (empty($data) && empty($schema)) {       //全部为空返回
        return false;
    }else{
        foreach ($data as $k=>$v) {
            if (in_array($k , $schema)) {
                $data[$k] = get_int($data[$k]);
            }
            if (in_array($k , $string)) {
                $data[$k] = get_str($data[$k]);
            }
        }
        return $data;
    }
}