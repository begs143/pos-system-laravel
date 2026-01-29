  <!-- Modal -->
  <form method="POST" action="{{ auth()->user()->roleRoute('category.store') }}">
      @csrf
      <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Add Category</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                      <div class="mb-3">
                          <label for="name" class="form-label">Name</label>
                          <input type="text" name="name" class="form-control" placeholder="Enter Category Name"
                              value="{{ old('name') }}" id="name" required>

                          @error('name')
                              <span class="invalid-feedback">{{ $message }}</span>
                          @enderror
                      </div>

                      <div class="mb-4">
                          <label for="description" class="form-label">Description</label>
                          <input type="text" name="description" class="form-control" id="description"
                              value="{{ old('description') }}" placeholder="Enter Description (Optional)">
                          @error('description')
                              <span class="invalid-feedback">{{ $message }}</span>
                          @enderror
                      </div>
                      <div class=" d-flex justify-content-end gap-2">

                          <button type="submit" class="btn btn-primary">Save</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                  </div>
              </div>
          </div>
  </form>
