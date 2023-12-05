
<form method="POST">
    <div id="editEvent" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Event details</h4>
                </div>
                <div class="modal-footer" style='text-align:left'>
                    <?php
                        Bootstrap3::createEntry("txtName", "col-md-12", "Event name","");
                    ?>
                    <div class='col-sm-12' style="margin-bottom:15px">
                        <label for="exampleFormControlSelect1">Select datetime</label>
                        <div class="form-group">
                            <div class='input-group date' id='event_time'>
                                <input type='text' id='eventTime' name='eventTime' class="form-control"/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php
                    $choices = System::getAllCluster();
                    Bootstrap3::createDropdown("dropCluster", "Event method (Cluster)", "col-md-12", $choices);
                    $choices = System::getAllGroup();
                    Bootstrap3::createDropdown("dropLoc", "Select machine group", "col-md-12", $choices);
                    ?>
                    <div class="col-md-12">
                        <ul id='lstLocation' class='list-group'></ul>
                    </div>
                </div>

                <div class="modal-footer">
                    <input style="display:none" type="text" value="" name="txtOldCluster">
                    <input style="display:none" type="text" value="" name="txtEventLoc">
                    <input style="display:none" type="text" value="" name="txtNewEventLoc">
                    <input style="display:none" type="text" value="" name='txtLinker'/>
                    <input type="submit" class="btn btn-primary" value="Save Changes"/>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>                    
            
        </div>
    </div>
</form>