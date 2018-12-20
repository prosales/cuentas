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
            url: "/lang/datatables-spanish.json"
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route('reports.receipts') !!}',
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
            {data: 'driver.business.business_name', name: 'name', orderable: false, searchable: false},
            {data: 'driver.name', name: 'name', orderable: false, searchable: false},
            {data: 'number', name: 'number'},
            {data: 'amount', name: 'amount'},
            {data: 'payment', name: 'payment'},
            {data: 'status', name: 'status'},
            {data: 'date', name: 'date'},
            {data: 'photo', name: 'photo'}
        ],
        order: [[0, 'asc']]
    });
</script>
@endpush
@endsection
