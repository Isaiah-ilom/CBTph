<?php
function logged_in()
{
    return (isset($_SESSION['reg_no']) && isset($_SESSION["name"]) && isset($_SESSION["userid"])) ? true : false;
}

function get_nth($string, $index)
{
    return substr($string, $index - 1, 1);
}

function lecturer_logged_in()
{
    return (isset($_SESSION['staff_id'])) ? true : false;
}

// SQLite Database Connection
$db_path = __DIR__ . '/../database.sqlite';

try {
    $pdo = new PDO("sqlite:$db_path");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create mysqli-compatible wrapper for backward compatibility
class SQLiteWrapper {
    private $pdo;
    public $error = '';
    public $insert_id = 0;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function query($sql) {
        try {
            $trimmedSql = trim($sql);
            $isWrite = stripos($trimmedSql, 'INSERT') === 0 || 
                       stripos($trimmedSql, 'UPDATE') === 0 || 
                       stripos($trimmedSql, 'DELETE') === 0;
            
            if ($isWrite) {
                $stmt = $this->pdo->prepare($sql);
                $result = $stmt->execute();
                if (stripos($trimmedSql, 'INSERT') === 0) {
                    $this->insert_id = $this->pdo->lastInsertId();
                }
                return $result ? new SQLiteResult(null) : false;
            } else {
                $result = $this->pdo->query($sql);
                return new SQLiteResult($result);
            }
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }
    
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }
    
    public function real_escape_string($string) {
        return addslashes($string);
    }
    
    public function close() {
        $this->pdo = null;
    }
}

class SQLiteResult {
    private $result;
    public $num_rows = 0;
    private $rows = [];
    private $index = 0;
    
    public function __construct($result) {
        $this->result = $result;
        if ($result) {
            $this->rows = $result->fetchAll(PDO::FETCH_ASSOC);
            $this->num_rows = count($this->rows);
            $this->index = 0;
        }
    }
    
    public function fetch_object() {
        if (isset($this->rows[$this->index])) {
            $obj = (object) $this->rows[$this->index];
            $this->index++;
            return $obj;
        }
        return null;
    }
    
    public function fetch_array() {
        if (isset($this->rows[$this->index])) {
            $row = $this->rows[$this->index];
            $this->index++;
            return $row;
        }
        return null;
    }
    
    public function fetch_assoc() {
        return $this->fetch_array();
    }
}

$mysqli = new SQLiteWrapper($pdo);
$connection = $mysqli;

$date_now = date("Y-m-d");

// Compatibility functions for mysqli - override only if not using real mysqli
if (!function_exists('mysqli_query_sqlite')) {
    function mysqli_query_sqlite($conn, $query) {
        return $conn->query($query);
    }
    
    function mysqli_fetch_array_sqlite($result) {
        if ($result && method_exists($result, 'fetch_array')) {
            return $result->fetch_array();
        }
        return null;
    }
    
    function mysqli_fetch_assoc_sqlite($result) {
        if ($result && method_exists($result, 'fetch_assoc')) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    function mysqli_error_sqlite($conn) {
        return $conn->error;
    }
}

class csv extends PDO
{
    public function import($file)
    {
        $file = fopen($file, 'r');
        var_dump($file);
    }
}

function output_errors($errors)
{
    return '<div class="alert alert-danger no_border_radius"><ul class="ul" style="list-style:none; padding: 5px; margin-top:7px"><li>' . implode('</li><li>', $errors) . '</li></ul></div>';
}

function is_admin()
{
    return (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin') ? true : false;
}

function getNumRow($table, $field, $value)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE $field = ?");
    $stmt->execute([$value]);
    return $stmt->rowCount() > 0 ? count($stmt->fetchAll()) : 0;
}

function getTotalNumRow($table)
{
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
    return $stmt->fetchColumn();
}

function nonRepeat($min, $max, $count)
{
    if ($max - $min < $count - 1) {
        return false;
    }
    $nonrepeatarray = array();
    for ($i = 0; $i < $count; $i++) {
        $rand = rand($min, $max);
        while (in_array($rand, $nonrepeatarray)) {
            $rand = rand($min, $max);
        }
        $nonrepeatarray[$i] = $rand;
    }
    return $nonrepeatarray;
}

function array_push_assoc($array, $key, $value)
{
    $array[$key] = $value;
    return $array;
}

function redirect($location)
{
    header("Location: $location");
    exit;
}

function getDefinite($field, $table, $key1, $v1, $key2, $v2, $key3, $v3)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT $field FROM $table WHERE $key1 = ? AND $key2 = ? AND $key3 = ?");
    $stmt->execute([$v1, $v2, $v3]);
    $row = $stmt->fetch();
    return $row ? $row[$field] : null;
}

function getDefiniteExam($field, $table, $key1, $v1, $key2, $v2, $key3, $v3, $key4, $v4)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT $field FROM $table WHERE $key1 = ? AND $key2 = ? AND $key3 = ? AND $key4 = ?");
    $stmt->execute([$v1, $v2, $v3, $v4]);
    $row = $stmt->fetch();
    return $row ? $row[$field] : null;
}

