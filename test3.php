#!/usr/bin/php -q
<?php
/**
 * 测试业务现场数据的IP正确率（样例中佛山的IP是否对应madhouse的IP库中的佛山IP)
 */
include(dirname(__FILE__)."/fun/common.php");
/*{{{madhouse IP中为佛山的C网段数组*/
$madhouseIps=file(dirname(__FILE__)."/madhouse_ip_foshan.csv");
foreach($madhouseIps as $line0) {
    list($madhouseIpStart,$province,$carrier,$citycode)=explode(',',$line0);
    $madhouseIpStart=str_replace("\"","",$madhouseIpStart);
    $MadhouseGuangFoShanIps[]=$madhouseIpStart;
}
$MadhouseGuangFoShanIps=array_unique($MadhouseGuangFoShanIps);
unset($madhouseIps);
$total_madhouse_guangzhou=sizeof($MadhouseGuangFoShanIps);
echo "Madhouse IP库中佛山的IPC网段有：{$total_madhouse_guangzhou}个\n";
/*}}}*/

/*{{{载入IPB小组的地域数据*/
$ipbIps=file(dirname(__FILE__)."/other/ipb_district.csv");
foreach($ipbIps as $line){
    list($provinceName,$cityName,$districtId)=explode(',',$line);
    $districtId=str_replace(array("\n","\r","\r\n"),"",$districtId);
    $Ipb_dist_dic[$districtId]['provinceName']=$provinceName;
    $Ipb_dist_dic[$districtId]['cityName']=$cityName;
}
/*}}}*/

$test_file=file(dirname(__FILE__)."/nuohua-VE-APP5.2-IP.csv");
$test_file=array_slice($test_file, 2, -1); // 验证所有样例中佛山IP是否在MADHOUSE的IP库中也为佛山
foreach($test_file as $line){
    list($adpositionId, $adpositionName, $testIp, $districtId, $districtName, $numImps)=explode(',', $line);
    if ( in_array($districtId,array_keys($Ipb_dist_dic)) ) {
        if ( $Ipb_dist_dic[$districtId]['cityName']=='佛山市' ) {
            $ipc=netRange($testIp,25);
            echo $line;
            if (in_array($ipc,$MadhouseGuangFoShanIps)) {
                $Y++;
                echo "检测到样例中的佛山IP{$testIp} C网段：{$ipc}, 是否在MADHOUSE的IP库中存在：Y\n";
            } else {
                $N++;
                echo "检测到样例中的佛山IP{$testIp} C网段：{$ipc}, 是否在MADHOUSE的IP库中存在：N\n";
            }
        }
    }
}
echo "total:".sizeof($test_file)." Y:{$Y} N:{$N}\n";
?>
