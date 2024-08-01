<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-clock me-15"></i>
        Treatment Chart
    </h5>
    {{-- <button id="buttonAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary"
        data-bs-toggle='modal' data-bs-target='#modal-dtable'>
        <i class="fa fa-table"></i>
        Info</button> --}}
</div>
<hr class="my-15 ">

<div class="table-responsive mb-4">
    <table id="myTable" class="table table-bordered table-hover table-striped mb-0 text-center">

        <thead>
            <tr class="bg-primary-light">
                <th>No</th>
                <th>Tooth No</th>

                <th>Treatment</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="tablebody">
            <tr>
                <td>1</td>
                <td>11</td>
                <td>Root Canal</td>
                <td> <input type="checkbox" id="checkbox_row3" class="filled-in chk-col-primary">
                    <label for="checkbox_row3">Done</label>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="d-flex align-items-center justify-content-between">
    <h5 class="box-title text-info mb-0 mt-2 "><i class="fa fa-clock me-15"></i>
        Dental Chart
    </h5>
    <button id="buttonAddRow" type="button" class="waves-effect waves-light btn btn-sm btn-outline-primary"
        data-bs-toggle='modal' data-bs-target='#modal-dtable'>
        <i class="fa fa-table"></i>
        Info</button>
</div>
<hr class="my-15 ">

<div class="row">
    <div class="col-xl-8">
        <div class="table-responsive mb-4">
            <table id="myTable" class="table table-bordered table-hover table-striped mb-0 text-center">

                <thead>
                    <tr class="bg-primary-light">
                        <th>No</th>
                        <th>Tooth No</th>
                        <th>Buccal</th>
                        <th>Palatal</th>
                        <th>Mesial</th>
                        <th>Distal</th>
                        <th>Occulusal</th>
                        <th>Lingual</th>
                        <th>Labial</th>
                        <th>Tooth Score</th>
                        <th>Treatment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tablebody">
                    <tr>
                        <td>1</td>
                        <td>11</td>
                        <td>7</td>
                        <td>0</td>
                        <td>9</td>
                        <td>0</td>
                        <td>0</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>2</td>
                        <td>Root Canal</td>
                        <td> <input type="checkbox" id="checkbox_row3" class="filled-in chk-col-primary">
                            <label for="checkbox_row3">Done</label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        {{-- </div> --}}
        <div class="d-none col-xl-4">
            {{-- <div class=" col-md-2"> --}}
            <div class="table-responsive">
                <table id="toothScoreTable"
                    class="table table-sm table-bordered table-hover table-striped mb-0 text-center b-1 border-dark">

                    <thead class="bg-dark">
                        {{-- <tr class="bg-primary-light"> --}}
                        <tr>
                            <th>Tooth Score</th>
                            <th>Code</th>
                            <th>Tooth Surface</th>
                            <th>Code</th>
                        </tr>
                    </thead>
                    <tbody id="tablebody">
                        <tr>
                            <td>Sound</td>
                            <td>0</td>
                            <td>Decayed</td>
                            <td>7</td>
                        </tr>
                        <tr>
                            <td>Decayed- D(d)</td>
                            <td>1</td>
                            <td>Filled</td>
                            <td>8</td>
                        </tr>
                        <tr>
                            <td>Missing- M</td>
                            <td>2</td>
                            <td>Have Fissure Sealant (HFS)</td>
                            <td>9</td>
                        </tr>
                        <tr>
                            <td>Filled- F</td>
                            <td>3</td>
                            <td>Need Fissure Sealant (NFS)</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td>For Extraction- X(x)</td>
                            <td>4</td>
                        </tr>
                        <tr>
                            <td>Impacted</td>
                            <td>5</td>
                        </tr>
                        <tr>
                            <td>Unerupted</td>
                            <td>6</td>
                        </tr>
                    </tbody>

                </table>
            </div>
            {{-- </div> --}}
        </div>
    </div>





    @include('appointment.tooth_info')
    <div class="d-none col-md-2">
        <div class="table-responsive">
            <table id="toothScoreTable"
                class="table table-sm table-bordered table-hover table-striped mb-0 text-center b-1 border-dark">

                <thead class="bg-dark">
                    {{-- <tr class="bg-primary-light"> --}}
                    <tr>
                        <th>Tooth Score</th>
                        <th>Code</th>
                    </tr>
                </thead>
                <tbody id="tablebody">
                    <tr>
                        <td>Sound</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>Decayed- D(d)</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>Missing- M</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>Filled- F</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>For Extraction- X(x)</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>Impacted</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>Unerupted</td>
                        <td>6</td>
                    </tr>
                </tbody>
                <thead class="bg-dark">
                    <tr>
                        <th>Tooth Surface</th>
                        <th>Code</th>
                    </tr>
                </thead>
                <tbody id="tablebody">
                    <tr>
                        <td>Decayed</td>
                        <td>7</td>
                    </tr>
                    <tr>
                        <td>Filled</td>
                        <td>8</td>
                    </tr>
                    <tr>
                        <td>Have Fissure Sealant (HFS)</td>
                        <td>9</td>
                    </tr>
                    <tr>
                        <td>Need Fissure Sealant (NFS)</td>
                        <td>10</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
