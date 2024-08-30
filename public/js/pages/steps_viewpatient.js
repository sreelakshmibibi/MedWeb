let apphistoryStepAdded = false;
let billhistoryStepAdded = false;
let appvisitcount = 0;

appvisitcount = $("#pvisitcount").val();

var form = $("#patientviewform").show();

function handleRemainingSteps(visitcount) {
    if (visitcount != "0") {
        handleAppHistoryStep();
        handleBillHistoryStep();
    }
}

function handleAppHistoryStep() {
    var apphistoryContent = $(".appointmenthistorydiv").html();
    // Add the history step if it hasn't been added
    if (!apphistoryStepAdded) {
        $("#patientviewform").steps("add", {
            title: "Appointment History",
            content: apphistoryContent,
            enableCancelButton: false,
            // enablePreviousButton: true,
            enableNextButton: true,
        });
        apphistoryStepAdded = true;
    }
}

function handleBillHistoryStep() {
    var billhistoryContent = $(".billhistorydiv").html();
    // Add the history step if it hasn't been added
    if (!billhistoryStepAdded) {
        $("#patientviewform").steps("add", {
            title: "Bills Paid",
            content: billhistoryContent,
            enableCancelButton: false,
            // enablePreviousButton: true,
            enableNextButton: true,
        });
        billhistoryStepAdded = true;
    }
}

$("#patientviewform").steps({
    headerTag: "h6",
    bodyTag: "section",
    transitionEffect: "none",
    titleTemplate: "#title#",
    labels: {
        finish: '<span><i class="fa fa-save"></i> Save</span>',
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        form = $("#patientviewform");

        return true;
    },
});
