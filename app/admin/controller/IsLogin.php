<?php
namespace app\admin\controller;

use app\admin\controller\Publics;
use think\App;
use think\facade\Request;
use think\facade\Session;
use AdminLib\Auth;
class IsLogin extends Publics
{
    protected $loginStatus,$current_action,$request;
    public function __construct(App $app, Request $request)
    {
        parent::__construct($app, $request);
        $this->request = $request;
        $this->loginStatus = Session::get('loginStatus');

        //是否登陆
        $this->isLogin();
        //权限
        $auth = new Auth();
        $this->current_action ='admin/'.Request()->controller().'/'.lcfirst(Request()->action());

        $uid = Session::get('uid');
        $uid = base64_decode($uid,true);
        $result = $auth->check($this->current_action,$uid);
        if (!$result) {
            echo "<div style='line-height: 34px;margin:70px 0 0 0;color:#666666;font-family:'微软雅黑',Arial;font-size:15px;'><center><img src='__PUBL/admin/imgs/qx.gif' width='60' height='60' /> 对不起您没有相应权限!请联系管理员.</center></div>";
            die;
        }

    }
    //判断是否登录
    protected function isLogin(){
        if($this->loginStatus != 1){
            exit('<script language="javascript">top.location.href="'.url('/Index/index').'"</script>');
        }
    }
}