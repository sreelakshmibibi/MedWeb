 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="firstname">First Name</label>
             <div class="input-group form-group">
                <select class="form-control select-title" id="title" name="title" required>
                    <option value="Dr." <?php

use App\Models\User;

 if (trim($name[0]) == "Dr.") echo "selected";?>>Dr.</option>
                    <option value="Mr." <?php if (trim($name[0]) == "Mr.") echo "selected";?>>Mr.</option>
                    <option value="Miss" <?php if (trim($name[0]) == "Miss.") echo "selected";?>>Miss</option>
                    <option value="Mrs." <?php if (trim($name[0]) == "Mrs.") echo "selected";?>>Mrs.</option>
                </select>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name"
                     required style="width: 100px;" value="{{ $name[0] ? $name[0] : ''}}">
             </div>
         </div>
     </div>
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="lastname">Last Name</label>
             <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" required value="{{ $name[0] ? $name[0] : ''}}">
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="gender">Gender</label>
             <select class="form-select" id="gender" name="gender" required>
                 <option value="M" <?php if( $staffProfile->gender == "M") echo "selected";?>>Male</option>
                 <option value="F" <?php if( $staffProfile->gender == "F") echo "selected";?>>Female</option>
                 <option value="O" <?php if( $staffProfile->gender == "O") echo "selected";?>>Others</option>
             </select>
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="date_of_birth">Date of Birth</label>
             <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required value="{{ $staffProfile->date_of_birth}}" >
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="role">Role</label>
             <select class="form-control select2 form-select" required id="role" name="role[]"
                 data-placeholder=" Select a Role" style="width: 100%;" multiple>
                 @foreach ($userTypes as $userType)
                     <option value="{{ $userType->id }}" <?php if ($userType->id == User::IS_ADMIN && $userDetails->is_admin ) echo "selected";?>
                     <?php if ($userType->id == User::IS_DOCTOR && $userDetails->is_doctor ) echo "selected";?> 
                     <?php if ($userType->id == User::IS_NURSE && $userDetails->is_nurse ) echo "selected";?>
                     <?php if ($userType->id == User::IS_RECEPTION && $userDetails->is_reception ) echo "selected";?>  
                     >{{ $userType->title }}</option>
                 @endforeach
             </select>
         </div>
     </div>
 </div>

 <div class="row">
     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="aadhaar_no">Aadhaar No</label>
             <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no" placeholder="Aadhaar no" required value="{{$staffProfile->aadhaar_no}}">
         </div>
     </div>

     <div class="col-md-3">
         <div class="form-group">
             <label class="form-label" for="email">E-mail Address</label>
             <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" required value="{{$userDetails->email}}">
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="phone">Contact
                 Number</label>
             <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number"
                 required value="{{ $staffProfile->phone}}">
         </div>
     </div>
     <div class="col-md-2">
         <div class="form-group">
             <label class="form-label" for="profile_photo">Photo</label>
             <input class="form-control @error('profile_photo') is-invalid @enderror" type="file" id="profile_photo"
                 name="profile_photo" placeholder="logo">
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
         <label class="form-label" for="address1">Residential Address</label>
         <input type="text" class="form-control" id="address1" name="address1" placeholder="Adress line 1"
             required value="{{$staffProfile->address1}}">
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <input type="text" class="form-control" id="address2" name="address2" placeholder="Adress line 2"
             required value="{{ $staffProfile->address2 }}">
     </div>

     <div class="form-group col-md-2 d-flex flex-wrap align-content-end">
         <select class="select2" id="country_id" name="country_id" required data-placeholder="Select a Country"
             style="width: 100%;">
             @foreach ($countries as $country)
                 <option value="{{ $country->id }}" <?php if ($staffProfile->country_id == $country->id) {
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
         <input class="form-control" type="text" id="pincode" name="pincode" required placeholder="pin code" value="{{$staffProfile->pincode}}">
     </div>
 </div>

 <div class="row">
     <div class="form-group mb-0">
         <label class="form-label" for="add_checkbox">Communication Address:&nbsp;</label>&nbsp;
         <input type="checkbox" id="add_checkbox" name="add_checkbox" class="filled-in chk-col-success" />
         <label for="add_checkbox">Same as Residential Address</label>
     </div>
     <div class="row" id="communicationAddress">
        <div class="form-group col-md-2">
            <input type="text" class="form-control " id="com_address1" name="com_address1" placeholder="Adress line 1"
                required  value="{{ $staffProfile->com_address1}}">
        </div>

        <div class="form-group col-md-2 ">
            <input type="text" class="form-control" id="com_address2" name="com_address2" placeholder="Adress line 2"
                required value="{{ $staffProfile->com_address2}}">
        </div>

        <div class="form-group col-md-2 ">
            <select class="select2" id="com_country_id" name="com_country_id" required data-placeholder="Select a Country"
                style="width: 100%;">
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" <?php if ($staffProfile->com_country_id == $country->id) {
                        echo 'selected';
                    } ?>>
                        {{ $country->country }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-2 ">
            <select class="select2" id="com_state_id" name="com_state_id" required data-placeholder="Select a State"
                style="width: 100%;">

            </select>
        </div>

        <div class="form-group col-md-2 ">
            <select class="select2" required id="com_city_id" name="com_city_id" data-placeholder="Select a City"
                style="width: 100%;">
            </select>
        </div>

        <div class="form-group col-md-2 ">
            <input class="form-control" type="text" id="com_pincode" name="com_pincode" required placeholder="pin code" value="{{$staffProfile->com_pincode}}">
        </div>
     </div>
 </div>
