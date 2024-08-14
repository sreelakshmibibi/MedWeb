function toggleCollectionDiv() {
    var collectionDiv = document.querySelector(".collectiondiv");
    if (collectionDiv.style.display === "none") {
        collectionDiv.style.display = "block";
    } else {
        collectionDiv.style.display = "none";
    }
}

function toggleIncomeDiv() {
    var incomeDiv = document.querySelector(".incomediv");
    if (incomeDiv.style.display === "none") {
        incomeDiv.style.display = "block";
    } else {
        incomeDiv.style.display = "none";
    }
}

function toggleServiceDiv() {
    var serviceDiv = document.querySelector(".servicediv");
    if (serviceDiv.style.display === "none") {
        serviceDiv.style.display = "block";
    } else {
        serviceDiv.style.display = "none";
    }
}

function togglePatientsDiv() {
    var patientsDiv = document.querySelector(".patientsdiv");
    if (patientsDiv.style.display === "none") {
        patientsDiv.style.display = "block";
    } else {
        patientsDiv.style.display = "none";
    }
}

function toggleDiseaseDiv() {
    var diseaseDiv = document.querySelector(".diseasediv");
    if (diseaseDiv.style.display === "none") {
        diseaseDiv.style.display = "block";
    } else {
        diseaseDiv.style.display = "none";
    }
}

document
    .getElementById("searchcollectionbtn")
    .addEventListener("click", toggleCollectionDiv);
document
    .getElementById("searchincomebtn")
    .addEventListener("click", toggleIncomeDiv);
document
    .getElementById("searchservicebtn")
    .addEventListener("click", toggleServiceDiv);
document
    .getElementById("searchpatientsbtn")
    .addEventListener("click", togglePatientsDiv);
document
    .getElementById("searchdiseasebtn")
    .addEventListener("click", toggleDiseaseDiv);
