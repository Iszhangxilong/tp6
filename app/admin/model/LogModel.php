<?php
namespace app\admin\model;

use think\Model;
use think\facade\Session;
use think\facade\Db;
class LogModel extends Model{

    static function setLog($content,$state,$ctype,$c_name = '登录后台'){

		$data["content"]=$content;
		$data["state"]=$state;
		$data['colname'] = $c_name;
		$data["admin"]=session('adminname');
        $data['admin']=base64_decode($data['admin'],true);
		$ip = clientIP();
		$data['city'] = getCity($ip)['city'];
		$data['ip'] = $ip;
		$data["times"]=date("Y-m-d H:i:s");
		$data['ctype']=$ctype;
		Db::name('log')->insert($data);
		
	}

	/**
	 * @param   get_log_list    获取日志
     * @param   $type   日志类型
	 */
	public function get_log_list($type,$page,$num){

	    //总条数
        $total = $this->log->where("ctype",$type)->count();
        //总页数
        $tot=ceil($total/$num);
        //实例化分页类
        $fpage=new PageOrg($total,$page,$num);
        $pageInfo=$fpage->getPageInfo();
        //查询数据
        $datalist = $this->log->where("ctype",$type)->order("id desc")->limit($pageInfo["row_offset"],$pageInfo["row_num"])->select();
        $getpage = $fpage->getpage($page,url('/Log/index/','',''),"&type=".$type);

        return ['list'=>$datalist,'total'=>$total,'tot'=>$tot,'getpage'=>$getpage];
    }

    //单删除
    public function log_del($id){

	    if(request()->isAjax())
	    {
            $res = $this->log->where('id',$id)->delete();
            if($res){
                return true;
            }else{
                return false;
            }
        }
    }
    //多删除（选中删除）
    public function log_check_del($del,$type){

	    if(request()->isPost())
	    {
            if(empty($del))
                return false;
            $res = $this->log->where('id','in',$del)->delete();
            if($res){
                header('Location:'.url('index?state=1&type='.$type));
                exit;
            }else{
                header('Location:'.url('index?state=2&type='.$type));
                exit;
            }
        }
    }
}
