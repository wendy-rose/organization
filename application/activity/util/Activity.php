<?php
namespace app\activity\util;

class Activity
{

    /**
     * 返回开始时间戳和结束时间戳的数组
     * @param $timeTypeOrTimeRange 1今天，2昨天，3本周，4上周，5本月，6上月，时间范围2016/1/1-2010/1/2
     * @return array
     */
    public static function getTimerRange($timeTypeOrTimeRange)
    {
        $rangeTime = array();
        if ($timeTypeOrTimeRange == 1) {
            $rangeTime['starttime'] = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $rangeTime['endtime'] = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        }elseif ($timeTypeOrTimeRange == 2) {
            $rangeTime['starttime'] = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
            $rangeTime['endtime'] = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
        }elseif ($timeTypeOrTimeRange == 3) {
            $currentTime = time();
            $nowWeek = date('w', $currentTime);
            $startDay = $currentTime - $nowWeek*60*60*24;
            $endDay = $currentTime + (6-$nowWeek)*60*60*24;
            $rangeTime['starttime'] = mktime(0,0,0,date('m',$startDay),date('d',$startDay),date('Y',$startDay));
            $rangeTime['endtime'] = mktime(23,59,59,date('m',$endDay),date('d',$endDay),date('Y',$endDay));
        }elseif ($timeTypeOrTimeRange == 4) {
            $rangeTime['starttime'] = mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
            $rangeTime['endtime'] = mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
        }elseif ($timeTypeOrTimeRange == 5) {
            $rangeTime['starttime'] = mktime(0,0,0,date('m'),1,date('Y'));
            $rangeTime['endtime'] = mktime(23,59,59,date('m'),date('t'),date('Y'));
        }elseif ($timeTypeOrTimeRange == 6) {
            $currentMonth = date('m');
            $currentYear = date('Y');
            if ($currentMonth == 1) {
                $lastMonth = 12;
                $lastYear = $currentYear -1;
            }else {
                $lastMonth = $currentMonth - 1;
                $lastYear = $currentYear;
            }
            $lastStartDay = $lastYear . '-' . $lastMonth . '-1';
            $rangeTime['starttime'] = mktime(0,0,0,$lastMonth,1,$lastYear);
            $rangeTime['endtime'] = mktime(23,59,59,$lastMonth,date('t', strtotime($lastStartDay)),$lastYear);
        }else {
            $timeType = explode('-', $timeTypeOrTimeRange);
            $starttime = strtotime($timeType[0]);
            $endtime = strtotime($timeType[1]);
            $rangeTime['starttime'] = mktime(0,0,0,date('m',$starttime),date('d',$starttime),date('Y',$starttime));
            $rangeTime['endtime'] = mktime(23,59,59,date('m',$endtime),date('d',$endtime),date('Y',$endtime));
        }
        return $rangeTime;
    }
}