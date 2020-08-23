<?php
namespace app\admin\controller;

use think\App;
use think\facade\Request;
use think\facade\Db;
use think\facade\View;
use app\admin\model\ColumnsModel;
use app\admin\controller\IsLogin;
class Column extends IsLogin {

    protected $state,$condit,$decode;
    public function __construct(App $app, Request $request)
    {
        parent::__construct($app, $request);
        $this->state  = get_int(request()->param('state'));
        $this->condit = get_int(request()->param('condit'));
        $this->decode = get_int(request()->param('decode'));
        View::assign(['state'=>$this->state,'condit'=>$this->condit,'decode'=>$this->decode]);
    }

    public function  menu()
    {
        $menulist = (new ColumnsModel())->menulist(0,0,15);
        View::assign('menulist',$menulist);
        return view();
    }
    //添加栏目
    public function add()
    {
        $column = new ColumnsModel();
        $select = $this->tid   ?   $column->see(0,$this->tid,0)   :   $column->see(0,0,0);
        //模型
        $fun = Db::name('fun')->where(['status'=>1])->order('id desc')->select();
        View::assign(['select'=>$select,'fun'=>$fun]);

        if ($this->action == 'add') {
            if(request()->isPost()) {
                $result = $column->addMenu();
            }else{
                header('Location:'.url('/Column/add?state=4'));
                exit;
            }
        }

        return view();
    }
    //修改栏目
    public function edit()
    {
        //模型
        $fun = Db::name('fun')->where(['status'=>1])->order('id desc')->select();
        $column = new ColumnsModel();
        $data = $column->detail($this->tid,'id,parent_id,c_names,d_names,e_names,link,route,num,fun,tpl,ishome,webs,ishow,photo,content,dates,admin,isfoot,photo1,photo2,seotitle,seokeywords,seodescription');

        $select = $column->see($pid=0,$data['parent_id'],0);//获取菜单下拉列表
        View::assign(['fun'=>$fun,'data'=>$data,'content'=>htmlspecialchars(stripslashes($data["content"])),'select'=>$select]);
        return view();
    }

    //显示模板
    public function showtpl()
    {
        $fid = get_int(input('post.fid'));
        $fun = Db::name('fun')->where('id','=',$fid)->find();
        if($fid){
            $data = Db::name('tpl')->where(["fid"=>$fid,'status'=>1])->field("id,title")->select();
            if($data){
                $rhtml = '';
                $html="[";
                foreach($data as $v){
                    $rhtml.="{\"id\":\"".$v["id"]."\",\"title\":\"".$v["title"]."\",\"fun\":\"".$fun["fun"]."\"},";
                }
                $html.=rtrim($rhtml,",");
                $html.="]";
            }else{
                $html="[{\"id\":\"0\"}]";
            }
            echo $html;
        }
    }

}