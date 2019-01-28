<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use App\Bank;

class BanksController extends Controller
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
        return view('banks.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banks.create');
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
            'name' => 'required|max:255'
        ]);

        $registro = Bank::create($request->all());

        return redirect()->route('banks.index')->with('success', 'Registro creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registro = Banks::find($id);
        return view('banks.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = Bank::find($id);
        return view('banks.edit', compact('registro'));
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
            'name' => 'required|max:255'
        ]);
        
        $registro = Bank::find($id);
        $registro->update($request->all());
        $registro->save();

        return redirect()->route('banks.index')->with('success', 'Registro actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Bank::destroy($id)) {
            return redirect()->route('banks.index')->with('success', 'Registro eliminado correctamente');
        } else {
            return redirect()->route('banks.index')->with('error', 'Registro no se pudo eliminar');
        }
    }

    public function data()
    {
        $records = Bank::all();

        $tabla = Datatables::of( $records )
                ->addColumn('action', function($registro){
                    $edit = '<a href="'.route('banks.edit',$registro->id).'" class="btn btn-primary btn-sm" data-title="Editar">Editar</a> ';
                    $show = '<a href="'.route('banks.show',$registro->id).'" class="btn btn-danger btn-sm" data-title="Eliminar">Eliminar</a>';
                    return $edit . $show;
                })
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }
}
