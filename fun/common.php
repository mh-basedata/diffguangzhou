<?php
/**
 *@brief 网段计算函数
 */
function netRange($IP,$mask=24) {
    $classclong=ip2long($IP)&~((1<<(32-$mask))-1);
    return long2ip($classclong);
}
?>

