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
        if(\Auth::user()->es_admin == 1)
            $drivers = Driver::select('name','id')->pluck('name','id');
        else {
            $drivers = Driver::select('name','id')
            ->leftJoin('business', 'business.id', '=', 'drivers.business_id')
            ->whereRaw('business.gas_station_id = ?', [\Auth::user()->gas_station_id])
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
            $this->validate($request, [
                'driver_id' => 'required',
                'number' => 'required|unique:receipts,number',
                'amount' => 'required',
                'type' => 'required'
            ]);
            
            $request->merge(['user_id' => Auth::user()->id]);
            $request->merge(['date' => Carbon::now()->toDateString()]);
            $request->merge(['payment' => $request->amount]);
            if($request->file('foto')) {
                $imagen = $request->file('foto');
                $nombre_imagen = time().'_'.str_random(10).'.'.$imagen->getClientOriginalExtension();
                Storage::disk('photos')->put($nombre_imagen,File::get($imagen), 'public');
                $request->merge(['photo' => 'photos/'.$nombre_imagen]);
            }
            else {
                $request->merge(['photo' => '']);
            }
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
        
    }

    public function report()
    {
        return view('reports.receipts');
    }

    public function data()
    {
        $tabla = Datatables::of( Receipt::with('driver')->orderBy('date','DESC')->get() )
                ->addColumn('photo', function($registro){
                    $photo = '<a href="'.$registro->photo.'" >'.url($registro->photo).'</a>';
                    return $photo;
                })
                ->rawColumns(['photo'])
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }

}