function getDefiniteCExam($field, $table, $key1, $v1, $key2, $v2, $key3, $v3)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT $field FROM $table WHERE $key1 = ? AND $key2 = ? AND $key3 = ?");
    $stmt->execute([$v1, $v2, $v3]);
    $row = $stmt->fetch();
    return $row ? $row[$field] : null;
}

function updateSetting($value, $id)
{
    global $pdo;
    $stmt = $pdo->prepare("UPDATE settings SET value = ? WHERE id = ?");
    return $stmt->execute([$value, $id]);
}

function updatePinLogin($count, $pin)
{
    global $pdo;
    $stmt = $pdo->prepare("UPDATE pin_logins SET usage_count = ? WHERE pin = ?");
    return $stmt->execute([$count, $pin]);
}

function updateInstructions($value, $id)
{
    global $pdo;
    $stmt = $pdo->prepare("UPDATE instructions SET value = ? WHERE id = ?");
    return $stmt->execute([$value, $id]);
}

function getField($field, $table, $id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT $field FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $row[$field] : null;
    } catch (Exception $e) {
        return null;
    }
}

function getDField($field, $table, $key, $v)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT $field FROM $table WHERE $key = ?");
        $stmt->execute([$v]);
        $row = $stmt->fetch();
        return $row ? $row[$field] : null;
    } catch (Exception $e) {
        return null;
    }
}

function MaskCreditCard($cc)
{
    $cc_length = strlen($cc);
    for ($i = 0; $i < $cc_length - 8; $i++) {
        if ($cc[$i] == '-') {
            continue;
        }
        $cc[$i] = 'X';
    }
    return $cc;
}

function FormatCreditCard($cc)
{
    $cc            = str_replace(array('-', ' '), '', $cc);
    $cc_length     = strlen($cc);
    $newCreditCard = substr($cc, -4);
    for ($i = $cc_length - 5; $i >= 0; $i--) {
        if ((($i + 1) - $cc_length) % 4 == 0) {
            $newCreditCard = '-' . $newCreditCard;
        }
        $newCreditCard = $cc[$i] . $newCreditCard;
    }
    return $newCreditCard;
}

function confirmQuery($result)
{
    if (!$result) {
        die("QUERY FAILED");
    }
}

function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function user_exit($table, $field, $input)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM $table WHERE $field = ?");
    $stmt->execute([$input]);
    return $stmt->rowCount() > 0 ? 1 : 0;
}

$secret_code = 'benx-technologies';

