<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use App\Business;
use App\GasStation;

class BusinessController extends Controller
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
        $total = Business::sum('balance');
        $total = number_format($total,2,'.',',');

        return view('business.index', compact('total'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stations = GasStation::pluck('name', 'id');
        $stations[0] = 'Seleccione';
        return view('business.create', compact('stations'));
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
            'owner_name' => 'required|max:255',
            'business_name' => 'required|max:255',
            'nit' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'gas_station_id' => 'required'
        ]);

        $request->merge(['balance' => 0]);
        $registro = Business::create($request->all());

        return redirect()->route('business.index')->with('success', 'Registro creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registro = Business::find($id);
        return view('business.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Business::find($id);
        $stations = GasStation::pluck('name', 'id');
        $stations[0] = 'Seleccione';
        return view('business.edit', compact('registro', 'stations'));
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
            'owner_name' => 'required|max:255',
            'business_name' => 'required|max:255',
            'nit' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'gas_station_id' => 'required'
        ]);
        
        $registro = Business::find($id);
        $registro->update($request->all());
        $registro->save();

        return redirect()->route('business.index')->with('success', 'Registro actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Business::destroy($id)) {
            return redirect()->route('business.index')->with('success', 'Registro eliminado correctamente');
        } else {
            return redirect()->route('business.index')->with('error', 'Registro no se pudo eliminar');
        }
    }

    public function data()
    {
        // $records = Business::select(
        //     'business.*',
        //     \DB::raw('
        //     (
        //         SELECT SUM(receipts.amount) FROM receipts
        //         INNER JOIN drivers ON drivers.id = receipts.driver_id
        //         WHERE drivers.business_id = business.id
        //     ) as monto,
        //     (
        //         SELECT SUM(deposits.amount) FROM deposits
        //         WHERE deposits.business_id = business.id
        //     ) as pagado
        //     ')
        // );
        $records = Business::all();

        $tabla = Datatables::of( $records )
                ->addColumn('balance', function($registro){
                    return '<b style="color: red;">Q '.number_format($registro->balance,2,'.',',').'</b>';
                })
                ->addColumn('action', function($registro){
                    $edit = '<a href="'.route('business.edit',$registro->id).'" class="btn btn-primary btn-sm" data-title="Editar"><i class="fa fa-edit"></i></a> ';
                    $show = '<a href="'.route('business.show',$registro->id).'" class="btn btn-danger btn-sm" data-title="Eliminar"><i class="fa fa-trash"></i></a>';
                    return $edit . $show;
                })
                ->rawColumns(['balance', 'action'])
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }
}
