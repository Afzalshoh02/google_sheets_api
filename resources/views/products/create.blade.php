@extends('layouts.app', [
    'title' => ' Добавить продукт',
    'description' => ' Добавить продукт',
])
@section('vendor-css')
    <link rel="stylesheet" type="text/css"
          href="/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
          href="/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endsection

@section('content-css')
    <link rel="stylesheet" type="text/css" href="/app-assets/css/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/pages/app-invoice.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/pages/app-user.css">
@endsection

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <section id="basic-datatable" class="app-product-list">
                <div class="card">
                    <div class="card-body">
                        <form id="frm_product" action="{{ route('products.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="save_product">
                            <input type="hidden" name="back_href" value="{{ url()->previous() }}">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Название продукта <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{ old('name') }}" placeholder="Введите название продукта">
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="price">Цена <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price"
                                               value="{{ old('price') }}" placeholder="Введите цену">
                                        @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="status">Статус</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="Allowed" {{ old('status') == 'Allowed' ? 'selected' : '' }}>Активный</option>
                                    <option value="Prohibited" {{ old('status') == 'Prohibited' ? 'selected' : '' }}>Неактивный</option>
                                </select>
                                @error('status')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Сохранить</button>
                            <button type="button" class="btn btn-secondary"
                                    onclick="location.replace('{{ url()->previous() }}');">Отмена
                            </button>
                        </form>
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection



