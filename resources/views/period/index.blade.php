@extends('layout')

@section('title')
<title>Periodos</title>
@endsection

@section('content')
<div class="container">
    <div class="col-lg-12">
        @include('includes.alert')
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h5 class="m-0 font-weight-bold text-primary">Periodos Academicos</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-secondary">
                        <th>#</th>
                        <th>Periodo</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($periods as $period)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $period->name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection