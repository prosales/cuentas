@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Crear Gasto</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'POST','route' => 'expenses.store', 'files'=>true]) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('project_id') ? ' has-danger' : '' }}">
                                    <label for="exampleSelect1">Proyecto</label>
                                    {{ Form::select('project_id', $projects, 0, ['class'=>'form-control', 'id'=>'project_id', 'required'=>true]) }}
                                    @if ($errors->has('project_id'))
                                    <div class="invalid-feedback">{{ $errors->first('project_id') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('detail') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Detalle</label>
                                    <textarea class="form-control{{ $errors->has('detail') ? ' is-invalid' : '' }}" name="detail" required>{{old('detail')}}</textarea>
                                    @if ($errors->has('detail'))
                                    <div class="invalid-feedback">{{ $errors->first('detail') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('amount') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Monto</label>
                                    <input type="number" min="0" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{old('amount')}}" required>
                                    @if ($errors->has('amount'))
                                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('foto') ? ' has-danger' : '' }}">
                                    <label for="foto">Foto</label>
                                    <input type="file" class="form-control-file{{ $errors->has('foto') ? ' is-invalid' : '' }}"  name="foto" value="{{old('foto')}}">
                                    @if ($errors->has('foto'))
                                    <div class="invalid-feedback">{{ $errors->first('foto') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg">Guardar</button>
                                <a href="{{ route('expenses.index') }}" class="btn btn-danger btn-lg">Cancelar</a>
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
    updateMenu('expenses');
</script>
@endpush
@endsection
