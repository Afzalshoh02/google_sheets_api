@extends('layouts.app', [
  'title' => "Product Management",
  'description' => "Manage your product catalog",
])
@section('vendor-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection

@section('content-css')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
            --danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.1);
        }

        .action-btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0,0,0,0.15);
        }

        .btn-primary {
            background: var(--primary-gradient);
        }

        .btn-success {
            background: var(--success-gradient);
        }

        .btn-danger {
            background: var(--danger-gradient);
        }

        .badge {
            padding: 0.5em 0.8em;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.8em;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table thead th {
            border: none;
            background-color: #f8f9fa;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .table td {
            vertical-align: middle;
            border-top: none;
            border-bottom: none;
        }

        .table td:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .table td:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: transparent;
        }

        .pagination .page-link {
            border: none;
            color: #6c757d;
            margin: 0 5px;
            border-radius: 8px !important;
        }

        .content-header {
            padding: 1.5rem 2rem 0;
        }

        .content-wrapper {
            padding: 0 2rem 2rem;
        }

        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            letter-spacing: 0.5px;
        }
    </style>
@endsection

@section('content')
    <div class="app-content content animate__animated animate__fadeIn">
        <div class="content-header row">
            <div class="content-header-right text-md-right col-12 d-md-block d-none">
                <div class="form-group breadcrumb-right">
                    <div class="btn-group" role="group">
                        <a href='{{route('products.create')}}' class='btn btn-primary btn-round action-btn mr-2'>
                            <i class="fas fa-plus mr-1"></i> Add Product
                        </a>
                        <div class="btn-group" role="group">
                            <button id="bulkActions" type="button" class="btn btn-light dropdown-toggle action-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog mr-1"></i> Bulk Actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="bulkActions">
                                <a class="dropdown-item" href='{{route('generate.records')}}'>
                                    <i class="fas fa-bolt mr-1 text-warning"></i> Generate 1000 Records
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href='{{route('truncate.records')}}' onclick="return confirm('Are you sure you want to clear ALL products?')">
                                    <i class="fas fa-trash-alt mr-1"></i> Clear Table
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <section id="products-datatable">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr class="animate__animated animate__fadeIn">
                                        <td class="font-weight-bold">#{{ $product->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-light-primary rounded mr-2">
                                                    <div class="avatar-content">
                                                        <i class="fas fa-box-open font-medium-3"></i>
                                                    </div>
                                                </div>
                                                <span>{{ $product->name }}</span>
                                            </div>
                                        </td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                        <td>
                                            @if($product->status == 'Allowed')
                                                <span class="badge badge-pill badge-light-success status-badge">
                                                <i class="fas fa-check-circle mr-1"></i> Active
                                            </span>
                                            @else
                                                <span class="badge badge-pill badge-light-danger status-badge">
                                                <i class="fas fa-times-circle mr-1"></i> Inactive
                                            </span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group" role="group">
                                                <a href='{{route('products.edit', $product)}}' class="btn btn-sm btn-primary action-btn mr-1">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button class="btn btn-sm btn-danger action-btn"
                                                        onclick="event.preventDefault();
                                                if(confirm('Are you sure you want to delete {{ $product->name }}?')) {
                                                    document.getElementById('delete-form-{{ $product->id }}').submit();
                                                }">
                                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                            </div>
                            <div class="pagination justify-content-end">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('vendor-js')
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection

@section('content-js')
    <script>
        // Add nice animations when interacting with elements
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                btn.classList.add('animate__animated', 'animate__pulse');
            });
            btn.addEventListener('mouseleave', () => {
                btn.classList.remove('animate__animated', 'animate__pulse');
            });
        });

        // Confirmation for bulk actions
        document.querySelectorAll('.bulk-action').forEach(item => {
            item.addEventListener('click', event => {
                if(!confirm(event.target.dataset.confirm)) {
                    event.preventDefault();
                }
            });
        });
    </script>
@endsection
