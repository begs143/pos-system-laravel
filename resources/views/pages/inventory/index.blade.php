   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">Product Inventory</h1>
                               <p class="mb-0">Manage your product inventory</p>
                           </div>
                           <div>



                           </div>
                       </div>
                   </div>
               </div>
               <div class="row">
                   <div class="col-12">
                       <div>
                           <div class="d-flex gap-2 mb-3 flex-wrap justify-content-between">
                               <input type="text" class="form-control" placeholder="Search products..."
                                   style="max-width: 250px;">
                               <div class="d-flex gap-2">


                                   <a class="btn btn-primary" href="{{ route('inventory.create') }}">
                                       <i class="ti ti-plus"></i>
                                       <span class="nav-text">Add Product</span>
                                   </a>

                                   <a class="btn btn-primary" href="">
                                       <i class="ti ti-file-excel"></i>
                                       <span class="nav-text">Export</span>
                                   </a>





                               </div>
                           </div>
                       </div>
                       <div class="card table-responsive">
                           <table class="table mb-0 text-nowrap">
                               <thead class="">
                                   <tr>
                                       <th>Image</th>
                                       <th>Code</th>
                                       <th>Category</th>
                                       <th>Brand</th>
                                       <th>Price</th>
                                       <th>Unit</th>
                                       <th>Quantity</th>
                                       <th>Action</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   <tr class="align-middle ">
                                       <td><a href=""><img src="./assets/images/product-1.png" alt=""
                                                   class="avatar avatar-md rounded" /><span class="ms-3">Gaming Joy
                                                   Stick</span></a>
                                       </td>

                                       <td>PRD001</td>
                                       <td>Electronics</td>
                                       <td>Brand Name</td>
                                       <td>$99.99</td>
                                       <td>pcs</td>
                                       <td>150</td>
                                       <td class="">
                                           <a href="#" class=""><i class="ti ti-edit  fs-5 "></i></a>
                                           <a href="#" class="link-danger"><i class="ti ti-trash ms-2 fs-5"></i></a>
                                       </td>
                                   </tr>


                               </tbody>
                               <tfoot class="">

                                   <tr>
                                       <td class="border-bottom-0">Showing product per page</td>
                                       <td colspan="9" class="border-bottom-0">
                                           <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                               <ul class="pagination mb-0">
                                                   <li class="page-item disabled">
                                                       <a class="page-link" href="#" tabindex="-1">Previous</a>
                                                   </li>
                                                   <li class="page-item active"><a class="page-link" href="#">1</a>
                                                   </li>
                                                   <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                   <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                   <li class="page-item">
                                                       <a class="page-link" href="#">Next</a>
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
                                   href="https://codescandy.com/" target="_blank" class="text-primary">CodesCandy</a>
                           </p>
                       </footer>
                   </div>

               </div>
           </div>
       </main>
   @endsection
