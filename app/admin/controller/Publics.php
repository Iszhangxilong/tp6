<?php
namespace app\admin\controller;

use app\BaseController;
use think\App;
use think\facade\Request;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use app\admin\model\MenuModel;

class Publics extends BaseController{

	protected $request,$id,$pid,$action,$cid,$aid,$yid,$fid,$tid,$parentid,$kwords,$page,$fun;

	public function __construct(App $app,Request $request){

		parent::__construct($app,$request);
		//搜索关键字
        $this->kwords = get_str((trim($this->request->param('kwords'))));
        $this->kwords = urlencode($this->kwords);
        View::assign("kwords",$this->kwords);

        //获取方法
        $this->action = get_str($this->request->param('action'));
        View::assign("action",$this->action);

        //接收参数id
        $this->id  = get_int($this->request->param('id'));
        $this->pid = get_int($this->request->param('pid'));
        $this->cid = get_int($this->request->param('cid'));
        $this->aid = get_int($this->request->param('aid'));
        $this->yid = get_int($this->request->param('yid'));
        $this->tid = get_int($this->request->param('tid'));
        View::assign(['id'=>$this->id,'pid'=>$this->pid,'cid'=>$this->cid,'aid'=>$this->aid,'tid'=>$this->tid]);

        //接收分页码
        $this->page = get_int($this->request->param('page'));
        $this->page = $this->page == 0 ? 1 : $this->page;
        View::assign("page",$this->page);

        //栏目功能模板
        $this->fid = get_int($this->request->param('fun'));
        $this->fun = Db::name('fun')->where('id',$this->fid)->value('fun');
        View::assign("fid",$this->fid);
        /**
         * @param   $logintime      //上次登陆时间
         * @param   $adminname      //登陆用户名
         * @param   $groupname      //用户组
         */
        $logintime = Session::get('logintime');
        $adminname = Session::get('adminname');
        $groupname = Session::get('groupname');
        View::assign(['logintime'=>$logintime,'adminname'=>base64_decode($adminname,true),'groupname'=>$groupname]);

        //系统设置
        $getSystemInit = Db::name('init')->where('id',1)->find();
        View::assign('getSystemInit',$getSystemInit);

        if ($this->yid) {

            $menu = new MenuModel();
            $menud = $menu->menud($this->yid);
            if (!$menud->isEmpty()) {
                if(!$menud[0]['chid']->isEmpty()){
                    $onechid = $menud[0]['chid'][0];
                }else{
                    $onechid = $menud[0];
                }
                View::assign("onechid",$onechid);
            }
            View::assign('menud',$menud);
        }

	}
}