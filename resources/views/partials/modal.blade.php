 @if (isset($title) && $title == 'Classes')
     <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h1 class="modal-title fs-5" id="exampleModalLabel">Create Class</h1>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <form action="#" method="POST" id="createClassForm">
                         @csrf
                         <div class="mb-3">
                             <label for="class_name" class="form-label">Class Name</label>
                             <input type="text" class="form-control" id="class_name" name="class_name"
                                 placeholder="Enter Class Name" required>
                         </div>
                         <div class="mb-3">
                             <label for="description" class="form-label">Description</label>
                             <textarea class="form-control" id="description" name="description" rows="5" placeholder="Enter Description"></textarea>
                         </div>
                         <div class="mb-3">
                             <label for="description" class="form-label">Tag CGI's (<span
                                     style="font-style:italic">ctrl+click for multi select</span>)</label>
                             <select class="form-control" id="cgi" name="cgi[]" multiple>
                                 <option value="CGI 1">CGI 1</option>
                                 <option value="CGI 2">CGI 2</option>
                                 <option value="CGI 3">CGI 3</option>
                                 <option value="CGI 4">CGI 4</option>
                                 <option value="CGI 4">CGI 4</option>
                                 <option value="CGI 4">CGI 4</option>
                                 <option value="CGI 4">CGI 4</option>
                                 <option value="CGI 4">CGI 4</option>
                                 <option value="CGI 4">CGI 4</option>
                             </select>
                         </div>
                         <div class="mb-3">
                             <label for="description" class="form-label">Class Image (<span
                                     style="font-style:italic">Optional</span>)</label>
                             <input type="file" class="form-control" id="class_image" name="class_image"
                                 accept="image/*">
                         </div>

                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="submit" class="btn btn-primary">Create Class</button>
                 </div>
                 </form>
             </div>
         </div>
     </div>
 @endif

 {{-- announcement Add --}}
 {{-- <div class="modal fade" id="announcementForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-5" id="exampleModalLabel">Announce something to your class</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">


                 <div id="toolbar" class="w-100">
                     <button class="ql-bold"></button>
                     <button class="ql-italic"></button>
                     <button class="ql-underline"></button>
                     <button class="ql-list" value="bullet"></button>
                     <button class="ql-clean"></button>
                 </div>
                 <div id="editor" style="height: 400px; width: 100%;" class="mb-2"></div>
                 <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-bullhorn"></i> Announce
                 </button>
             </div>

         </div>
     </div>
 </div> --}}
