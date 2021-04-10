<?php
namespace Phppot;

class Member
{
    const HOST = 'localhost';

    const USERNAME = 'root';

    const PASSWORD = 'root';

    const DATABASENAME = 'user_registration';

    private $ds;

    private $query;

    public function isEmailExists()
    {
        ini_set('display_errors', 1); error_reporting(E_ALL);
        var_dump(function_exists('mysqli_connect'));

        mysqli_connect('localhost', 'root', 'root', 'user_registration', '8889') or die('Could not connect to mysql: <hr>'.mysql_error());
        $connect = mysqli_connect('localhost', 'root', 'root', 'user_registration', '8889');
        $email1 = mysqli_real_escape_string($connect, $_POST['email']);

        $result3 = mysqli_query($connect, "SELECT email AS email FROM tbl_member WHERE email = '".$email1."'");
        $emailrow = mysqli_fetch_array($result3);

        if(mysqli_num_rows($result3) > 0 )
        {
            return true;
        }

        return false;
    }

    /**
     * to signup / register a user
     *
     * @return string[] registration status message
     */
    public function registerMember()
    {
        global $query;
        global $conn;

        $isEmailExists = $this->isEmailExists();
        if ($isEmailExists)
        {
            $response = array(
                "status" => "error",
                "message" => "Email already exists."
            );
        }
        else
        {
            //$query = "INSERT INTO tbl_member(firstname, lastname, role, email, password)
              //        VALUES ($_POST[firstname], $_POST[firstname], $_POST[firstname], $_POST[firstname], $_POST[firstname])";
            /*$paramType = 'sss';
            $paramValue = array(
                $_POST["firstname"],
                $_POST["lastname"],
                $_POST["role"],
                $hashedPassword,
                $_POST["email"]
            );
            $memberId = $this->ds->insert($query, $paramType, $paramValue);
            if (! empty($memberId)) {
                $response = array(
                    "status" => "success",
                    "message" => "You have registered successfully."
                );



            $conn = new \mysqli('localhost', 'root', 'root', 'user_registration');
            if ($conn->query($query) === TRUE) {
              echo "New record created successfully";
            } else {
              echo "Error: ";
            }*/

            ini_set('display_errors', 1); error_reporting(E_ALL);

            // 1. Create connection to database

            mysqli_connect('localhost', 'root', 'root', 'user_registration') or die('Could not connect to mysql: <hr>'.mysql_error());
            $connect2 = mysqli_connect('localhost', 'root', 'root', 'user_registration');
            // 2. Select database

            //mysqli_select_db("user_registration") or die('Could not connect to database:<hr>'.mysql_error());


            // 3. Assign variables (after connection as required by escape string)

            $firstname = mysqli_real_escape_string($connect2, $_POST['firstname']);
            $lastname = mysqli_real_escape_string($connect2, $_POST['lastname']);
            $role = mysqli_real_escape_string($connect2, $_POST['role']);
            $email = mysqli_real_escape_string($connect2, $_POST['email']);
            $hashedPassword = password_hash($_POST['signup-password'], PASSWORD_DEFAULT);
            $password = mysqli_real_escape_string($connect2, $hashedPassword);

            // 4. Insert data into table

            mysqli_query($connect2, "INSERT INTO tbl_member (firstname, lastname, role, email, password) VALUES ('$firstname', '$lastname', '$role', '$email', '$password')");

            $response = array(
                "status" => "success",
                "message" => "Your information has been successfully added to the database."
            );

            print_r($_POST);

            //mysql_close();
        }
        return $response;
    }

    public function getMember($email)
    {
        $mysqli = new \mysqli('localhost', 'root', 'root', 'user_registration');
        $result = $mysqli->query("SELECT password FROM tbl_member WHERE email = $email");
        echo $result;
        return $result;

        /*$query = 'SELECT email FROM tbl_member where email = ?';
        $paramType = 's';
        $paramValue = array(
            $email
        );
        $memberRecord = $this->ds->select($result, $paramType, $paramValue);
        return $memberRecord;*/
    }

    /**
     * to login a user
     *
     * @return string
     */
    public function loginMember()
    {
        $email = $_POST["login-email"];
        $password = $_POST["login-password"];

        $startconn = new \mysqli('localhost', 'root', 'root', 'user_registration');


        $result1 = mysqli_query($startconn, "SELECT email AS email FROM tbl_member WHERE email = '".$email."'");
        $emailrow = mysqli_fetch_array($result1);

        if(mysqli_num_rows($result1) > 0 )
        {
            $result2 = mysqli_query($startconn, "SELECT password AS password FROM tbl_member WHERE email = '".$email."'");
            $row = mysqli_fetch_array($result2);

            if (password_verify($password, $row['password']))
            {
                $loginPassword = 1;
            }
            else
            {
                $loginPassword = 0;
            }
        }
        else
        {
            $loginPassword = 0;
        }

        if ($loginPassword == 1) {
            // login sucess so store the member's username in
            // the session
            session_start();
            $_SESSION["email"] = $emailrow["email"];
            session_write_close();
            $url = "./home.php";
            header("Location: $url");
        } else if ($loginPassword == 0) {
            $loginStatus = "Invalid username or password.";
            return $loginStatus;
        }
    }

