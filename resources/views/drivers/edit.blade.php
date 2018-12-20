@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Editar Chofer</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'PUT','route' => ['drivers.update', $registro->id]]) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Nombre</label>
                                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $registro->name }}" required>
                                    @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('business_id') ? ' has-danger' : '' }}">
                                    <label for="exampleSelect1">Empresa</label>
                                    {{ Form::select('business_id', $business, $registro->business_id, ['class'=>'form-control', 'id'=>'business_id']) }}
                                    @if ($errors->has('business_id'))
                                    <div class="invalid-feedback">{{ $errors->first('business_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg">Guardar</button>
                                <a href="{{ route('drivers.index') }}" class="btn btn-danger btn-lg">Cancelar</a>
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
    updateMenu('drivers');
</script>
@endpush
@endsection
