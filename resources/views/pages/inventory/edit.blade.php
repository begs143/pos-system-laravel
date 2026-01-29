@extends('layouts.app')
@section('content')
    <!-- MAIN CONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                        <div class="">
                            <h1 class="fs-3 mb-1">Update Product</h1>
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
                            <form method="POST" enctype="multipart/form-data"
                                action="{{ auth()->user()->roleRoute('inventory.update', $inventory->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <!-- Product Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Product Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $inventory->name) }}" required>
                                    </div>

                                    <!-- Product Code -->
                                    <div class="col-md-6 mb-3">
                                        <label for="product_code" class="form-label">Product Code</label>
                                        <input type="text" class="form-control" id="product_code" name="product_code"
                                            value="{{ old('product_code', $inventory->product_code) }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Category -->
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id" required>
                                            <option value="" disabled hidden>Select category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $inventory->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Unit -->
                                    <div class="col-md-4 mb-3">
                                        <label for="unit_id" class="form-label">Unit</label>
                                        <select class="form-select @error('unit_id') is-invalid @enderror" id="unit_id"
                                            name="unit_id" required>
                                            <option value="" disabled hidden>Select unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}"
                                                    {{ old('unit_id', $inventory->unit_id) == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->abbreviation }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('unit_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Reorder Level -->
                                    <div class="col-md-2 mb-3">
                                        <label for="reorder_level" class="form-label">Reorder Level</label>
                                        <input type="number" class="form-control" id="reorder_level" name="reorder_level"
                                            value="{{ old('reorder_level', $inventory->reorder_level) }}" placeholder="0">
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Cost Price -->
                                    <div class="col-md-4 mb-3">
                                        <label for="cost_price" class="form-label">Cost Price</label>
                                        <input type="number" class="form-control" id="cost_price" name="cost_price"
                                            value="{{ old('cost_price', $inventory->cost_price) }}" step="0.01"
                                            required>
                                    </div>

                                    <!-- Selling Price -->
                                    <div class="col-md-4 mb-3">
                                        <label for="selling_price" class="form-label">Selling Price</label>
                                        <input type="number" class="form-control" id="selling_price" name="selling_price"
                                            value="{{ old('selling_price', $inventory->selling_price) }}" step="0.01"
                                            required>
                                    </div>

                                    <!-- Stock Quantity -->
                                    <div class="col-md-4 mb-3">
                                        <label for="quantity_on_hand" class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" id="quantity_on_hand"
                                            name="quantity_on_hand"
                                            value="{{ old('quantity_on_hand', $inventory->stockBalance->quantity_on_hand ?? 0) }}"
                                            required>
                                    </div>





                                    <!-- Product Image -->
                                    <div class="col-md-6 mb-4">
                                        <label for="product_image" class="form-label">Product Image</label>
                                        <input type="file" class="form-control" id="product_image" name="product_image"
                                            accept="image/*">

                                        @if ($inventory->product_image)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $inventory->product_image) }}"
                                                    alt="{{ $inventory->name }}" class="avatar avatar-md rounded">
                                            </div>
                                        @endif
                                    </div>


                                    <div class="col-md-6 mb-4">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="is_active" class="form-select">
                                            <option value="1"> Active </option>
                                            <option value="0"> Inactive </option>
                                        </select>

                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>

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
