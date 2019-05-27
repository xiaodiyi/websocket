<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserCommonController extends Controller
{
    //

    public function position($ip)
    {
        $taobaoUrl = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $taobaoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ( $ch,  CURLOPT_NOSIGNAL,true);//支持毫秒级别超时设置
        curl_setopt($ch, CURLOPT_TIMEOUT, 1200);   //1.2秒未获取到信息，视为定位失败
        $myCity = curl_exec($ch);
        curl_close($ch);
        $myCity = json_decode($myCity, true);
        return $myCity;
    }

    public function makeNumber($length)
    {
        $arr = array(1,2,3,4,5,6,7,8,9,0);
        shuffle($arr);
        $number= implode($arr);
        return substr($number,0,$length);
    }

    public function getDistrict($area)
    {
        $districtArr = '';
        $districtArr = explode('/', $area);
        switch (count($districtArr)){
            case '3' : break;
            case '2' : $districtArr[2] = '';break;
            case '1' : $districtArr[1] = '';$districtArr[2] = '';break;
            default  : $districtArr[0] = '';$districtArr[1] = '';$districtArr[2] = '';
        }
        $province = $districtArr[0];
        $city = $districtArr[1];
        $district = $districtArr[2];
        $local = [$province, $city, $district];
        return $local;
    }

    public function getAge($birthday)
    {
            $age = strtotime($birthday);
            if($age === false){
                return false;
            }
            list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age));
            $now = strtotime("now");
            list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now));
            $age = $y2 - $y1;
            if((int)($m2.$d2) < (int)($m1.$d1))
                $age -= 1;
            if($age<0){
                $age = 0;
            }
            return $age;
    }
}
