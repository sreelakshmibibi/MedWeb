<?php
use App\Models\TeethRow;
use Illuminate\Support\Facades\Session;

$upper_ped_teethImages = [[['class' => 'pediatric molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '55'], ['class' => 'pediatric molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '54'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '53'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '52'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '51'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '61'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '62'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '63'], ['class' => 'pediatric molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '64'], ['class' => 'pediatric molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '65']]];

$upper_teethImages = [
    [
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '18'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '17'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '16'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '15'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '14'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '13'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '12'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '11'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '21'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '22'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '23'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '24'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '25'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '26'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '27'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '28'],
    ],
];

$lower_ped_teethImages = [[['class' => 'pediatric molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '85'], ['class' => 'pediatric molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '84'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '83'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '82'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '81'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '71'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '72'], ['class' => 'pediatric inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '73'], ['class' => 'pediatric molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '74'], ['class' => 'pediatric molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '75']]];

$lower_teethImages = [
    [
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '48'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '47'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '46'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '45'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '44'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '43'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '42'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '41'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '31'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '32'],
        ['class' => 'normal inccan', 'image' => 'images/tooth/img1.svg', 'teeth_name' => '33'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '34'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '35'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '36'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '37'],
        ['class' => 'normal molar', 'image' => 'images/tooth/tooth_img/img1.svg', 'teeth_name' => '38'],
    ],
];

// $additionalTeethImages = [['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'], ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'], ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'], ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'], ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'], ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'], ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'], ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'], ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'], ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png']];

// $additionalNormalTeethImages = [
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric inccan', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
//     ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
// ];

?>

<div class=" row">
    <div class="alert alert-success" id="successMessage" style="display:none;"></div>
    <div class="box no-border">
        <div class="box-body tooth-boxbody  ">
            <div class="tooth_body">
                <div class="dental-chart ">
                    @foreach ($upper_ped_teethImages as $row)
                        <div class="row teethrow" id="trow1">
                            @foreach ($row as $tooth)
                                <div class="tooth px-2 pt-2 {{ $tooth['class'] }} 
                                @php echo ($tooth['teeth_name']=='51') ? 'be-1' : ''; @endphp"
                                    id="div{{ $tooth['teeth_name'] }}">
                                    <img id="{{ $tooth['teeth_name'] }}" class="teeth_image"
                                        src="{{ asset($tooth['image']) }}" alt=""
                                        title="T{{ $tooth['teeth_name'] }}">
                                    <p class="image-text">{{ $tooth['teeth_name'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        {{-- <div class=" row mb-2">
                            @foreach ($additionalTeethImages as $tooth)
                                <div class="tooth  {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                </div>
                            @endforeach
                        </div> --}}
                    @endforeach

                    @foreach ($upper_teethImages as $row)
                        <div class="row bb-1 teethrow" id="trow2">
                            @foreach ($row as $tooth)
                                <div class="tooth px-2 pt-2 pb-1 {{ $tooth['class'] }}  
                                @php echo ($tooth['teeth_name']=='11') ? 'be-1' : ''; @endphp"
                                    id="div{{ $tooth['teeth_name'] }}">
                                    <img id="{{ $tooth['teeth_name'] }}" class="teeth_image"
                                        src="{{ asset($tooth['image']) }}" alt=""
                                        title="T{{ $tooth['teeth_name'] }}">
                                    <p class="image-text">{{ $tooth['teeth_name'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        {{-- <div class=" row ">
                            @foreach ($additionalNormalTeethImages as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                </div>
                            @endforeach
                        </div> --}}
                    @endforeach

                    @foreach ($lower_teethImages as $row)
                        {{-- <div class=" row mt-4">
                            @foreach ($additionalNormalTeethImages as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                </div>
                            @endforeach
                        </div> --}}
                        <div class="row teethrow" id="trow3">
                            @foreach ($row as $tooth)
                                <div class="tooth px-2 pt-1 pb-2 {{ $tooth['class'] }}  
                                @php echo ($tooth['teeth_name']=='41') ? 'be-1' : ''; @endphp"
                                    id="div{{ $tooth['teeth_name'] }}" style="direction: rtl;">
                                    <p class="image-text mb-0">{{ $tooth['teeth_name'] }}</p>
                                    <img id="{{ $tooth['teeth_name'] }}" class="teeth_image"
                                        src="{{ asset($tooth['image']) }}" alt=""
                                        title="T{{ $tooth['teeth_name'] }}">
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    @foreach ($lower_ped_teethImages as $row)
                        {{-- <div class=" row mt-2">
                            @foreach ($additionalTeethImages as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                </div>
                            @endforeach
                        </div> --}}
                        <div class="row teethrow" id="trow4">
                            @foreach ($row as $tooth)
                                <div class="tooth px-2 pt-1 pb-2 {{ $tooth['class'] }} 
                                @php echo ($tooth['teeth_name']=='81') ? 'be-1' : ''; @endphp"
                                    id="div{{ $tooth['teeth_name'] }}" style="direction: rtl;">
                                    <p class="image-text mb-0">{{ $tooth['teeth_name'] }}</p>
                                    <img id="{{ $tooth['teeth_name'] }}" class="teeth_image"
                                        src="{{ asset($tooth['image']) }}" alt=""
                                        title="T{{ $tooth['teeth_name'] }}">
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div class="select-chart position-absolute">
                    <div class="row">
                        <div class="select-div" title="Primary Maxilliary Definition">
                            <input type="checkbox" id="checkbox_row1" name="rowChecked"
                                class="filled-in chk-col-primary" value="<?= TeethRow::Row1 ?>">
                            <label for="checkbox_row1">Select Row</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="select-div" title="Permanent Maxilliary Definition">
                            <input type="checkbox" id="checkbox_row2" name="rowChecked"
                                class="filled-in chk-col-primary" value="<?= TeethRow::Row2 ?>">
                            <label for="checkbox_row2">Select Row</label>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="select-div" title="Permanent Mandibular Definition">
                            <input type="checkbox" id="checkbox_row3" name="rowChecked"
                                class="filled-in chk-col-primary" value="<?= TeethRow::Row3 ?>">
                            <label for="checkbox_row3">Select Row</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="select-div" title="Primary Mandibular Definition">
                            <input type="checkbox" id="checkbox_row4" name="rowChecked"
                                class="filled-in chk-col-primary" value="<?= TeethRow::Row4 ?>">
                            <label for="checkbox_row4">Select Row</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row position-absolute" style="left:2rem; top:0;">
                {{-- <div class="row position-absolute" style="left:2rem; top:-1.5rem;"> --}}
                <div class="select-div">
                    <input type="checkbox" id="checkbox_all" class="filled-in chk-col-success"
                        value="<?= TeethRow::RowAll ?>">
                    <label for="checkbox_all">Other</label>
                </div>
            </div>
            <div>
                <ul class="list-style-none ps-0">
                    <li><span class="badge badge-dot badge-danger"></span>&nbsp; Treatment ongoing</li>
                    <li><span class="badge badge-dot badge-success"></span>&nbsp; Treatment completed before</li>
                    <li><span class="badge badge-dot" style="background-color:yellow"></span>&nbsp; Treatment Follow up
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var images = document.querySelectorAll('.teeth_image');
        var appId = '<?= Session::get('appId') ?>';
        var patientId = '<?= Session::get('patientId') ?>';
        $('#app_id').val(appId);
        $('#patient_id').val(patientId);
        $('#xapp_id').val(appId);
        $('#xpatient_id').val(patientId);

        var toothData = @json($toothIds); // Convert PHP array to JSON format

        toothData.forEach(function(tooth) {
            var toothId = tooth.tooth_id;
            var rowId = tooth.row_id;
            var treatStatus = tooth.treatment_status;
            if (toothId != null) {
                var anatomyImage = tooth.anatomy_image;

                var divId = '#div' + toothId;
                if (treatStatus == 1) {
                    $(divId).addClass('treatment');
                } else {
                    $(divId).addClass('follow');
                }
                // $(divId).addClass('treatment');

                var teethId = '#' + toothId;
                // var imgSrc = "{{ asset('') }}" + anatomyImage;
                // Replace the file extension from .png to .svg
                var svgImage = anatomyImage.replace(/\.png$/, '.svg');
                var imgSrc = "{{ asset('') }}" + svgImage;
                $(teethId).attr('src', imgSrc);
            }
            if (rowId != null) {
                var rowId = '#trow' + rowId;
                if (treatStatus == 1) {
                    $(rowId).addClass('treatment');
                } else {
                    $(rowId).addClass('follow');
                }
            }
        });

        images.forEach(function(img) {

            img.addEventListener('click', function() {
                var teethName = this.id;
                $('.exam_chiefComplaint').hide();
                var divId = '#div' + teethName;
                $(divId).addClass('blue');
                // $(this).toggleClass('selected');
                $('#tooth_id').val(teethName);
                $('#xteeth_id').val(teethName);

                if ($(divId).hasClass('molar')) {
                    $('#premolars_molars').show();
                    $('#incisors_canines').hide();
                } else if ($(divId).hasClass('inccan')) {
                    $('#incisors_canines').show();
                    $('#premolars_molars').hide();
                }
                $.ajax({
                    url: "{{ route('get.toothExamination', ['toothId' => ':toothId', 'appId' => ':appId', 'patientId' => ':patientId']) }}"
                        .replace(':toothId', teethName)
                        .replace(':appId', appId)
                        .replace(':patientId', patientId),
                    type: "GET",
                    dataType: "json",

                    success: function(response) {
                        var examination = response
                            .examination; // Assuming there's only one item in the array

                        if (examination != null) {
                            // Set the value of tooth_score_id field
                            var toothScoreId = examination.tooth_score_id;
                            $('#tooth_score_id').val(toothScoreId);

                            // Loop through options to find the corresponding text and select it
                            $('#tooth_score_id option').each(function() {
                                if ($(this).val() == toothScoreId) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });
                            $('#tooth_score_id').trigger('change');
                            $('#chief_complaint').val(examination.chief_complaint);

                            // $('#chief_complaint1').val(examination.chief_complaint);
                            $('#hpi').val(examination.hpi);
                            $('#diagnosis').val(examination.diagnosis);
                            $('#dental_examination').val(examination
                                .dental_examination);
                            $('#remarks').val(examination.remarks);
                            var disease_id = examination.disease_id;
                            $('#disease_id').val(disease_id);

                            var diseaseName = response
                                .diseaseName.name;
                            var found = false;
                            // Loop through options to find the corresponding text and select it
                            $('#disease_id option').each(function() {
                                if ($(this).val() == disease_id) {
                                    $(this).prop('selected', true);
                                    found = true; // Set the flag to true
                                    return false; // Exit the loop once found
                                }
                            });

                            if (!found) {
                                $('.disease_id_select').empty();
                                var diseaseSelect = $('.disease_id_select');
                                var option = new Option(diseaseName, disease_id,
                                    true, true);
                                diseaseSelect.append(option).trigger(
                                    'change');
                                $('.disease_id_select').val(disease_id);
                                // manually trigger the `select2:select` event
                                // diseaseSelect.trigger({
                                //     type: 'select2:select',
                                //     params: {
                                //         data: data
                                //     }
                                // });
                            }

                            $(".disease_id_select").select2("destroy");
                            $(".disease_id_select").select2({
                                dropdownParent: $('#modal-teeth'),
                                width: "100%",
                                placeholder: "Select a Disease",
                                tags: true,
                                tokenSeparators: [","],
                                createTag: function(params) {
                                    var term = $.trim(params.term);
                                    if (term === "") {
                                        return null;
                                    }
                                    // Check if the term already exists as an option
                                    var found = false;
                                    $(".treatment_id_select option")
                                        .each(function() {
                                            if ($.trim($(this)
                                                    .text()) ===
                                                term) {
                                                found = true;
                                                return false; // Exit the loop early
                                            }
                                        });
                                    if (!found) {
                                        return {
                                            id: term,
                                            text: term,
                                            newTag: true
                                        };
                                    }
                                    return null;
                                },
                                insertTag: function(data, tag) {
                                    data.push(tag);
                                }
                            });

                            var treatment_id = examination.treatment_id;
                            $('#treatment_id').val(treatment_id);

                            // Loop through options to find the corresponding text and select it
                            $('#treatment_id option').each(function() {
                                if ($(this).val() == treatment_id) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });
                            var treatment_plan_id = examination
                                .treatment_plan_id;
                            $('#treatment_plan_id').val(treatment_plan_id);

                            // Loop through options to find the corresponding text and select it
                            $('#treatment_plan_id option').each(function() {
                                if ($(this).val() ==
                                    treatment_plan_id) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });
                             var shade_id = examination
                                .shade_id;
                            $('#shade_id').val(shade_id);

                            // Loop through options to find the corresponding text and select it
                            $('#shade_id option').each(function() {
                                if ($(this).val() ==
                                    shade_id) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });
                             var instructions = examination
                                .instructions;
                            $('#instructions').val(instructions);

                            var treatment_status = examination.treatment_status;
                            $('#treatment_status').val(treatment_status);

                            // Loop through options to find the corresponding text and select it
                            $('#treatment_status option').each(function() {
                                if ($(this).val() == treatment_status) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });

                            var palatal_condn = examination.palatal_condn;
                            $('#palatal_condn').val(palatal_condn);

                            if (palatal_condn !== null) {
                                $("#Palatal").show();
                                // var dpartId = '#' + $('.dparts[title="Palatal"]')
                                //     .attr(
                                //         'id');
                                var dpartId = $('.dparts[title="Palatal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            // Loop through options to find the corresponding text and select it
                            $('#palatal_condn option').each(function() {
                                if ($(this).val() == palatal_condn) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });

                            var mesial_condn = examination.mesial_condn;
                            $('#mesial_condn').val(mesial_condn);
                            if (mesial_condn !== null) {
                                $("#Mesial").show();
                                // var dpartId = '#' + $('.dparts[title="Mesial"]')
                                //     .attr(
                                //         'id');
                                var dpartId = $('.dparts[title="Mesial"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            // Loop through options to find the corresponding text and select it
                            $('#mesial_condn option').each(function() {
                                if ($(this).val() == mesial_condn) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });

                            var distal_condn = examination.distal_condn;
                            $('#distal_condn').val(distal_condn);

                            if (distal_condn !== null) {
                                $("#Distal").show();
                                // var dpartId = '#' + $('.dparts[title="Distal"]')
                                //     .attr(
                                //         'id');
                                var dpartId = $('.dparts[title="Distal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            // Loop through options to find the corresponding text and select it
                            $('#distal_condn option').each(function() {
                                if ($(this).val() == distal_condn) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });

                            var buccal_condn = examination.buccal_condn;
                            $('#buccal_condn').val(buccal_condn);

                            if (buccal_condn !== null) {
                                $("#Buccal").show();
                                // var dpartId = '#' + $('.dparts[title="Buccal"]')
                                //     .attr(
                                //         'id');
                                var dpartId = $('.dparts[title="Buccal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            // Loop through options to find the corresponding text and select it
                            $('#buccal_condn option').each(function() {
                                if ($(this).val() == buccal_condn) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });

                            var occulusal_condn = examination.occulusal_condn;
                            $('#occulusal_condn').val(occulusal_condn);

                            if (occulusal_condn !== null) {
                                $("#Occulusal").show();
                                // var dpartId = '#' + $('.dparts[title="Occulusal"]')
                                //     .attr(
                                //         'id');
                                var dpartId = $('.dparts[title="Occulusal"]');
                                $(dpartId).css({
                                    'background-color': 'red',
                                });
                                $(dpartId).addClass('red');
                            }

                            // Loop through options to find the corresponding text and select it
                            $('#occulusal_condn option').each(function() {
                                if ($(this).val() == occulusal_condn) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });

                            var labial_condn = examination.labial_condn;
                            $('#labial_condn').val(labial_condn);

                            // Loop through options to find the corresponding text and select it
                            $('#labial_condn option').each(function() {
                                if ($(this).val() == labial_condn) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });

                            var lingual_condn = examination.lingual_condn;
                            $('#lingual_condn').val(lingual_condn);

                            // Loop through options to find the corresponding text and select it
                            $('#lingual_condn option').each(function() {
                                if ($(this).val() == lingual_condn) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });
                        } else {
                            toothData.forEach(function(tooth) {
                                var toothId = tooth.tooth_id;
                                if (toothId == teethName) {
                                    var palatal_condn = tooth
                                        .palatal_condn;
                                    $('#palatal_condn').val(
                                        palatal_condn);

                                    if (palatal_condn !== null) {
                                        $("#Palatal").show();
                                        var dpartId = $(
                                            '.dparts[title="Palatal"]'
                                        );
                                        $(dpartId).css({
                                            'background-color': 'red',
                                        });
                                        $(dpartId).addClass('red');
                                    }

                                    var mesial_condn = tooth
                                        .mesial_condn;
                                    $('#mesial_condn').val(
                                        mesial_condn);
                                    if (mesial_condn !== null) {
                                        $("#Mesial").show();
                                        var dpartId = $(
                                            '.dparts[title="Mesial"]'
                                        );
                                        $(dpartId).css({
                                            'background-color': 'red',
                                        });
                                        $(dpartId).addClass('red');
                                    }

                                    var distal_condn = tooth
                                        .distal_condn;
                                    $('#distal_condn').val(
                                        distal_condn);

                                    if (distal_condn !== null) {
                                        $("#Distal").show();
                                        var dpartId = $(
                                            '.dparts[title="Distal"]'
                                        );
                                        $(dpartId).css({
                                            'background-color': 'red',
                                        });
                                        $(dpartId).addClass('red');
                                    }

                                    var buccal_condn = tooth
                                        .buccal_condn;
                                    $('#buccal_condn').val(
                                        buccal_condn);

                                    if (buccal_condn !== null) {
                                        $("#Buccal").show();
                                        var dpartId = $(
                                            '.dparts[title="Buccal"]'
                                        );
                                        $(dpartId).css({
                                            'background-color': 'red',
                                        });
                                        $(dpartId).addClass('red');
                                    }

                                    var occulusal_condn = tooth
                                        .occulusal_condn;
                                    $('#occulusal_condn').val(
                                        occulusal_condn);

                                    if (occulusal_condn !== null) {
                                        $("#Occulusal").show();
                                        var dpartId = $(
                                            '.dparts[title="Occulusal"]'
                                        );
                                        $(dpartId).css({
                                            'background-color': 'red',
                                        });
                                        $(dpartId).addClass('red');
                                    }

                                    var labial_condn = tooth
                                        .labial_condn;
                                    $('#labial_condn').val(
                                        labial_condn);

                                    var lingual_condn = tooth
                                        .lingual_condn;
                                    $('#lingual_condn').val(
                                        lingual_condn);
                                }
                            });

                        }

                        var xrays = response.xrays;
                        if (Array.isArray(xrays) && xrays.length > 0) {
                            // Show the link
                            $('#uploadedXrays').show();
                            $('#uploadedXrays').attr('data-id', examination.id);
                            $('#xtooth_exam_id').val(examination.id);
                        } else {
                            // Hide the link if no xrays or not an array
                            $('#uploadedXrays').hide();
                            $('#uploadedXrays').attr('data-id', null);
                            $('#xtooth_exam_id').val('');
                        }
                    },

                });

                $('#modal-teeth').modal('show');
                // $('#modal-teeth').show();

            });

            $('#uploadedXrays').click(function() {
                $('#modal-documents').modal('show');
            });

        });

        // Check initial state of checkbox
        if ($('#checkbox_all').is(':checked')) {
            $('.exam_toothdiv').hide();
            $('#modal-teeth').modal('show');
            // $('#trow1').addClass('rowbordered');
            // $('#trow2').addClass('rowbordered');
            // $('#trow3').addClass('rowbordered');
            // $('#trow4').addClass('rowbordered');
        } else {
            $('.exam_toothdiv').show();
            // $('#trow1').removeClass('rowbordered');
            // $('#trow2').removeClass('rowbordered');
            // $('#trow3').removeClass('rowbordered');
            // $('#trow4').removeClass('rowbordered');
        }

        $('#checkbox_all').change(function() {
            if ($(this).is(':checked')) {
                $('.exam_toothdiv').hide();
                $('#modal-teeth').modal('show');
                $('.exam_chiefComplaint').show();
                // $('#trow1').addClass('rowbordered');
                // $('#trow2').addClass('rowbordered');
                // $('#trow3').addClass('rowbordered');
                // $('#trow4').addClass('rowbordered');
            } else {
                $('.exam_toothdiv').show();
                $('.exam_chiefComplaint').hide();
                // $('#trow1').removeClass('rowbordered');
                // $('#trow2').removeClass('rowbordered');
                // $('#trow3').removeClass('rowbordered');
                // $('#trow4').removeClass('rowbordered');
            }
        });

        if ($('#checkbox_all').is(':checked')) {
            $('.exam_toothdiv').hide();
            $('#row_id').val(5);
            getRowData(5, patientId, appId);
            $('#modal-teeth').modal('show');

        } else {
            $('.exam_toothdiv').show();

        }


        $('#checkbox_all').change(function() {
            if ($(this).is(':checked')) {
                $('.exam_toothdiv').hide();
                $('.exam_chiefComplaint').show();
                $('#row_id').val(5);
                getRowData(5, patientId, appId);
                $('#modal-teeth').modal('show');

            } else {
                $('.exam_toothdiv').show();
                $('.exam_chiefComplaint').hide();

            }
        });

        if ($('#checkbox_row1').is(':checked')) {
            $('.exam_toothdiv').hide();
            $('#row_id').val(1);
            getRowData(1, patientId, appId);
            $('#modal-teeth').modal('show');
            $('#trow1').addClass('rowbordered');
        } else {
            $('.exam_toothdiv').show();
            $('#trow1').removeClass('rowbordered');
        }


        $('#checkbox_row1').change(function() {
            if ($(this).is(':checked')) {
                $('.exam_toothdiv').hide();
                $('.exam_chiefComplaint').show();
                $('#row_id').val(1);
                getRowData(1, patientId, appId);
                $('#modal-teeth').modal('show');
                $('#trow1').addClass('rowbordered');
            } else {
                $('.exam_toothdiv').show();
                $('.exam_chiefComplaint').hide();
                $('#trow1').removeClass('rowbordered');
            }
        });

        if ($('#checkbox_row2').is(':checked')) {
            $('.exam_toothdiv').hide();
            $('.exam_chiefComplaint').show();
            $('#row_id').val(2);
            getRowData(2, patientId, appId);
            $('#modal-teeth').modal('show');
            $('#trow2').addClass('rowbordered');
        } else {
            $('.exam_toothdiv').show();
            $('.exam_chiefComplaint').hide();
            $('#trow2').removeClass('rowbordered');
        }

        $('#checkbox_row2').change(function() {
            if ($(this).is(':checked')) {
                $('.exam_toothdiv').hide();
                $('.exam_chiefComplaint').show();
                $('#row_id').val(2);
                getRowData(2, patientId, appId);
                $('#modal-teeth').modal('show');
                $('#trow2').addClass('rowbordered');
            } else {
                $('.exam_toothdiv').show();
                $('.exam_chiefComplaint').hide();
                $('#trow2').removeClass('rowbordered');
            }
        });

        if ($('#checkbox_row3').is(':checked')) {
            $('.exam_toothdiv').hide();
            $('.exam_chiefComplaint').show();
            $('#row_id').val(3);
            getRowData(3, patientId, appId);
            $('#modal-teeth').modal('show');
            $('#trow3').addClass('rowbordered');
        } else {
            $('.exam_toothdiv').show();
            $('.exam_chiefComplaint').hide();
            $('#trow3').removeClass('rowbordered');
        }

        $('#checkbox_row3').change(function() {
            if ($(this).is(':checked')) {
                $('.exam_toothdiv').hide();
                $('.exam_chiefComplaint').show();
                $('#row_id').val(3);
                getRowData(3, patientId, appId);
                $('#modal-teeth').modal('show');
                $('#trow3').addClass('rowbordered');
            } else {
                $('.exam_chiefComplaint').hide();
                $('.exam_toothdiv').show();
                $('#trow3').removeClass('rowbordered');
            }
        });

        if ($('#checkbox_row4').is(':checked')) {
            $('.exam_toothdiv').hide();
            $('.exam_chiefComplaint').show();
            $('#row_id').val(4);
            getRowData(4, patientId, appId);
            $('#modal-teeth').modal('show');
            $('#trow4').addClass('rowbordered');
        } else {
            $('.exam_chiefComplaint').hide();
            $('.exam_toothdiv').show();
            $('#trow4').removeClass('rowbordered');
        }

        $('#checkbox_row4').change(function() {
            if ($(this).is(':checked')) {
                $('.exam_toothdiv').hide();
                $('.exam_chiefComplaint').show();
                $('#row_id').val(4);
                getRowData(4, patientId, appId);
                $('#modal-teeth').modal('show');
                $('#trow4').addClass('rowbordered');
            } else {
                $('.exam_toothdiv').show();
                $('.exam_chiefComplaint').hide();
                $('#trow4').removeClass('rowbordered');
            }
        });

        function getRowData(rowId, patientId, appId) {
            $.ajax({
                url: '{{ route('get.toothExamination', ['toothId' => ':toothId', 'appId' => ':appId', 'patientId' => ':patientId']) }}'
                    .replace(':toothId', rowId)
                    .replace(':appId', appId)
                    .replace(':patientId', patientId),
                type: "GET",
                dataType: "json",

                success: function(response) {
                    var examination = response
                        .examination; // Assuming there's only one item in the array

                    if (examination != null) {


                        // Set the value of tooth_score_id field
                        var toothScoreId = examination.tooth_score_id;
                        $('#tooth_score_id').val(toothScoreId);

                        // Loop through options to find the corresponding text and select it
                        $('#tooth_score_id option').each(function() {
                            if ($(this).val() == toothScoreId) {
                                $(this).prop('selected', true);
                                return false; // Exit the loop once found
                            }
                        });
                        $('#tooth_score_id').trigger('change');
                        $('#chief_complaint').val(examination.chief_complaint);
                        //$('#chief_complaint1').val(examination.chief_complaint);
                        $('#hpi').val(examination.hpi);
                        $('#diagnosis').val(examination.diagnosis);
                        $('#dental_examination').val(examination
                            .dental_examination);
                        $('#remarks').val(examination.remarks);
                        var disease_id = examination.disease_id;
                        $('#disease_id').val(disease_id);

                        // Loop through options to find the corresponding text and select it
                        $('#disease_id option').each(function() {
                            if ($(this).val() == disease_id) {
                                $(this).prop('selected', true);
                                return false; // Exit the loop once found
                            }
                        });

                        var treatment_id = examination.treatment_id;
                        $('#treatment_id').val(treatment_id);

                        // Loop through options to find the corresponding text and select it
                        $('#treatment_id option').each(function() {
                            if ($(this).val() == treatment_id) {
                                $(this).prop('selected', true);
                                return false; // Exit the loop once found
                            }
                        });
                        var shade_id = examination
                                .shade_id;
                            $('#shade_id').val(shade_id);

                            // Loop through options to find the corresponding text and select it
                            $('#shade_id option').each(function() {
                                if ($(this).val() ==
                                    shade_id) {
                                    $(this).prop('selected', true);
                                    return false; // Exit the loop once found
                                }
                            });
                             var instructions = examination
                                .instructions;
                            $('#instructions').val(instructions);

                        var treatment_status = examination.treatment_status;
                        $('#treatment_status').val(treatment_status);

                        // Loop through options to find the corresponding text and select it
                        $('#treatment_status option').each(function() {
                            if ($(this).val() == treatment_status) {
                                $(this).prop('selected', true);
                                return false; // Exit the loop once found
                            }
                        });


                    }
                    var xrays = response.xrays;
                    if (Array.isArray(xrays) && xrays.length > 0) {
                        // Show the link
                        $('#uploadedXrays').show();
                        $('#uploadedXrays').attr('data-id', examination.id);
                        $('#xtooth_exam_id').val(examination.id);
                    } else {
                        // Hide the link if no xrays or not an array
                        $('#uploadedXrays').hide();
                        $('#uploadedXrays').attr('data-id', null);
                        $('#xtooth_exam_id').val('');
                    }
                },

            });
        }

    });
</script>
