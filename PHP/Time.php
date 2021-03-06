<?php
class Time{
    /*
     * 两个日期之间的所有日期
     * */
    public function prDates($start,$end){
        $dates = [];
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        while ($dt_start<=$dt_end){
            $dates[] = date('Y-m-d',$dt_start);
            $dt_start = strtotime('+1 day',$dt_start);
        }
        return $dates;
    }

    /*
     * 获取两个时间相差的天数
     * */
    public function diffBetweenTwoDays ($day1, $day2) {
        //对接结束时间
        $second1 = is_int($day1)?$day1:strtotime($day1);
        //当前时间
        $second2 = is_int($day2)?$day2:strtotime($day2);

        $second1 = date('Y-m-d 00:00:00',$second1);
        $second2 = date('Y-m-d 00:00:00',$second2);
        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        $second1 = strtotime($second1);
        $second2 = strtotime($second2);

        return ($second1 - $second2) / 86400;
    }

    /**
     * 根据相应日期  获取该日期所在的周，月及该日期之前的N天的日期数据(N包括该日期)
     * @param $date 时间点
     * @param $type   w|m|(int)n
     * @return array 返回对应的日期的集合
     *   */
    public function get_dates($date,$type = '1'){
        if($type =='w'){
            return get_weeks($date);
        }elseif($type == 'm'){
            return get_day($date,2);
        }elseif(is_numeric($type)){
            $dates =[];
            $date = is_string($date)?strtotime($date):$date;
            $dates[] = date('Y-m-d',$date);
            for($i=1;$i<$type;$i++){
                $dates[] = date('Y-m-d',strtotime('-'. $i.'day',$date));
            }
            return $dates;
        }
        return [];
    }

    /*
     * 获取周
     * */
    public function get_weeks($date){
        $date = is_int($date)?$date:strtotime($date);
        $week = date('N',$date);
        for ($i=1;$i<8;$i++){
            $weeks[] = date('Y-m-d',strtotime(' +'. $i-$week .' days',$date));
        }
        return $weeks;
    }

    /**
     * 获取当月天数
     * @param $date
     * @param $rtype 1天数 2具体日期数组
     * @return
     */
    public function get_day( $date ,$rtype = '1'){
        $tem = explode('-' , $date);    //切割日期 得到年份和月份
        $year = $tem['0'];
        $month = $tem['1'];
        if( in_array($month , array( '1' , '3' , '5' , '7' , '8' , '01' , '03' , '05' , '07' , '08' , '10' , '12')))
        {
            // $text = $year.'年的'.$month.'月有31天';
            $text = '31';
        }
        elseif( $month == 2 )
        {
            if ( $year%400 == 0 || ($year%4 == 0 && $year%100 !== 0) )    //判断是否是闰年
            {
                // $text = $year.'年的'.$month.'月有29天';
                $text = '29';
            }
            else{
                // $text = $year.'年的'.$month.'月有28天';
                $text = '28';
            }
        }
        else{
            // $text = $year.'年的'.$month.'月有30天';
            $text = '30';
        }
        if ($rtype == '2') {
            for ($i = 1; $i <= $text ; $i ++ ) {
                $i = str_pad($i,2,'0',STR_PAD_LEFT);
                $month = str_pad($month,2,'0',STR_PAD_LEFT);
                $r[] = $year."-".$month."-".$i;
            }
        } else {
            $r = $text;
        }
        return $r;
    }

