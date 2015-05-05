#!/usr/bin/php -q
<?php
/**
 * 测试业务现场数据的IP正确率
 */
include(dirname(__FILE__)."/fun/common.php");
/*{{{madhouse IP中为广州的C网段数组*/
$madhouseIps=file(dirname(__FILE__)."/madhouse_ip_guangzhou.csv");
foreach($madhouseIps as $line0) {
    list($madhouseIpStart,$province,$carrier,$citycode)=explode(',',$line0);
    $madhouseIpStart=str_replace("\"","",$madhouseIpStart);
    $MadhouseGuangZhouIps[]=$madhouseIpStart;
}
$MadhouseGuangZhouIps=array_unique($MadhouseGuangZhouIps);
unset($madhouseIps);
$total_madhouse_guangzhou=sizeof($MadhouseGuangZhouIps);
echo "Madhouse IP库中广州的IPC网段有：{$total_madhouse_guangzhou}个\n";
/*}}}*/

$test_file=file(dirname(__FILE__)."/nuohua-VE-APP5.2-IP.csv");
$test_file=array_slice($test_file, 2, -1); // 验证所有样例中广州IP是否在MADHOUSE的IP库中也为广州
foreach($test_file as $line){
    list($adpositionId, $adpositionName, $testIp, $districtId, $districtName, $numImps)=explode(',', $line);
    if (strstr($adpositionName,'广州')) {
        $ipc=netRange($testIp,25);
        if (in_array($ipc,$MadhouseGuangZhouIps)) {
            $Y++;
            echo "检测到样例中的广州IP{$testIp} C网段：{$ipc}, 是否在MADHOUSE的IP库中存在：Y\n";
        } else {
            $N++;
            echo "检测到样例中的广州IP{$testIp} C网段：{$ipc}, 是否在MADHOUSE的IP库中存在：N\n";
        }
    }
}
echo "total:".sizeof($test_file)." Y:{$Y} N:{$N}\n";
?>
