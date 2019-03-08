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
                <div class="card-header">Gastos</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">Crear Registro</a>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 25px;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-control-label" >Proyecto</label>
                                {{ Form::select('project_id', $projects, 0, ['class'=>'form-control', 'id'=>'project_id']) }}
                            </div>
                        </div>
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
                                    <th>#</th>
                                    <th>No. NOG</th>
                                    <th>Proyecto</th>
                                    <th>Detalle</th>
                                    <th>Monto</th>
                                    <th>Foto</th>
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
    updateMenu('expenses');
    var table = $('#table-records').DataTable({
        language: {
            url: "lang/datatables-spanish.json"
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: '{!! route('expenses.data') !!}',
            data: function(params) {
                params.project_id = $('#project_id').val();
                params.start_date = $('#start_date').val();
                params.end_date = $('#end_date').val();
            }
        },
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "Todos"]],
        buttons: [
            {
                extend: 'excel',
                text: 'Exportar Excel',
                title: 'Reporte Gastos'
            }
        ],
        columns: [
            {data: 'id', name: 'id'},
            {data: 'project.number_nog', name: 'number_nog', orderable: false, searchable: false},
            {data: 'project.name', name: 'name', orderable: false, searchable: false},
            {data: 'detail', name: 'detail'},
            {data: 'amount', name: 'amount'},
            {data: 'photo', name: 'photo'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
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
