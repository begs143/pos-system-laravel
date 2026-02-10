  <!-- Modal -->
  <form method="POST" action="{{ auth()->user()->roleRoute('user-role.store') }}">
      @csrf
      <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Add User</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">

                      {{-- Name --}}
                      <div class="mb-3">
                          <label for="name" class="form-label">Name</label>
                          <input type="text" name="name" id="name"
                              class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                              placeholder="Enter Name" required>
                          @error('name')
                              <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                      </div>

                      {{-- Username --}}
                      <div class="mb-3">
                          <label for="username" class="form-label">Username</label>
                          <input type="text" name="username" id="username"
                              class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}"
                              placeholder="Enter username" required>
                          @error('username')
                              <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                      </div>

                      {{-- Role --}}
                      <div class="mb-3">
                          <label for="role" class="form-label">Role</label>
                          <select name="role" id="role" class="form-control @error('role') is-invalid @enderror"
                              required>
                              <option value="user" selected {{ old('role') === 'user' ? 'selected' : '' }}>User
                              </option>
                              <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                          </select>
                          @error('role')
                              <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                      </div>

                      {{-- Password --}}
                      <div class="mb-4">
                          <label for="password" class="form-label">Password</label>
                          <input type="password" name="password" id="password"
                              class="form-control @error('password') is-invalid @enderror" placeholder="Enter Password"
                              required>
                          @error('password')
                              <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                      </div>


                      {{-- Buttons --}}
                      <div class="d-flex justify-content-end gap-2">
                          <button type="submit" class="btn btn-primary">Save</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>

                  </div>
              </div>
          </div>
      </div>
  </form>
