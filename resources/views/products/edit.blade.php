@extends('layouts.app', [
    'title' => 'Редактировать продукт',
    'description' => 'Редактирование информации о продукте',
])
@section('vendor-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.5/sweetalert2.min.css">
@endsection

@section('content-css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            --secondary-gradient: linear-gradient(135deg, #899FFE 0%, #A972FF 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --danger-gradient: linear-gradient(135deg, #FF758C 0%, #FF7EB3 100%);
            --page-bg: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.95);
        }

        body {
            background: var(--page-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .app-content {
            background: transparent;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            background: var(--card-bg);
            backdrop-filter: blur(8px);
            overflow: hidden;
            transition: all 0.3s ease;
            border-left: 5px solid #6B73FF;
        }

        .card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        .card-title {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 0;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e0e6ed;
            transition: all 0.3s ease;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #6B73FF;
            box-shadow: 0 0 0 0.2rem rgba(107, 115, 255, 0.25);
        }

        .form-group label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .btn {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background: var(--primary-gradient);
            background-size: 200% auto;
        }

        .btn-secondary {
            background: var(--secondary-gradient);
            background-size: 200% auto;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0, 0, 0, 0.1);
            background-position: right center;
        }

        .text-danger {
            color: #FF758C !important;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .required-star {
            color: #FF758C;
            font-size: 1.2em;
            vertical-align: middle;
        }

        .form-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
        }

        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 10px;
            color: #6B73FF;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 2rem;
        }

        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(rgba(107, 115, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            z-index: -1;
            opacity: 0.3;
        }

        .select2-container--default .select2-selection--single {
            height: auto;
            padding: 12px 15px;
            border: 1px solid #e0e6ed;
            border-radius: 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="bg-pattern"></div>
    <div class="app-content content animate__animated animate__fadeIn">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Редактирование продукта</h2>
                        </div>
                    </div>
                </div>
            </div>

            <section id="product-edit-form">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-edit mr-1"></i> Основная информация
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="frm_product" action="{{ route('products.update', $product->id) }}" method="POST" class="animate__animated animate__fadeIn">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="back_href" value="{{ url()->previous() }}">

                            <div class="form-section">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">
                                                Название продукта <span class="required-star">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light-primary border-primary">
                                                        <i class="fas fa-box-open text-primary"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control" id="name" name="name"
                                                       value="{{ old('name', $product->name) }}"
                                                       placeholder="Введите название продукта">
                                            </div>
                                            @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <div class="form-group">
                                            <label for="price">
                                                Цена <span class="required-star">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-light-success border-success">
                                                        <i class="fas fa-tag text-success"></i>
                                                    </span>
                                                </div>
                                                <input type="number" step="0.01" class="form-control" id="price" name="price"
                                                       value="{{ old('price', $product->price) }}"
                                                       placeholder="Введите цену">
                                            </div>
                                            @error('price')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="form-section-title">
                                    <i class="fas fa-cog"></i> Дополнительные параметры
                                </h5>
                                <div class="form-group">
                                    <label for="status">Статус продукта</label>
                                    <select class="form-control select2" id="status" name="status">
                                        <option value="Allowed" {{ old('status', $product->status) == 'Allowed' ? 'selected' : '' }}>Активный</option>
                                        <option value="Prohibited" {{ old('status', $product->status) == 'Prohibited' ? 'selected' : '' }}>Неактивный</option>
                                    </select>
                                    @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="action-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i> Обновить
                                </button>
                                <button type="button" class="btn btn-secondary" id="cancel-btn">
                                    <i class="fas fa-times mr-1"></i> Отмена
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('vendor-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.5/sweetalert2.all.min.js"></script>
@endsection

@section('content-js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cancel button functionality
            document.getElementById('cancel-btn').addEventListener('click', function() {
                window.location.href = document.querySelector('input[name="back_href"]').value;
            });

            // Form submission with confirmation
            document.getElementById('frm_product').addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Обновить продукт?',
                    text: "Вы уверены, что хотите сохранить изменения?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6B73FF',
                    cancelButtonColor: '#FF758C',
                    confirmButtonText: 'Да, обновить!',
                    cancelButtonText: 'Отмена'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            // Add animations to form elements
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('animate__animated', 'animate__pulse');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('animate__animated', 'animate__pulse');
                });
            });
        });
    </script>
@endsection
