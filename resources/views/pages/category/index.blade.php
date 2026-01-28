   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">Category</h1>
                               <p class="mb-0">Manage your product categories</p>
                           </div>

                           <div>
                               <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                   <i class="ti ti-plus"></i>
                                   <span class="nav-text">Add Category</span>
                               </button>
                           </div>


                       </div>
                   </div>
               </div>

               {{-- Add Category Modal --}}
               @include('pages.category.modal-create')


           </div>

           {{-- Success Message --}}
           @include('partials.success-message')
           @include('partials.error-message')
           {{-- Edit Category Modal --}}

           <div class="row py-1">
               <div class="col-12">
                   <div>
                       <form action="{{ route('category.index') }}" method="GET">
                           <div class="d-flex gap-2 mb-3" style="max-width: 230px;">
                               <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                   placeholder="Search categories...">

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
                                   <th>Description</th>
                                   <th>Created At</th>
                                   <th>Action</th>
                               </tr>
                           </thead>
                           <tbody>
                               @forelse ($categories as $category)
                                   @include('pages.category.modal-edit')

                                   <tr class="align-middle ">
                                       <td>{{ $category->name }}</td>
                                       <td>{{ $category->description }}</td>
                                       <td>{{ $category->created_at }}</td>
                                       <td class="">
                                           <a href="#" data-bs-toggle="modal"
                                               data-bs-target="#editCategoryModal{{ $category->id }}">
                                               <i class="ti ti-edit fs-5"></i>
                                           </a>
                                           </a>
                                           <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                               style="display: inline;">
                                               @csrf
                                               @method('DELETE')
                                               <button type="submit"
                                                   class="btn btn-link p-0 m-0 align-baseline link-danger"
                                                   onclick="return confirm('Are you sure you want to delete this category?');">
                                                   <i class="ti ti-trash ms-2 fs-5"></i>
                                               </button>
                                           </form>


                                       </td>
                                   </tr>
                               @empty
                                   <td colspan="5" class="text-center">No categories found.</td>
                               @endforelse

                           </tbody>

                           @php
                               $current = $categories->currentPage();
                               $last = $categories->lastPage();
                           @endphp

                           <tfoot>
                               <tr>
                                   <td class="border-bottom-0">Showing categories per page</td>
                                   <td colspan="9" class="border-bottom-0">
                                       <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                           <ul class="pagination mb-0">

                                               {{-- Prev --}}
                                               <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                                                   <a class="page-link" href="{{ $categories->previousPageUrl() ?? '#' }}"
                                                       tabindex="-1">
                                                       Previous
                                                   </a>
                                               </li>

                                               {{-- Page numbers --}}
                                               @for ($i = 1; $i <= $last; $i++)
                                                   <li class="page-item {{ $current == $i ? 'active' : '' }}">
                                                       <a class="page-link"
                                                           href="{{ $categories->url($i) }}">{{ $i }}</a>
                                                   </li>
                                               @endfor

                                               {{-- Next --}}
                                               <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                                                   <a class="page-link" href="{{ $categories->nextPageUrl() ?? '#' }}">
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
                               href="https://codescandy.com/" target="_blank" class="text-primary">MarcTech406</a>
                       </p>
                   </footer>
               </div>

           </div>



           </div>
       </main>
   @endsection
