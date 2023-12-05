
/* DateTime picker */
$(function () {
    $('#event_time').datetimepicker({
        icons: {
            time: "fa fa-clock-o"
        },
        minDate: new Date()
    });
});

/* Preview event name */
var term = "";
var course = "Course";

function updatePreviewName() {
    document.getElementById("previewName").innerHTML = `${course}-${term}`;
    if (term == "") {
        document.getElementById("previewName").innerHTML = `${course}:`;
    } else {
        document.getElementById("previewName").innerHTML = `${course}-${term}`;
    }
}

/* Select multiple location */
let selectedLocation = new Set();
function undoAddLocation() {
    selectedLocation.delete(this.parentElement.childNodes[0].textContent);
    this.parentElement.remove();
}

function addLocation() {
    let locationPreview = document.getElementById("lstLocation");
    let location = document.getElementById("selectLocation").value;
    if (location == "Select your option") return;
    if (selectedLocation.has(location)) return;

    var newLoc = document.createElement("LI");
    newLoc.className = "itemLocation list-group-item";

    var textnode = document.createTextNode(location);
    newLoc.appendChild(textnode);

    var btnClose = document.createElement("SPAN");
    btnClose.className = "close";
    btnClose.innerHTML = 'x';
    btnClose.addEventListener("click", undoAddLocation);
    newLoc.appendChild(btnClose);

    locationPreview.insertBefore(newLoc, locationPreview.childNodes[0]);
    selectedLocation.add(location);
    document.getElementById("selectLocation").value = "Select your option";
}

/* Preview Event */
function updatePreviewModal(){
    let eventName = document.getElementById("modalEventName");
    let eventCluster = document.getElementById("modalEventCluster");
    let eventLocation = document.getElementById("modalEventLocation");
    let eventTime = document.getElementById("modalEventTime");
    let eventDuration = document.getElementById("modalEventDuration");

    let eName = document.getElementById("selectCourse").value;
    tyName = document.getElementById("txtTerm").value;
    rfName = document.getElementById("msg").value;
    cluster = document.getElementById("selectCluster").value;
    locations = Array.from(selectedLocation).join(", ");
    dateTime = document.getElementById("eventTime").value;
    duration = document.getElementById("endOffset").value / 60;

    if(tyName){
        eName = eName + "-" + tyName;
    }
    if (rfName) {
        eName = eName + "-" + rfName;
    }
    eventName.innerHTML = "Event name: " + eName;
    eventCluster.innerHTML = "Event Method: " + cluster;
    eventLocation.innerHTML = "Location held: " + locations;
    eventTime.innerHTML = "Date and Time: " + dateTime;
    eventDuration.innerHTML = "Duration: " + duration + " hour(s)";
    document.getElementsByName("txtEventLocation")[0].value = locations;
}

function getStartTimeOffset(){
    return document.getElementById("timeOffsetStart");
}

$(document).ready(function () {
    $("#txtTerm").keyup(function () {
        term = document.getElementById("txtTerm").value;
        updatePreviewName();
    });
    $("#selectCourse").change(function () {
        course = document.getElementById("selectCourse").value;
        updatePreviewName();
    });
    $("#selectLocation").change(function () {
        addLocation();
    });
})