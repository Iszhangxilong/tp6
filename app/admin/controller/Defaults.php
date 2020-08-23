<?php
namespace app\admin\controller;

use app\BaseController;
use think\facade\App;
use think\facade\Request;
use think\facade\View;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;
use app\admin\model\LogModel;
use app\admin\controller\IsLogin;
class Defaults extends IsLogin
{
    public function index(){
        //目录结构
        $menudata = Db::name('menu')->where('parent_id=0 and ishow=1')->order('num asc,id asc')->select();
        //管理日志文件大小
        $logArray = ['./runtime/admin/log','./runtime/index/log'];
        $countLog = 0;
        foreach ($logArray as $v) {
            $countLog += dirsize($v);
        }
        //前台+后台总日志大小
        $countLog = format_bytes($countLog);
        $code = 0;
        if (ceil($countLog) >= ceil(512000.0)) {
            $code = 1;
        }
        View::assign( ['menudata'=>$menudata,'code'=>$code]);
        return view();
    }

    public function desc(){
        //信息总量
        $count1 = Db::name('article')->count();
        //总点击量
        $count2 = Db::name('article')->sum('clicks');
        //管理员数
        $count3 = Db::name('admin')->where('datatype=1')->count();
        //总访问量
        $count4 = Db::name('census')->find();
        //获取发布数据
        $countdata = $this->countdata();
        $countdate = $this->countdate();
        //获取访问量
        $countvisit = $this->countvisit();
        //获取点击量
        $countclicks = $this->countclick();
        View::assign([
            'count1'        =>  $count1,
            'count2'        =>  $count2,
            'count3'        =>  $count3,
            'count4'        =>  $count4['znum'],
            'countdate'     =>  $countdate,
            'countdata'     =>  $countdata,
            'countvisit'    =>  $countvisit,
            'countclicks'   =>  $countclicks
        ]);

        return view();
    }

    //退出系统
    public function outlogin(){
        LogModel::setLog("退出登陆","退出",3);
        Session::delete('adminname');
        Session::delete('uid');
        Session::delete('logintime');
        //操作日志
        header("location: ".url('/Index/index'));
        exit;
    }

    //清理缓存
    public function clearCache(){
        $this->delFileByDir('./runtime/admin/temp');

        $this->delFileByDir('./runtime/index/temp');
        return josn(1);
    }

    //获取本月的天数
    public function countdate(){
        $dates=date('t',time());//获取这个月的总天数

        $arr=array();
        for ($i=1; $i <= $dates; $i++) {
            $arr[]=$i;
        }
        $arr1=json_encode($arr);
        return $arr1;
    }


    //获取本月每天的数据发布量
    public function countdata(){
        $nian=date('Y',time());
        $yue=date('m',time());
        $data1=Db::query("select year(dates) as year,month(dates) as month,day(dates) as days,count(case day(dates) when {$yue} then dates else 0 end) as total from spzg_article where year(dates) = {$nian} and month(dates)={$yue} group by day(dates)");
        $dates=date('t',time());//获取这个月的总天数
        $arr=array();
        for ($i=1; $i <= $dates; $i++) {
            $arr[]=0;
        }
        for ($k=0; $k < count($data1) ; $k++) {
            $mmc=$data1[$k]['days']-1;
            $arr[$mmc]=intval($data1[$k]['total']);
        }
        $arr1=json_encode($arr);
        return $arr1;
    }


    //获取本月每天的点击量
    public function countclick(){
        $nian=date('Y',time());
        $yue=date('m',time());
        $data1=Db::query("select year(dates) as year,month(dates) as month,day(dates) as days,count(case day(dates) when {$yue} then dates else 0 end) as total from spzg_clicks where year(dates) = {$nian} and month(dates)={$yue} group by day(dates)");
        $dates=date('t',time());//获取这个月的总天数

        $arr=array();
        for ($i=1; $i <= $dates; $i++) {
            $arr[]=0;
        }

        for ($k=0; $k < count($data1) ; $k++) {
            $mmc=$data1[$k]['days']-1;
            $arr[$mmc]=intval($data1[$k]['total']);
        }


        $arr1=json_encode($arr);

        return $arr1;
    }


    //获取本月每天的访问量
    public function countvisit(){
        $nian=date('Y',time());
        $yue=date('m',time());
        $data1=Db::query("select year(dates) as year,month(dates) as month,day(dates) as days,count(case day(dates) when {$yue} then dates else 0 end) as total from spzg_censdetail where year(dates) = {$nian} and month(dates)={$yue} group by day(dates)");
        $dates=date('t',time());//获取这个月的总天数
        $arr=array();
        for ($i=1; $i <= $dates; $i++) {
            $arr[]=0;
        }

        for ($k=0; $k < count($data1) ; $k++) {
            $mmc=$data1[$k]['days']-1;
            $arr[$mmc]=intval($data1[$k]['total']);
        }
        $arr1=json_encode($arr);

        return $arr1;
    }
}