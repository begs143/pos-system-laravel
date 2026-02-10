@extends('layouts.app')
@section('content')
    <!-- MAIN CONTENT -->
    <main id="content" class="content py-10">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
                        <div class="">
                            <h1 class="fs-3 mb-1">Add Users</h1>
                            <p class="mb-0">Manage your user role</p>
                        </div>
                        <div>
                            <a href="{{ auth()->user()->roleRoute('user-role') }}" class="btn btn-primary">
                                <i class="ti ti-arrow-left"></i>
                                <span class="nav-text">Back</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ auth()->user()->roleRoute('user.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Enter Name" id="name" name="name"
                                            value="{{ old('name') }}" required>

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Enter Email" id="email" name="email"
                                            value="{{ old('email') }}">

                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                                            id="username" name="username" value="{{ old('username') }}"
                                            placeholder="Enter Username" required>

                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="role" class="form-label">User Role</label>
                                        <select class="form-select @error('role') is-invalid @enderror" id="role"
                                            name="role" required>

                                            <option value="admin">Admin</option>
                                            <option value="user">Cashier</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Enter Password" required>

                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" autocomplete="new-password"
                                            placeholder="Enter Confirm Password" required>

                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                    <button type="reset" class="btn btn-secondary">Clear</button>
                                </div>

                            </form>
                        </div>
                    </div>


                </div>

            </div>

            <x-footer-layout />

        </div>


        </div>
    </main>
@endsection