    public function doesApptExist()
    {
      //create connection
      mysqli_connect('localhost', 'root', 'root', 'user_registration', '8889') or die('Could not connect to mysql: <hr>');
      $connect = mysqli_connect('localhost', 'root', 'root', 'user_registration');

      //store the date chosen in date_for variable
      $date_for = mysqli_real_escape_string($connect, $_POST['datepicker-name']);

      //run a query to find rows in database with a date_for value that matches the date chosen
      $result1 = mysqli_query($connect, "SELECT * FROM appts WHERE date_for = '".$date_for."'");
      $allapptsondate = mysqli_fetch_all($result1, MYSQLI_ASSOC);

      //if there is more than zero rows of appointments that match the date chosen
      if(mysqli_num_rows($result1) > 0 )
      {
        //if the user has chosen a time
        if(isset($_POST['timechosen']))
        {
          //Store the time they chose in a variable
          $start_of = mysqli_real_escape_string($connect, $_POST['timechosen']);
        }
        else
        {
          //if the user hasn't chosen a time, this is an error, return false and ask to choose a time
          $apptstatus = array(
              "status" => "error",
              "message" => "Please choose a time."
          );
          //the booland_err array is an array of the bool (whether Appt slot is taken or not) and the error, which
          //is an array of the status and the message, which is returned to createmeet.php
          $boolanderr_array = array(
              "bool" => "false",
              "error" => $apptstatus
          );
          echo $boolanderr_array;
          return $boolanderr_array;
        }

        //Now run the query again and store all rows in $all
        mysqli_connect('localhost', 'root', 'root', 'user_registration', '8889') or die('Could not connect to mysql: <hr>');
        $connect = mysqli_connect('localhost', 'root', 'root', 'user_registration');
        $result2 = mysqli_query($connect, "SELECT start_of AS start_of FROM appts WHERE date_for = '".$date_for."'");
        $all = mysqli_fetch_all($result2, MYSQLI_ASSOC);

        if(mysqli_num_rows($result2) > 0)
        {
          foreach($all as $rows):
            //for each row returned by the query:
            if($rows['start_of'] == $start_of)
            {
              //check if the row's starting time is equal to the starting time selected by user
              //if the starting time does exist, return that the appointment time is taken
              $apptstatus = array(
                  "status" => "error",
                  "message" => "Appointment time taken."
              );
              $boolanderr_array = array(
                  "bool" => 'true',
                  "error" => $apptstatus
              );
              return $boolanderr_array;
            }
          endforeach;

          //there are appointments for this day, but not at the time chosen, so the itme is available.
          $apptstatus = array(
              "status" => "success",
              "message" => "Appointment time available."
          );
          $boolanderr_array = array(
              "bool" => "false",
              "error" => "null"
          );
          return $boolanderr_array;
        }
        else
        {
          //if the start_of time wasn't found in the rows with the same day as the chosen day
          //then the appointment time is available, return success.

          $boolanderr_array = array(
              "bool" => "false",
              "error" => "null"
          );
          return $boolanderr_array;
        }
      }
      else
      {
          //if no rows are found for the same date as the chosen date, then the day is available
          $appttaken = 0;
          $apptstatus = array(
              "status" => "success",
              "message" => "Appointment time available."
          );
          $boolanderr_array = array(
              "bool" => "false",
              "error" => "null"
          );
          return $boolanderr_array;
      }

    }

    public function createAppt()
    {
      mysqli_connect('localhost', 'root', 'root', 'user_registration') or die('Could not connect to mysql: <hr>'.mysql_error());
      $connect = mysqli_connect('localhost', 'root', 'root', 'user_registration');

      //store an array returned from doesApptExist, which returns array of [bool, error_array], into $apptExists
      $apptExists = $this->doesApptExist();

      if(!($apptExists["error"] == "null"))
      {
        //if the error index isn't "null", then retrieve the error array and return it
        $mssgarray = $apptExists["error"];
        return $apptExists["error"];
      }

      if (($apptExists["bool"] == "true"))
      {
        //if the Appt doest exist, ie the bool is "true", then return the error that the time is taken.
          $apptstatus = array(
              "status" => "error",
              "message" => "Appointment time taken."
          );
          return $apptstatus;
      }
      else
      {
        //if doesApptExist yields false, proceed. Create a connection:
        mysqli_connect('localhost', 'root', 'root', 'user_registration') or die('Could not connect to mysql: <hr>'.mysql_error());
        $connect2 = mysqli_connect('localhost', 'root', 'root', 'user_registration');

        //Store variables from html input boxes into php variables
        $start_of = mysqli_real_escape_string($connect2, $_POST['timechosen']);
        $end_of = mysqli_real_escape_string($connect2, $_POST['datepicker-name']);
        $date_scheduled = mysqli_real_escape_string($connect2, date("d/m/Y"));
        $date_for = mysqli_real_escape_string($connect2, $_POST['datepicker-name']);
        $client_for = mysqli_real_escape_string($connect2, $_POST['datepicker-name']);

        //Insert data into table in the database
        mysqli_query($connect2, "INSERT INTO appts (start_of, end_of, date_scheduled, date_for, client_for) VALUES ('$start_of', '$end_of', '$date_scheduled', '$date_for', '$client_for')");

        //Set and return success message
        $apptstatus = array(
            "status" => "success",
            "message" => "Your information has been successfully added to the database."
        );

        return $apptstatus;
      }

    }
}
?>
