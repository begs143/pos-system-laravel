@extends('layouts.app')
@section('content')
    <!-- MAIN CONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                        <div class="">
                            <h1 class="fs-3 mb-1">Add Product</h1>
                            <p class="mb-0">Manage your inventory items</p>
                        </div>
                        <div>
                            <a href="{{ auth()->user()->roleRoute('inventory.index') }}" class="btn btn-primary">Go to
                                Inventory List</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <form action="{{ auth()->user()->roleRoute('inventory.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Enter product name" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="product_code" class="form-label">Product Code</label>
                                        <input type="text" class="form-control" id="product_code" name="product_code"
                                            placeholder="Enter product code" required>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label">Category</label>

                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id" required>

                                            <option value="" disabled hidden
                                                {{ old('category_id') ? '' : 'selected' }}>
                                                Select category
                                            </option>

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="unit_id" class="form-label">Unit</label>

                                        <select class="form-select @error('unit_id') is-invalid @enderror" id="unit_id"
                                            name="unit_id" required>

                                            <option value="" disabled hidden {{ old('unit_id') ? '' : 'selected' }}>
                                                Select unit
                                            </option>

                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->abbreviation }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('unit_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label for="reorder_level" class="form-label">Reorder Level</label>
                                        <input type="number" class="form-control" id="reorder_level" placeholder="0"
                                            name="reorder_level">
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="cost_price" class="form-label">Cost Price</label>
                                        <input type="number" class="form-control" id="cost_price" placeholder="0.00"
                                            step="0.01" name="cost_price" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="selling_price" class="form-label">Selling Price</label>
                                        <input type="number" class="form-control" name="selling_price" id="selling_price"
                                            placeholder="0.00" step="0.01" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="quantity_on_hand" class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" id="quantity_on_hand"
                                            name="quantity_on_hand" placeholder="0" min="1" step="1"
                                            onkeydown="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== '.'"
                                            required>
                                    </div>


                                    <div class="mb-4">
                                        <label for="product_image" class="form-label">Product Image</label>
                                        <input type="file" class="form-control" id="product_image" name="product_image"
                                            accept="image/*">
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Add Product</button>
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
                                href="https://codescandy.com/" target="_blank" class="text-primary">CodesCandy</a> </p>
                    </footer>
                </div>

            </div>

        </div>
    </main>
@endsection
