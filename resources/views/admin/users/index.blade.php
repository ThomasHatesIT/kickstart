@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">All Registered Users</h4>
    </div>
    <div class="card-body">
        {{-- Include the partial for displaying session alerts --}}
        @include('partials._alerts')

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                     <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="d-grid d-md-flex gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <th>{{ $user->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{-- implode(', ', $user->getRoleNames()->toArray()) --}}
                                {{-- Using first() is cleaner if users only have one role --}}
                                {{ ucfirst($user->getRoleNames()->first() ?? 'N/A') }}
                            </td>
                            <td>
                                @if ($user->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif ($user->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($user->status == 'banned')
                                    <span class="badge bg-dark">Banned</span>
                                @elseif ($user->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M, Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    {{-- Don't allow actions on the currently logged-in admin --}}
                                    @if($user->id !== auth()->id())
                                        {{-- 1. Seller Approval Logic --}}
                                        @if ($user->hasRole('seller') && $user->status == 'pending')
                                            <form action="{{ route('admin.sellers.approve', $user->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Approve Seller">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.sellers.reject', $user->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Reject Seller">Reject</button>
                                            </form>

                                        {{-- 2. Banning Logic --}}
                                        @elseif ($user->status == 'approved')
                                            <form action="{{ route('admin.users.ban', $user->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-dark" data-bs-toggle="tooltip" title="Ban User">Ban</button>
                                            </form>

                                        {{-- 3. Unbanning Logic --}}
                                        @elseif ($user->status == 'banned')
                                            <form action="{{ route('admin.users.unban', $user->id) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Unban User">Unban</button>
                                            </form>
                                        @else
                                            <span>—</span>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic">You</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No users match the current filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endpush