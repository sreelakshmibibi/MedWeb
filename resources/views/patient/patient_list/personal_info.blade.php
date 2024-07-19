 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="firstname">First Name <span class="text-danger">
                     *</span></label>
             <div class="input-group form-group">

                 <select class="form-control select-title" id="title" name="title" required style="width: 40%;">
                     <option value="" selected disabled>Select Title</option>
                     <option value="Mr.">Mr.</option>
                     <option value="Mrs.">Mrs.</option>
                     <option value="Miss">Miss</option>
                     <option value="Master">Master</option>
                     <option value="Ms.">Ms.</option>
                     <option value="Mx.">Mx.</option>
                 </select>
                 <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name"
                     required style="width: 60%;">
             </div>
         </div>
     </div>
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="lastname">Last Name <span class="text-danger">
                     *</span></label>
             <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required>
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="gender">Gender <span class="text-danger">
                     *</span></label>
             <select class="form-select" id="gender" name="gender" required>
                 <option value="">Select Gender</option>
                 <option value="M">Male</option>
                 <option value="F">Female</option>
                 <option value="O">Others</option>
             </select>
         </div>
     </div>

     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="blood_group">Blood Group</label>
             <select class="select2" id="blood_group" name="blood_group" data-placeholder="Select a blood group"
                 style="width: 100%;">
                 <option value="">Select Blood Group</option>
                 <option value="A+">A+</option>
                 <option value="A-">A-</option>
                 <option value="B+">B+</option>
                 <option value="B+">B+</option>
                 <option value="AB+">AB+</option>
                 <option value="AB-">AB-</option>
                 <option value="O+">O+</option>
                 <option value="O-">O-</option>
             </select>
         </div>
     </div>

     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="date_of_birth">Date of Birth <span class="text-danger">
                     *</span></label>
             <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
         </div>
     </div>
 </div>

 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="aadhaar_no">Aadhaar No</label>
             <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no" placeholder="aadhaar no">
         </div>
     </div>

     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="email">E-mail Address</label>
             <input type="email" class="form-control" id="email" name="email" placeholder="E-mail">
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="phone">Contact
                 Number <span class="text-danger">
                     *</span></label>
             <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number"
                 required>
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="alter_phone">Alternate Contact Number</label>
             <input type="text" class="form-control" id="alter_phone" name="alter_phone"
                 placeholder="Phone Number">
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="regdate">Registration Date <span class="text-danger">
                     *</span></label>
             <input type="date" class="form-control" id="regdate" name="regdate" value="<?php echo date('Y-m-d'); ?>"
                 required>
         </div>
     </div>

 </div>

 <!--address-->
 <div class="row">
     <div class="form-group col-md-2">
         <label class="form-label" for="address1">Communication Address <span class="text-danger">
                 *</span></label>
         <input type="text" class="form-control" id="address1" name="address1" placeholder="Adress line 1"
             required>
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <input type="text" class="form-control" id="address2" name="address2" placeholder="Adress line 2"
             required>
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <select class="select2" id="country_id" name="country_id" required data-placeholder="Select a Country"
             style="width: 100%;">
             @foreach ($countries as $country)
                 <option value="{{ $country->id }}" <?php if ($country->id == 101) {
                     echo 'selected';
                 } ?>>
                     {{ $country->country }}</option>
             @endforeach
         </select>
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <select class="select2" id="state_id" name="state_id" required data-placeholder="Select a State"
             style="width: 100%;">

         </select>
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <select class="select2" required id="city_id" name="city_id" data-placeholder="Select a City"
             style="width: 100%;">
         </select>
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <input class="form-control" type="text" id="pincode" name="pincode" required placeholder="pin code">
     </div>
 </div>
 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="marital_status">Marital Status</label>
             <select class="form-select" id="marital_status" name="marital_status">
                 <option value="">Select Marital Status</option>
                 <option value="Single">Single</option>
                 <option value="Married">Married</option>
                 <option value="Widowed">Widowed</option>
                 <option value="Divorced">Divorced</option>
                 <option value="Separated">Separated</option>
             </select>
         </div>
     </div>


 </div>
