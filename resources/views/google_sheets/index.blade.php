@extends('layouts.app', [
    'title' => 'Настройки Google Sheets',
    'description' => 'Управление интеграцией с Google Sheets',
])
@section('vendor-css')
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
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
    <style>
        .google-sheet-card {
            border-radius: 12px;
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
            border: none;
        }
        .google-sheet-card .card-header {
            background-color: #4285F4;
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.5rem;
            border-bottom: none;
        }
        .google-sheet-card .card-body {
            padding: 2rem;
        }
        .sheet-icon {
            color: #4285F4;
            font-size: 1.5rem;
            margin-right: 10px;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #DFE3E7;
        }
        .form-control:focus {
            border-color: #4285F4;
            box-shadow: 0 0 0 0.2rem rgba(66, 133, 244, 0.25);
        }
        .btn-save {
            background-color: #4285F4;
            border: none;
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-save:hover {
            background-color: #3367D6;
            transform: translateY(-2px);
        }
        .current-sheet {
            background-color: #F8FAFC;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #4285F4;
        }
        .help-text {
            color: #6C757D;
            font-size: 0.85rem;
            margin-top: 5px;
        }
        .input-group-text {
            background-color: #F1F3F5;
            border: 1px solid #DFE3E7;
        }
    </style>
@endsection

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Интеграция с Google Sheets</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Главная</a></li>
                                    <li class="breadcrumb-item active">Настройки Google Sheets</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section id="google-sheet-form" class="app-google-sheet-form">
                <div class="card google-sheet-card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fab fa-google sheet-icon"></i> Настройки подключения
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('google_sheets.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="spreadsheet_id" class="font-weight-bold">Spreadsheet ID</label>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="spreadsheet_id" name="spreadsheet_id"
                                                   value="{{ old('spreadsheet_id', $spreadsheet->spreadsheet_id ?? '') }}"
                                                   placeholder="Введите ID таблицы Google Sheets">
                                        </div>
                                        <small class="help-text">
                                            Пример: 1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms
                                        </small>
                                        @error('spreadsheet_id')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <button type="submit" class="btn btn-save">
                                    <i class="fas fa-save mr-1"></i> Сохранить настройки
                                </button>

                                @if($spreadsheet)
                                    <div class="current-sheet flex-grow-1 ml-4">
                                        <strong><i class="fas fa-info-circle text-primary mr-1"></i> Текущий Spreadsheet ID:</strong>
                                        <code>{{ $spreadsheet->spreadsheet_id }}</code>
                                    </div>
                                @endif
                            </div>
                        </form>

                        <div class="mt-4 pt-3 border-top">
                            <h5 class="font-weight-bold"><i class="fas fa-question-circle text-primary mr-2"></i>Как получить Spreadsheet ID?</h5>
                            <ol class="pl-2">
                                <li>Откройте нужную таблицу в Google Sheets</li>
                                <li>Посмотрите в адресную строку браузера</li>
                                <li>Скопируйте ID между "/d/" и "/edit"</li>
                            </ol>
                            <p class="text-muted mb-0">
                                Пример: <code>https://docs.google.com/spreadsheets/d/<span class="text-danger">1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms</span>/edit#gid=0</code>
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
