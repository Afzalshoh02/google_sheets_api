@extends('layouts.app', [
    'title' => 'Добавить продукт',
    'description' => 'Создание нового продукта',
])
@section('vendor-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
@endsection

@section('content-css')
    <style>
        :root {
            --primary-color: #7367F0;
            --primary-light: #EAE8FF;
            --success-color: #28C76F;
            --danger-color: #EA5455;
            --text-dark: #3A3B45;
            --text-light: #6E6B7B;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
            transition: all 0.3s ease;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(34, 41, 47, 0.05);
            padding: 1.5rem;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        }

        .card-title {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0;
        }

        .form-control {
            height: calc(2.75rem + 2px);
            border-radius: 8px;
            border: 1px solid #D8D6DE;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);
        }

        label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .required-star {
            color: var(--danger-color);
            margin-left: 4px;
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.786rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px 0 rgba(34, 41, 47, 0.12);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #5F52E6;
            border-color: #5F52E6;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px 0 rgba(34, 41, 47, 0.15);
        }

        .btn-secondary {
            background-color: white;
            border-color: #D8D6DE;
            color: var(--text-dark);
        }

        .btn-secondary:hover {
            background-color: #F8F8F8;
            border-color: #D8D6DE;
            color: var(--text-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px 0 rgba(34, 41, 47, 0.15);
        }

        .text-danger {
            color: var(--danger-color) !important;
            font-size: 0.857rem;
            margin-top: 0.5rem;
        }

        .form-section {
            padding: 2rem;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .input-icon input {
            padding-left: 2.5rem;
        }

        .select-icon {
            position: relative;
        }

        .select-icon i {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: var(--text-light);
            pointer-events: none;
        }

        .select-icon select {
            padding-left: 2.5rem;
            appearance: none;
            -webkit-appearance: none;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--text-light);
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: var(--primary-color);
            text-decoration: none;
        }

        .back-link i {
            margin-right: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="app-content content animate__animated animate__fadeIn">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-12 mb-2">
                    <div class="breadcrumbs-top">
                        <a href="{{ url()->previous() }}" class="back-link">
                            <i class="fas fa-arrow-left"></i> Назад к списку продуктов
                        </a>
                    </div>
                </div>
            </div>

            <section id="product-create">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fas fa-plus-circle mr-1"></i> Добавить новый продукт</h2>
                    </div>

                    <div class="form-section">
                        <form id="frm_product" action="{{ route('products.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="save_product">
                            <input type="hidden" name="back_href" value="{{ url()->previous() }}">

                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="form-group input-icon">
                                        <i class="fas fa-tag"></i>
                                        <label for="name">Название продукта <span class="required-star">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{ old('name') }}" placeholder="Введите название продукта">
                                        @error('name')
                                        <div class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <div class="form-group input-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                        <label for="price">Цена <span class="required-star">*</span></label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price"
                                               value="{{ old('price') }}" placeholder="0.00">
                                        @error('price')
                                        <div class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group select-icon">
                                        <i class="fas fa-toggle-on"></i>
                                        <label for="status">Статус</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="Allowed" {{ old('status') == 'Allowed' ? 'selected' : '' }}>Активный</option>
                                            <option value="Prohibited" {{ old('status') == 'Prohibited' ? 'selected' : '' }}>Неактивный</option>
                                        </select>
                                        @error('status')
                                        <div class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end mt-2">
                                    <button type="button" class="btn btn-secondary mr-1"
                                            onclick="location.replace('{{ url()->previous() }}');">
                                        <i class="fas fa-times mr-1"></i> Отмена
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save mr-1"></i> Сохранить продукт
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('content-js')
    <script>
        // Add animations to form elements
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('animate__animated', 'animate__pulse');
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('animate__animated', 'animate__pulse');
            });
        });

        // Form validation animation
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            document.querySelectorAll('[required]').forEach(input => {
                if (!input.value) {
                    input.classList.add('animate__animated', 'animate__shakeX');
                    setTimeout(() => {
                        input.classList.remove('animate__animated', 'animate__shakeX');
                    }, 1000);
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
@endsection
