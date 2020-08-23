<?php
namespace app\admin\controller;

use app\admin\controller\IsLogin;
use app\admin\model\ColumnsModel;
use think\facade\Db;
use think\Debug;
class Sitemap extends Islogin{

    public function index()
    {
        $starttime = explode(' ',microtime());
        //获取当前域名
        $url = get_http_type().$_SERVER['HTTP_HOST'];
        //是否已经存在文件， 如果存在删除，不存在直接生成
        if(file_exists('sitemap.xml')) {
            unlink('sitemap.xml');
        }
        //  实例化栏目模型类，   获取所有栏目结构
        $column = new ColumnsModel();
        $menu = $column->menud(1,0,0,2,'id,parent_id,c_names,fun,tpl,route,webs');
        //生成站点地图
        $res = self::setMap($url,$menu);
        //运行时间
        $endtime = explode(' ',microtime());
        $thistime = $endtime[0]+$endtime[1]-($starttime[0]+$starttime[1]);
        $time = round($thistime,3);

        if($res) {
            return json(['msg'=>1,'time'=>$time]);
        }
    }

    static function setMap($url,$menu = []) {
        //引入扩展类
        require_once('vendor/mimvp-sitemap-php-master/sitemap.php');
        $cost_time_start = \getMillisecond();
        $sitemap = new \Sitemap($url);
        $sitemap->addItem('/', '1.0', 'always',time());

        foreach($menu as $k=>$v) {
            //判断一级栏目
            if(empty($v['chid']) && $v['fun'] !== '聚合页') {
                if(!empty($v['webs'])) {
                    $sitemap->addItem($v['webs'], '0.8', $v['c_names'], time());
                }elseif(!empty($v['route'])) {
                    $sitemap->addItem('/'.$v['route'], '0.8', $v['c_names'], time());
                }else {
                    $sitemap->addItem('/'.$v['tpl'].'/index/id/'.$v['id'], '0.8', $v['c_names'], time());
                }

            }
            //判断一级栏目
            if($v['fun'] == '聚合页') {
                if(!empty($v['webs'])) {
                    $sitemap->addItem($v['webs'], '0.8', $v['c_names'], time());
                }elseif(!empty($v['route'])) {
                    $sitemap->addItem('/'.$v['route'], '0.8', $v['c_names'], time());
                }else{
                    $sitemap->addItem('/'.$v['tpl'].'/index/id/'.$v['id'], '0.8',$v['c_names'], time());
                }
            }
            //判断子栏目
            foreach($v['chid'] as $key=>$val) {
                if(empty($val['chid']) && $val['fun'] !== '聚合页') {
                    if(!empty($val['webs'])) {
                        $sitemap->addItem($val['webs'], '0.8', $val['c_names'], time());
                    }elseif(!empty($val['route'])) {
                        $sitemap->addItem('/'.$val['route'], '0.8',$val['c_names'], time());
                    }else {
                        $sitemap->addItem('/'.$val['tpl'].'/index/id/'.$val['id'], '0.8',$val['c_names'], time());
                    }
                }

                if($val['fun'] == '聚合页') {
                    if(!empty($val['webs'])) {
                        $sitemap->addItem($val['webs'], '0.8', $val['c_names'], time());
                    }elseif(!empty($val['route'])) {
                        $sitemap->addItem('/'.$val['route'], '0.8', $val['c_names'], time());
                    }else{
                        $sitemap->addItem('/'.$val['tpl'].'/index/id/'.$val['id'], '0.8',$val['c_names'], time());
                    }
                }

                foreach ($val['chid'] as $ks=>$vs) {
                    if(empty($vs['chid']) && $vs['fun'] !== '聚合页') {
                        if(!empty($vs['webs'])) {
                            $sitemap->addItem($vs['webs'], '0.8', $vs['c_names'], time());
                        }elseif(!empty($vs['route'])) {
                            $sitemap->addItem('/'.$vs['route'], '0.8',$vs['c_names'], time());
                        }else {
                            $sitemap->addItem('/'.$vs['tpl'].'/index/id/'.$vs['id'], '0.8',$vs['c_names'], time());
                        }
                    }

                    if($vs['fun'] == '聚合页') {
                        if(!empty($vs['webs'])) {
                            $sitemap->addItem($vs['webs'], '0.8', $vs['c_names'], time());
                        }elseif(!empty($vs['route'])) {
                            $sitemap->addItem('/'.$vs['route'], '0.8', $vs['c_names'], time());
                        }else{
                            $sitemap->addItem('/'.$vs['tpl'].'/index/id/'.$vs['id'], '0.8',$vs['c_names'], time());
                        }
                    }

                }

            }

        }

        $sitemap->endSitemap();

        return true;
    }
}