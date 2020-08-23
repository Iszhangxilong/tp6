<?php
namespace app\admin\controller;

use app\BaseController;
use app\admin\controller\Publics;
use think\App;
use think\facade\Db;
use think\facade\View;
use think\facade\Request;
use app\admin\model\ColumnsModel;
use app\admin\model\ArticleModel;

class Newslist extends Publics
{
    protected $tpl,$state,$condit,$decode,$limit,$field,$c_name;

    public function __construct(App $app, Request $request)
    {
        parent::__construct($app, $request);
        //  模板
        $this->tpl = ColumnsModel::where('id',$this->id)->where('datatype',1)->value('tpl');
        View::assign('tpl',$this->tpl);
        //  返回状态
        $this->state  = get_int($this->request->param('state'));
        $this->condit = get_int($this->request->param('condit'));
        $this->decode = get_int($this->request->param('decode'));
        View::assign(['state'=>$this->state,'condit'=>$this->condit,'decode'=>$this->decode]);
        //  系统设置
        $init = Db::name('init')->where('id',1)->find();
        $this->limit = $init['limit'] == 0  ?   15  :   $init['limit'];
        //指定字段
        $this->field = "id,parentid,title,admin,dates,clicks,photo,num,yid";
        //获取栏目层级
        $menulist = $this->cid ? (new ColumnsModel())->see1(0,$this->cid,0) : (new ColumnsModel())->see1(0,$this->id,0);
        View::assign('menulist',$menulist);
        //栏目名称
        $this->c_name = $this->cid ? (new ColumnsModel())->detail($this->cid,'id,c_names')['c_names'] : (new ColumnsModel())->detail($this->id,'id,c_names')['c_names'];
    }

    public function index()
    {
        $column = new ColumnsModel();
        $leftmenu = $column->leftColumn(0);

        View::assign('leftmenu',$leftmenu);
        return view('index');
    }

    public function menuindex()
    {
        $article = new ArticleModel();
        $view = "";
        //判断模板
        switch ($this->fun) {
            case "单篇":
                $data = ColumnsModel::where('id',$this->id)->field('id,c_names,bodys')->find();
                $view = "single";
                break;
            case "新闻":
                $data = $article->newslist($this->id,$this->kwords,$this->field,$this->limit,$this->fid,$this->page);
                $view = "news";
                break;
        }
        View::assign('data',$data);
        return view($view);
    }
    //修改单篇
    public function modsingle()
    {
        if(request()->isPost()) {
            $column = new ColumnsModel();
            $result = $column->editSingle($this->id);
            if ($result) {
                header('Location:'.url('/Newslist/menuindex',['fun'=>$this->fid,'id'=>$this->id,'state'=>1]));
                exit;
            }else{
                header('Location:'.url('/Newslist/menuindex',['fun'=>$this->fid,'id'=>$this->id,'state'=>2]));
                exit;
            }
        }else{
            header('Location:'.url('/Newslist/menuindex',['fun'=>$this->fid,'id'=>$this->id,'state'=>4]));
            exit;
        }
    }

    //新闻---添加
    public function newsadd()
    {
        View::assign('date',date('Y-m-d'));
        if($this->action == 'add') {
            if($this->request->isPost()) {
                $result = (new ArticleModel())->addData($this->c_name);
                if ($result) {
                    $this->urlView(1,'newsadd');
                }else{
                    $this->urlView(2,'newsadd');
                }
            }else{
                $this->urlView(4,'newsadd');
            }
        }
        return view();
    }
    //新闻修改
    public function newsedit()
    {
        $article = new ArticleModel();
        //详情
        $data = $article->detail($this->id,'id,parentid,title,kwords,dates,num,photo,content,ishome,home,bodys,seotitle,seokeywords,seodescription');
        View::assign('data',$data);
        if ($this->action == 'mod') {
            if($this->request->isPost()) {
                $result = $article->editData($this->id,$this->c_name);
                if($result) {
                    $this->urlView(1,'newsedit','mod');
                }else{
                    $this->urlView(2,'newsedit','mod');
                }
            }else{
                $this->urlView(4,'newsedit');
            }
        }
        return view();
    }

    //公共单删除方法
    public function ajaxdel()
    {
        if ($this->request->isAjax()) {
            $resutl = (new ArticleModel())->del($this->id);
            if ($resutl) {
                return json(1);
            }else{
                return json(2);
            }
        }else{
            return json(3);
        }
    }
    //公共多删除方法
    public function checkdel()
    {
        if ($this->request->isPost()) {
            $ids = $_POST['del'];
            $result = !empty($ids) ? (new ArticleModel())->del($ids) : false;
            if (!$result) {
                $this->urlView(2,'menuindex','del');
            }
            $this->urlView(1,'menuindex','del');

        }else{
            $this->urlView(3,'menuindex','del');
        }
    }

    //公共跳转方法
    public function urlView(int $state , string $action , string $method = 'add')
    {
        if($method == 'add') {
            header('Location:'.url('/Newslist/'.$action,['fun'=>$this->fid,'id'=>$this->id,'state'=>$state]));
        }else if($method == 'mod'){
            header('Location:'.url('/Newslist/'.$action,['fun'=>$this->fid,'cid'=>$this->cid,'id'=>$this->id,'condit'=>$state]));
        }else{
            header('Location:'.url('/Newslist/'.$action,['fun'=>$this->fid,'id'=>$this->id,'decode'=>$state]));
        }
        exit;
    }

}
