<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use App\Receipt;
use App\Driver;
use App\Business;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ReceiptsController extends Controller
{
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->es_admin == 1)
            $drivers = Driver::select('name','id')->pluck('name','id');
        else {
            $drivers = Driver::select(DB::raw('drivers.name, drivers.id'))
            ->leftJoin('business', 'business.id', '=', 'drivers.business_id')
            ->whereRaw('business.gas_station_id = ?', [Auth::user()->gas_station_id])
            ->pluck('name','id');
        }
        $drivers[0] = 'Seleccione';
        $options = [
            '' => 'Seleccione',
            'Regular' => 'Regular',
            'Super' => 'Super',
            'V-Power' => 'V-Power',
            'Diesel' => 'Diesel',
            'Otros' => 'Otros'
        ];
        return view('receipts.index', compact('drivers', 'options'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $messages = [
                'number.unique' => 'El número de recibo ingresado ya existe.'
            ];

            $validator = Validator::make($request->all(), [
                'driver_id' => 'required',
                'number' => 'required|unique:receipts,number',
                'amount' => 'required',
                'type' => 'required',
                'foto' => 'required',
                'galonaje' => 'required'
            ], $messages);
    
            if ($validator->fails()) {
                return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
            }
    
            
            $request->merge(['user_id' => Auth::user()->id]);
            $request->merge(['date' => Carbon::now()->toDateString()]);
            $request->merge(['payment' => $request->amount]);
            if($request->observations!='') {
                $request->merge(['observations' => '']);
            }

            $imagen = $request->file('foto');
            $nombre_imagen = time().'_'.str_random(10).'.'.$imagen->getClientOriginalExtension();
            Storage::disk('photos')->put($nombre_imagen,File::get($imagen), 'public');
            $request->merge(['photo' => 'photos/'.$nombre_imagen]);

            $registro = Receipt::create($request->all());

            $driver = Driver::find($request->driver_id);
            $business = Business::find($driver->business_id);
            $business->balance += $request->amount;
            $business->save();
            
            DB::commit();
            return redirect()->route('receipts.index')->with('success', 'Registro creado correctamente');
        }
        catch(\Exception $e) {
            DB::rollBack();
            return redirect()->route('receipts.index')->with('error', 'Ocurrio un problema al registrar el recibo.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registro = Receipt::find($id);

        return view('receipts.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $registro = Receipt::find($id);
        $driver = Driver::find($registro->driver_id);
        $business = Business::find($driver->business_id);
        $business->balance = floatval($business->balance) - floatval($registro->amount);
        $business->save();

        if ($registro->delete()) {
            return redirect()->route('receipts.report')->with('success', 'Registro eliminado correctamente');
        } else {
            return redirect()->route('receipts.report')->with('error', 'Registro no se pudo eliminar');
        }
    }

    public function report()
    {
        if(\Auth::user()->es_admin == 1)
            $business = Business::select(DB::raw('business_name as name'), 'id')->pluck('name','id');
        else
            $business = Business::select(DB::raw('business_name as name'), 'id')->where('gas_station_id', \Auth::user()->gas_station_id)->pluck('name','id');

        $business[0] = 'Todas';

        return view('reports.receipts', compact('business'));
    }

    public function data(Request $request)
    {
        $where = $request->start_date && $request->end_date ? "receipts.date BETWEEN '".$request->start_date."' AND '".$request->end_date."'" : 'TRUE';
        $where = $request->business_id > 0 ? $where." AND business.id = ".$request->business_id : $where;
        
        $records = Receipt::select('receipts.*')
                    ->leftJoin('drivers', 'drivers.id', '=', 'receipts.driver_id')
                    ->leftJoin('business', 'business.id', '=', 'drivers.business_id')
                    ->whereRaw($where)
                    ->with('driver')
                    ->orderBy('date','DESC')
                    ->get();
        
        $tabla = Datatables::of( $records )
                ->addColumn('amount', function($registro){
                    
                    return 'Q '.number_format($registro->amount,2,'.',',');
                })
                ->addColumn('payment', function($registro){
                    
                    return 'Q '.number_format($registro->payment,2,'.',',');
                })
                ->addColumn('status', function($registro){
                    $status = '<span class="badge badge-primary">Pendiente</span>';
                    if($registro->to_cancel == 1) {
                        $status = '<span class="badge badge-success">Cancelado</span>';
                    }
                    else if($registro->payment != $registro->amount) {
                        $status = '<span class="badge badge-warning">Abonado</span>';
                    }

                    return $status;
                })
                ->addColumn('photo', function($registro){
                    $photo = '';
                    if($registro->photo!='')
                        $photo = '<a href="'.url($registro->photo).'" target="_blank">Foto</a>';

                    return $photo;
                })
                ->addColumn('action', function($registro){
                    if($registro->to_cancel == 1) {
                        $show = '';
                    }
                    else if($registro->payment != $registro->amount) {
                        $show = '';
                    }
                    else {
                        $show = '<a href="'.route('receipts.show',$registro->id).'" class="btn btn-danger btn-sm" data-title="Eliminar"><i class="fa fa-trash"></i></a>';
                    }
                    return $show;
                })
                ->rawColumns(['amount','payment','status','photo','action'])
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }

    public function report_galonaje()
    {
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        $years = [
            '2019' => '2019',
            '2020' => '2020',
            '2021' => '2021',
            '2022' => '2022'
        ];

        return view('reports.galonajes', compact('months', 'years'));
    }

    public function data_galonajes(Request $request)
    {
        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        
        $records = Receipt::select('receipts.type', DB::raw('sum(galonaje) as total'))->whereRaw('MONTH(date) = ? AND YEAR(date) = ?', [$request->month, $request->year])->groupBy('type')->get();

        $tabla = Datatables::of( $records )
                ->addColumn('month', function($registro) use($months, $request){
                    return $months[$request->month];
                })
                ->addColumn('year', function($registro) use($request){
                    return $request->year;
                })
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }

}
