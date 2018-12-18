@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Crear Usuario</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'POST','route' => 'users.store']) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Nombre</label>
                                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" required>
                                    @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Correo Electrónico</label>
                                    <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required>
                                    @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Contraseña</label>
                                    <input type="text" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                    @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('es_admin') ? ' has-danger' : '' }}">
                                    <label for="exampleSelect1">Tipo</label>
                                    <select class="form-control" name="es_admin">
                                        <option value="1">Administrador</option>
                                        <option value="0">Ingresos</option>
                                    </select>
                                    @if ($errors->has('es_admin'))
                                    <div class="invalid-feedback">{{ $errors->first('es_admin') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('gas_station_id') ? ' has-danger' : '' }}">
                                    <label for="exampleSelect1">Gasolinera</label>
                                    {{ Form::select('gas_station_id', $stations, 0, ['class'=>'form-control', 'id'=>'gas_station_id']) }}
                                    @if ($errors->has('gas_station_id'))
                                    <div class="invalid-feedback">{{ $errors->first('gas_station_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg">Guardar</button>
                                <a href="{{ route('users.index') }}" class="btn btn-danger btn-lg">Cancelar</a>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</main>
@push('scripts')
<script>
    updateMenu('users');
</script>
@endpush
@endsection
