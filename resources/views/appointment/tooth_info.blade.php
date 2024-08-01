<div class="modal fade modal-right slideInRight" id="modal-dtable" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable h-p100">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa-solid fa-calendar-days"></i> Tooth Score & Tooth Surface Condition
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">

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
            </div>

            <div class="modal-footer modal-footer-uniform">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success float-end" id="newAppointmentBtn">Save</button>
            </div>
        </div>
    </div>
</div>
