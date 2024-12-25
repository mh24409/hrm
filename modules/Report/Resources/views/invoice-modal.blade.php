 <!-- Modal -->
 <div class="modal fade" id="invoiceViewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-body" id="viewData">
             </div>
             <div class="modal-footer">
                 <button type="button" class="btn btn-danger-soft me-2"
                     data-bs-dismiss="modal">{{ localize('close') }}</button>
                 <button class="btn btn-success me-2" onclick="printPage()"><i
                         class="typcn typcn-printer me-1"></i>{{ localize('print') }}</button>
             </div>
         </div>
     </div>
 </div>
