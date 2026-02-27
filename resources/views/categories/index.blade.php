@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="page-title">
                            <i class="fas fa-tags me-2" style="color: var(--orange-primary);"></i>
                            Service Categories
                        </h2>
                        <p class="page-subtitle">Manage all service request categories</p>
                    </div>
                    <a href="{{ route('categories.create') }}" class="btn-create">
                        <i class="fas fa-plus-circle me-2"></i>
                        Create New Category
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row">
        <div class="col-12">
            <div class="categories-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>
                            <i class="fas fa-list me-2" style="color: var(--orange-primary);"></i>
                            All Categories
                        </h5>
                        <span class="total-count">{{ $categories->total() }} total</span>
                    </div>
                </div>

                <div class="card-body">
                    @if($categories->count() > 0)
                        <div class="table-responsive">
                            <table class="categories-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category Name</th>
                                        <th>Description</th>
                                        <th>Total Requests</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <span class="category-id">#{{ $category->id }}</span>
                                        </td>
                                        <td>
                                            <div class="category-name">
                                                <i class="fas fa-tag me-2" style="color: var(--orange-primary);"></i>
                                                <strong>{{ $category->name }}</strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="category-description">
                                                {{ Str::limit($category->description, 50) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="request-count">
                                                {{ $category->service_requests_count }}
                                                {{ Str::plural('request', $category->service_requests_count) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="created-info">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $category->created_at->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('categories.edit', $category) }}" 
                                                   class="btn-action edit" data-tooltip="Edit Category">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if($category->service_requests_count == 0)
                                                    <form action="{{ route('categories.destroy', $category) }}" 
                                                          method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action delete" 
                                                                data-tooltip="Delete Category"
                                                                onclick="return confirm('Are you sure you want to delete this category?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn-action delete disabled" 
                                                            data-tooltip="Cannot delete - has requests"
                                                            disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pagination-wrapper">
                            {{ $categories->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <h5>No Categories Found</h5>
                            <p>Get started by creating your first service category.</p>
                            <a href="{{ route('categories.create') }}" class="btn-primary-custom">
                                <i class="fas fa-plus-circle me-2"></i>
                                Create Category
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--dark-card) 0%, var(--dark-secondary) 100%);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .page-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-bottom: 0;
    }

    .btn-create {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
    }

    .btn-create:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
    }

    .btn-create i {
        transition: all 0.3s ease;
    }

    .btn-create:hover i {
        transform: rotate(90deg);
    }

    /* Categories Card */
    .categories-card {
        background: var(--dark-card);
        border: 1px solid var(--border-dark);
        border-radius: 20px;
        overflow: hidden;
    }

    .card-header-custom {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-dark);
        background: rgba(0, 0, 0, 0.2);
    }

    .card-header-custom h5 {
        margin-bottom: 0;
        color: var(--text-primary);
        font-weight: 600;
    }

    .total-count {
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Categories Table */
    .categories-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
    }

    .categories-table thead th {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-dark);
    }

    .categories-table tbody tr {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .categories-table tbody tr:hover {
        background: rgba(249, 115, 22, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .categories-table td {
        padding: 1rem;
        color: var(--text-primary);
        border: none;
    }

    .category-id {
        font-weight: 600;
        color: var(--orange-primary);
        font-size: 0.9rem;
    }

    .category-name {
        display: flex;
        align-items: center;
        font-weight: 500;
    }

    .category-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .request-count {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        background: rgba(249, 115, 22, 0.1);
        color: var(--orange-primary);
        border-radius: 20px;
        font-size: 0.85rem;
    }

    .created-info {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .created-info i {
        color: var(--orange-primary);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-dark);
        transition: all 0.3s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-action.edit:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
        transform: translateY(-2px);
    }

    .btn-action.delete:hover:not(:disabled) {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
        transform: translateY(-2px);
    }

    .btn-action.delete.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 100px;
        height: 100px;
        background: rgba(249, 115, 22, 0.1);
        border: 2px dashed var(--orange-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 3rem;
        color: var(--orange-primary);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .empty-state h5 {
        color: var(--text-primary);
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    .btn-primary-custom {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--orange-primary), #f97316);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
    }

    .btn-primary-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.4);
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        border-top: 1px solid var(--border-dark);
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 0.25rem;
    }

    .page-link {
        padding: 0.5rem 0.75rem;
        background: transparent;
        border: 1px solid var(--border-dark);
        color: var(--text-secondary);
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: var(--orange-primary);
        color: white;
        border-color: var(--orange-primary);
    }

    .page-item.active .page-link {
        background: var(--orange-primary);
        color: white;
        border-color: var(--orange-primary);
    }

    /* Tooltip */
    [data-tooltip] {
        position: relative;
    }

    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 0.25rem 0.5rem;
        background: var(--dark-card);
        color: var(--text-primary);
        border: 1px solid var(--border-dark);
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 10;
    }

    [data-tooltip]:hover:before {
        opacity: 1;
        visibility: visible;
        bottom: 120%;
    }
</style>
@endpush