    /**
     * 获取毫秒时间戳
     * @return [type] [description]
     */
    public function msectime() {
        list($tmp1, $tmp2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
    }

    /**
     * [time_show description]
     * @param  [int] $time 数字
     * @param  [int] $isms 是否是毫秒
     * @return char       时间
     */
    public function time_show($time, $isms){
        if ($isms) {
            $a = substr($time,-3);
        }
        $time = substr($time,0,-3);
        if($time < 60){
            $showtime =  '00:00:'.str_pad($time, 2, 0, STR_PAD_LEFT);
        }
        else if($time < 3600 && $time >= 60){
            $showtime = '00:'.str_pad(floor($time / 60), 2, 0, STR_PAD_LEFT).':'.str_pad($time % 60, 2, 0, STR_PAD_LEFT);
        }
        else if($time >= 3600 && $time < 24*3600){
            $showtime = str_pad(floor($time / 3600), 2, 0, STR_PAD_LEFT).':'.str_pad(floor(($time % 3600) / 60), 2, 0, STR_PAD_LEFT).':'.str_pad($time % 60, 2, 0, STR_PAD_LEFT);
        }else{
            return false;
        }
        $showtime = $isms ? $showtime.'.'.$a : $showtime;
        return $showtime;
    }

    /**
     *时间格式处理
     *x<60分钟，显示x分钟之前；1<=x<24，显示x小时之前；x>=24小时，显示1991-01-01 00:00:00
     */
    public function timeshow($datetime){
        $time = time() - $datetime;
        if($time < 60){
            $showtime = $time."秒前";
        }
        else if($time < 3600 && $time >= 60){
            $showtime = ceil($time / 60)."分钟前";
        }
        else if($time >= 3600 && $time < 24*3600){
            $showtime = ceil($time / 60 / 60)."小时前";
        }
        else if($time >= 24*3600 && $time < 30*24*3600){
            $showtime = ceil($time / 60 / 60 / 24)."天前";
        }
        else if($time >= 24*3600){
            $showtime = date("Y-m-d H:i:s",$datetime);
        }
        return $showtime;
    }

    /**
     *时间格式处理
     *x<60分钟，显示x分钟之前；1<=x<24，显示x小时之前；24小时<=x<3天，显示x天；x>=3，显示3天
     */
    public function timeformat($datetime){
        $time = time() - $datetime;
        if($time < 60){
            $showtime = $time."秒前";
        }
        else if($time < 3600 && $time >= 60){
            $showtime = ceil($time / 60)."分钟之前";
        }
        else if($time >= 3600 && $time < 24*3600){
            $showtime = ceil($time / 60 / 60)."小时之前";
        }
        else{
            $showtime = date("Y-m-d H:i",$datetime);
        }
        // else if($time >= 24*3600 && $time < 30*24*3600){
        // 	$showtime = ceil($time / 60 / 60 / 24)."天之前";
        // }
        // else if($time >= 30*24*3600 && $time < 12*30*24*3600){
        // 	$showtime = ceil($time / 60 / 60 / 24/30)."个月之前";
        // }
        return $showtime;
    }

    //时间格式化2
    public function formatTime($time) {

        $t = time() - $time;
        $mon = (int) ($t / (86400 * 30));
        if ($mon >= 12) {
            return '一年前';
        }
        if ($mon >= 6) {
            return '半年前';
        }
        if ($mon >= 3) {
            return '三个月前';
        }
        if ($mon >= 2) {
            return '二个月前';
        }
        if ($mon >= 1) {
            return '一个月前';
        }
        $day = (int) ($t / 86400);
        if ($day >= 1) {
            return $day . '天前';
        }
        $h = (int) ($t / 3600);
        if ($h >= 1) {
            return $h . '小时前';
        }
        $min = (int) ($t / 60);
        if ($min >= 1) {
            return $min . '分前';
        }
        return '刚刚';
    }

    //时间格式化2
    public function pincheTime($time) {
        $today  =  strtotime(date('Y-m-d')); //今天零点
        $here  =  (int)(($time - $today)/86400) ; 
        if($here==1){
            return '明天';  
        }
        if($here==2) {
            return '后天';  
        }
        if($here>=3 && $here<7){
            return $here.'天后';  
        }
        if($here>=7 && $here<30){
            return '一周后';  
        }
        if($here>=30 && $here<365){
            return '一个月后';  
        }
        if($here>=365){
            $r = (int)($here/365).'年后'; 
            return   $r;
        }
        return '今天';
    }

    //时间格式化2
    public function ele_wait_Time($time) {
        $mon = (int) ($time / (86400 * 30));
        if ($mon >= 12) {
            return '一年前';
        }
        if ($mon >= 6) {
            return '半年前';
        }
        if ($mon >= 3) {
            return '三个月前';
        }
        if ($mon >= 2) {
            return '二个月前';
        }
        if ($mon >= 1) {
            return '一个月前';
        }
        $day = (int) ($time / 86400);
        if ($day >= 1) {
            return $day . '天';
        }
        $h = (int) ($time / 3600);
        if ($h >= 2) {
            return $h . '小时';
        }
        $min = (int) ($time / 60);
        if ($min >= 1) {
            return $min . '分钟';
        }
        return '0分钟';
    }

    /**
     * 检查是否为一个合法的时间格式
     *
     * @access  public
     * @param   string  $time
     * @return  void
     */
    public function isTime($time) {
        $pattern = '/[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}/';

        return preg_match($pattern, $time);
    }

    /**
     * 判断一个字符串是否是一个合法时间
     *
     * @param string $string
     * @return boolean
     */
    public function isDate($string) {
        if (preg_match('/^\d{4}-[0-9][0-9]-[0-9][0-9]$/', $string)) {
            $date_info = explode('-', $string);
            return checkdate(ltrim($date_info[1], '0'), ltrim($date_info[2], '0'), $date_info[0]);
        }
        if (preg_match('/^\d{8}$/', $string)) {
            return checkdate(ltrim(substr($string, 4, 2), '0'), ltrim(substr($string, 6, 2), '0'), substr($string, 0, 4));
        }
        return false;
    }
}