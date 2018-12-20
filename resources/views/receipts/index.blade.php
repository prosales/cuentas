@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Crear Recibo / Vale</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'POST','route' => 'receipts.store', 'files'=>true]) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('driver_id') ? ' has-danger' : '' }}">
                                    <label for="exampleSelect1">Chofer</label>
                                    {{ Form::select('driver_id', $drivers, 0, ['class'=>'form-control', 'id'=>'driver_id', 'required'=>true]) }}
                                    @if ($errors->has('driver_id'))
                                    <div class="invalid-feedback">{{ $errors->first('driver_id') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('number') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Número de Recibo</label>
                                    <input type="text" class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}" name="number" required>
                                    @if ($errors->has('number'))
                                    <div class="invalid-feedback">{{ $errors->first('number') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('plate_number') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Número de Placa</label>
                                    <input type="text" class="form-control{{ $errors->has('plate_number') ? ' is-invalid' : '' }}" name="plate_number" required>
                                    @if ($errors->has('plate_number'))
                                    <div class="invalid-feedback">{{ $errors->first('plate_number') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('amount') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Monto</label>
                                    <input type="text" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" required>
                                    @if ($errors->has('amount'))
                                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="foto">Foto Recibo</label>
                                    <input type="file" class="form-control-file"  name="foto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('type') ? ' has-danger' : '' }}">
                                    <label for="exampleSelect1">Por concepto de:</label>
                                    {{ Form::select('type', $options, 0, ['class'=>'form-control', 'id'=>'type', 'required'=>true]) }}
                                    @if ($errors->has('type'))
                                    <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('observations') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Observaciones</label>
                                    <textarea  class="form-control{{ $errors->has('observations') ? ' is-invalid' : '' }}" name="observations"></textarea>
                                    @if ($errors->has('observations'))
                                    <div class="invalid-feedback">{{ $errors->first('observations') }}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group{{ $errors->has('date') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Fecha</label>
                                    <input type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" required>
                                    @if ($errors->has('date'))
                                    <div class="invalid-feedback">{{ $errors->first('date') }}</div>
                                    @endif
                                </div>
                            </div> -->
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg">Guardar</button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</main>
@if(session('success'))
    @push('scripts')
    <script>
    alertify.success('Exito!, {{ session('success') }}');
    </script>
    @endpush
@endif
@if(session('error'))
    @push('scripts')
    <script>
    alertify.error('Espera!, {{ session('error') }}');
    </script>
    @endpush
@endif
@push('scripts')
<script>
    updateMenu('receipts');
    // var table = $('#table-records').DataTable({
    //     language: {
    //         url: "lang/datatables-spanish.json"
    //     },
    //     processing: true,
    //     serverSide: true,
    //     ajax: '{!! route('drivers.data') !!}',
    //     columns: [
    //         {data: 'id', name: 'id'},
    //         {data: 'name', name: 'name'},
    //         {data: 'plate_number', name: 'plate_number'},
    //         {data: 'action', name: 'action', orderable: false, searchable: false}
    //     ],
    //     order: [[0, 'asc']]
    // });
</script>
@endpush
@endsection
