<?php
// Use to create Bootstrap v3.3.7 object

class Bootstrap3
{
    public static function createAlert($message, $destination="") :void {        
        $script = "
        <script>
            alert('".$message."');
            window.location.href='".$destination."';
        </script>";
        echo $script;
    }

    public static function createEventPanel($header, $content, $type): void {
        echo "
        <div class='col-sm-4'>
            <div class='panel panel-".$type."'>
                <div class='panel-heading'>".$header."</div>
                <div class='panel-body'>".$content."</div>
            </div>
        </div>";
    }

    public static function createDropdown($name, $select_message, $size, $choices=array()): void {
        $dropdown = "
        <div class='".$size."'>
            <label for='exampleFormControlSelect1'>".$select_message."</label>
            <select onfocus='this.size=8;' onblur='this.size=1' onchange='this.size=1; this.blur();' name='".$name."' id='".$name."' class='form-control' style='margin-bottom:15px;' '>
                <option selected>Select your option</option>";
                foreach($choices as $choice) {
                    $dropdown = $dropdown."<option value='".$choice."'>".$choice."</option>";
                }
            $dropdown = $dropdown.
            "</select>
        </div>";
        echo $dropdown;
    }

    public static function createEntry($name, $size, $desc, $hint=""): void {
        echo "
        <div class='".$size."'>
            <label for='exampleFormControlSelect1'>".$desc."</label>
            <div class='form-group'>
                <input class='form-control' name='".$name."' id='".$name."' type='text' placeholder='".$hint."'>
            </div>
        </div>";
    }

    public static function createToggleList($header, $body, $type, $i) :void {
        echo
        "<div class='panel panel-warning'>
            <div class='panel-heading'> 
                <h4 class='panel-title'>
                    <a class='toggleA' data-toggle='collapse' data-parent='#accordion' href='#collapse".$i."' 
                    style='text-decoration: none;'>".$header."</a>
                </h4>
            </div>
            <div id='collapse".$i."' class='panel-collapse collapse'>
                <div class='panel-body'>
                    $body
                </div>
            </div>
        </div>";
    }

    public static function createSearchTable(array $events, $user){
        $counter = 0;
        echo "<table class='result-table'>
            <tr>
                <th class='col-title'>Event name</th>
                <th class='col-title'>Date (MM/DD/YYYY)</th>
                <th class='col-title'>Time</th>
                <th class='col-title'>Group machine</th>
                <th class='col-title'>Cluster name</th>
                <th class='col-title'></th>
            </tr>";
        foreach ($events as $e) {
            $editible = $user->isEditible($e->getName()) ? "" : "disabled";
            echo "
                <tr class='table-row' id='view".$counter."'>
                    <td class='td-content' style='width: 250px'>" . $e->getName() . "</td>
                    <td class='td-content' style='width: 150px'>" . $e->getTime("m/d/Y") . "</td>
                    <td class='td-content' style='width: 100px'>" . $e->getTime("h:ia") . "</td>
                    <td class='td-content' style='width: 520px'>" . $e->getLocation() . "</td>
                    <td class='td-content' style='width: fit-content'>" . $e->getCluster() . "</td>
                    <td class='td-content' style='text-align:center'>
                    <button class='btn btn-lg' data-toggle='modal' id='".$counter."'
                        data-target='#editEvent' onclick='updateEditModal(this)' ".$editible."><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></button>
                    </td>
                    <td style='display:none'>".$e->getLinkerStr()."</td>
                </tr>";
            $counter++;
        }
        echo "</table>";
    }
}
?>