 <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Add Supplier</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form>
                     <div class="mb-3">
                         <label for="name" class="col-form-label">Supplier Name</label>
                         <input type="text" class="form-control" id="name" required>
                     </div>

                     <div class="mb-3">
                         <label for="contactPerson" class="form-label">Contact Person</label>
                         <input type="text" class="form-control" id="contactPerson" required>
                     </div>

                     <div class="mb-3">
                         <label for="contactNumber" class="form-label">Phone</label>
                         <input type="number" class="form-control" id="contactNumber" required>
                     </div>

                     <div class="mb-3">
                         <label for="emailAddress" class="form-label">Email</label>
                         <input type="email" class="form-control" id="emailAddress" required>
                     </div>

                     <div class="mb-3">
                         <label for="message-text" class="col-form-label">Address</label>
                         <textarea class="form-control" id="message-text"></textarea>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                 <button type="button" class="btn btn-primary">Save changes</button>
             </div>
         </div>
     </div>
 </div>
