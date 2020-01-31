<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Activity;
use App\Rules\TimeValidation;
use App\Rules\DateValidation;
use Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\BaseXCS;

class AntelopeActivity extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Antelope Activity Controller
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get a validator for an incoming log request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        if ($data['patrol_end_date'] == null) {
            $data['patrol_end_date'] = $data['patrol_start_date'];
        };

        return Validator::make($data, [
            'patrol_start_date' => ['required', 'date'],
            'patrol_end_date' => ['date', new DateValidation($data['patrol_start_date'])],
            'start_time' => ['required', 'string'],
            'end_time' => ['required', 'string', new TimeValidation($data['start_time'], $data['patrol_start_date'], $data['patrol_end_date'])],
            'type' => ['required', 'string', 'max:30'],
            'details' => ['required', 'string'],
            'patrol_area' => ['required', 'array', 'min:1'],
            'patrol_area.*' => ['required', 'string'],
            'patrol_priorities' => ['required', 'integer'],
        ]);
    }

    /**
     * Create a new log instance after validation
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $data = $this->convertTimezone($data);

        $log = Activity::create([
            'patrol_start_date' => date("Y-m-d", strtotime($data['patrol_start_date'])),
            'patrol_end_date' => date("Y-m-d", strtotime($data['patrol_end_date'])),
            'start_time' => date("H:i:s", strtotime($data['start_time'])),
            'end_time' => date("H:i:s", strtotime($data['end_time'])),
            'type' => $data['type'],
            'details' => $data['details'],
            'user_id' => Auth::user()->id,
            'patrol_area' => json_encode($data['patrol_area']),
            'priorities' => $data['patrol_priorities'],
        ]);

        return $log;
    }

    /**
     * The activity log has been registered
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function submitted(Request $request, $log)
    {
        //
    }

    /**
     * Convert the Timezone for the timestamps
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function convertTimezone(array $data)
    {
        $patrol_start_date = $data['patrol_start_date'];
        $start_time = $data['start_time'];
        $patrol_end_date = $data['patrol_end_date'];
        $end_time = $data['end_time'];

        $start_date_time = date('Y-m-d H:i:s', strtotime("$patrol_start_date $start_time"));
        $end_date_time = date('Y-m-d H:i:s', strtotime("$patrol_end_date $end_time"));

        $start_date_time = BaseXCS::convertTimezone(Auth::user()->id, $start_date_time);
        $end_date_time = BaseXCS::convertTimezone(Auth::user()->id, $end_date_time);

        $data['patrol_start_date'] = date('Y-m-d', strtotime($start_date_time));
        $data['start_time'] = date('H:i:s', strtotime($start_date_time));

        $data['patrol_end_date'] = date('Y-m-d', strtotime($end_date_time));
        $data['end_time'] = date('H:i:s', strtotime($end_date_time));

        return $data;
    }

    /**
     * Handle an activity log submit for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request)
    {
        if ($request->patrol_end_date == null) {
            $request->patrol_end_date = $request->patrol_start_date;
        };

        $this->validator($request->all())->validate();

        $log = $this->create($request->all());

        return $this->submitted($request, $log);
    }

    /**
     * Construct Activity Page
     *
     * @return View
     */
    public function constructPage()
    {
        $constants = \Config::get('constants');

        return view('activity_database')->with('constants', $constants);
    }

    /**
     * Gets all activity in database
     *
     * @return View
     */
    public function passActivityData()
    {
    $query = Activity::query()
    ->select([
        'activity.id',
        'activity.user_id',
        'activity.patrol_start_date',
        'activity.patrol_end_date',
        'activity.start_time',
        'activity.end_time',
        'activity.details',
        'activity.patrol_area',
        'activity.priorities',
        'activity.type',
        'users.name',
        'users.department_id',
        'users.website_id'
    ])
    ->join('users', function($join) {
        $join->on('activity.user_id', '=', 'users.id');
    });

    return datatables($query)
    ->addColumn('name_department_id', function($row){
                if ( $row->department_id == null ) {
                    return $row->name;
                }
                else return $row->name.' '.$row->department_id;})
    ->addColumn('patrol_start_end_date', function($row){
                if ( $row->patrol_end_date == null or $row->patrol_end_date == $row->patrol_start_date ) {
                    return $row->patrol_start_date;
                }
                else return $row->patrol_start_date.' - '.$row->patrol_end_date;})
    ->rawColumns(['details'])
    ->toJson();
    }

    /**
     * Gets specific activity instance
     *
     * @return View
     */
    public function passActivityInstance($id)
    {
        $constants = \Config::get('constants');
        if(Auth::user()->level() >= $constants['access_level']['sit'] or Auth::user()->id == Activity::find($id)->user_id) {
            $log = Activity::find($id);

            $log->user_name = User::find($log['user_id'])->name;
            $log->department_id = User::find($log['user_id'])->department_id;
            $log->website_id = User::find($log['user_id'])->website_id;

            return $log;
        } else return 'noob hax0r';
    }


    /**
     * Gets a specific users' activity
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function activityData($id)
    {
        $constants = \Config::get('constants');
        if(Auth::user()->level() >= $constants['access_level']['sit'] or Auth::user()->id == $id) {
            $query = Activity::query()
            ->select([
                'id',
                'user_id',
                'patrol_start_date',
                'patrol_end_date',
                'start_time',
                'end_time',
                'details',
                'patrol_area',
                'priorities',
                'type',
            ])->where('user_id', '=', $id);

            return Datatables($query)
            ->addColumn('patrol_start_end_date', function($row){
                    if ( $row->patrol_end_date == null or $row->patrol_end_date == $row->patrol_start_date ) {
                        return $row->patrol_start_date;
                    }
                    else return $row->patrol_start_date.' - '.$row->patrol_end_date;})
            ->addColumn('patrol_duration', function($row){
                    if ( $row->patrol_end_date == null or $row->patrol_end_date == $row->patrol_start_date ) {
                        $start = Carbon::parse($row->start_time);
                        $end = Carbon::parse($row->end_time);
                        $totalDuration = $end->diffInSeconds($start);
                        return gmdate('H:i:s', $totalDuration);
                    }
                    else {
                        $start_date_time = date('Y-m-d H:i:s', strtotime("$row->patrol_start_date $row->start_time"));
                        $end_date_time = date('Y-m-d H:i:s', strtotime("$row->patrol_end_date $row->end_time"));

                        $start_date_time = strtotime($start_date_time);
                        $end_date_time = strtotime($end_date_time);

                        $total_duration = $end_date_time - $start_date_time;

                        return BaseXCS::convertToDuration($total_duration);
                    };})
            ->rawColumns(['details'])
            ->toJson();
        } else return 'pwnd hax0r';
    }
}
