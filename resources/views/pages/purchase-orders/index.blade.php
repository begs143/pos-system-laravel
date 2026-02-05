@extends('layouts.app')
@section('content')
    <!-- MAIN CONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <div class="row">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="">
                        <h1 class="fs-3 mb-1">Product Category</h1>
                        <p class="mb-0">Manage your product categories</p>
                    </div>

                    <div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="ti ti-plus"></i>
                            <span class="nav-text">New P.O</span>
                        </button>
                    </div>


                </div>
            </div>

            @include('partials.success-message')
            @include('partials.error-message')

            <div class="row">
                <div class="col-12">
                    <div>
                        <form action="" method="GET">
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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Cost Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Widget A</td>
                                    <td><input type="number" value="10"></td>
                                    <td><input type="number" class="form-control cost_price" value="48"></td>
                                    <td class="subtotal">480</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <x-footer-layout />
        </div>
    </main>
@endsection
