<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use DB;
use App\Project;
use App\Expense;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ExpensesController extends Controller
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
        return view('expenses.index', compact('projects'));
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
        return view('expenses.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'required',
                'detail' => 'required',
                'amount' => 'required',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
            }

            if($request->file('foto')) {
                $imagen = $request->file('foto');
                $nombre_imagen = time().'_'.str_random(10).'.'.$imagen->getClientOriginalExtension();
                Storage::disk('photos')->put($nombre_imagen,File::get($imagen), 'public');
                $request->merge(['photo' => 'photos/'.$nombre_imagen]);
            }
            else {
                $request->merge(['photo' => '']);
            }

            $registro = Expense::create($request->all());
            
            return redirect()->route('expenses.index')->with('success', 'Registro creado correctamente');
        }
        catch(\Exception $e) {
            return redirect()->route('expenses.index')->with('error', 'Ocurrio un problema al registrar el recibo.');
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
        $registro = Expense::find($id);

        return view('expenses.show', compact('registro'));
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
        $registro = Expense::find($id);
        if ($registro->delete()) {
            return redirect()->route('expenses.index')->with('success', 'Registro eliminado correctamente');
        } else {
            return redirect()->route('expenses.index')->with('error', 'Registro no se pudo eliminar');
        }
    }

    public function data(Request $request)
    {
        $records = Expense::with('project')->get();
        
        $tabla = Datatables::of( $records )
                ->addColumn('amount', function($registro){
                            
                    return 'Q '.number_format($registro->amount,2,'.',',');
                })
                ->addColumn('photo', function($registro){
                    $photo = '';
                    if($registro->photo!='')
                        $photo = '<a href="'.url($registro->photo).'" target="_blank">Foto</a>';

                    return $photo;
                })
                ->addColumn('action', function($registro){
                    
                    $show = '<a href="'.route('expenses.show',$registro->id).'" class="btn btn-danger btn-sm" data-title="Eliminar"><i class="fa fa-trash"></i></a>';
                    return $show;
                })
                ->rawColumns(['photo','action'])
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }
}
