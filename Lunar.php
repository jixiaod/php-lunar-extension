<?php

$hex_lunar = array(
    0x04bd8,0x04ae0,0x0a570,0x054d5,0x0d260,0x0d950,0x16554,0x056a0,0x09ad0,0x055d2,
    0x04ae0,0x0a5b6,0x0a4d0,0x0d250,0x1d255,0x0b540,0x0d6a0,0x0ada2,0x095b0,0x14977,
    0x04970,0x0a4b0,0x0b4b5,0x06a50,0x06d40,0x1ab54,0x02b60,0x09570,0x052f2,0x04970,
    0x06566,0x0d4a0,0x0ea50,0x06e95,0x05ad0,0x02b60,0x186e3,0x092e0,0x1c8d7,0x0c950,
    0x0d4a0,0x1d8a6,0x0b550,0x056a0,0x1a5b4,0x025d0,0x092d0,0x0d2b2,0x0a950,0x0b557,
    0x06ca0,0x0b550,0x15355,0x04da0,0x0a5d0,0x14573,0x052d0,0x0a9a8,0x0e950,0x06aa0,
    0x0aea6,0x0ab50,0x04b60,0x0aae4,0x0a570,0x05260,0x0f263,0x0d950,0x05b57,0x056a0,
    0x096d0,0x04dd5,0x04ad0,0x0a4d0,0x0d4d4,0x0d250,0x0d558,0x0b540,0x0b5a0,0x195a6,
    0x095b0,0x049b0,0x0a974,0x0a4b0,0x0b27a,0x06a50,0x06d40,0x0af46,0x0ab60,0x09570,
    0x04af5,0x04970,0x064b0,0x074a3,0x0ea50,0x06b58,0x055c0,0x0ab60,0x096d5,0x092e0,
    0x0c960,0x0d954,0x0d4a0,0x0da50,0x07552,0x056a0,0x0abb7,0x025d0,0x092d0,0x0cab5,
    0x0a950,0x0b4a0,0x0baa4,0x0ad50,0x055d9,0x04ba0,0x0a5b0,0x15176,0x052b0,0x0a930,
    0x07954,0x06aa0,0x0ad50,0x05b52,0x04b60,0x0a6e6,0x0a4e0,0x0d260,0x0ea65,0x0d530,
    0x05aa0,0x076a3,0x096d0,0x04bd7,0x04ad0,0x0a4d0,0x1d0b6,0x0d250,0x0d520,0x0dd45,
    0x0b5a0,0x056d0,0x055b2,0x049b0,0x0a577,0x0a4b0,0x0aa50,0x1b255,0x06d20,0x0ada0,
    0x14b63
);

function total_days_num_of_year($y)
{
    global $hex_lunar;
    $sum = 348;

    for ($i=0x8000; $i>0x8; $i>>=1) {
        $sum += ($hex_lunar[$y-1900] & $i)? 1: 0;
    }

    return $sum+leap_days($y);
}

function leap_days($y)
{
    global $hex_lunar;
    if (leap_month($y)) {
        return ($hex_lunar[$y-1900] & 0x10000)? 30: 29;

    } else {
        return 0;
    }
}

function leap_month($y)
{
    global $hex_lunar;
    return $hex_lunar[$y-1900] & 0xf;
}

function month_days($y,$m)
{
    global $hex_lunar;
    return ($hex_lunar[$y-1900] & (0x10000>>$m))? 30: 29;
}

function datetolunar_php($year,$month,$day)
{
    $leap=0;
    $temp=0;

    $timestamp = mktime(0,0,0,$month,$day,$year);

    if ($year < 1970) {
        $temp = 0;

        for ($i=1970; $i>$year; $i--) {
            $temp = total_days_num_of_year($i);
            $offset -= $temp;
        }
    }

    $offset = round($timestamp/86400+25537);
    $dayCyl = $offset + 40;
    $monCyl = 14;

    for ($i=1900; $i<$year && $offset>0; $i++) {
        $temp = total_days_num_of_year($i);
        $offset -= $temp;
        $monCyl += 12;
    }

    if ($offset<0) {
        $offset += $temp;
        $i--;
        $monCyl -= 12;
    }

    $year = $i;
    $yearCyl = $i-1864;
    $leap = leap_month($i); //闰哪个月
    $isLeap = false;

    for ($i=1; $i<13 && $offset>0; $i++) {
        //闰月
        if ($leap>0 && $i==($leap+1) && $isLeap==false) {
            $i--;
            $isLeap = true;
            $temp = leap_days($year);

        } else {
            $temp = month_days($year, $i);
        }

        //解除闰月
        if ($isLeap==true && $i==($leap+1)) {
            $isLeap = false;
        }

        $offset -= $temp;

        if ($isLeap == false) {
            $monCyl ++;
        }
    }

    if ($offset==0 && $leap>0 && $i==$leap+1)
        if ($isLeap) {
            $isLeap = false;

        } else {
            $isLeap = true;
            $i--;
            $monCyl--;
        }

    if ($offset<0) {
        $offset += $temp;
        $i--;
        $monCyl--;
    }

    $month = $i;
    $day = $offset + 1;

    $tiangan = array("甲","乙","丙","丁","戊","己","庚","辛","壬","癸");
    $dizhi = array("子[鼠]","丑[牛]","寅[虎]","卯[兔]","辰[龙]","巳[蛇]","午[马]","未[羊]","申[猴]","酉[鸡]","戌[狗]","亥[猪]");

    $lunar_month_names = array(' ','正','二','三','四','五','六','七','八','九','十','冬','腊');
    $lunar_day_names = array(
        "*","初一","初二","初三","初四","初五","初六","初七","初八","初九","初十",
        "十一","十二","十三","十四","十五","十六","十七","十八","十九","二十",
        "廿一","廿二","廿三","廿四","廿五","廿六","廿七","廿八","廿九","三十"
    );

    return sprintf("%s年 %s%s月%s",$tiangan[$yearCyl%10].$dizhi[$yearCyl%12], ($isLeap?" 闰":""),$lunar_month_names[$month],$lunar_day_names[$day]);
}




