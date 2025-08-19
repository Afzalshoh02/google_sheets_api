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
            <section id="google-sheet-form" class="app-google-sheet-form">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('google_sheets.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="spreadsheet_id">Spreadsheet ID</label>
                                        <input type="text" class="form-control" id="spreadsheet_id" name="spreadsheet_id"
                                               value="{{ old('spreadsheet_id', $spreadsheet->spreadsheet_id ?? '') }}" placeholder="Введите Spreadsheet ID">
                                        @error('spreadsheet_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>

                        @if($spreadsheet)
                            <div class="mt-3">
                                <strong>Текущий Spreadsheet ID:</strong> {{ $spreadsheet->spreadsheet_id }}
                            </div>
                        @endif
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection



