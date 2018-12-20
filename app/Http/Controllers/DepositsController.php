<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use App\Deposit;
use App\Receipt;
use App\Driver;
use App\Business;
use App\Payment;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DepositsController extends Controller
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
            $business = Business::select(DB::raw('business_name as name'), 'id')->pluck('name','id');
        else
            $business = Business::select(DB::raw('business_name as name'), 'id')->where('gas_station_id', \Auth::user()->gas_station_id)->pluck('name','id');

        $business[0] = 'Seleccione';
        return view('deposits.index', compact('business'));
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
                'business_id' => 'required',
                'number' => 'required|unique:receipts,number',
                'amount' => 'required',
                'foto' => 'required'
            ]);

            $business = Business::find($request->business_id);
            if($business->balance > 0) {
                $request->merge(['user_id' => Auth::user()->id]);
                $request->merge(['date' => Carbon::now()->toDateString()]);
                
                $imagen = $request->file('foto');
                $nombre_imagen = time().'_'.str_random(10).'.'.$imagen->getClientOriginalExtension();
                Storage::disk('photos')->put($nombre_imagen,File::get($imagen), 'public');
                $request->merge(['photo' => 'photos/'.$nombre_imagen]);

                $registro = Deposit::create($request->all());

                $business->balance -= $request->amount;
                $business->save();

                $receipts = Receipt::select('receipts.*')
                        ->leftJoin('drivers', 'drivers.id', '=', 'receipts.driver_id')
                        ->leftJoin('business', 'business.id', '=', 'drivers.business_id')
                        ->where('receipts.to_cancel', 0)
                        ->where('business.id', $request->business_id)
                        ->get();
                
                $amount = floatval($request->amount);
                foreach($receipts as $item) {
                    if($amount == 0) {
                        break;
                    }
                    else {
                        if($item->payment <= $amount) {
                            $amount -= $item->payment;
                            $item->payment = 0;
                            $item->to_cancel = 1;
                            $item->save();

                            Payment::create([
                                'receipt_id' => $item->id,
                                'deposit_id' => $registro->id,
                                'payment' => $item->amount
                            ]);
                        }
                        else {
                            $payment = $item->payment - $amount;
                            $item->payment = $payment;
                            $item->save();

                            Payment::create([
                                'receipt_id' => $item->id,
                                'deposit_id' => $registro->id,
                                'payment' => $amount
                            ]);
                        }
                    }
                }

                DB::commit();
                return redirect()->route('deposits.index')->with('success', 'Registro creado correctamente');
            }
            else {
                DB::rollBack();
                return redirect()->route('deposits.index')->with('error', 'El usuario no posee saldo pendiente.');
            }
        }
        catch(\Exception $e) {
            DB::rollBack();
            return redirect()->route('deposits.index')->with('error', 'Ocurrio un problema al crear el depósito');
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
        return view('reports.deposits');
    }

    public function data()
    {
        $tabla = Datatables::of( Deposit::with('business')->orderBy('date','DESC')->get() )
                ->addColumn('photo', function($registro){
                    $photo = '';
                    if($registro->photo!='')
                        $photo = '<a href="'.url($registro->photo).'" target="_blank">Foto</a>';

                    return $photo;
                })
                ->rawColumns(['photo'])
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }

    public function receipts($business_id)
    {
        $records = Receipt::select('receipts.*')
                    ->leftJoin('drivers', 'drivers.id', '=', 'receipts.driver_id')
                    ->leftJoin('business', 'business.id', '=', 'drivers.business_id')
                    ->where('receipts.to_cancel', 0)
                    ->where('business.id', $business_id)
                    ->with('driver')
                    ->get();
        
        return $records;
    }
}
