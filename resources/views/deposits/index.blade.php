@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Crear Depósito</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'POST','route' => 'deposits.store', 'files'=>true]) }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('business_id') ? ' has-danger' : '' }}">
                                    <label for="exampleSelect1">Empresa</label>
                                    {{ Form::select('business_id', $business, 0, ['class'=>'form-control', 'id'=>'business_id']) }}
                                    @if ($errors->has('business_id'))
                                    <div class="invalid-feedback">{{ $errors->first('business_id') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('number') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Número de Boleta</label>
                                    <input type="text" class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}" name="number" value="{{old('number')}}" required>
                                    @if ($errors->has('number'))
                                    <div class="invalid-feedback">{{ $errors->first('number') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('amount') ? ' has-danger' : '' }}">
                                    <label class="form-control-label" >Monto</label>
                                    <input type="text" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{old('amount')}}" required>
                                    @if ($errors->has('amount'))
                                    <div class="invalid-feedback">{{ $errors->first('amount') }}</div>
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
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('foto') ? ' has-danger' : '' }}">
                                    <label for="excel">Foto Boleta</label>
                                    <input type="file" class="form-control-file{{ $errors->has('foto') ? ' is-invalid' : '' }}"  name="foto" required>
                                    @if ($errors->has('foto'))
                                    <div class="invalid-feedback">{{ $errors->first('foto') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg">Guardar</button>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-12">
                                <table class="table table-hover" id="table-records">
                                <thead>
                                    <tr>
                                        <th>Chofer</th>
                                        <th>Número de placa</th>
                                        <th>Número de Recibo</th>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Pendiente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                </table> 
                            </div>
                        </div>
                        <hr/>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <label style="font-size: 20px; font-weight: bold;">Total: </label> <span id="total" style="font-size: 20px; margin-left: 25px;">Q 0</span>
                            </div>
                        </div>
                        <hr/>

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
    updateMenu('deposits');

    $('#business_id').on('change', update_receipts);

    function update_receipts(e) {

        if(e!=null)
            e.preventDefault();

        var id = $(this).val();
        var total = 0;
        $.ajax({
            type: 'GET',
            url: '{{url('receipts')}}' + '/' + id + '/pending',
            dataType: 'JSON',
            success: function(data) {
                table = $('#table-records > tbody');
                table.empty();
                $('#total').text('Q 0');
                $.each(data, function(key, value) {
                    table.append(
                        '<tr>' +
                            '<td>'+value.driver.name+'</td>' +
                            '<td>'+value.plate_number+'</td>' +
                            '<td>'+value.number+'</td>' +
                            '<td>'+value.date+'</td>' +
                            '<td>'+value.amount+'</td>' +
                            '<td>'+value.payment+'</td>' +
                        '<tr/>'
                    );

                    total += value.payment;
                    $('#total').text('Q '+total);
                });
            },
            error: function() {

            }
        });
    }
    
    function validar() {
        business_id = $('#business_id').val();
        var total = 0;
        if(business_id > 0) {
            $.ajax({
                type: 'GET',
                url: '{{url('receipts')}}' + '/' + business_id + '/pending',
                dataType: 'JSON',
                success: function(data) {
                    table = $('#table-records > tbody');
                    table.empty();
                    $('#total').text('Q 0');
                    $.each(data, function(key, value) {
                        table.append(
                            '<tr>' +
                                '<td>'+value.driver.name+'</td>' +
                                '<td>'+value.plate_number+'</td>' +
                                '<td>'+value.number+'</td>' +
                                '<td>'+value.date+'</td>' +
                                '<td>'+value.amount+'</td>' +
                                '<td>'+value.payment+'</td>' +
                            '<tr/>'
                        );
                        total += value.payment;
                        $('#total').text('Q '+total);
                    });
                },
                error: function() {

                }
            });
        }
    }
    validar();

</script>
@endpush
@endsection
