@extends('layouts.app')
@section('content')
    <!-- MAIN CONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <div class="row">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="">
                        <h1 class="fs-3 mb-1">Purchase Order</h1>
                        <p class="mb-0">Manage your order</p>
                    </div>




                </div>
            </div>

            @include('partials.success-message')
            @include('partials.error-message')



            <div class="row">
                <div class="col-12">
                    <div>
                        <form action="{{ auth()->user()->roleRoute('purchase-orders.index') }}" method="GET">
                            <div class="d-flex justify-content-between align-items-center mb-3">

                                <!-- Left: Search -->
                                <div class="d-flex gap-2" style="max-width: 230px;">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control" placeholder="Search P.O...">

                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </div>

                                <!-- Right: Create PO -->
                                <div>
                                    <a href="{{ auth()->user()->roleRoute('purchase-orders.create') }}"
                                        class="btn btn-primary">
                                        <i class="ti ti-plus"></i>
                                        <span class="nav-text">Create P.O</span>
                                    </a>
                                </div>

                            </div>

                        </form>
                    </div>
                    <div class="card table-responsive">
                        <table class="table mb-0 text-nowrap table-hover">
                            <thead class="table-light border-light">
                                <tr>
                                    <th>PO No.</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchaseOrders as $po)
                                    @include('pages.purchase-orders.modal-edit')
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-warning',
                                            'sent' => 'bg-primary',
                                            'received' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                        ];
                                    @endphp
                                    <tr>
                                        <td>{{ $po->po_number }}</td>
                                        <td>{{ $po->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $po->supplier->name ?? '-' }}</td>
                                        <td>₱{{ number_format($po->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $statusClasses[$po->status] ?? 'bg-secondary' }}">
                                                {{ ucfirst($po->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">

                                                <a href="#" data-bs-toggle="modal"
                                                    data-bs-target="#editStatusModal{{ $po->id }}">
                                                    <i class="ti ti-edit fs-5"></i>
                                                </a>
                                                <!-- View -->
                                                <a href="#" class="text-primary" title="View">
                                                    <i class="ti ti-eye fs-5"></i>
                                                </a>

                                                <!-- Print -->
                                                <a href="#" class="text-success" title="Print">
                                                    <i class="ti ti-printer fs-5"></i>
                                                </a>

                                                <form
                                                    action="{{ auth()->user()->roleRoute('purchase-orders.destroy', $po->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-link p-0 m-0 align-baseline link-danger"
                                                        onclick="return confirm('Are you sure you want to delete this purchase order?');">
                                                        <i class="ti ti-trash ms-2 fs-5"></i>
                                                    </button>
                                                </form>

                                            </div>
                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No purchase orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>

                            @php
                                $current = $purchaseOrders->currentPage();
                                $last = $purchaseOrders->lastPage();
                            @endphp

                            <tfoot>
                                <tr>
                                    <td class="border-bottom-0">
                                        Showing {{ $purchaseOrders->perPage() }} products per page
                                    </td>
                                    <td colspan="9" class="border-bottom-0">
                                        <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                            <ul class="pagination mb-0">

                                                {{-- Prev --}}
                                                <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                                                    <a class="page-link"
                                                        href="{{ $purchaseOrders->previousPageUrl() ?? '#' }}"
                                                        tabindex="-1">
                                                        Previous
                                                    </a>
                                                </li>

                                                {{-- Page numbers --}}
                                                @for ($i = 1; $i <= $last; $i++)
                                                    <li class="page-item {{ $current == $i ? 'active' : '' }}">
                                                        <a class="page-link"
                                                            href="{{ $purchaseOrders->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endfor

                                                {{-- Next --}}
                                                <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                                                    <a class="page-link"
                                                        href="{{ $purchaseOrders->nextPageUrl() ?? '#' }}">
                                                        Next
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>


            <x-footer-layout />
        </div>
    </main>
@endsection
@push('pos-sale-script')
    @if (session('success'))
        <script>
            localStorage.removeItem("poCart");
        </script>
    @endif
@endpush
