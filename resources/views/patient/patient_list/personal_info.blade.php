 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="firstname">First Name</label>
             <div class="input-group form-group">
                 <button class="btn dropdown-toggle form-control btn-sm" type="button" data-bs-toggle="dropdown"
                     aria-expanded="false">Title</button>
                 <ul class="dropdown-menu">
                     <li class="dropdown-item text-light">Mr.</li>
                     <li class="dropdown-item text-light">Miss</li>
                     <li class="dropdown-item text-light">Mrs.</li>
                 </ul>
                 <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name"
                     required style="width: 100px;">
             </div>
         </div>
     </div>
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="lastname">Last Name</label>
             <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required>
         </div>
     </div>
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="gender">Gender</label>
             <select class="form-select" id="gender" name="gender" required>
                 <option value="">Select Gender</option>
                 <option value="M">Male</option>
                 <option value="F">Female</option>
                 <option value="O">Others</option>
             </select>
         </div>
     </div>
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="date_of_birth">Date of Birth</label>
             <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
         </div>
     </div>
 </div>

 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="aadhaar">Aadhaar No</label>
             <input type="email" class="form-control" id="aadhaar" name="aadhaar" placeholder="aadhaar no" required>
         </div>
     </div>

     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="email">E-mail Address</label>
             <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required>
         </div>
     </div>
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="phone">Contact
                 Number</label>
             <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number"
                 required>
         </div>
     </div>
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="phone">Registration Date</label>
             <input type="date" class="form-control" id="regdate" name="regdate" required>
         </div>
     </div>

 </div>

 <!--address-->
 <div class="row">
     <div class="form-group col-md-2">
         <label class="form-label" for="address1">Residential Address</label>
         <input type="text" class="form-control" id="address1" name="address1" placeholder="Adress line 1" required>
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <input type="text" class="form-control" id="address2" name="address2" placeholder="Adress line 2" required>
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <select class="select2" required id="city_id" name="city_id" data-placeholder="Select a City"
             style="width: 100%;">
         </select>
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <select class="select2" id="state_id" name="state_id" required data-placeholder="Select a State"
             style="width: 100%;">

         </select>
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
         <input class="form-control" type="text" id="pincode" name="pincode" required placeholder="pin code">
     </div>
 </div>

 <div class="row">
     <div class="form-group mb-0">
         <label class="form-label" for="caddress1">Communication Address:&nbsp;</label>&nbsp;
         <input type="checkbox" id="add_checkbox" class="filled-in chk-col-success" />
         <label for="add_checkbox">Same as Residential Address</label>
     </div>
     <div class="form-group col-md-2">
         <input type="text" class="form-control " id="caddress1" name="caddress1" placeholder="Adress line 1"
             required>
     </div>

     <div class="form-group col-md-2 ">
         <input type="text" class="form-control" id="caddress2" name="caddress2" placeholder="Adress line 2"
             required>
     </div>

     <div class="form-group col-md-2 ">
         <select class="select2" required id="ccity_id" name="ccity_id" data-placeholder="Select a City"
             style="width: 100%;">
         </select>
     </div>

     <div class="form-group col-md-2 ">
         <select class="select2" id="cstate_id" name="cstate_id" required data-placeholder="Select a State"
             style="width: 100%;">

         </select>
     </div>

     <div class="form-group col-md-2 ">
         <select class="select2" id="ccountry_id" name="ccountry_id" required data-placeholder="Select a Country"
             style="width: 100%;">
             @foreach ($countries as $country)
                 <option value="{{ $country->id }}" <?php if ($country->id == 101) {
                     echo 'selected';
                 } ?>>
                     {{ $country->country }}</option>
             @endforeach
         </select>
     </div>

     <div class="form-group col-md-2 ">
         <input class="form-control" type="text" id="cpincode" name="cpincode" required placeholder="pin code">
     </div>
 </div>
