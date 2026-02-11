@extends('layouts.app')
@section('content')
    <main id="content" class="content py-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <div class="">
                            <h1 class="fs-3 mb-1">Inventory</h1>
                            <p class="mb-0">Manage your product inventory</p>
                        </div>
                        <div>
                            <a class='btn btn-primary' href='/create-product'>Add Product</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div>
                        <form action="" method="GET">
                            <div class="d-flex gap-2 mb-3" style="max-width: 230px;">
                                <input type="text" name="search" value="" class="form-control"
                                    placeholder="Search Logs...">

                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card table-responsive ">
                        <table class="table mb-0 text-nowrap table-hover">
                            <thead class="table-light border-light">
                                <tr>

                                    <th>User</th>
                                    <th>Module</th>
                                    <th>Action</th>
                                    <th>Details</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                    <tr>
                                        <!-- User -->
                                        <td>{{ optional($activity->causer)->name ?? 'System' }}</td>

                                        <!-- Module -->
                                        <td>{{ ucfirst($activity->log_name ?? 'general') }}</td>

                                        <!-- Action -->
                                        <td>{{ ucfirst($activity->description) }}</td>

                                        <!-- Details -->
                                        <td>
                                            @if ($activity->properties)
                                                @php
                                                    // Convert properties array to readable string
                                                    $props = [];
                                                    foreach ($activity->properties as $key => $value) {
                                                        if (is_array($value)) {
                                                            $value = implode(', ', $value);
                                                        }
                                                        $props[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $value;
                                                    }
                                                @endphp
                                                {{ implode(' | ', $props) }}
                                            @else
                                                —
                                            @endif
                                        </td>

                                        <!-- Date -->
                                        <td>{{ $activity->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            No activity logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @php
                                $current = $activities->currentPage();
                                $last = $activities->lastPage();
                            @endphp

                            <tfoot>
                                <tr>
                                    <td class="border-bottom-0">
                                        Showing {{ $activities->perPage() }} products per page
                                    </td>
                                    <td colspan="9" class="border-bottom-0">
                                        <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                            <ul class="pagination mb-0">

                                                {{-- Prev --}}
                                                <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $activities->previousPageUrl() ?? '#' }}"
                                                        tabindex="-1">
                                                        Previous
                                                    </a>
                                                </li>

                                                {{-- Page numbers --}}
                                                @for ($i = 1; $i <= $last; $i++)
                                                    <li class="page-item {{ $current == $i ? 'active' : '' }}">
                                                        <a class="page-link"
                                                            href="{{ $activities->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endfor

                                                {{-- Next --}}
                                                <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $activities->nextPageUrl() ?? '#' }}">
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
            <div class="row">
                <div class="col-12">
                    <footer class="text-center py-2 mt-6 text-secondary ">
                        <p class="mb-0">Copyright © 2026 InApp Inventory Dashboard. Developed by <a
                                href="https://codescandy.com/" target="_blank" class="text-primary">CodesCandy</a> </p>
                    </footer>
                </div>

            </div>




        </div>

        </div>
    </main>
@endsection
