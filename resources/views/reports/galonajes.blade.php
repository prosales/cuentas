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
                <div class="card-header">Reporte de Galonajes</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" >Mes</label>
                                {{ Form::select('month', $months, 0, ['class'=>'form-control', 'id'=>'month']) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="form-control-label" >Año</label>
                                {{ Form::select('year', $years, 0, ['class'=>'form-control', 'id'=>'year']) }}
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
                                    <th>Gasolina</th>
                                    <th>Galonaje</th>
                                    <th>Mes</th>
                                    <th>Año</th>
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
    updateMenu('report_galonaje');
    var table = $('#table-records').DataTable({
        language: {
            url: "/lang/datatables-spanish.json"
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: '{!! route('reports.galonajes') !!}',
            data: function(params) {
                params.month = $('#month').val();
                params.year = $('#year').val();
            }
        },
        dom: 'Blfrtip',
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        buttons: [
            {
                extend: 'excel',
                text: 'Exportar Excel',
                title: 'Reporte galonajes'
            }
        ],
        columns: [
            {data: 'type', name: 'type', orderable: false, searchable: false},
            {data: 'total', name: 'total', orderable: false, searchable: false},
            {data: 'month', name: 'month', orderable: false, searchable: false},
            {data: 'year', name: 'year', orderable: false, searchable: false}
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
