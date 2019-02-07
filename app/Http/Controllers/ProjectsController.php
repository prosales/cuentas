<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use App\Project;

class ProjectsController extends Controller
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
        return view('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'number_nog.unique' => 'El número de NOG ingresado ya existe.'
        ];

        $validator = Validator::make($request->all(), [
            'number_nog' => 'required|unique:projects,number_nog',
            'name' => 'required|max:191',
            'amount' => 'required',
            'municipality' => 'required',
            'place' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withInput()
                        ->withErrors($validator);
        }

        $request->merge([
            'balance' => 0,
            'percentage' => 0
        ]);
        $registro = Project::create($request->all());

        return redirect()->route('projects.index')->with('success', 'Registro creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registro = Project::find($id);
        return view('projects.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Project::find($id);
        return view('projects.edit', compact('registro'));
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
        $messages = [
            'number_nog.unique' => 'El número de NOG ingresado ya existe.'
        ];

        $validator = Validator::make($request->all(), [
            'number_nog' => 'required|unique:projects,number_nog,'.$id,
            'name' => 'required|max:191',
            'amount' => 'required',
            'municipality' => 'required',
            'place' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withInput()
                        ->withErrors($validator);
        }
        
        $registro = Project::find($id);
        $registro->update($request->all());
        $registro->save();

        return redirect()->route('projects.index')->with('success', 'Registro actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Project::destroy($id)) {
            return redirect()->route('projects.index')->with('success', 'Registro eliminado correctamente');
        } else {
            return redirect()->route('projects.index')->with('error', 'Registro no se pudo eliminar');
        }
    }

    public function data()
    {
        $records = Project::select(
            'projects.*',
            \DB::raw('(SELECT SUM(amount) FROM expenses WHERE expenses.project_id = projects.id) as expenses')
        );

        $tabla = Datatables::of( $records )
                ->addColumn('amount', function($registro){
                    return 'Q '.number_format($registro->amount,0,'.',',');
                })
                ->addColumn('pending', function($registro){
                    return 'Q '.number_format(($registro->amount - $registro->balance),0,'.',',');
                })
                ->addColumn('expenses', function($registro){
                    return 'Q '.number_format($registro->expenses,0,'.',',');
                })
                ->addColumn('remaining', function($registro){
                    return '<b style="color: red;">Q '.number_format(($registro->balance - $registro->expenses),0,'.',',').'</b>';
                })
                ->addColumn('percentage', function($registro){
                    return $registro->percentage." %";
                })
                ->addColumn('action', function($registro){
                    $edit = '<a href="'.route('projects.edit',$registro->id).'" class="btn btn-primary btn-sm" data-title="Editar">Editar</a> ';
                    $show = '<a href="'.route('projects.show',$registro->id).'" class="btn btn-danger btn-sm" data-title="Eliminar">Eliminar</a>';
                    return $edit . $show;
                })
                ->addIndexColumn()
                ->rawColumns(['remaining', 'action'])
                ->make(true);

        return $tabla;
    }
}
