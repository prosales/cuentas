@extends('layouts.app')

@section('content')
<style>
.dt-buttons {
    margin-bottom: 25px !important;
}
.buttons-excel{
    border-style: solid;
    border-width: 0 1px 4px 1px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
    color: #fff;
    background-color: #219724;
    border-color: #1f8c22;
    cursor: pointer;
}
</style>
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Reporte de Recibos</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" >Fecha Inicio</label>
                                <input type="date" class="form-control" id="start_date">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" >Fecha Fin</label>
                                <input type="date" class="form-control" id="end_date">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label" >Empresa</label>
                                {{ Form::select('business_id', $business, 0, ['class'=>'form-control', 'id'=>'business_id']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <button class="btn btn-info" id="filtrar" >Filtrar</button>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <table class="table table-hover" id="table-records">
                            <thead>
                                <tr>
                                    <th>Empresa</th>
                                    <th>Chofer</th>
                                    <th>No. Recibo</th>
                                    <th>Monto</th>
                                    <th>Saldo Pendiente</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Url Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            </table> 
                        </div>
                    </div>
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
    updateMenu('report_receipt');
    var table = $('#table-records').DataTable({
        language: {
            url: '{!! url("lang/datatables-spanish.json") !!}'
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: '{!! route('reports.receipts') !!}',
            data: function(params) {
                params.start_date = $('#start_date').val();
                params.end_date = $('#end_date').val();
                params.business_id = $('#business_id').val();
            }
        },
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        buttons: [
            {
                extend: 'excel',
                text: 'Exportar Excel',
                title: 'Reporte recibos'
            }
        ],
        columns: [
            {data: 'driver.business.business_name', name: 'business', orderable: false, searchable: false},
            {data: 'driver.name', name: 'driver', orderable: false, searchable: false},
            {data: 'number', name: 'number'},
            {data: 'amount', name: 'amount'},
            {data: 'payment', name: 'payment'},
            {data: 'status', name: 'status'},
            {data: 'date', name: 'date'},
            {data: 'photo', name: 'photo'}
        ],
        order: [[0, 'asc']]
    });

    $('#filtrar').on('click', function(e){
        table.draw();
        e.preventDefault();
    });
</script>
@endpush
@endsection
