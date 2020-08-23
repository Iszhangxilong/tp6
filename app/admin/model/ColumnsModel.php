<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use think\facade\Db;
use app\admin\model\LogModel;
use app\admin\model\RuleModel;
use think\model\concern\SoftDelete;
class ColumnsModel extends Model{
    use SoftDelete;
    //软删除字段
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    protected $intType = ['parent_id' , 'ishome' , 'ishow' , 'isfoot' , 'fun' , 'num'];

    protected $stringType = ['c_names' , 'd_names' , 'e_names' , 'tpl'];

    protected $name = "columns";

    protected $select;

    //  递归获取栏目
    public function menud(int $ishow , int $id , int $len=0 , int $lv=2 , string $field='*')
    {
        $where = [
            'parent_id' => $id,
            'ishow'     => $ishow,
            'ishome'    => 1
            ];
        $left = $this->where($where)->order('num asc,id asc')->field($field)->select();
        $left = $left->all();
        for ($i=0; $i < count($left) ; $i++) {
            $left[$i]['fun'] = Db::name('fun')->where('id','=',$left[$i]['fun'])->value('fun');
            $sub = $this->menud($ishow,$left[$i]['id'],$len+1,$lv,$field);
            $left[$i]['chid']=$sub;
        }

        return $left;
    }

    //获取左侧栏目列表
    public function leftColumn($id){
        $data = $this->where('parent_id=:pid and datatype=:dp',['pid'=>$id,'dp'=>1])->order('num asc,id asc,dates asc')->select();

        foreach($data as $k=>$v){
            $data[$k]->chid = $this->leftColumn($v->id);
        }

        return $data;
    }

    //单篇修改
    public function editSingle($id) {
        $dd = input('post.');
        $result = $this->where('id',$id)->save($dd);
        if($result) {
            return true;
        }else{
            return false;
        }
    }

