@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Bancos</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('banks.create') }}" class="btn btn-primary btn-sm">Crear Registro</a>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <table class="table table-hover" id="table-records">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
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
    updateMenu('banks');
    var table = $('#table-records').DataTable({
        language: {
            url: "lang/datatables-spanish.json"
        },
        processing: true,
        serverSide: true,
        ajax: '{!! route('banks.data') !!}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'asc']]
    });
</script>
@endpush
@endsection
