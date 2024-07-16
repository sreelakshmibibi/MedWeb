<div class="row">
    <div class="col-12 col-xl-12">
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label" for="tooth_selected">Tooth Selected <span class="text-danger">
                        *</span></label>
                <select class="form-select" id="tooth_selected" name="tooth_selected" required>
                    <option value="all">All tooth</option>
                    <option value="1">Row-1</option>
                    <option value="2">Row-2</option>
                    <option value="3">Row-3</option>
                    <option value="4">Row-4</option>
                    <option value="tooth_in">T11</option>
                    <option value="tooth_mol">T12</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col b-1 exam_toothdiv" style="display: none">
        <div class="box-body">
            <div class="row">
                <div class="col ">
                    <div class="dparts-wrapper" id="incisors_canines" style="display: none;">
                        <div class="dparts part-left" title="Mesial" id="part-left"></div>
                        <div class="dparts part-top" title="Buccal" id="part-top"></div>
                        <div class="dparts part-right" title="Distal" id="part-right"></div>
                        <div class="dparts part-bottom" title="Palatal" id="part-bottom"></div>
                    </div>

                    <div class="dparts-wrapper" id="premolars_molars" style="display: none;">
                        <div class="dparts dpart-left" title="Mesial" id="dpart-left"></div>
                        <div class="dparts dpart-top" title="Buccal" id="dpart-top"></div>
                        <div class="dparts dpart-right" title="Distal" id="dpart-right"></div>
                        <div class="dparts dpart-bottom" title="Palatal" id="dpart-bottom"></div>
                        <div class="dparts dpart-center" title="Occulusal" id="dpart-center"></div>
                    </div>
                </div>
                <div class="col pe-0">
                    <div class="col-md-12 pe-0">
                        <div class="form-group">
                            <label class="form-label" for="tooth_no">Tooth No</label>
                            <input type="text" class="form-control" id="tooth_no" name="tooth_no"
                                placeholder="tooth no">
                        </div>
                    </div>

                    <div class="col-md-12 pe-0">
                        <div class="form-group">
                            <label class="form-label" for="tooth_score">Tooth Score <span class="text-danger">
                                    *</span></label>
                            <select class="form-select" id="tooth_score" name="tooth_score" required>
                                <option value="">Select Score</option>
                                <option value="0">0- Sound</option>
                                <option value="1">1- Decayed- D(d)</option>
                                <option value="2">2- Missing- M</option>
                                <option value="3">3- Filled- F</option>
                                <option value="4">4- For Extraction- X(x)</option>
                                <option value="5">5- Impacted</option>
                                <option value="6">6- Unerupted</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-4">
                <div class="col-md-6 " id="Buccal" style="display: none">
                    <div class="form-group">
                        <label class="form-label" for="buccal_condn">Buccal <span class="text-danger">
                                *</span></label>
                        <select class="form-select" id="buccal_condn" name="buccal_condn" required>
                            <option value="">Select Condition</option>
                            <option value="7">7- Decayed</option>
                            <option value="8">8- Filled</option>
                            <option value="9">9- Have Fissure Sealant (HFS)</option>
                            <option value="10">10- Need Fissure Sealant (NFS)</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-6 " id="Palatal" style="display: none">
                    <div class="form-group">
                        <label class="form-label" for="palatal_condn">Palatal <span class="text-danger">
                                *</span></label>
                        <select class="form-select" id="palatal_condn" name="palatal_condn" required>
                            <option value="">Select Condition</option>
                            <option value="7">7- Decayed</option>
                            <option value="8">8- Filled</option>
                            <option value="9">9- Have Fissure Sealant (HFS)</option>
                            <option value="10">10- Need Fissure Sealant (NFS)</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-6 " id="Mesial" style="display: none">
                    <div class="form-group">
                        <label class="form-label" for="mesial_condn">Mesial <span class="text-danger">
                                *</span></label>
                        <select class="form-select" id="mesial_condn" name="mesial_condn" required>
                            <option value="">Select Condition</option>
                            <option value="7">7- Decayed</option>
                            <option value="8">8- Filled</option>
                            <option value="9">9- Have Fissure Sealant (HFS)</option>
                            <option value="10">10- Need Fissure Sealant (NFS)</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-6 " id="Distal" style="display: none">
                    <div class="form-group">
                        <label class="form-label" for="distal_condn">Distal <span class="text-danger">
                                *</span></label>
                        <select class="form-select" id="distal_condn" name="distal_condn" required>
                            <option value="">Select Condition</option>
                            <option value="7">7- Decayed</option>
                            <option value="8">8- Filled</option>
                            <option value="9">9- Have Fissure Sealant (HFS)</option>
                            <option value="10">10- Need Fissure Sealant (NFS)</option>

                        </select>
                    </div>
                </div>

                <div class="col-md-6 " id="Occulusal" style="display: none">
                    <div class="form-group">
                        <label class="form-label" for="occulusal_condn">Occulusal <span class="text-danger">
                                *</span></label>
                        <select class="form-select" id="occulusal_condn" name="occulusal_condn" required>
                            <option value="">Select Condition</option>
                            <option value="7">7- Decayed</option>
                            <option value="8">8- Filled</option>
                            <option value="9">9- Have Fissure Sealant (HFS)</option>
                            <option value="10">10- Need Fissure Sealant (NFS)</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="form-label" for="lingual_condn">Lingual <span class="text-danger">
                                *</span></label>
                        <select class="form-select" id="lingual_condn" name="lingual_condn" required>
                            <option value="">Select Condition</option>
                            <option value="7">7- Decayed</option>
                            <option value="8">8- Filled</option>
                            <option value="9">9- Have Fissure Sealant (HFS)</option>
                            <option value="10">10- Need Fissure Sealant (NFS)</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="form-label" for="labial_condn">Labial <span class="text-danger">
                                *</span></label>
                        <select class="form-select" id="labial_condn" name="labial_condn" required>
                            <option value="">Select Condition</option>
                            <option value="7">7- Decayed</option>
                            <option value="8">8- Filled</option>
                            <option value="9">9- Have Fissure Sealant (HFS)</option>
                            <option value="10">10- Need Fissure Sealant (NFS)</option>

                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="box-body">
            <div class="row">
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="form-label" for="complaint">Chief Complaint <span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control" id="complaint" name="complaint"
                            placeholder="Chief Complaint">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label" for="hpi">HPI</label>
                        <input type="text" class="form-control" id="hpi" name="hpi" placeholder="HPI">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="form-label" for="dexam">Dental Examination <span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control" id="dexam" name="dexam"
                            placeholder="Dental Examination">
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="form-label" for="diagnosis">Diagnosis <span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control" id="diagnosis" name="diagnosis"
                            placeholder="diagnosis">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="form-label" for="xray">X-Ray <span class="text-danger">
                                *</span></label>
                        <input type="file" class="form-control" id="xray" type="file" name="xray[]"
                            multiple>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="form-group">
                        <label class="form-label" for="treatment">Treatment <span class="text-danger">
                                *</span></label>
                        <select class="select2" id="treatment" name="treatment" required
                            data-placeholder="Select a Treatment" style="width: 100%;">

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">
                    <div class="form-group">
                        <label class="form-label" for="remarks">Remarks</label>
                        <input type="text" class="form-control" id="remarks" name="remarks"
                            placeholder="remarks">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
