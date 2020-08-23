<?php
namespace app\admin\controller;

use app\BaseController;
use think\facade\App;
use think\Request;
use think\facade\View;
use think\facade\Db;
use think\facade\Session;
use think\captcha\facade\Captcha;
use app\admin\model\LogModel;

class Index extends BaseController
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        //网站信息
        $setinfo = Db::name('setting')->where('id',1)->find();

        //登陆设置
        $getSystemInit = Db::name('init')->where('id',1)->find();
        View::assign(['setinfo'=>$setinfo,'getSystemInit'=>$getSystemInit]);
        return view();
    }
    public function login()
    {
        if(request()->isAjax()) {

            //获取当前访问的ip
            $ip = clientIP();
            if($ip == "::1"){
                $ip = '127.0.0.1';
            }
            $result = $this->xianIP($ip);
            if(!$result){
                return json(['msg'=>9,'msbox'=>"对不起，您不能通过此IP访问本后台哦！"]);
            }

            //登陆设置
            $getSystemInit = Db::name('init')->where('id',1)->find();

            //接受参数
            $username = trim($this->request->param('username'));
            $password = trim($this->request->param('password'));

            //判断是否启用验证码,1代表启用，0代表禁用
            if($getSystemInit['mo_captcha_reg'] == 1) {
                $vdcode=trim(strtoupper($this->request->post("vdcode")));    //启用验证码后接收验证码参数

                //验证验证码
                if( !empty($username) && !empty($password) && !empty($vdcode)){
                    //判断验证码
                    if(!captcha_check($vdcode)){
                        //验证码不正确
                        return json(['msg'=>1,'msbox'=>'验证码不正确']);
                    }
                }else{
                    return json(['msg'=>3,'msbox'=>'请填写完整的信息']);
                }
            }else{
                //判断账号与密码是否为空的
                if( empty($username) && empty($password)){
                    return json(['msg'=>3,'msbox'=>'请填写完整的信息']);
                }
            }

            //判断账户是否存在
            $user = Db::name('admin')->where('adminname',$username)->find();
            if(empty($user))
                return json(['msg'=>7,'msbox'=>'账号不存在！请重新输入！']);

            /**
             * 存在该账号
             * 判断账号锁定状态
             * status=0		锁定
             * status=1		正常
             */
            if($user['status'] == 0)
                return json(['msg'=>6,'msbox'=>'该账号已锁定！请联系管理员或开发人员！']);

            //判断密码是否正确
            if($user['password'] !== md5($password)){
                //最大登录次数
                $mo_max_logintime = intval($getSystemInit['mo_max_logintime']);
                if($mo_max_logintime !== 0){
                    //如果有这个ssession判断
                    if(Session::has('mo_max_logintime')){
                        $num = session('mo_max_logintime');
                        if($num < $mo_max_logintime){
                            $num++;
                            session('mo_max_logintime',$num);
                            return json(['msg'=>2,'msbox'=>'密码错误！重新输入！']);
                        }else{
                            //修改账号锁定
                            Db::name('admin')->where(['adminname'=>$username])->update(array('status'=>0));
                            return json(['msg'=>5,'msbox'=>'您已超过登录失败最大次数！账号已锁定！如需解锁请联系超级管理员或开发人员！']);
                        }
                    }else{
                        //没有就添加一个session
                        session('mo_max_logintime',0);
                    }
                }else{
                    return json(['msg'=>2,'msbox'=>'密码错误！重新输入！']);
                }
            }else{
                //存储用户名     base64格式加密用户名
                session('adminname',base64_encode($user['adminname']));
                //存储用户id
                session('uid',base64_encode($user['id']));
                //存储登录上次登录时间
                session('logintime',$user['logintime']);
                //是否登录
                session('loginStatus',1);
                //修改登录时间
                Db::name('admin')->where('id',$user['id'])->update(['logintime'=>date("Y-m-d H:i:s",time())]);
                //操作日志
                LogModel::setLog("登陆成功","登陆",3);
                return json(['msg'=>4,'msbox'=>'登录成功']);

            }



        }else{
            return json(['msg'=>8,'msbox'=>'提交方式不正确']);
        }
    }

    /**
     * @param   xianIp()    查询限制访问的ip
     * @param   $strIP      查询的ip
     * @param   $ipSet      查询访问设置，1代表无限制！2代表启用白名单！3代表启用黑名单
     */
    public function xianIP($strIP){
        $ipSet = Db::name('ip_itype')->where('id',1)->find();
        if($ipSet['itype'] == 1){
            return true;
        }elseif($ipSet['itype'] == 2) {
            //启用白名单
            $bdata = Db::name('ip_bname')->order('id desc,dates desec')->select();
            for ($i = 0; $i < count($bdata); $i++) {
                if (strpos($bdata[$i]['ip'], '-') !== false) {
                    //判断IP区间xx.xx.xx.xx-xx.xx.xx.xx
                    $aa = @explode($bdata[$i]['ip'], '-');
                    $aa1 = get_iplong($aa[0]);
                    $aa2 = get_iplong($aa[1]);
                    $aa3 = get_iplong($strIP);
                    if ($aa3 >= $aa1 && $aa3 <= $aa2) {
                        return true; // 'IP在此范围内';
                    }
                } elseif (strpos($bdata[$i]['ip'], '*') !== false) {
                    //判断ip类型xx.xx.xx.*
                    $t = strrpos($strIP, ".");
                    $sr = substr($strIP, 0, $t);
                    $sr = $sr . ".*";
                    if ($sr == $bdata[$i]) {
                        return true; //把当前ip自后以为转化成*和数据库比较
                    }

                } else {
                    //判断ip类型xx.xx.xx.xx
                    if ($strIP == $bdata[$i]['ip']) {
                        return true; //有匹配项返回true
                    }
                }
            }
            return false;
        }else{
            //限制类型黑名单
            $hdata = Db::name('ip_hname')->order('id desc,dates desc')->field('ip')->select();

            for ($i=0; $i < count($hdata) ; $i++) {
                if(strpos($hdata[$i]['ip'],'-') !== false){
                    //判断IP区间xx.xx.xx.xx-xx.xx.xx.xx
                    $aa=@explode($hdata[$i]['ip'],'-');
                    $aa1= get_iplong($aa[0]);
                    $aa2= get_iplong($aa[1]);
                    $aa3= get_iplong($strIP);
                    if($aa3>=$aa1 && $aa3 <=$aa2){
                        return false; // 'IP在此范围内';
                    }
                }elseif(strpos($hdata[$i]['ip'],'*') !== false){
                    //判断ip类型xx.xx.xx.*
                    $t=strrpos($strIP,".");
                    $sr=substr($strIP,0,$t);
                    $sr=$sr.".*";
                    if($sr==$hdata[$i]){
                        return false; //把当前ip自后以为转化成*和数据库比较
                    }

                }else{
                    //判断ip类型xx.xx.xx.xx
                    if($strIP==$hdata[$i]['ip']){
                        return false; //有匹配项返回true
                    }
                }
            }

            return true;
        }
    }

    /**
     * 将ip地址转换成int型
     * @param $ip  ip地址
     * @return number 返回数值
     */
    public function get_iplong($ip){
        return bindec(decbin(ip2long($ip)));
    }

}
