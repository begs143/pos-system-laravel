   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">Supplier</h1>
                               <p class="mb-0">Manage your product suppliers</p>
                           </div>

                           <div>
                               <a class="btn btn-primary" href="{{ route('supplier.index') }}">
                                   <i class="ti ti-arrow-left"></i>
                                   <span class="nav-text">Back</span>
                               </a>
                           </div>


                       </div>
                   </div>
               </div>

              

               <div class="row ">
                   <div class="col-12">
                       <div class="card">
                           <div class="card-body p-4">
                               <form accept="{{ route('supplier.store') }}" method="POST">
                                   @csrf
                                   <div class="row">
                                       <div class="col-md-6 mb-3">
                                           <label for="supplierName" class="form-label">Supplier Name</label>
                                           <input type="text" class="form-control" id="supplierName" name="name"
                                               class="form-control @error('supplierName') is-invalid @enderror"
                                               placeholder="Enter Supplier Name" value="{{ old('supplierName') }}" required>

                                           @error('supplierName')
                                               <span class="invalid-feedback">
                                                   {{ $message }}
                                               </span>
                                           @enderror
                                       </div>
                                       <div class="col-md-6 mb-3">
                                           <label for="contactPerson" class="form-label">Contact Person</label>
                                           <input type="text" class="form-control" id="contactPerson"
                                               class="form-control @error('contactPerson') is-invalid @enderror"
                                               name="contact_person" placeholder="Enter Contact Person"
                                               value="{{ old('contactPerson') }}" required>

                                           @error('contactPerson')
                                               <span class="invalid-feedback">
                                                   {{ $message }}
                                               </span>
                                           @enderror
                                       </div>
                                   </div>
                                   <div class="row">
                                       <div class="col-md-6 mb-3">
                                           <label for="supplierPhone" class="form-label">Phone</label>
                                           <input type="number" class="form-control" id="supplierPhone"
                                               class="form-control @error('supplierPhone') is-invalid @enderror"
                                               name="phone" placeholder="09xxxxxxxxx" value="{{ old('supplierPhone') }}"
                                               required>

                                           @error('supplierPhone')
                                               <span class="invalid-feedback">
                                                   {{ $message }}
                                               </span>
                                           @enderror
                                       </div>
                                       <div class="col-md-6 mb-3">
                                           <label for="supplierEmail" class="form-label">Email</label>
                                           <input type="email" class="form-control" id="supplierEmail"
                                               class="form-control @error('supplierEmail') is-invalid @enderror"
                                               name="email" placeholder="Enter Email"
                                               value="{{ old('supplierEmail') }}" required>

                                           @error('supplierEmail')
                                               <span class="invalid-feedback">
                                                   {{ $message }}
                                               </span>
                                           @enderror
                                       </div>
                                   </div>


                                   <div class="mb-3">
                                       <label for="supplierAddress" class="form-label">Address</label>
                                       <textarea class="form-control @error('supplierAddress') is-invalid @enderror" id="supplierAddress"
                                           name="address" rows="4" placeholder="Enter supplier address">{{ old('supplierAddress') }}</textarea>

                                       @error('supplierAddress')
                                           <span class="invalid-feedback">
                                               {{ $message }}
                                           </span>
                                       @enderror
                                   </div>
                                   <div class="d-flex gap-2">
                                       <button type="submit" class="btn btn-primary">Add Supplier</button>
                                       <button type="reset" class="btn btn-secondary">Clear</button>
                                   </div>

                               </form>
                           </div>
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
