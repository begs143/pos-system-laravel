   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">Unit</h1>
                               <p class="mb-0">Manage your product units</p>
                           </div>

                           <div>
                               <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUnitModal">
                                   <i class="ti ti-plus"></i>
                                   <span class="nav-text">Add Unit</span>
                               </button>
                           </div>


                       </div>
                   </div>
               </div>

               {{-- Add Unit Modal --}}
               @include('pages.units.modal-create')


           </div>

           {{-- Success Message --}}
           @include('partials.success-message')
           @include('partials.error-message')

           <div class="row py-1">
               <div class="col-12">
                   <div>
                       <form action="{{ auth()->user()->roleRoute('units.index') }}" method="GET">
                           <div class="d-flex gap-2 mb-3" style="max-width: 230px;">
                               <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                   placeholder="Search units...">

                               <button type="submit" class="btn btn-primary">
                                   <i class="ti ti-search"></i>
                               </button>
                           </div>
                       </form>
                   </div>
                   <div class="card table-responsive">
                       <table class="table mb-0 text-nowrap">
                           <thead class="">
                               <tr>

                                   <th>Name</th>
                                   <th>Abbreviation</th>
                                   <th>Created At</th>
                                   <th>Action</th>
                               </tr>
                           </thead>
                           <tbody>
                               @forelse ($units as $unit)
                                   @include('pages.units.modal-edit')

                                   <tr class="align-middle ">
                                       <td>{{ $unit->name }}</td>
                                       <td>{{ $unit->abbreviation }}</td>
                                       <td>{{ $unit->created_at }}</td>
                                       <td class="">
                                           <a href="#" data-bs-toggle="modal"
                                               data-bs-target="#editUnitModal{{ $unit->id }}">
                                               <i class="ti ti-edit fs-5"></i>
                                           </a>
                                           </a>

                                           <form action="{{ auth()->user()->roleRoute('units.destroy', $unit->id) }}"
                                               method="POST" style="display: inline;">
                                               @csrf
                                               @method('DELETE')
                                               <button type="submit"
                                                   class="btn btn-link p-0 m-0 align-baseline link-danger"
                                                   onclick="return confirm('Are you sure you want to delete this unit?');">
                                                   <i class="ti ti-trash ms-2 fs-5"></i>
                                               </button>
                                           </form>

                                       </td>
                                   </tr>
                               @empty
                                   <td colspan="5" class="text-center text-muted">No units found.</td>
                               @endforelse

                           </tbody>

                           @php
                               $current = $units->currentPage();
                               $last = $units->lastPage();
                           @endphp

                           <tfoot>
                               <tr>
                                   <td class="border-bottom-0">
                                       Showing {{ $units->perPage() }} units per page
                                   </td>
                                   <td colspan="9" class="border-bottom-0">
                                       <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                           <ul class="pagination mb-0">

                                               {{-- Prev --}}
                                               <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                                                   <a class="page-link" href="{{ $units->previousPageUrl() ?? '#' }}"
                                                       tabindex="-1">
                                                       Previous
                                                   </a>
                                               </li>

                                               {{-- Page numbers --}}
                                               @for ($i = 1; $i <= $last; $i++)
                                                   <li class="page-item {{ $current == $i ? 'active' : '' }}">
                                                       <a class="page-link"
                                                           href="{{ $units->url($i) }}">{{ $i }}</a>
                                                   </li>
                                               @endfor

                                               {{-- Next --}}
                                               <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                                                   <a class="page-link" href="{{ $units->nextPageUrl() ?? '#' }}">
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
