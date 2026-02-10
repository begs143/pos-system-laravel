   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->

       <main id="content" class="content py-10">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">User Roles</h1>
                               <p class="mb-0">Manage your product users</p>
                           </div>

                           <div>
                               <a href="{{ auth()->user()->roleRoute('user.create') }}" class="btn btn-primary">
                                   <i class="ti ti-plus"></i>
                                   <span class="nav-text">Add User</span>
                               </a>
                           </div>


                       </div>
                   </div>

               </div>


               {{-- Success Message --}}
               @include('partials.success-message')
               @include('partials.error-message')

               {{-- Edit Category Modal --}}
               <div class="row py-1">
                   <div class="col-12">
                       <div>
                           {{-- <form action="{{ auth()->user()->roleRoute('category.index') }}" method="GET"> --}}
                           <form action="" method="GET">
                               <div class="d-flex gap-2 mb-3" style="max-width: 230px;">
                                   <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                       placeholder="Search users...">

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
                                       <th>Email</th>
                                       <th>Role</th>
                                       <th>Created At</th>
                                       <th>Action</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   @forelse ($users as $user)
                                       <tr>
                                           <td>{{ $user->name }}</td>
                                           <td>{{ $user->email ?? '-' }}</td>
                                           <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                                           <td>{{ $user->created_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                           <td class="text-center">
                                               <!-- Edit Button -->
                                               <a href="{{ auth()->user()->roleRoute('user.edit', $user->id) }}"
                                                   class="btn-primary me-1" title="Edit User">
                                                   <i class="ti ti-edit fs-5"></i>
                                               </a>

                                               <!-- Delete Button -->
                                               <form action="{{ auth()->user()->roleRoute('user.destroy', $user->id) }}"
                                                   method="POST" class="d-inline">
                                                   @csrf
                                                   @method('DELETE')

                                                   <button type="submit"
                                                       class="btn btn-link p-0 m-0 align-baseline link-danger"
                                                       onclick="return confirm('Are you sure you want to delete this user?');">
                                                       <i class="ti ti-trash ms-2 fs-5"></i>
                                                   </button>
                                               </form>
                                           </td>
                                       </tr>
                                   @empty
                                       <tr>
                                           <td colspan="6" class="text-center">No users found.</td>
                                       </tr>
                                   @endforelse
                               </tbody>

                               @php
                                   $current = $users->currentPage();
                                   $last = $users->lastPage();
                               @endphp

                               <tfoot>
                                   <tr>
                                       <td class="border-bottom-0">
                                           Showing {{ $users->perPage() }} users per page
                                       </td>
                                       <td colspan="9" class="border-bottom-0">
                                           <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                               <ul class="pagination mb-0">

                                                   {{-- Prev --}}
                                                   <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                                                       <a class="page-link" href="{{ $users->previousPageUrl() ?? '#' }}"
                                                           tabindex="-1">
                                                           Previous
                                                       </a>
                                                   </li>

                                                   {{-- Page numbers --}}
                                                   @for ($i = 1; $i <= $last; $i++)
                                                       <li class="page-item {{ $current == $i ? 'active' : '' }}">
                                                           <a class="page-link"
                                                               href="{{ $users->url($i) }}">{{ $i }}</a>
                                                       </li>
                                                   @endfor

                                                   {{-- Next --}}
                                                   <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                                                       <a class="page-link" href="{{ $users->nextPageUrl() ?? '#' }}">
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

   {{-- @push('app-script')
       @if (session('error') || $errors->any())
           <script>
               document.addEventListener('DOMContentLoaded', function() {
                   let modal = new bootstrap.Modal(
                       document.getElementById('addUserModal')
                   );
                   modal.show();
               });
           </script>
       @endif
   @endpush --}}
