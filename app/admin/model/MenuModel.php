<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use think\facade\Db;
use think\facade\Config;

class MenuModel extends Model{

    protected $name = "menu";       //表名称
    //获取顶级菜单下面的子菜单
    public function menud(int $yid)
    {

        $data = $this->where(['parent_id'=>$yid,'ishow'=>1])->order('num asc,id asc')->field('id,parent_id,p_names,model,webs')->select();
        foreach ($data as $k=>$v) {
            $data[$k]['chid'] = $this->menud($v['id']);
        }
        return $data;
    }
    //菜单列表
    public function menulist( int $pid , int $len , string $padding):string
    {

        if($pid==0){
            $this->list="<div class='head'><ul><li style='width: 35%;'>菜单名称</li><li style='width: 15%;'>模型</li><li style='width: 10%;'>排序</li><li style='width: 10%;'>是否显示</li><li style='width: 10%;'>权限标示</li><li style='width: 20%;'>操作</li></ul></div>"; //表头
            $this->list.="<div class='body'><div class='list-col'>";
        }

        $data=$menu->where('parent_id','=',$pid)->field('id,parent_id,p_names,model,num,ishow,juris,webs,path')->order('num asc,id asc')->select(); //查询栏目

        foreach($data as $k=>$v){
            $pathcount=count(@explode(",",$v["path"]));
            if($pathcount==1){
                $this->list.="<div class='table-list'><ul class='column-1'>";
                $this->list.="<li style='width: 35%;'><i class='icon icon-caret-right' ></i><a href='".url('editmenu?id='.$v['id'])."'>".$v['p_names']."</a></li>";
            }else{
                $this->list.="<div class='table-list' ><ul class='column-".$pathcount."'>";
                $this->list.="<li style='width: 35%;padding-left:".$padding."px;'><i class='icon icon-caret-right' ></i><a href='".url('editmenu?id='.$v['id'])."'>".$v['p_names']."</a></li>";
            }
            $this->list.="<li style='width: 15%;'>".$v['model']."</li>";
            $this->list.="<li style='width: 10%;'><input type='text' class='form-control input-sm' style='width: 60px' value='".$v['num']."'></li>";
            if($v['ishow']==1){
                $this->list.="<li style='width: 10%;'>显示</li>";
            }else{
                $this->list.="<li style='width: 10%;'>隐藏</li>";
            }
            $this->list.="<li style='width: 10%;'>".$v['juris']."</li>";
            $this->list.="<li style='width: 20%;'><a href='".url('editmenu?id='.$v['id'])."'><i class='icon icon-edit' style='color: #0183d6'></i>修改</a><span class='line'>|</span><a href='#' Onclick='return menudel(this);' wow='".$v['id']."' style='color:red'><i class='icon icon-trash' style='color:red'></i>删除</a>";
            if($pathcount<3){
                //$this->list.="<span class='line'>|</span><a href='#'>添加下级菜单</a>";
            }
            $this->list.="</li></ul>";
            if($pathcount==3){//这个可以用来显示到几级
                $this->list.="<div class='table-list' style='display: none;'>";
            }
            self::menulist($v['id'],$len+1,$padding+20);

            if($pathcount==3){
                $this->list.="</div>";
            }
            $this->list.="</div>";
        }


        if($pid==0){
            $this->list.="</div></div>"; //表头
        }
        return $this->list;
    }
}