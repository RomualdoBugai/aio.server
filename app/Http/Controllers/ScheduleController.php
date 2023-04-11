<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request as Request;
use Carbon\Carbon;
use App\Services\Useful\User as User;
use DatePeriod;

class ScheduleController extends Controller
{


    public static function createDateRangeArray($strDateFrom, $strDateTo)
    {

        $aryRange   = array();

        $iDateFrom  = mktime(1,0,0,substr($strDateFrom,5,2),    substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo    = mktime(1,0,0,substr($strDateTo,5,2),      substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange,date('Y-m-d',$iDateFrom));
            while ($iDateFrom<$iDateTo) {
                $iDateFrom += 86400;
                array_push($aryRange,date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }


    public static function weekForDate($date, $weekStartSunday = false)
    {

        $timestamp = strtotime($date);

        if($weekStartSunday) {
            $start = (date("D", $timestamp) == 'Sun') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Last Sunday', $timestamp));
            $end = (date("D", $timestamp) == 'Sat') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Next Saturday', $timestamp));
        } else {
            $start = (date("D", $timestamp) == 'Mon') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Last Monday', $timestamp));
            $end = (date("D", $timestamp) == 'Sun') ? date('Y-m-d', $timestamp) : date('Y-m-d', strtotime('Next Sunday', $timestamp));
        }

        return array('start' => $start, 'end' => $end);
    }

    /*
    $date = date_create(null);
    $tz = date_timezone_get($date);
    echo timezone_name_get($tz);
    */

    public function buildCalendarByMonth($year, $month = '01')
    {
        $endOfMonth = Carbon::createFromFormat('Y-m-d', $year . "-" . $month . "-01")->endOfMonth()->format("t");
		$calendar   = array($year, $month);

        $input      = [
            'start_at'      => implode("-", array($year, $month, '01')) . " 00:01",
            'end_at'        => implode("-", array($year, $month, $endOfMonth)) . " 23:59",
            'user_id'       => User::id(),
            'count'         => 1,
            'splitByDay'    => 1
        ];

        $client     = new \App\Services\Client();
        $result     = $client->execute($input, 'schedulingUserServiceGet', '1.0', 'get');

        $total      = [];

        if ($result['status'] == true) {
            $total = $result['data'];
        }

        $data = [
            'todayInMonth' => Carbon::now()->format('m'),
            'today'     => Carbon::now()->format('d'),
            'year'  => $year,
            'month' => $month,
            'total' => $total
        ];

        return view('app.schedule.widget.month', $data);
    }

    public function index()
    {

        $nextMonth 	        = Carbon::now()->addMonth(1);
        $currentMonth       = Carbon::now();
        $previousMonth 	    = Carbon::now()->subMonth(1);

        $week               = self::weekForDate($currentMonth->format('Y-m-d'), true);

        $days               = self::createDateRangeArray($week['start'], $week['end']);

        $wk     = [];
        for($d  = 0; $d < 7; $d++) {
            list($year, $month, $day) = explode("-", $days[$d]);
            $wk[$d] = self::build($year, $month, $day, $day);
        }

        $today = [
            'year'      => $currentMonth->format("Y"),
            'month'     => $currentMonth->format("m"),
            'day'       => $currentMonth->format("d"),
            'name'      => message('calendar', 'month-' . strtolower(Carbon::now()->format('M')) . '-name'),
        ];

        $data               = [
            'title'         => message('template', 'scheduling-index'),
            'today'         => $today,
            'calendars'     => [
                'previous'  => $this->buildCalendarByMonth($previousMonth->format('Y'), $previousMonth->format('m') ),
                'current'   => $this->buildCalendarByMonth($currentMonth->format('Y'),  $currentMonth->format('m')  ),
                'next'      => $this->buildCalendarByMonth($nextMonth->format('Y'),     $nextMonth->format('m')     ),
                'week'      => $wk
            ],
            'view'          => [
                'schedule'  => self::today($today),
                'expense'   => \App::call("App\Http\Controllers\Widget\ExpensesController@index")
            ],
            'breadcrumbs' => [
                [
                    'link'  => true,
                    'label' => message('me', 'index'),
                    'url'   => route('me')
                ],
                [
                    'link'  => false,
                    'label' => message('schedule', 'index'),
                    'url'   => null
                ],
            ]
        ];

        return view('app.schedule.index', $data);
    }

    public static function build($year, $month, $startDay, $endDay)
    {
		$calendar   = array($year, $month);

        $input  = [
            'start_at'      => implode("-", array($year, $month, $startDay))    . " 00:01",
            'end_at'        => implode("-", array($year, $month, $endDay))      . " 23:59",
            'user_id'       => User::id(),
            'count'         => 1,
            'splitByDay'    => 1
        ];

        $client = new \App\Services\Client();
        $result = $client->execute($input, 'schedulingUserServiceGet', '1.0', 'get');

        $total  = [];

        if ($result['status'] == true) {
            $total = $result['data'];
        }

        return [
            'year'  => $year,
            'month' => $month,
            'day'   => $startDay,
            'week'  => message('calendar', 'week-' . strtolower(date('D', strtotime(implode("-", [$year, $month, $startDay])))) . '-prefix'),
            'total' => $total
        ];
    }

    public static function today($today)
    {

        $date   = implode("-", [$today['year'] , $today['month'] , $today['day'] ]);

        $data   = [
            'start_at'      => $date . " 00:01",
            'end_at'        => $date . " 23:59",
            'user_id'       => User::id(),
            'count'         => 0,
            'splitByDay'    => 0
        ];

        $client = new \App\Services\Client();
        $result = $client->execute($data, 'schedulingUserServiceGet', '1.0', 'get');

        $scheduling = ( $result['status'] == true ? $result['data'] : [] );

        $data   = [
            "data" => $scheduling
        ];

        return view('app.schedule.widget.user-schedule')
        ->with('data', $scheduling)
        ->render();
    }

    public function userScheduleByDate(Request $request)
    {
        $input  = $request->input();
        $date   = $input['date'];

        $data   = [
            'start_at'      => $date . " 00:01",
            'end_at'        => $date . " 23:59",
            'user_id'       => User::id(),
            'count'         => 0,
            'splitByDay'    => 0
        ];

        $client = new \App\Services\Client();
        $result = $client->execute($data, 'schedulingUserServiceGet', '1.0', 'get');

        $scheduling = ( $result['status'] == true ? $result['data'] : [] );

        $data   = [
            "data" => $scheduling
        ];

        $html   = view('app.schedule.widget.user-schedule')
        ->with('data', $scheduling)
        ->render();

        return response()
        ->json(
            [
                'success'   => true,
                'html'      => $html
            ]
        );
    }

}
