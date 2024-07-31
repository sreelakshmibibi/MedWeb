 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="firstname">First Name <span class="text-danger">
                     *</span></label>
             <div class="input-group form-group">
                 <select class="form-control select-title" id="title" name="title" required style="width: 40%;">
                     <option value="" selected disabled>Select Title</option>
                     <option value="Dr.">Dr.</option>
                     <option value="Mr.">Mr.</option>
                     <option value="Miss">Miss</option>
                     <option value="Mrs.">Mrs.</option>
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
             <label class="form-label" for="date_of_birth">Date of Birth <span class="text-danger">
                     *</span></label>
             <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
             <div id="dobError" class="invalid-feedback"></div>
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="role">Role <span class="text-danger">
                     *</span></label>
             <select class="form-control select2 form-select" required id="role" name="role[]"
                 data-placeholder="Select a Role" style="width: 100%;" multiple>
                 @foreach ($userTypes as $userType)
                     <option value="{{ $userType->id }}">{{ $userType->title }}</option>
                 @endforeach
             </select>
         </div>
     </div>
 </div>

 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="aadhaar_no">Aadhaar No <span class="text-danger">
                     *</span></label>
             <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no" placeholder="Aadhaar no"
                 required>
         </div>
     </div>

     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="email">E-mail Address <span class="text-danger">
                     *</span></label>
             <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
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
             <label class="form-label" for="profile_photo">Photo</label>
             <input class="form-control @error('profile_photo') is-invalid @enderror" type="file"
                 id="profile_photo" name="profile_photo" placeholder="logo">
             @error('profile_photo')
                 <div class="invalid-feedback">{{ $message }}</div>
             @enderror
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <canvas id="logoCanvas" style="height: 64px;"></canvas>
         </div>
     </div>
 </div>

 <!--address-->
 <div class="row">
     <div class="form-group col-md-2">
         <label class="form-label" for="address1">Residential Address <span class="text-danger">
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
     <div class="form-group mb-0">
         <label class="form-label" for="add_checkbox">Communication Address <span class="text-danger">
                 *</span>&nbsp;</label>&nbsp;
         <input type="checkbox" id="add_checkbox" name="add_checkbox" class="filled-in chk-col-success" />
         <label for="add_checkbox">Same as Residential Address</label>
     </div>
     <div class="row px-0 mx-0" id="communicationAddress">
         <div class="form-group col-md-2">
             <input type="text" class="form-control " id="com_address1" name="com_address1"
                 placeholder="Adress line 1" required>
         </div>

         <div class="form-group col-md-2 ">
             <input type="text" class="form-control" id="com_address2" name="com_address2"
                 placeholder="Adress line 2" required>
         </div>

         <div class="form-group col-md-2 ">
             <select class="select2" id="com_country_id" name="com_country_id" required
                 data-placeholder="Select a Country" style="width: 100%;">
                 @foreach ($countries as $country)
                     <option value="{{ $country->id }}" <?php if ($country->id == 101) {
                         echo 'selected';
                     } ?>>
                         {{ $country->country }}</option>
                 @endforeach
             </select>
         </div>

         <div class="form-group col-md-2 ">
             <select class="select2" id="com_state_id" name="com_state_id" required
                 data-placeholder="Select a State" style="width: 100%;">

             </select>
         </div>

         <div class="form-group col-md-2 ">
             <select class="select2" required id="com_city_id" name="com_city_id" data-placeholder="Select a City"
                 style="width: 100%;">
             </select>
         </div>

         <div class="form-group col-md-2 ">
             <input class="form-control" type="text" id="com_pincode" name="com_pincode" required
                 placeholder="pin code">
         </div>
     </div>
 </div>
