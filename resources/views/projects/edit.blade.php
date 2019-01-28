@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Editar Proyecto</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'PUT','route' => ['projects.update', $registro->id]]) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('number_nog') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >No. NOG</label>
                                    <input type="text" class="form-control{{ $errors->has('number_nog') ? ' is-invalid' : '' }}" name="number_nog" value="{{ $registro->number_nog }}" required>
                                    @if ($errors->has('number_nog'))
                                    <div class="invalid-feedback">{{ $errors->first('number_nog') }}</div>
                                    @endif
                                </div>
                            </div>
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
                                <div class="form-group{{ $errors->has('amount') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Monto</label>
                                    <input type="number" min="0" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ $registro->amount }}" required>
                                    @if ($errors->has('amount'))
                                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('municipality') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Municipalidad</label>
                                    <input type="text" class="form-control{{ $errors->has('municipality') ? ' is-invalid' : '' }}" name="municipality" value="{{ $registro->municipality }}" required>
                                    @if ($errors->has('municipality'))
                                    <div class="invalid-feedback">{{ $errors->first('municipality') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('place') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Lugar</label>
                                    <input type="text" class="form-control{{ $errors->has('place') ? ' is-invalid' : '' }}" name="place" value="{{ $registro->place }}" required>
                                    @if ($errors->has('place'))
                                    <div class="invalid-feedback">{{ $errors->first('place') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg">Guardar</button>
                                <a href="{{ route('projects.index') }}" class="btn btn-danger btn-lg">Cancelar</a>
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
    updateMenu('projects');
</script>
@endpush
@endsection
