<?php
/*
 *测试广州IP，madhouse版和ipb版的差异
 */
//district的id：1156440100
$madhouseIps=file(dirname(__FILE__)."/madhouse_ip_guangzhou.csv");
foreach($madhouseIps as $line0) {
    list($madhouseIpStart,$province,$carrier,$citycode)=explode(',',$line0);
    //echo $madhouseIpStart;
    $madhouseIpStart=str_replace("\"","",$madhouseIpStart);
    $MadhouseGuangZhouIps[]=$madhouseIpStart;
}
$MadhouseGuangZhouIps=array_unique($MadhouseGuangZhouIps);
unset($madhouseIps);
$total_madhouse_guangzhou=sizeof($MadhouseGuangZhouIps);
echo "Madhouse IP库中广州的IPC网段有：{$total_madhouse_guangzhou}个\n";

$search_ip_content=file(dirname(__FILE__)."/superadmin2014_guangzhou.csv");
foreach ($search_ip_content as $line) {
    list($ipStart,$ipEnd,$district_id,$college_id)=explode(',',$line);
    $longIpStart=ip2long($ipStart);
    $longIpEnd=ip2long($ipEnd);
    for($i=$longIpStart;$i<$longIpEnd;$i+=128) {
        if (in_array(long2ip($i),$MadhouseGuangZhouIps)) {
            $y++;
            echo "检查ipb测试IP".long2ip($i)."中是否在madhouse IP中存在:Y\n";
        } else {
            $n++;
            echo "检查ipb测试IP".long2ip($i)."中是否在madhouse IP中存在:N\n";
        }
    }
}
echo "total:{$total_madhouse_guangzhou} 存在的:{$y} 不存在的:{$n}\n";

?>
