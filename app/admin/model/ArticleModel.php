<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use think\facade\Db;
use think\facade\Config;
use AdminLib\PageOrg;
use app\admin\model\LogModel;
use think\model\concern\SoftDelete;
class ArticleModel extends Model{

    use SoftDelete;
    protected $name = "article";       //表名称
    //定义处理字段
    protected $intType = [
        'id',
        'parentid',
        'num',
        'home',
        'ishome'
    ];
    protected $stringType = [
        'title',
        'kwords',
        'content'
    ];
    //软删除字段
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;

    //获取数据列表
    public function newslist(int $id , string $kwords , string $field , int $num , int $fun , int $page):array
    {
        //参数绑定
        $param = "?id=".$id.'&fun='.$fun;
        //条件数组
        $where = [
            'parentid'  =>  $id,
        ];
        //判断生成跳转参数
        if (!empty($kwords)) {
            $where['kwords|title'] = ['like','%'.$kwords.'%'];
            $param .= "&kwords=".$kwords;
        }
        //文章总数量
        $total = $this->where($where)->count();
        //总页数
        $tot      = ceil($total/$num);
        $fpage    = new PageOrg($total,$page,$num);
        $pageInfo = $fpage->getPageInfo();
        $datalist = $this->where($where)->field($field)->order("ishome desc,num asc,id desc")->limit($pageInfo["row_offset"],$pageInfo["row_num"])->select();
        $getpage=$fpage->getpage($page,url('/Newslist/menuindex/'),$param."");

        return ['list'=>$datalist,'total'=>$total,'tot'=>$tot,'getpage'=>$getpage];
    }

    //获取数据详情
    public function detail(int $id , string $field = '*')
    {
        $data = $this->where('id',$id)->field($field)->find();
        return $data;
    }

    //数据添加
    public function addData(string $c_name):int
    {
        $dd = input('post.');
        //类型处理
        $dd = handArray($dd , $this->intType , $this->stringType);
        $dd['admin'] = base64_decode(session('adminname'),true);
        $result = $this->save($dd);
        //获取主键自增id
        $insid = $this->id;
        if ($result) {
            LogModel::setLog("添加文章：”{$dd['title']}“，id：{$insid}",'添加',1,$c_name);
            return true;
        }else{
            return false;
        }

    }
    //数据修改
    public function editData(int $id , string $c_name):int
    {
        $dd = input('post.');
        $dd = handArray($dd , $this->intType , $this->stringType);
        $dd['admin']  = base64_decode(session('adminname'),true);
        $dd['home']   = empty($dd['home'])  ?  0  :  $dd['home'];
        $dd['ishome'] = empty($dd['ishome'])?  0  :  $dd['ishome'];
        $result = $this->where('id',$id)->save($dd);
        if($result) {
            LogModel::setLog("修改文章：”{$dd['title']}“，id：{$id}",'修改',1,$c_name);
            return true;
        }else{
            return false;
        }
    }
    //数据删除
    public function del($ids)
    {
        $res = $this->destroy($ids);
        return $res;
    }

}