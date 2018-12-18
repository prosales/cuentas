<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use DB;
use App\Driver;
use App\Business;

class DriversController extends Controller
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
        return view('drivers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(\Auth::user()->es_admin == 1)
            $business = Business::select(DB::raw('business_name as name'), 'id')->pluck('name','id');
        else
            $business = Business::select(DB::raw('business_name as name'), 'id')->where('gas_station_id', \Auth::user()->gas_station_id)->pluck('name','id');

        $business[0] = 'Seleccione';
        return view('drivers.create', compact('business'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'dpi' => 'required',
            'business_id' => 'required'
        ]);

        $registro = Driver::create($request->all());

        return redirect()->route('drivers.index')->with('success', 'Registro creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registro = Driver::find($id);
        return view('drivers.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Driver::find($id);
        if(\Auth::user()->es_admin == 1)
            $business = Business::select(DB::raw('business_name as name'), 'id')->pluck('name','id');
        else
            $business = Business::select(DB::raw('business_name as name'), 'id')->where('gas_station_id', \Auth::user()->gas_station_id)->pluck('name','id');
        $business[0] = 'Seleccione';
        return view('drivers.edit', compact('registro', 'business'));
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
        $request->merge(['id' => $id]);
        $this->validate($request, [
            'name' => 'required|max:255',
            'dpi' => 'required',
            'business_id' => 'required'
        ]);
        
        $registro = Driver::find($id);
        $registro->update($request->all());
        $registro->save();

        return redirect()->route('drivers.index')->with('success', 'Registro actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Driver::destroy($id)) {
            return redirect()->route('drivers.index')->with('success', 'Registro eliminado correctamente');
        } else {
            return redirect()->route('drivers.index')->with('error', 'Registro no se pudo eliminar');
        }
    }

    public function data()
    {
        if(\Auth::user()->es_admin == 1)
            $records = Driver::with('business')->get();
        else {
            $records = Driver::select(DB::raw('drivers.*'))
            ->leftJoin('business', 'business.id', '=', 'drivers.business_id')
            ->whereRaw('business.gas_station_id = ?', [\Auth::user()->gas_station_id])
            ->with('business')
            ->get();
        }

        $tabla = Datatables::of( $records )
                ->addColumn('business', function($registro){
                    $business = $registro->business!=null ? $registro->business->business_name : '';
                    return $business;
                })
                ->addColumn('action', function($registro){
                    $edit = '<a href="'.route('drivers.edit',$registro->id).'" class="btn btn-primary btn-sm" data-title="Editar">Editar</a> ';
                    $show = '<a href="'.route('drivers.show',$registro->id).'" class="btn btn-danger btn-sm" data-title="Eliminar">Eliminar</a>';
                    return $edit . $show;
                })
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }
}
