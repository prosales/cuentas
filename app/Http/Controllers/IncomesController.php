<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use DB;
use App\Project;
use App\Bank;
use App\Income;

class IncomesController extends Controller
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
        $projects = Project::select(DB::raw("CONCAT(number_nog,' - ',name) as name"),'id')->pluck('name','id');
        $projects[0] = 'Seleccione';
        $banks = Bank::select('name','id')->pluck('name','id');
        $banks[0] = 'Seleccione';
        return view('incomes.index', compact('projects', 'banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::select(DB::raw("CONCAT(number_nog,' - ',name) as name"),'id')->pluck('name','id');
        $projects[0] = 'Seleccione';
        $banks = Bank::select('name','id')->pluck('name','id');
        $banks[0] = 'Seleccione';
        return view('incomes.create', compact('projects', 'banks'));
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
            $validator = Validator::make($request->all(), [
                'project_id' => 'required',
                'bank_id' => 'required',
                'check_amount' => 'required',
                'date' => 'required'
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
            }

            if(!$request->invoice) {
                $request->merge(['invoice' => '-']);
            }

            if(!$request->invoice_amount) {
                $request->merge(['invoice_amount' => 0]);
            }

            $project = Project::find($request->project_id);
            $project->balance += $request->check_amount;

            $percentage = (100 * $project->balance) / $project->amount;
            $project->percentage = round($percentage, 2);

            $project->save();

            $registro = Income::create($request->all());
            
            DB::commit();
            return redirect()->route('incomes.index')->with('success', 'Registro creado correctamente');
        }
        catch(\Exception $e) {
            DB::rollBack();
            return redirect()->route('incomes.index')->with('error', $e->getMessage());
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

    public function data(Request $request)
    {
        $where = $request->start_date && $request->end_date ? "date BETWEEN '".$request->start_date."' AND '".$request->end_date."'" : "TRUE";
        $where = $request->project_id ? $where." AND project_id = ".$request->project_id : $where;
        $where = $request->bank_id ? $where." AND bank_id = ".$request->bank_id : $where;

        $records = Income::with('project', 'bank')->whereRaw($where)->get();
        
        $tabla = Datatables::of( $records )
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }
}
