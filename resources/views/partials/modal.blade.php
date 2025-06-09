 @if (isset($title) && $title == 'Classes')
     <div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="exampleModalLabel">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h1 class="modal-title fs-5" id="exampleModalLabel">Create Class</h1>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <div class="my-3 alert alert-warning d-none" id="errors">
                         <ul class="px-3 m-0" id="errorList"></ul>
                     </div>
                     <form id="createClassForm">
                         <div class="mb-3">
                             <label for="class_name" class="form-label">Class Name</label>
                             <input type="text" class="form-control" id="class_name" name="class_name"
                                 placeholder="Enter Class Name" required>
                         </div>
                         <div class="mb-3">
                             <label for="class_description" class="form-label">Description</label>
                             <textarea class="form-control" id="class_description" name="class_description" rows="5"
                                 placeholder="Enter Class Description"></textarea>
                         </div>



                         @if (isset($courses) && $courses->count() > 0)

                             <select name="course_name" id="course_name" class="form-select mb-3">
                                 <option value="" selected disabled>Select Course</option>
                                 @foreach ($courses as $course)
                                     {
                                     <option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
                                     }
                                 @endforeach
                             </select>
                         @else
                             <div class="alert alert-warning mt-3" role="alert">
                                No courses available. Please add a course first.
                             </div>
                         @endif
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


 @if (isset($title) && $title == 'Instructor')
     <div class="modal fade" id="instructor_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h1 class="modal-title fs-5" id="exampleModalLabel">Assign Instructor</h1>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <form id="assing_fi_form">
                         <select name="" id="" multiple>
                             <option value="1">1</option>
                             <option value="2">3</option>
                             <option value="3">2</option>
                         </select>
                 </div>
                 <div class="modal-footer">

                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-primary">Save changes</button>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 @endif
