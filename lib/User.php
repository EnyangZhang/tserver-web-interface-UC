<?php
    require_once(__DIR__.'/dbconnect.php');
    require_once(__DIR__.'/../phpObj/Bootstrap3.php');
    
class User {
    private $user_id;
    private $user_name;
    private $address;
    private $level;
    private $email;
    private $phoneNum;
    private $city;
    private $country;
    private $gender;
    private $dob;
    private $subjects = array();

    public function __construct(string $user_id) {
        $this->user_id = $user_id;
    }

    public function getUserID() :string { return $this->user_id; }
    public function getUserName() :string { return $this->user_name; }

    public function getUserSubjects() {
        return $this->subjects;
    }

    public function getUserLevel() {
        $rank = array("Exam Officer", "Course co-ordinator", "Tutor");
        return $rank[$this->level]; 
    }
    public function getAddress() {
        if (strlen($this->address) != '') return $this->address;
        return 'None';
    }
    public function setAddress($address) :void { $this->address = $address; }
    public function getEmail() :string { return $this->email; }
    public function setEmail($email) :void { $this->email = $email; }
    public function getPhone() : string { return $this->phoneNum; }
    public function setPhone($phoneNum) :void { $this->phoneNum = $phoneNum; }
    public function getCity() : string {
        if (strlen($this->city) != '') return $this->city;
        return 'None';
    }
    public function setCity($city) :void { $this->city = $city; }
    public function getCountry() : string {
        if (strlen($this->country) != '') return $this->country;
        return 'None';
    }
    public function setCountry($country) :void { $this->country = $country; }
    public function getGender() : string {
        if (strlen($this->gender) != '') return $this->gender;
        return 'None';
    }
    public function setGender($gender) :void { $this->gender = $gender; }
    public function getDoB() : string { return $this->dob;
    }
    public function setDoB($dob) :void { $this->dob = $dob; }

    public function login(string $passwd) :bool {
        $conn = ConnectDB();
        $check = false;
        if ($conn->connect_error) {
            return $check;
        }

        $stmt = $conn->prepare("CALL getUserPass(?, @out)");
        $stmt->bind_param('s', $this->user_id);
        $stmt->execute();
        
        if ($stmt->affected_rows == 1) {
            $res = $conn->query("SELECT @out AS encrypted");
            $cred = $res->fetch_assoc();
            if (password_verify($passwd, $cred['encrypted'])) {
                $check = true;
                $this->getUser();
            } else {
                Bootstrap3::createAlert("Login Unsuccessful. Please check your PASSWORD.");     
            }       
        } else {            
            Bootstrap3::createAlert("Username not found. Please check your USERNAME.");
        }
        
        $conn->close();
        return $check;
    }

    public function getUser() : void {
        $exec = QueryDB("CALL getUserInfo('%s')", array($this->user_id));
        $result = $exec->fetch_assoc();

        $this->user_name = $result['full_name'];
        $this->address = $result['address'];
        $this->email = $result['email'];
        $this->gender = $result['gender'];
        $this->phoneNum = $result['phoneNum'];
        $this->city = $result['city'];
        $this->country = $result['country'];
        $this->dob = $result['dateOfBirth'];

        $this->level = $result['access_level'];
        if ($this->level == 1) {
            $this->subjects = explode(',', $result['subject']);
        } else if ($this->level == 0) {
            // Exam officer can add any course-related events
            $exec = QueryDB("CALL getCourses()", array());
            while ($result = mysqli_fetch_assoc($exec)) {
                array_push($this->subjects, $result['course_name']);
            }
        }
    }

    public function updateUserCredentials(): void {
        QueryDB("CALL updateUserCredentials('%s', '%s', '%s', '%s','%s',
            '%s', '%s', '%s')", array($this->address, $this->email, $this->phoneNum,
            $this->gender, $this->dob, $this->city, $this->country, $this->user_id));
    }

    public function isEditible(string $event_name){
        if ($this->level == 0) {
            return true;
        } else if ($this->level == 1) {
            foreach($this->subjects as $subject){
                return strpos($event_name, $subject) !== false;
            }
        } else {
            return false;
        }
    }
}