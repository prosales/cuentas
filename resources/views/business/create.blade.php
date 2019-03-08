@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Crear Empresa</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'POST','route' => 'business.store']) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('owner_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Nombre del cliente</label>
                                    <input type="text" class="form-control{{ $errors->has('owner_name') ? ' is-invalid' : '' }}" name="owner_name" required>
                                    @if ($errors->has('owner_name'))
                                    <div class="invalid-feedback">{{ $errors->first('owner_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('business_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Nombre de la empresa</label>
                                    <input type="text" class="form-control{{ $errors->has('business_name') ? ' is-invalid' : '' }}" name="business_name" required>
                                    @if ($errors->has('business_name'))
                                    <div class="invalid-feedback">{{ $errors->first('business_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('nit') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >NIT</label>
                                    <input type="text" class="form-control{{ $errors->has('nit') ? ' is-invalid' : '' }}" name="nit" required>
                                    @if ($errors->has('nit'))
                                    <div class="invalid-feedback">{{ $errors->first('nit') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Dirección</label>
                                    <input type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" required>
                                    @if ($errors->has('address'))
                                    <div class="invalid-feedback">{{ $errors->first('address') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Teléfono</label>
                                    <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" required>
                                    @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Correo electrónico</label>
                                    <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required>
                                    @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
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
                                <a href="{{ route('business.index') }}" class="btn btn-danger btn-lg">Cancelar</a>
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
    updateMenu('business');
</script>
@endpush
@endsection
