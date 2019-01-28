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
                <div class="card-header">Proyectos</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">Crear Registro</a>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <table class="table table-hover" id="table-records">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No. NOG</th>
                                    <th>Nombre</th>
                                    <th>Monto</th>
                                    <th>Municipalidad</th>
                                    <th>Lugar</th>
                                    <th>Pendiente</th>
                                    <th>Porcentaje</th>
                                    <th>Acciones</th>
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
    updateMenu('projects');
    var table = $('#table-records').DataTable({
        language: {
            url: "lang/datatables-spanish.json"
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route('projects.data') !!}',
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
        buttons: [
            {
                extend: 'excel',
                text: 'Exportar Excel',
                title: 'Reporte Proyectos'
            }
        ],
        columns: [
            {data: 'id', name: 'id'},
            {data: 'number_nog', name: 'number_nog'},
            {data: 'name', name: 'name'},
            {data: 'amount', name: 'amount'},
            {data: 'municipality', name: 'municipality'},
            {data: 'place', name: 'place'},
            {data: 'pending', name: 'pending'},
            {data: 'percentage', name: 'percentage'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'asc']]
    });
</script>
@endpush
@endsection
