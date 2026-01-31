  <!-- Modal -->
  <form method="POST" action="{{ auth()->user()->roleRoute('stockmovement.store') }}">
      @csrf
      <div class="modal fade" id="restockModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Manage Stock</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                      <div class="mb-3">
                          <label class="form-label">Product Name</label>
                          <input type="hidden" name="product_id" value="{{ $product->id }}">
                          <input type="text" class="form-control" value="{{ $product->name }}" disabled>
                      </div>

                      <div class="mb-3">
                          <label class="form-label">Type</label>
                          <select name="type" class="form-select type-select" required>
                              <option value="in" selected>Stock In / Add Stock</option>
                              <option value="out">Stock Out / Remove Stock</option>
                          </select>
                      </div>

                      <div class="mb-3 d-none supplier-field">
                          <label class="form-label">Supplier</label>
                          <select name="supplier_id" class="form-select">
                              <option value="">Select Supplier (Optional)</option>
                              @foreach ($suppliers as $supplier)
                                  <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                              @endforeach
                          </select>
                      </div>

                      <div class="mb-3">
                          <label class="form-label">Quantity</label>
                          <input type="number" name="quantity" class="form-control quantity-input"
                              placeholder="Enter quantity" min="1" step="1"
                              onkeydown="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== '.'"
                              required>

                          @error('quantity')
                              <span class="invalid-feedback d-block">{{ $message }}</span>
                          @enderror
                      </div>

                      <div class="mb-4">
                          <label class="form-label">Remarks<small class="text-muted"> (can explain the
                                  details or reason here. e.g adjustment)</small></label>


                          <textarea class="form-control" name="remarks" rows="5" placeholder="Enter remarks" required></textarea>

                          @error('remarks')
                              <span class="invalid-feedback d-block">{{ $message }}</span>
                          @enderror
                      </div>

                      <div class="d-flex justify-content-end gap-2">
                          <button type="submit" class="btn btn-primary">Save</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                  </div>

              </div>
          </div>
  </form>
