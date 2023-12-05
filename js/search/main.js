$(function() {
    $("#search-machine").autocomplete( {
        source: function(data, callback) {
            $.ajax({
                url: 'ajax_search.php',
                method: 'GET',
                dataType: 'json',
                data: {
                    name: data.term
                }, success: function(res) {
                    //if (document.getElementById('search-machine').value.length >= 3) $("#search-machine").val(res[0]);
                    callback(res);
                }
            })
        }
    })

    'use strict';
    /*==================================================================
        [ Daterangepicker ]*/
    try {

        $('#input-start').daterangepicker({
            ranges: true,
            autoApply: true,
            applyButtonClasses: false,
            autoUpdateInput: false
        },function (start, end) {
            $('#input-start').val(start.format('MM/DD/YYYY'));
            $('#input-end').val(end.format('MM/DD/YYYY'));
        });

    } catch(er) {console.log(er);}
});

/* DateTime picker */
$(function () {
    $('#event_time').datetimepicker({
        icons: {
            time: "fa fa-clock-o"
        },
        minDate: new Date()
    });
});

// Edit modal
function updateEditModal(obj){
    let rowID = obj.id;
    let record = document.getElementById('view'+rowID).childNodes;
    var locations = Array.from(record[7].innerHTML.split(', '));
    document.getElementsByName("txtEventLoc")[0].value = record[7].innerHTML;
    document.getElementsByName("txtNewEventLoc")[0].value = record[7].innerHTML;
    document.getElementsByName("txtOldCluster")[0].value = record[9].innerHTML;

    document.getElementById('txtName').value = record[1].innerHTML;
    document.getElementById('eventTime').value = record[3].innerHTML + " " + record[5].innerHTML;
    document.getElementById('dropCluster').value = record[9].innerHTML.trim();
    console.log(record[9].innerHTML);
    document.getElementsByName('txtLinker')[0].value = record[13].innerHTML;

    selectedLocation.clear();
    document.getElementById("lstLocation").innerHTML = '';
    locations.forEach(loc => {
        addLocation(loc);
    });
}

// Event locations
let selectedLocation = new Set();
function undoAddLocation() {
    selectedLocation.delete(this.parentElement.childNodes[0].textContent);
    this.parentElement.remove();
    document.getElementsByName("txtNewEventLoc")[0].value = Array.from(selectedLocation).join(', ');
}

function addLocation(location) {
    let locationPreview = document.getElementById("lstLocation");
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
    document.getElementById("dropLoc").value = "Select your option";
}

$(document).ready(function () {
    $("#dropLoc").change(function () {
        addLocation(this.value);
        document.getElementsByName("txtNewEventLoc")[0].value = Array.from(selectedLocation).join(', ');
    });
})