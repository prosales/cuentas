<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;
use App\User;

class UsersController extends Controller
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
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
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
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $request->merge(['password' => bcrypt($request->input('password')), 'business_id' => 0]);
        $registro = User::create($request->all());

        return redirect()->route('users.index')->with('success', 'Registro creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registro = User::find($id);
        return view('users.show', compact('registro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registro = User::find($id);
        return view('users.edit', compact('registro'));
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
            'email' => 'unique:users,email,'.$request->id,
        ]);
        
        $data = null;
        if($request->input('password') && $request->input('password')!=null) {
            $data = $request->merge(['password' => bcrypt($request->input('password'))]);
        }
        else {
            $data = $request->except(['password']);
        }
        $registro = User::find($id);
        $registro->update($data);
        $registro->save();

        return redirect()->route('users.index')->with('success', 'Registro actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (User::destroy($id)) {
            return redirect()->route('users.index')->with('success', 'Registro eliminado correctamente');
        } else {
            return redirect()->route('users.index')->with('error', 'Registro no se pudo eliminar');
        }
    }

    public function data()
    {
        $tabla = Datatables::of( User::all() )
                ->addColumn('action', function($registro){
                    $edit = '<a href="'.route('users.edit',$registro->id).'" class="btn btn-primary btn-sm" data-title="Editar">Editar</a> ';
                    $show = '<a href="'.route('users.show',$registro->id).'" class="btn btn-danger btn-sm" data-title="Eliminar">Eliminar</a>';
                    return $edit . $show;
                })
                ->addIndexColumn()
                ->make(true);

        return $tabla;
    }
}
