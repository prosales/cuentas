@extends('layouts.app')

@section('content')
<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 color">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Eliminar Usuario</div>

                <div class="card-body">
                    {{ Form::open(['method' => 'DELETE','route' => ['users.destroy', $registro->id]]) }}
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Estas seguro de eliminar el registro ?</h3>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-lg">Eliminar</button>
                                <a href="{{ route('users.index') }}" class="btn btn-danger btn-lg">Cancelar</a>
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
    updateMenu('users');
</script>
@endpush
@endsection