    //添加修改栏目时的下拉列表
    public function see1(int $pid , int $tid , int $len):string {
        if($pid==0){
            $this->select="<select name='parentid' data-placeholder='请选择' id='gsbm' class='chosen-select form-control'>";
        }
        $menu = $this->where('parent_id','=',$pid)->where('datatype','=',1)->field('id,parent_id,parentpath,c_names,fun,tpl')->order('num asc,id asc')->select();
        foreach($menu as $vr) {
            $this->select.="<option value='".$vr['id']."'";
            if($vr['id']==$tid){
                $this->select.="selected='selected'";
            }
            if($len==0){
                $this->select.=">|--".$vr['c_names']."</option>";
            }else{
                $this->select.=">";
                for ($i=0; $i < $len ; $i++) {
                    $this->select.="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $this->select.="|--".$vr['c_names']."</option>";
            }
            $this->see1($vr['id'],$tid,$len+1);
        }
        if($pid==0){
            $this->select.="</select>";
        }
        return $this->select;

    }

    //菜单列表
    public function menulist( int $pid , int $len , int $padding):string
    {

        $ji=$len+1;

        if($pid==0){
            $this->list="<div class='head'><ul><li style='width: 20%;'>栏目名称</li><li style='width: 10%;'>内容模型</li><li style='width: 10%;'>模板</li><li style='width: 10%;'>栏目分类</li><li style='width: 5%;'>排序</li><li style='width: 10%;'>是否显示</li><li style='width: 10%;'>是否显示导航</li><li style='width: 10%;'>是否显示底部</li><li style='width: 15%;'>操作</li></ul></div>"; //表头
            $this->list.="<div class='body'><div class='list-col'>";
        }

        $data = $this->where('parent_id','=',$pid)->where('datatype','=',1)->field('id,parent_id,c_names,num,ishow,webs,parentpath,fun,ishome,isfoot,tpl')->order('num asc,id asc')->select(); //查询栏目

        foreach($data as $k=>$v){
            $fun = Db::name('fun')->where('id','=',$v['fun'])->value('fun');
            $pathcount=count(@explode(",",$v["parentpath"]));
            if($pathcount==1)
            {
                $this->list.="<div class='table-list'><ul class='column-1'>";

                $this->list .= "<li style='width: 20%;'><i class='icon icon-caret-right' ></i><a href='" . url('/Column/edit?tid=' . $v['id']) . "'><font style='color:#90" . $ji . "'>" . $ji . "级、</font>" . $v['c_names'] . "</font></a></li>";
            }else{
                $this->list.="<div class='table-list'><ul class='column-".$pathcount."'>";
                $this->list.="<li style='width: 20%;padding-left:".$padding."px;'><i class='icon icon-caret-right' ></i><a href='".url('/Column/edit?tid='.$v['id'])."'><font style='color:#90".$ji."'>".$ji."级、</font>".$v['c_names']."</a></li>";
            }
            $this->list.="<li style='width: 10%;'>".$fun."</li>";
            $this->list.="<li style='width: 10%;'>".$v['tpl']."</li>";

            if($v['parent_id'] == 0){
                $this->list.="<li style='width: 10%;'>父级栏目</li>";
            }else{
                $this->list.="<li style='width: 10%;'>普通栏目</li>";
            }
            //$this->list.="<li style='width: 5%;'><input type='text' class='form-control input-sm' style='width: 60px' value='".$v['num']."'></li>";
            $this->list.="<li style='width: 5%;'>".$v['num']."</li>";
            if($v['ishome']){
                $this->list.="<li style='width: 10%;cursor:pointer;color:green;' ondblclick='dbclick({$v['id']},this);' wow='ishome'>显示</li>";
            }else{
                $this->list.="<li style='width: 10%;cursor:pointer;color:red;' ondblclick='dbclick({$v['id']},this);' wow='ishome'>不显示</li>";
            }
            if($v['ishow']==1){
                $this->list.="<li style='width: 10%;cursor:pointer;color:green;' ondblclick='dbclick({$v['id']},this);' wow='ishow'>显示</li>";

            }else{
                $this->list.="<li style='width: 10%;cursor:pointer;color:red;' ondblclick='dbclick({$v['id']},this);' wow='ishow'>不显示</li>";
            }
            if($v['isfoot']==1){
                $this->list.="<li style='width: 10%;cursor:pointer;color:green;' ondblclick='dbclick({$v['id']},this);' wow='isfoot'>显示</li>";
            }else{
                $this->list.="<li style='width: 10%;cursor:pointer;color:red;' ondblclick='dbclick({$v['id']},this);' wow='isfoot'>不显示</li>";
            }

            $this->list.="<li style='width: 15%;cursor:pointer;'><a href='".url('/Column/edit?tid='.$v['id'])."'><i class='icon icon-edit' style='color: #0183d6'></i>修改</a><span class='line'>|</span><a href='javascript:;' Onclick='return menudel(this,{$v['id']});' style='color:red'><i class='icon icon-trash' style='color:red'></i>删除</a>";

            if($pathcount<3){

                $this->list .= "<span class='line'>|</span><a href='" . url('addmenu?tid=' . $v['id']) . "'><i class=\"icon icon-plus\" style='color: #0183d6'></i>添加子级</a>";

            }
            $this->list.="</li></ul>";
            if($pathcount==4){//这个可以用来显示到几级
                $this->list.="<div class='table-list' style='display: none;'>";

            }
            self::menulist($v['id'],$len+1,$padding+20);

            $this->list.="</div>";
        }
        if($pid==0){
            $this->list.="</div></div>"; //表头
        }
        return $this->list;
    }

    //添加修改栏目时的下拉列表
    public function see( int $pid , int $tid, int $len):string
    {

        if($pid==0){
            $this->select="<select name='parent_id' data-placeholder='请选择' id='' class='chosen-select form-control'>";
            $this->select.="<option value='0'>顶级栏目</option>";
        }
        $val = $this->where('parent_id','=',$pid)->where('datatype','=',1)->field('id,parent_id,parentpath,c_names,fun,tpl')->order('num asc,id asc')->select();
        foreach($val as $vr){
            $this->select.="<option value='".$vr['id']."'";
            if($vr['id']==$tid){
                $this->select.="selected='selected'";
            }
            if($len==0){
                $this->select.=">|--".$vr['c_names']."</option>";
            }else{
                $this->select.=">";
                for ($i=0; $i < $len ; $i++) {
                    $this->select.="&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $this->select.="|--".$vr['c_names']."</option>";
            }
            self::see($vr['id'],$tid,$len+1);
        }
        if($pid==0){
            $this->select.="</select>";
        }

        return $this->select;
    }

    //栏目详情
    public function detail(int $id , string $field = '*')
    {
        $data = $this->where('id',$id)->field($field)->find();
        return $data;
    }

    //栏目添加
    public function addMenu()
    {
        $dd = input('post.');
        //类型处理
        $dd = handArray($dd,$this->intType,$this->stringType);
        $dd['admin'] = base64_decode(session('adminname'),true);
        $link   = $dd['link'];
        $route  = $dd['route'];
        $tpl    = $dd['tpl'];
        if ($dd['parent_id']) {
            $part = $this->where('id','=',$dd['parent_id'])->field('parentpath')->find();
            $path=$part['parentpath'].',|'.$dd['parent_id'].'|';
        }else{
            $path='|0|';
        }
        $dd['parentpath'] = $path;
        $lv=@explode(',',$part['parentpath']); //限制菜单 只能添加到三级
        if (count($lv) < 4) {
            $result = $this->save($dd);
            $insid = $this->id;
            if( $result ) {
                LogModel::setLog("添加栏目：“{$dd['c_names']}”，id：".$insid,"添加",2);
                //给超级管理员赋予权限
                $this->giveAuth($insid);
                //设置路由
                if($link !== "" && $route !== "") {
                    $link = htmlspecialchars_decode($link);
                    (new RuleModel())->AddRute($route,$link);
                }elseif($route !== "" && $link == ""){
                    (new RuleModel())->AddRute($route,'index/'.$tpl . '/index?id=' . $insid);
                }elseif($route == "" && $link !== ""){
                    (new RuleModel())->AddRute($tpl,$link);
                }else{
                    (new RuleModel())->AddRute($route,$link);
                }
                $this->urlView(1,'add',$dd['parent_id']);
            }else{
                $this->urlView(2,'add',$dd['parent_id']);
            }

        }else{
            $this->urlView(3,'add',$dd['parent_id']);
        }
    }

    //公共跳转
    public function urlView (int $state , string $action , int $tid)
    {
        if($action == 'add') {
            header('Location:'.url('/Column/add',['tid'=>$tid,'state'=>$state]));
        }else if($action == 'edit'){
            header('Location:'.url('/Column/add',['state'=>$state]));
        }else{
            header('Location:'.url('/Column/add',['state'=>$state]));
        }
        exit;
    }

    //赋权限
    public function giveAuth( int $insid )
    {
        $auth = Db::name('auth_group')->where('id','=',1)->find();
        $role=$auth['columnrole'];
        if($role){
            $role=$role.','.$insid;
        }else{
            $role=$insid;
        }
        $result = Db::name('auth_group')->where('id',1)->update(['columnrole'=>$role]);
        if ($result) {
            return true;
        }else{
            return false;
        }
    }

}
