@extends('layouts.admin')

@section('title', 'Customer Directory - FoodERP System')
@section('page_title', 'Customer Directory & Accounts')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Customer Management</h4>
        <p class="text-muted small mb-0">View registered customers, profiles, and order history</p>
    </div>
    <a href="{{ route('admin.customers.create') }}" class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-person-plus-fill me-1"></i> Register New Customer
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
    <div class="card-body p-3 border-bottom bg-light">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search customer by name, email, phone, city..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark btn-sm rounded-pill w-100 fw-semibold">Search</button>
            </div>
            @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100">Clear</a>
                </div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Total Orders</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $customer->name }}</div>
                            <small class="text-muted">{{ Str::limit($customer->address, 35) }}</small>
                        </td>
                        <td><span class="small text-muted">{{ $customer->email ?? 'N/A' }}</span></td>
                        <td>
                            <a href="tel:{{ $customer->phone }}" class="text-danger fw-semibold text-decoration-none small">
                                <i class="bi bi-telephone-fill me-1"></i>{{ $customer->phone }}
                            </a>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $customer->city }}</span></td>
                        <td>
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">{{ $customer->orders_count }} Orders</span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-outline-info btn-sm rounded-3 me-1" title="View Customer Details & History">
                                <i class="bi bi-eye"></i> View History
                            </a>
                            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-outline-dark btn-sm rounded-3 me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete customer {{ $customer->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-light p-3">
        {{ $customers->links() }}
    </div>
</div>
@endsection
