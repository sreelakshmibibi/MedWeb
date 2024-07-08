 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="firstname">First Name</label>
             <div class="input-group form-group">

                 <select class="form-control select-title" id="title" name="title" required>
                     <option value="Mr." <?php if (trim($name[0]) == "Mr.") echo "selected";?> >Mr.</option>
                     <option value="Mrs." <?php if (trim($name[0]) == "Mrs.") echo "selected";?> >Mrs.</option>
                     <option value="Miss" <?php if (trim($name[0]) == "Miss") echo "selected";?> >Miss</option>
                     <option value="Master" <?php if (trim($name[0]) == "Master") echo "selected";?> >Master</option>
                     <option value="Ms." <?php if (trim($name[0]) == "Ms.") echo "selected";?> >Ms.</option>
                     <option value="Mx." <?php if (trim($name[0]) == "Mx.") echo "selected";?> >Mx.</option>
                 </select>
                 <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name"
                     required style="width: 100px;" value="{{ $name[1] ? $name[1] : ''}}">
             </div>
         </div>
     </div>
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="lastname">Last Name</label>
             <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required value="{{ $patientProfile->last_name}}">
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="gender">Gender</label>
             <select class="form-select" id="gender" name="gender" required>
                <option value="M" {{ $patientProfile->gender == "M" ? 'selected' : '' }}>Male</option>
                <option value="F" {{ $patientProfile->gender == "F" ? 'selected' : '' }}>Female</option>
                <option value="O" {{ $patientProfile->gender == "O" ? 'selected' : '' }}>Others</option>
             </select>
         </div>
     </div>

     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="blood_group">Blood Group</label>
             <select class="select2" id="blood_group" name="blood_group" data-placeholder="Select a blood group"
                 style="width: 100%;">
                <option value="">Select Blood Group</option>
                <option value="A+" {{ $patientProfile->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                <option value="A-" {{ $patientProfile->blood_group == 'A-' ? 'selected' : '' }}>A-</option>
                <option value="B+" {{ $patientProfile->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                <option value="B-" {{ $patientProfile->blood_group == 'B-' ? 'selected' : '' }}>B-</option>
                <option value="AB+" {{ $patientProfile->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                <option value="AB-" {{ $patientProfile->blood_group == 'AB-' ? 'selected' : '' }}>AB-</option>
                <option value="O+" {{ $patientProfile->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                <option value="O-" {{ $patientProfile->blood_group == 'O-' ? 'selected' : '' }}>O-</option>
             </select>
         </div>
     </div>

     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="date_of_birth">Date of Birth</label>
             <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required value="{{ $patientProfile->date_of_birth }}">
         </div>
     </div>
 </div>

 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="aadhaar_no">Aadhaar No</label>
             <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no" placeholder="aadhaar no" value="{{ $patientProfile->aadhaar_no }}">
         </div>
     </div>

     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="email">E-mail Address</label>
             <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" value="{{ $patientProfile->email }}" >
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="phone">Contact Number</label>
             <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number"
                 required value="{{ $patientProfile->phone }}">
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="alter_phone">Alternate Contact Number</label>
             <input type="text" class="form-control" id="alter_phone" name="alter_phone"
                 placeholder="Phone Number" value="{{ $patientProfile->alter_phone }}">
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="regdate">Registration Date</label>
             <input type="date" class="form-control" id="regdate" name="regdate" value="{{ \Carbon\Carbon::parse($patientProfile->created_at)->format('Y-m-d') }}" 
                 required>
         </div>
     </div>

 </div>

 <!--address-->
 <div class="row">
     <div class="form-group col-md-2">
         <label class="form-label" for="address1">Communication Address</label>
         <input type="text" class="form-control" id="address1" name="address1" placeholder="Adress line 1"
             required  value="{{ $patientProfile->address1 }}">
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <input type="text" class="form-control" id="address2" name="address2" placeholder="Adress line 2"
             required value="{{ $patientProfile->address2 }}">
     </div>

    <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
        <select class="select2" id="country_id" name="country_id" required data-placeholder="Select a Country" style="width: 100%;">
            @foreach ($countries as $country)
                <option value="{{ $country->id }}" {{ $patientProfile->country_id == $country->id ? 'selected' : '' }}>{{ $country->country }}</option>
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
         <input class="form-control" type="text" id="pincode" name="pincode" required placeholder="pin code" value="{{ $patientProfile->pincode }}">
     </div>
 </div>
