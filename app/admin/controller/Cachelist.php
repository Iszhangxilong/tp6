<?php
namespace app\admin\controller;

use app\admin\controller\IsLogin;
class Cachelist extends IsLogin {

    public function del(){

        $dir = ['./runtime/admin/temp'];

        foreach($dir as $v) {
            $this->delFileByDir($v);
        }
        return json(1);
    }
    /**
     * 递归删除缓存文件
     * @param $dir
     */
    public function delFileByDir($dir) {
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
}