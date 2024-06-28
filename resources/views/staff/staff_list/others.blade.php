 <!--id-->
 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="sid">Staff ID</label>
             <input type="text" class="form-control" id="sid" name="sid" placeholder="Staff ID" required>
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="uid">User ID</label>
             <input type="text" class="form-control" id="uid" name="uid" placeholder="National ID"
                 required>
         </div>
     </div>
 </div>

 <!--name-->
 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="fname">First Name</label>
             <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" required>
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="lname">Last Name</label>
             <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" required>
         </div>
     </div>
 </div>

 <!--gender-->
 <div class="form-group mt-2">
     <label class="form-label col-md-4">Gender</label>
     <input name="gender" type="radio" checked class="form-control with-gap" id="male" value="M">
     <label for="male">Male</label>
     <input name="gender" type="radio" class="form-control with-gap" id="female" value="F">
     <label for="female">Female</label>
     <input name="gender" type="radio" class="form-control with-gap" id="other" value="O">
     <label for="other">Other</label>
 </div>

 <!--dob-->
 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="dob">Date of Birth</label>
             <input type="date" class="form-control" id="dob" name="dob">
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="age">Age</label>
             <input type="text" class="form-control" id="age" name="age" placeholder="age">
         </div>
     </div>
 </div>

 <!--address-->
 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="address">Address Line
                 1</label>
             <input type="text" class="form-control" id="address" name="address" placeholder="Adress line 1">
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="address">Address Line
                 2</label>
             <input type="text" class="form-control" id="address" name="address" placeholder="Adress line 2">
         </div>
     </div>
 </div>

 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="location3">City</label>
             <select class="form-select" id="location3" name="location">
                 <option value="">Select City</option>
                 <option value="Hyderabad">Hyderabad</option>
                 <option value="Dubai">Dubai</option>
                 <option value="Delhi">Delhi</option>
             </select>
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="location3">State</label>
             <select class="form-select" id="location3" name="location">
                 <option value="">Select State</option>
                 <option value="Kerala">Kerala</option>
                 <option value="Karnataka">Karnataka</option>
                 <option value="Tamil Nadu">Tamil Nadu</option>
             </select>
         </div>
     </div>
 </div>

 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="location3">Country</label>
             <select class="form-select" id="location3" name="location">
                 <option value="">Select Country</option>
                 <option value="India">India</option>
                 <option value="UAE">UAE</option>
                 <option value="USA">USA</option>
             </select>
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="pincode">Pin Code</label>
             <input class="form-control" type="text" id="pincode" name="pincode" placeholder="XXX XXX">
         </div>
     </div>
 </div>

 <!--contact details-->
 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="email">E-mail</label>
             <input type="email" class="form-control" id="email" name="email" placeholder="E-mail">
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="phone">Contact
                 Number</label>
             <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number">
         </div>
     </div>
 </div>

 <!--department-->
 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="department">Department</label>
             <input type="text" class="form-control" id="department" name="department" placeholder="Department"
                 required>
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="designation">Designation</label>
             <input type="text" class="form-control" id="designation" name="designation"
                 placeholder="Designation" required>
         </div>
     </div>
 </div>

 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="experience">Experience</label>
             <input type="text" class="form-control" id="experience" name="experience" placeholder="experience"
                 required>
         </div>
     </div>
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="education">Education</label>
             <input type="text" class="form-control" id="education" name="education" placeholder="Education"
                 required>
         </div>
     </div>
 </div>
 <div class="row">
     <div class="col-md-6">
         <div class="form-group">
             <label class="form-label" for="logo">Photo</label>
             <input class="form-control" type="file" id="photo" name="photo" placeholder="photo">
         </div>
     </div>
     <div class="col-md-6">
         <canvas id="photoCanvas" style=" height:64px;">

         </canvas>
     </div>
 </div>

 <!--status-->
 <div class="form-group mt-2">
     <label class="form-label col-md-6">Active</label>
     <input name="status" type="radio" checked class="form-control with-gap" id="yes" value="Y">
     <label for="yes">Yes</label>
     <input name="status" type="radio" class="form-control with-gap" id="no" value="N">
     <label for="no">No</label>
 </div>
