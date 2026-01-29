{{-- Edit Unit Modal --}}
<form method="POST" action="{{ auth()->user()->roleRoute('unit.update', $unit->id) }}">
    @csrf
    @method('PUT')

    <div class="modal fade" id="editUnitModal{{ $unit->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name{{ $unit->id }}" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="name{{ $unit->id }}" value="{{ old('name', $unit->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="abbreviation{{ $unit->id }}" class="form-label">Abbreviation</label>
                        <input type="text" name="abbreviation"
                            class="form-control @error('abbreviation') is-invalid @enderror"
                            id="abbreviation{{ $unit->id }}" value="{{ old('abbreviation', $unit->abbreviation) }}">
                        @error('abbreviation')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
