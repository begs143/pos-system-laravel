   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">Update Supplier</h1>
                               <p class="mb-0">Manage your product suppliers</p>
                           </div>

                           <div>

                               <a class="btn btn-primary" href="{{ auth()->user()->roleRoute('supplier.index') }}">
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
                               <form method="POST"
                                   action="{{ auth()->user()->roleRoute('supplier.update', $supplier->id) }}">
                                   @csrf
                                   @method('PUT')
                                   <div class="row">
                                       <div class="col-md-6 mb-3">
                                           <label for="name" class="form-label">Supplier Name</label>
                                           <input type="text" class="form-control" id="name" name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               placeholder="Enter Supplier Name" value="{{ old('name', $supplier->name) }}"
                                               required>

                                           @error('name')
                                               <span class="invalid-feedback">
                                                   {{ $message }}
                                               </span>
                                           @enderror
                                       </div>
                                       <div class="col-md-6 mb-3">
                                           <label for="contact_person" class="form-label">Contact Person</label>
                                           <input type="text" class="form-control" id="contact_person"
                                               class="form-control @error('contact_person') is-invalid @enderror"
                                               name="contact_person" placeholder="Enter Contact Person"
                                               value="{{ old('contact_person', $supplier->contact_person) }}" required>
                                           @error('contact_person')
                                               <span class="invalid-feedback">
                                                   {{ $message }}
                                               </span>
                                           @enderror
                                       </div>
                                   </div>
                                   <div class="row">

                                       <div class="col-md-6 mb-3">
                                           <label for="phone" class="form-label">Phone</label>
                                           <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" placeholder="09xxxxxxxxx"
                                               value="{{ old('phone', $supplier->phone) }}" maxlength="11" required>

                                           @error('phone')
                                               <span class="invalid-feedback">{{ $message }}</span>
                                           @enderror
                                       </div>

                                       <div class="col-md-6 mb-3">
                                           <label for="email" class="form-label">Email</label>
                                           <input type="email" class="form-control" id="email"
                                               class="form-control @error('email') is-invalid @enderror" name="email"
                                               placeholder="Enter Email" value="{{ old('email', $supplier->email) }}"
                                               required>

                                           @error('email')
                                               <span class="invalid-feedback">
                                                   {{ $message }}
                                               </span>
                                           @enderror
                                       </div>
                                   </div>


                                   <div class="mb-3">
                                       <label for="address" class="form-label">Address</label>
                                       <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="4"
                                           placeholder="Enter supplier address">{{ old('address', $supplier->address) }}</textarea>

                                       @error('address')
                                           <span class="invalid-feedback">
                                               {{ $message }}
                                           </span>
                                       @enderror
                                   </div>
                                   <div class="d-flex gap-2">
                                       <button type="submit" class="btn btn-primary">Save Changes</button>
                                   </div>

                               </form>
                           </div>
                       </div>


                   </div>

               </div>


               <x-footer-layout />
           </div>
       </main>
   @endsection
