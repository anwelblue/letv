<?php
namespace Anwelblue\Letv;

/**
 * 乐视tv直播类
 * 
 * @author Anwelblue
 * 
 *
 */
class Live
{
    /**
     * api对接地址
     * 
     * @var string 
     */
    private $url = 'http://api.open.letvcloud.com/live/execute';
    
    /**
     * 用户id
     * 
     * @var string 
     */
    private $userid;
    
    /**
     * 用户密钥
     * 
     * @var string
     */
    private $secretkey;
    
    /**
     * 错误信息
     * 
     * @var array
     */
    private $e;
    
    /**
     * 初始化，用户id，用户密钥
     * 
     * @param string $userid
     * @param string $secretkey
     */
    public function __construct($userid,$secretkey){
        $this->userid = $userid;
        $this->secretkey = $secretkey;
    }
   
   /**
     * 创建活动,返回活动id，否则返回false
     * 
     * @param array $data
     * @return boolean|string
     */
    public function create(array $data = []){
        $data['method'] = 'letv.cloudlive.activity.create';
        $data = $this->makeParams($data);
        $http = new Http();
        $result = $http->post($this->url,$data);
        $result = json_decode($result,true);
        if(isset($result['errCode'])){
            $this->e = $result;
            return false;
        }
        return $result['activityId'];
    }
    
    /**
     * 查询活动，如果输入id则返回指定活动，否则返回全部活动
     * 
     * @param string $id
     * @return array
     */
    public function read($id = ''){
        $data = [
            'activityId' => $id,
            'method' => 'letv.cloudlive.activity.search'
        ];
        $data = $this->makeParams($data);
        $http = new Http();
        $result = $http->get($this->url,$data);
        $result = json_decode($result,true);
        return $result;
    }
    
    /**
     * 更新活动
     * 
     * @param string $id
     * @param array $data
     * @return boolean
     */
    public function update($id,$data = []){
        $data['activityId'] = $id;
        $data['method'] = 'letv.cloudlive.activity.modify';
        $data = $this->makeParams($data);
        $http = new Http();
        $result = $http->post($this->url,$data);
        $result = json_decode($result,true);
        if(is_null($result))return true;
        $this->e = $result;
        return false;
    }
    
    /**
     * 上传封面图片
     * 
     * @param string $id 
     * @param string $image
     * @return boolean|string
     */
    public function image($id, $image){
        $data = [
            'activityId' => $id,
            'method' => 'letv.cloudlive.activity.modifyCoverImg'
        ];
        $data = $this->makeParams($data);
        $data['file'] = '@'.$image;
        $http = new Http();
        $result = $http->post($this->url,$data);
        $result = json_decode($result,true);
        if(isset($result['errCode'])){
            $this->e = $result;
            return false;
        }
        return $result['coverImgUrl'];
    }
    
    /**
     * 获取播放地址
     * 
     * @param string $id
     * @return boolean|string
     */
    public function url($id){
        $data = [
            'activityId' => $id,
            'method' => 'letv.cloudlive.activity.playerpage.getUrl'
        ];
        $data = $this->makeParams($data);
        $http = new Http();
        $result = $http->get($this->url,$data);
        $result = json_decode($result,true);
        if(isset($result['errCode'])){
            $this->e = $result;
            return false;
        }
        return $result['playPageUrl'];
    }
    
    /**
     * 传入活动ID，查询录制视频的videoId和videoUnique，其中videoUnique组合成播放地址。
     * 
     * @param string $id
     * @return boolean|array
     */
    public function playInfo($id){
        $data = [
            'activityId' => $id,
            'method' => 'letv.cloudlive.activity.getPlayInfo'
        ];
        $data = $this->makeParams($data);
        $http = new Http();
        $result = $http->get($this->url,$data);
        $result = json_decode($result,true);
        if(isset($result['errCode'])){
            $this->e = $result;
            return false;
        }
        return $result['machineInfo'];
    }
    
    /**
     * 获取推流信息
     * 
     * @param string $id
     * @return boolean|array
     */
    public function pushInfo($id){
        $data = [
            'activityId' => $id,
            'method' => 'letv.cloudlive.activity.getPushUrl'
        ];
        $data = $this->makeParams($data);
        $http = new Http();
        $result = $http->get($this->url,$data);
        $result = json_decode($result,true);
        if(isset($result['errCode'])){
            $this->e = $result;
            return false;
        }
        return $result['lives'];
    }
    
    /**
     * 构建请求参数
     * 
     * @param array $data
     * @return array
     */
    private function makeParams($data){
        $data['userid'] = $this->userid;
        $data['timestamp'] = time()*1000-3000;
        if(! isset($data['ver']))$data['ver'] = '3.0';
        unset($data['sign']);
        ksort($data);
        $str = '';
        foreach($data as $k => $v){
            $str .= $k . $v; 
        }
        $data['sign'] = md5($str.$this->secretkey);
        return $data;
    }
    
    
    /**
     * 获取错误信息
     * @return array
     */
    public function error(){
        return $this->e;
    }
    
}