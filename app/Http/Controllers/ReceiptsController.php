<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use App\Receipt;
use App\Driver;
use Auth;
use DB;

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
        $drivers = Driver::select(DB::raw("CONCAT(name,' - ',plate_number) AS name"),'id')->pluck('name','id');
        $drivers[0] = "Seleccione";
        return view('receipts.index', compact('drivers'));
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
                'date' => 'required'
            ]);
            
            $request->merge(['user_id' => Auth::user()->id]);
            $registro = Receipt::create($request->all());

            $driver = Driver::find($request->driver_id);
            $driver->balance += $request->amount;
            $driver->save();
            
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
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }

}
