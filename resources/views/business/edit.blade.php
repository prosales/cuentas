@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Editar Empresa</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'PUT','route' => ['business.update', $registro->id]]) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('owner_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Nombre del cliente</label>
                                    <input type="text" class="form-control{{ $errors->has('owner_name') ? ' is-invalid' : '' }}" name="owner_name" value="{{ $registro->owner_name }}" required>
                                    @if ($errors->has('owner_name'))
                                    <div class="invalid-feedback">{{ $errors->first('owner_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('business_name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Nombre de la empresa</label>
                                    <input type="text" class="form-control{{ $errors->has('business_name') ? ' is-invalid' : '' }}" name="business_name" value="{{ $registro->business_name }}" required>
                                    @if ($errors->has('business_name'))
                                    <div class="invalid-feedback">{{ $errors->first('business_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('gas_station_id') ? ' has-danger' : '' }}">
                                    <label for="exampleSelect1">Gasolinera</label>
                                    {{ Form::select('gas_station_id', $stations, $registro->gas_station_id, ['class'=>'form-control', 'id'=>'gas_station_id']) }}
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
