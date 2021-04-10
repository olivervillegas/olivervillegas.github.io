<?php
use Phppot\Member;
if (! empty($_POST["schedule-btn"])) {
    require_once './Model/confirm.php';
    $member = new Member();
    $ApptAvail = $member->createAppt();
    //$ApptBooking = $member->createAppt();
}
?>

<HTML>
  <HEAD>
  <TITLE>Set up a Meeting</TITLE>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/assets/css/styles.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.js"></script>
  <script>
    $( function()
    {
      $("#datepicker").datepicker({
        type: "POST",
        minDate: 0, maxDate: "+1M +10D",
        altField: "#alternate",
        dateFormat: "YYYY-MM-DD",
        onSelect: function()
        {
          var dateObject = $(this).datepicker('getDate');
        }
        });
    } );
    function scheduleValidation()
    {
      var valid = true;

      $("#timechosen").removeClass("error-field");

      if (dateObject.trim() == "") {
        $("#datechosen-info").html("required.").css("color", "#ee0000").show();
        $("#datepicker").addClass("error-field");
        valid = false;
      }

      return valid;
    }
  </script>
  </HEAD>
  <BODY>
    <div class="phppot-container">
  		<div class="page-header">
  			<span class="login-signup"><a href="logout.php">Logout</a></span>
  		</div>
  		<div class="page-content">Welcome ?php echo $FirstName</div>
  	</div>
    <div class="phppot-container">
      <div class="page-content">
        <form name="schedule" action="createmeet.php" method="post" onsubmit="return scheduleValidation()">
          <div class="row">
            <?php
              if (! empty($ApptAvail["status"]))
               {
                  ?>
                              <?php
                  if ($ApptAvail["status"] == "error")
                  {
                      ?>
                      <div class="server-response error-msg"><?php echo $ApptAvail["message"]; ?></div>
                              <?php
                  }
                  else if ($ApptAvail["status"] == "success")
                  {
                      ?>
                              <div class="server-response success-msg"><?php echo $ApptAvail["message"]; ?></div>
                              <?php
                  }
                  ?>
                  <?php
              }
            ?>
						<div class="inline-block">
              <div class="form-label">
                Date<span class="required error" name="date" id="datechosen-info"></span>
                <div>
                  <input class="input-box-330" type="text" name="datepicker-name" value="" id="datepicker">
                </div>
                Appointment Type<span class="required error" name="type" id="typechosen"></span>
                <div>
                  <select name = appt_type id = "appttype">
                    <option>Regular Trim/Haircut</option>
                    <option>Coloring/Dye/Bleach</option>
                    <option>Manicure/Pedicure</option>
                    <option>Eyebrow/Eyelash/Facial Hair</option>
                    <option>Makeup/Nails</option>
                  </select>
                </div>
              </div>
						</div>
          </div>
          <div class="row">
						<div class="inline-block">
              <div class="form-label">
                Select Time<span class="required error" name="time" id="time-info"></span>
              </div>
              <div id="timechosen">
                <select name = "timechosen" type="text" id = "timechosen">
                  <option value="09:00:00">9:00 AM</option>
                  <option value="09:30:00">9:30 AM</option>
                  <option value="10:00:00">10:00 AM</option>
                  <option value="10:30:00">10:30 AM</option>
                  <option value="11:00:00">11:00 AM</option>
                  <option value="11:30:00">11:30 AM</option>
                  <option value="12:00:00">12:00 PM</option>
                  <option value="12:30:00">12:30 PM</option>
                  <option value="13:00:00">1:00 PM</option>
                  <option value="13:30:00">1:30 PM</option>
                  <option value="14:00:00">2:00 PM</option>
                  <option value="14:30:00">2:30 PM</option>
                  <option value="15:00:00">3:00 PM</option>
                  <option value="15:30:00">3:30 PM</option>
                  <option value="16:00:00">4:00 PM</option>
                  <option value="16:30:00">4:30 PM</option>
                  <option value="17:00:00">5:00 PM</option>
                  <option value="17:30:00">5:30 PM</option>
                  <option value="18:30:00">6:00 PM</option>
                </select>
              </div>


              <div class="error-msg" id="error-msg"></div>
              <div class="row">
    						<input class="btn" type="submit" name="schedule-btn"
    							id="schedule-btn" value="schedule">
    					</div>
						</div>
          </div>
        </form>
  		</div>
  	</div>
    <script>
      function makeInvisible()
      {
        document.getElementById().style.visibility = "hidden";
      }
      function makeVisible()
      {
        document.getElementById().style.visibility = "visible";
      }
    </script>
    <script>
    $('#datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: "-100:+0",
        dateFormat: 'dd/mm/yy'

    });
    </script>
    <script>
    function scheduleValidation()
    {
      var valid = true;

      $("#timechosen").removeClass("error-field");



      return valid;
    }
    </script>
  </BODY>
</HTML>
