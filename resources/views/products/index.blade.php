@extends('layouts.app', [
  'title' => "Products",
  'description' => "Products",
])
@section('vendor-css')

@endsection

@section('content-css')

@endsection

@section('content')
    <div class="app-content content">
        <div class="content-header row">
            <div class="content-header-right text-md-right col-12 d-md-block d-none">
                <div class="form-group breadcrumb-right">
                    <div class="dropdown">
                        <a href='{{route('products.create')}}' class='btn btn-primary btn-round waves-effect'>
                            Добавить продукт
                        </a>
                        <a href='{{route('generate.records')}}' class='btn btn-primary btn-round waves-effect'>
                            Сгенерировать 1000
                        </a>
                        <a href='{{route('truncate.records')}}' class='btn btn-primary btn-round waves-effect'>
                            Очистить таблицу
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <section id="basic-datatable" class="app-user-list">
                <div class="card">
                    <div class="card-body">
                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <table class="datatables-basic table">
                                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        <table class="user-list-table table dataTable no-footer dtr-column table-bordered"
                                               id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>№</th>
                                                <th>Название</th>
                                                <th>Цена</th>
                                                <th>Статус</th>
                                                <th>Действия</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($products as $product)
                                                <tr>
                                                    <td>{{ $product->id }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ number_format($product->price, 2) }}</td>
                                                    <td>
                                                        @if($product->status == 'Allowed')
                                                            <span class="badge badge-success">Разрешено</span>
                                                        @else
                                                            <span class="badge badge-danger">Запрещено</span>
                                                        @endif
                                                    </td>
                                                    <td align="center">
                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                            <a type="button" class="btn bg-primary btn-outline-primary text-white"
                                                                     href='{{route('products.edit', $product)}}'>
                                                               Изменить
                                                            </a>
                                                            <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>

                                                            <a type="button" class="btn bg-danger btn-outline-danger text-white"
                                                               onclick="event.preventDefault(); if(confirm('Вы уверены, что хотите удалить этот продукт?')) document.getElementById('delete-form-{{ $product->id }}').submit();">
                                                                Удалить
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div class="pagination justify-content-end mt-2" style="padding: 10px;">
                                            {{ $products->links() }}
                                        </div>
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
@endsection

@section('vendor-js')

@endsection

@section('content-js')


@endsection
