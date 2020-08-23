<?php
namespace app\admin\model;
use think\Model;
use think\facade\Db;
use think\facade\Session;
class RuleModel extends Model{

    protected $name = "rule";
  	/**
  	*  新增路由标识
  	*  @param [str] $rute  路由标识 
  	*  @param [str] $link  实际地址
  	*  @return [int] 1标识已经存在 2标识为空 不添加到缓存 200添加成功
  	**/
  	public function AddRute($rute,$link){

    		if($rute!=''){

            $array = self::GetCache();

  	        $isin = array_key_exists($rute,$array);

  	        if($isin == true){

  	        	  return 1;
  	        }

  	        $array[$rute] = $link;

  	        self::UpdateCache($array);

  	        return 200;
    		}

  		  return 2;
  	}

  	/**
  	*  修改路由标识
  	*  @param [str] $rute 变更的路由标识
  	*  @param [str] $oldrute 之前的标识
  	*  @param [str] $link 实际对应的地址
  	*  @return [int] 2标识都为空 1标识以呗其它信息使用 
  	**/
	public function EditRute($rute,$oldrute,$link){


		$array = self::GetCache();	//获取缓存的路由


		if($rute=='' && $oldrute==''){  //新旧标识都为空，不进行任何操作

			return 2;

		}elseif($rute=='' && $oldrute!=''){ //新的标识为空 跟新删除掉就得标识

			unset($array[$oldrute]); //注意unset是没有返回值的

			self::UpdateCache($array);

			return 200;

		}elseif($rute!='' && $oldrute==''){ //旧的标识为空 跟新新的标识到缓存

			$isin = array_key_exists($rute,$array); //判断新的标识是否重名

			if($isin==true){

				return 3;

			}else{
			    foreach($array as $k=>$v){
			        if($v == $link){
			            $array[$rute] = $array[$k];
			            unset($array[$k]);
                    }
                }

				$array[$rute] = $link;

				self::UpdateCache($array);

				return 200;
			}

		}elseif($rute!='' && $oldrute!=''){ //新旧标识都不为空 判断跟新

			if($rute == $oldrute){ //新旧标识相等 不做处理

				$isin = array_key_exists($oldrute,$array);

				if($array[$oldrute] == $link && $isin==true){
					return 200; //旧索引对应的值是否存在 存在不做操作了
				}else{
					$array[$rute] = $link;

					self::UpdateCache($array);

					return 200;
				}
//				if($isin==true){
//
//					return 200; //旧索引对应的值是否存在 存在不做操作了
//
//				}else{  //旧索引对应的值不存在 旧跟新缓存
//
//					$array[$rute] = $link;
//
//					self::UpdateCache($array);
//
//					return 200;
//				}

			}else{  //不相等
                    
				$isin = array_key_exists($rute,$array);

				if($isin==true){

                    return 1;

				}else{  //不重名

					unset($array[$oldrute]);

                    $array[$rute] = $link;

                    self::UpdateCache($array);

                    return 200;
				}
			}

		}

	}

  	/**
  	*  删除单个路由
  	*  @param [str] $rute 要删除的路由
  	*  @return [int] 200
  	**/
    public function AjaxDelRute($rute){

      	if($rute!=''){

        		$array = self::GetCache();
    	    	unset($array[$rute]);

    	    	self::UpdateCache($array);

      	}

      	return 200;

    }

    /**
  	*  删除单个路由
  	*  @param [array] $arr 要删除的路由 索引数组
  	*  @return [int] 200
  	**/
    public function DelRute($arr){

      	$array = self::GetCache();

      	for ($i=0; $i < count($arr) ; $i++) { 
        		if($arr[$i]['rute_mark']!=''){
                    
                unset($array[$arr[$i]['rute_mark']]);
        		}
      	}

      	self::UpdateCache($array);

      	return 200;

    }


    /**
    *  获取路由缓存信息
    *  @return [array] 关联数组 
    **/
    private function GetCache(){

    	  $res = Db::name('rule')->where('cnames=:cc',['cc'=>'columns'])->value('data');

        return  json_decode($res,true);
    }

    /**
    *  跟新路由缓存信息
    *  @param [array]$data 关联数组
    *  @return [int] 默认1 
    **/
    private function UpdateCache($data){
        
        //$dd['dateline'] = time();
        $dd['data'] = json_encode($data); 

        $res = Db::name('rule')->where('cnames=:cc',['cc'=>'columns'])->save($dd);

        return 1;

    }

}