function register_user()
{
    global $pdo;
    if (isset($_POST['register'])) {
        $reg_no           = sanitize($_POST['reg_no']);
        $password         = sanitize($_POST['password']);
        $hash             = md5(md5($password));
        $email            = sanitize($_POST['email']);
        $confirm_password = sanitize($_POST['confirm_password']);
        $first_name       = sanitize($_POST['first_name']);
        $last_name        = sanitize($_POST['last_name']);
        $institution      = isset($_POST['institution']) ? sanitize($_POST['institution']) : '';
        $subject_1        = isset($_POST['subject_1']) ? sanitize($_POST['subject_1']) : '';
        $subject_2        = isset($_POST['subject_2']) ? sanitize($_POST['subject_2']) : '';
        $subject_3        = isset($_POST['subject_3']) ? sanitize($_POST['subject_3']) : '';
        $subject_4        = isset($_POST['subject_4']) ? sanitize($_POST['subject_4']) : '';
        $gender           = isset($_POST['gender']) ? sanitize($_POST['gender']) : '';
        
        $required_fields  = array('reg_no', 'password', 'confirm_password');
        $errors = array();
        
        foreach ($_POST as $key => $value) {
            if (empty($value) && in_array($key, $required_fields) === true) {
                $errors[] = 'Fields marked with an asterisk Are Required';
                break;
            }
        }
        
        if (empty($errors) === true) {
            if (user_exit('students', 'reg_no', $reg_no) > 0) {
                $errors[] = 'Sorry, The Reg No \'' . $reg_no . '\' is already Registered.';
            }
            if (strlen($password) < 3) {
                $errors[] = 'Sorry Your Password must be at least 6 characters';
            }
            if ($confirm_password !== $password) {
                $errors[] = 'Your password do not match';
            }
            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $errors[] = 'A valid Email Address is Required';
            }
            if (!empty($email) && user_exit('students', 'email', $email) > 0) {
                $errors[] = 'Sorry, The Email Address \'' . $email . '\' is already in Use.';
            }
        }
        
        if (empty($errors) === true && empty($_POST) === false) {
            $stmt = $pdo->prepare("INSERT INTO students(first_name, last_name, password, email, gender, reg_no, institution, subject_1, subject_2, subject_3, subject_4) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$first_name, $last_name, $hash, $email, $gender, $reg_no, $institution, $subject_1, $subject_2, $subject_3, $subject_4]);
            
            die("<div class='alert alert-success'><p class='text-center'>Congratulations! You Have Successfully Registered<br>
                You will be redirected To Login Page In few seconds</p></div><meta http-equiv='refresh' content='3;url=login.php' />");
        } else {
            echo output_errors($errors);
        }
    }
}

function qusTotalNums($exam_id){
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM questions WHERE course_id = ?");
    $stmt->execute([$exam_id]);
    return count($stmt->fetchAll());
}

function onlyNums($data)
{
    return preg_replace('/\D/', '', $data);
}

function login_user($location)
{
    global $pdo;
    if (isset($_POST['login'])) {
        $matric_no       = $_POST['reg_no'];
        $matric_no       = sanitize($matric_no);
        $pin             = isset($_POST['pin']) ? sanitize($_POST['pin']) : '';
        $pin             = onlyNums($pin);
        $errors = array();
        $required_fields = array('reg_no');
        
        foreach ($_POST as $key => $value) {
            if (empty($value) && in_array($key, $required_fields) === true) {
                $errors[] = 'The Reg No. Field is Required';
                break;
            }
        }
        
        if (empty($errors) === true) {
            if ((user_exit('students', 'reg_no', $matric_no) === 0)) {
                $errors[] = 'The Reg number you entered is incorrect';
            } else {
                if (!empty($pin)) {
                    $seventh   = get_nth($pin, 7);
                    $fifteenth = get_nth($pin, 15);
                    $pin_exist = user_exit('pins', 'pin', $pin);
                    
                    if (strlen($pin) !== 16) {
                        $errors[] = 'Invalid Pin, Make sure you are using a valid scratch card';
                    } elseif ($seventh < 5 || $fifteenth > 7) {
                        $errors[] = "The pin you entered is not generated for this CBT";
                    } elseif ($pin_exist === 0) {
                        $errors[] = "The pin you entered is invalid";
                    } else {
                        $pin_login_exist = user_exit('pin_logins', 'pin', $pin);
                        if ($pin_login_exist > 0) {
                            $last_user_by = getDField('reg_no', 'pin_logins', 'pin', $pin);
                            if ($last_user_by != $matric_no) {
                                $errors[] = "This pin has been used by another student";
                            } else {
                                $usage_count = getDField('usage_count', 'pin_logins', 'pin', $pin);
                                if ($usage_count > 9) {
                                    $errors[] = "You have exceeded the usage limit of this scratch card";
                                }
                            }
                        } else {
                            $last_used = date("Y-m-d");
                            $stmt = $pdo->prepare("INSERT INTO pin_logins(pin,reg_no,usage_count,last_used) VALUES(?,?,?,?)");
                            $stmt->execute([$pin, $matric_no, '1', $last_used]);
                        }
                    }
                }
            }
            
            $stmt = $pdo->prepare("SELECT * FROM students WHERE reg_no = ? LIMIT 1");
            $stmt->execute([$matric_no]);
            $row = $stmt->fetch();
            
            if ($row) {
                $db_first_name  = $row['first_name'];
                $db_last_name   = $row['last_name'];
                $db_matric_no   = $row['reg_no'];
                $db_user_id     = $row['id'];
                $db_profile_pic = $row['profile_pic'];
            }
        }
        
        if (empty($errors) === true && empty($_POST) === false && isset($db_user_id)) {
            $_SESSION['userid']       = $db_user_id;
            $_SESSION['reg_no']       = $db_matric_no;
            $_SESSION['name']         = "$db_first_name $db_last_name";
            $_SESSION['profile_pics'] = $db_profile_pic;
            $_SESSION['pin']          = $pin;
            $_SESSION['institution_name'] = getField('value','settings','4');
            redirect(ROOT_URL . $location);
        } else {
            echo output_errors($errors);
        }
    }
}

function nopin_login_user($location)
{
    global $pdo;
    if (isset($_POST['login'])) {
        $matric_no       = $_POST['reg_no'];
        $matric_no       = sanitize($matric_no);
        $errors = array();
        $required_fields = array('reg_no');
        
        foreach ($_POST as $key => $value) {
            if (empty($value) && in_array($key, $required_fields) === true) {
                $errors[] = 'The Reg No. Field is Required';
                break;
            }
        }
        
        if (empty($errors) === true) {
            if ((user_exit('students', 'reg_no', $matric_no) === 0)) {
                $errors[] = 'The Reg number you entered is incorrect';
            }
            
            $stmt = $pdo->prepare("SELECT * FROM students WHERE reg_no = ? LIMIT 1");
            $stmt->execute([$matric_no]);
            $row = $stmt->fetch();
            
            if ($row) {
                $db_first_name  = $row['first_name'];
                $db_last_name   = $row['last_name'];
                $db_matric_no   = $row['reg_no'];
                $db_user_id     = $row['id'];
                $db_profile_pic = $row['profile_pic'];
            }
        }
        
        if (empty($errors) === true && empty($_POST) === false && isset($db_user_id)) {
            $_SESSION['userid']       = $db_user_id;
            $_SESSION['reg_no']       = $db_matric_no;
            $_SESSION['name']         = "$db_first_name $db_last_name";
            $_SESSION['profile_pics'] = $db_profile_pic;
            $_SESSION['institution_name'] = getField('value','settings','4');
            redirect(ROOT_URL . $location);
        } else {
            echo output_errors($errors);
        }
    }
}

function login_lecturer($location)
{
    global $pdo;
    if (isset($_POST['login'])) {
        $staff_id        = $_POST['staff_id'];
        $password        = $_POST['password'];
        $password        = sanitize($password);
        $staff_id        = sanitize($staff_id);
        $password        = md5(md5($password));
        $errors = array();
        $required_fields = array('password', 'staff_id');
        
        foreach ($_POST as $key => $value) {
            if (empty($value) && in_array($key, $required_fields) === true) {
                $errors[] = 'Both fields Are Required';
                break;
            }
        }
        
        if (empty($errors) === true) {
            if ((user_exit('teachers', 'staff_id', $staff_id) === 0)) {
                $errors[] = 'Sorry, The Staff ID \'' . $staff_id . '\' does not exist';
            }
            
            $stmt = $pdo->prepare("SELECT * FROM teachers WHERE staff_id = ? LIMIT 1");
            $stmt->execute([$staff_id]);
            $row = $stmt->fetch();
            
            if ($row) {
                $db_staff_id  = $row['staff_id'];
                $db_password  = $row['password'];
                $db_user_id   = $row['id'];
                $db_biometric = $row['biometrics'];
                
                if ($password != $db_password) {
                    $errors[] = 'password incorrect';
                }
            }
        }
        
        if (empty($errors) === true && empty($_POST) === false && isset($db_user_id)) {
            if (($password == $db_password) && ($staff_id == $db_staff_id)) {
                $_SESSION['userid']   = $db_user_id;
                $_SESSION['staff_id'] = $db_staff_id;
                $_SESSION['password'] = $db_password;
                redirect(ROOT_URL . $location);
            }
        } else {
            echo output_errors($errors);
        }
    }
}

function login_admin($location)
{
    global $pdo;
    if (isset($_POST['login'])) {
        $ac              = $_POST['access_code'];
        $ac              = sanitize($ac);
        $errors = array();
        $required_fields = array('access_code');
        
        foreach ($_POST as $key => $value) {
            if (empty($value) && in_array($key, $required_fields) === true) {
                $errors[] = 'The Access Code Field is Required';
                break;
            }
        }
        
        if (empty($errors) === true) {
            if ((user_exit('admins', 'access_code', $ac) === 0)) {
                $errors[] = 'Sorry, The Access Code Is Not Admin On This System';
            }
            
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE access_code = ? LIMIT 1");
            $stmt->execute([$ac]);
            $row = $stmt->fetch();
            
            if ($row) {
                $db_name    = $row['name'];
                $db_ac      = $row['access_code'];
                $db_user_id = $row['id'];
                $type       = "admin";
            }
        }
        
        if (empty($errors) === true && empty($_POST) === false && isset($db_user_id)) {
            $_SESSION['userid']      = $db_user_id;
            $_SESSION['access_code'] = $db_ac;
            $_SESSION['name']        = $db_name;
            $_SESSION['user_type']   = $type;
            redirect(ROOT_URL . $location);
        } else {
            echo output_errors($errors);
        }
    }
}

function register()
{
    global $pdo;
    if (isset($_POST['register'])) {
        $reg_no          = sanitize($_POST['reg_no']);
        $phone           = isset($_POST['phone']) ? sanitize($_POST['phone']) : '';
        $email           = isset($_POST['email']) ? sanitize($_POST['email']) : '';
        $first_name      = sanitize($_POST['first_name']);
        $last_name       = sanitize($_POST['last_name']);
        $subjects        = array();
        $subjects[1]     = isset($_POST['subject1']) ? sanitize($_POST['subject1']) : '';
        $subjects[2]     = isset($_POST['subject2']) ? sanitize($_POST['subject2']) : '';
        $subjects[3]     = isset($_POST['subject3']) ? sanitize($_POST['subject3']) : '';
        $subjects[4]     = isset($_POST['subject4']) ? sanitize($_POST['subject4']) : '';
        $errors = array();
        $required_fields = array('name');
        
        foreach ($_POST as $key => $value) {
            if (empty($value) && in_array($key, $required_fields) === true) {
                $errors[] = 'Fields marked with an asterisk Are Required';
                break;
            }
        }
        
        if (empty($errors) === true) {
            if (user_exit('students', 'reg_no', $reg_no) > 0) {
                $errors[] = 'Sorry, The Reg No \'' . $reg_no . '\' is already Registered.';
            }
            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $errors[] = 'A valid Email Address is Required';
            }
            if (!empty($email) && user_exit('students', 'email', $email) > 0) {
                $errors[] = 'Sorry, The Email Address \'' . $email . '\' is already in Use.';
            }
        }
        
        if (empty($errors) === true && empty($_POST) === false) {
            $stmt = $pdo->prepare("INSERT INTO students(reg_no, first_name, last_name, phone, email) VALUES(?,?,?,?,?)");
            $stmt->execute([$reg_no, $first_name, $last_name, $phone, $email]);
            
            unset($_SESSION['userid']);
            unset($_SESSION['reg_no']);
            unset($_SESSION['name']);
            
            $last_id = $pdo->lastInsertId();
            $_SESSION['userid'] = $last_id;
            $_SESSION['reg_no'] = $reg_no;
            $_SESSION['name']   = "$first_name $last_name";
            
            foreach ($subjects as $subject => $value) {
                if ($value != '') {
                    $stmt = $pdo->prepare("INSERT INTO registered_exams (student_id, subject_id) VALUES (?, ?)");
                    $stmt->execute([$last_id, $value]);
                }
            }
            
            die("<div class='alert alert-success'><p class='text-center'>Congratulations! You Have Successfully Registered<br>
                Please Click The Login Button Below To Login And Start Exam <br><br>
                <a class='btn btn-success' href='login.php'>Login </a></p>
                </div>");
        } else {
            echo output_errors($errors);
        }
    }
}
