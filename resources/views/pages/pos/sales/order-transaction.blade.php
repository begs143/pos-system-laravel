@extends('layouts.app')
@section('content')
    <!-- MAIN CONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="">
                            <h1 class="fs-3 mb-1">Purchase Order </h1>
                            <p class="mb-0">Manage your transactions</p>
                        </div>


                    </div>
                </div>
            </div>




            <div class="row py-1">
                <div class="col-12">
                    <!-- Search Form -->
                    <div class="mb-3" style="max-width: 250px;">
                        <form action="" method="GET">
                            <div class="input-group">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Search invoice no...">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Transaction Table -->
                    <div class="card table-responsive">
                        <table class="table  table-hover mb-0 text-nowrap align-middle">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th class=>Total</th>
                                    <th class=>Paid</th>
                                    <th class=>Change</th>
                                    <th>Cashier</th>
                                    <th class=>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sales as $sale)
                                    <tr>
                                        <td>#{{ $sale->invoice_no }}</td>
                                        <td>{{ $sale->sale_date->format('Y-m-d H:i') }}</td>
                                        <td class="">{{ number_format($sale->total_amount, 2) }}</td>
                                        <td class="">{{ number_format($sale->amount_paid ?? 0, 2) }}</td>
                                        <td class="">{{ number_format($sale->change ?? 0, 2) }}</td>
                                        <td>{{ $sale->cashier->name ?? 'N/A' }}</td>
                                        <td>
                                            {{-- <a href="{{ route('sales.show', $sale->id) }}"
                                                class="btn btn-sm btn-primary me-2">
                                                View
                                            </a> --}}


                                            <a href="{{ auth()->user()->roleRoute('pos.sale.order-details', $sale->id) }}"
                                                class="btn btn-sm btn-secondary">
                                                <i class="ti ti-eye"></i> View
                                            </a>


                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @php
                                $current = $sales->currentPage();
                                $last = $sales->lastPage();
                            @endphp

                            <tfoot>
                                <tr>
                                    <td class="border-bottom-0">
                                        Showing {{ $sales->perPage() }} transactions per page
                                    </td>
                                    <td colspan="6" class="border-bottom-0">
                                        <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                            <ul class="pagination mb-0">

                                                {{-- Prev --}}
                                                <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $sales->previousPageUrl() ?? '#' }}"
                                                        tabindex="-1">
                                                        Previous
                                                    </a>
                                                </li>

                                                {{-- Page numbers --}}
                                                @for ($i = 1; $i <= $last; $i++)
                                                    <li class="page-item {{ $current == $i ? 'active' : '' }}">
                                                        <a class="page-link"
                                                            href="{{ $sales->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endfor

                                                {{-- Next --}}
                                                <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $sales->nextPageUrl() ?? '#' }}">Next</a>
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
