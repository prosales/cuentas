@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Crear Ingreso</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'POST','route' => 'incomes.store']) }}
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
                                <div class="form-group{{ $errors->has('bank_id') ? ' has-danger' : '' }}">
                                    <label for="exampleSelect1">Banco</label>
                                    {{ Form::select('bank_id', $banks, 0, ['class'=>'form-control', 'id'=>'bank_id', 'required'=>true]) }}
                                    @if ($errors->has('bank_id'))
                                    <div class="invalid-feedback">{{ $errors->first('project_id') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('check_amount') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Monto Cheque</label>
                                    <input type="text" class="form-control{{ $errors->has('check_amount') ? ' is-invalid' : '' }}" name="check_amount" value="{{old('check_amount')}}" required>
                                    @if ($errors->has('check_amount'))
                                    <div class="invalid-feedback">{{ $errors->first('check_amount') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('date') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Fecha</label>
                                    <input type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" value="{{old('date')}}" required>
                                    @if ($errors->has('date'))
                                    <div class="invalid-feedback">{{ $errors->first('date') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('invoice') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >No. Factura</label>
                                    <input type="text" class="form-control{{ $errors->has('invoice') ? ' is-invalid' : '' }}" name="invoice" value="{{old('invoice')}}" >
                                    @if ($errors->has('invoice'))
                                    <div class="invalid-feedback">{{ $errors->first('invoice') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('invoice_amount') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Monto Factura</label>
                                    <input type="number" min="0" class="form-control{{ $errors->has('invoice_amount') ? ' is-invalid' : '' }}" name="invoice_amount" value="{{old('invoice_amount')}}" >
                                    @if ($errors->has('invoice_amount'))
                                    <div class="invalid-feedback">{{ $errors->first('invoice_amount') }}</div>
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
