{{-- Edit Category Modal --}}
<form method="POST" action="{{ auth()->user()->roleRoute('purchase-orders.update', $po->id) }}">
    @csrf
    @method('PUT')

    <div class="modal fade" id="editStatusModal{{ $po->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status{{ $po->id }}" class="form-label">Status</label>
                        <select class="form-select" id="status{{ $po->id }}" name="status" required>
                            <option value="pending" {{ $po->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="sent" {{ $po->status == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="received" {{ $po->status == 'received' ? 'selected' : '' }}>Received</option>
                            <option value="cancelled" {{ $po->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </div>
        </div>
</form>
