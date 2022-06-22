<?php
require_once('User.php');

class DatabaseConn
{
  /** @var \DatabaseConn */
  private static $dbconn;

  /** @var \myslqi */
  private $conn;

  private function __construct($servername, $username, $password, $database)
  {
    try {
      $this->conn = new mysqli($servername, $username, $password, $database);
      mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

      /* check connection */
      if ($this->conn->connect_errno || !$this->conn->ping()) {
        $this->conn = null;
      }
    } catch (Exception $e) {
      $this->conn = null;
    }
  }

  public static function get_conn(): ?DatabaseConn
  {
    try {
      if (DatabaseConn::$dbconn == null) {
        $dbconfig = parse_ini_file('.env');
        $servername = $dbconfig['DB_HOST'];
        $username = $dbconfig['DB_USERNAME'];
        $password = $dbconfig['DB_PASSWORD'];
        $database = $dbconfig['DB_DATABASE'];
        DatabaseConn::$dbconn = new DatabaseConn($servername, $username, $password, $database);
      }
      if (DatabaseConn::$dbconn && DatabaseConn::$dbconn->conn) {
        return DatabaseConn::$dbconn;
      }
      return null;
    } catch (Exception $e) {
      return null;
    }
  }

  public function auth(string $username, string $password): ?User
  {
    if (!($this->conn instanceof mysqli)) return null;
    if ($this->validate($username, $password)) {
      try {
        $q = 'SELECT password, type, name FROM users WHERE username=?';
        $stmt = $this->conn->prepare($q);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        if ($rowcount == 1) {
          $stmt->bind_result($pw_hash, $type, $name);
          $stmt->fetch();
          if (password_verify($password, $pw_hash)) {
            $stmt->close();
            $details = [];
            $details['username'] = $username;
            $details['type'] = $type;
            $details['name'] = $name;
            if ($type === 'customer') {
              $q1 = 'SELECT type FROM customer WHERE username=?';
              $stmt1 = $this->conn->prepare($q1);
              $stmt1->bind_param('s', $username);
              $stmt1->execute();
              $stmt1->store_result();
              $rowcount = $stmt1->num_rows();
              if ($rowcount == 1) {
                $stmt1->bind_result($customer_type);
                $stmt1->fetch();
                $details['customer_type'] = $customer_type;
                if ($customer_type === 'individual') {
                  $q2 = 'SELECT NIC, DoB FROM individual WHERE username=?';
                  $stmt2 = $this->conn->prepare($q2);
                  $stmt2->bind_param('s', $username);
                  $stmt2->execute();
                  $stmt2->store_result();
                  $rowcount = $stmt2->num_rows();
                  if ($rowcount == 1) {
                    $stmt2->bind_result($NIC, $dob);
                    $stmt2->fetch();
                    $details['NIC'] = $NIC;
                    $details['DoB'] = $dob;
                  }
                  $stmt2->close();
                } else if ($customer_type === 'organization') {
                  $q2 = 'SELECT owner_NIC FROM organization WHERE username=?';
                  $stmt2 = $this->conn->prepare($q2);
                  $stmt2->bind_param('s', $username);
                  $stmt2->execute();
                  $stmt2->store_result();
                  $rowcount = $stmt2->num_rows();
                  if ($rowcount == 1) {
                    $stmt2->bind_result($ownerNIC);
                    $stmt2->fetch();
                    $details['ownerNIC'] = $ownerNIC;
                  }
                  $stmt2->close();
                }
              }
              $stmt1->close();
            }
            return User::createUser($details);
          }
        }
        $stmt->close();
      } catch (Exception $e) {
        return null;
      }
    }
    return null;
  }

  public function createUser(User $user, string $password, string $creator): bool
  {
    if (!($this->conn instanceof mysqli)) return false;
    $name = $user->getName();
    $username = $user->getUsername();
    $type = $user->getType();
    if ($this->validate($username, $password)) {
      ($this->conn)->begin_transaction();
      try {
        $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $q = 'INSERT INTO users (username, password, type, name, created_by) VALUES (?, ?, ?, ?, ?);';
        $stmt = $this->conn->prepare($q);
        $stmt->bind_param('sssss', $username, $hashed, $type, $name, $creator);
        $status = $stmt->execute();
        $stmt->close();
        if ($status && $user instanceof Customer) {
          $customer_type = $user->getCustomerType();
          $q1 = 'INSERT INTO customer (username, type) VALUES (?, ?);';
          $stmt1 = $this->conn->prepare($q1);
          $stmt1->bind_param('ss', $username, $customer_type);
          $status &= $stmt1->execute();
          $stmt1->close();
          if ($user instanceof Organization) {
            $q2 = 'INSERT INTO organization (username, owner_NIC) VALUES (?, ?);';
            $stmt2 = $this->conn->prepare($q2);
            $ownerNIC = $user->getOwnerNIC();
            $stmt2->bind_param('ss', $username, $ownerNIC);
            $status &= $stmt2->execute();
            $stmt2->close();
          } else if ($user instanceof Individual) {
            $q2 = 'INSERT INTO individual (username, NIC, DoB) VALUES (?, ?, ?);';
            $stmt2 = $this->conn->prepare($q2);
            $NIC = $user->getNIC();
            $dobStr = $user->getDoB()->format('D M d Y \G\M\TO');
            $stmt2->bind_param('sss', $username, $NIC, $dobStr);
            $status &= $stmt2->execute();
            $stmt2->close();
          }
        }
        if ($status) ($this->conn)->commit();
        else ($this->conn)->rollback();
        return $status;
      } catch (Exception $e) {
        ($this->conn)->rollback();
        return false;
      }
    }
    return false;
  }

  public function createBranch(string $branch_id, string $branch_name, string $location, string $manager, string $creator): bool
  {
    if (!($this->conn instanceof mysqli)) return false;
    ($this->conn)->begin_transaction();
    try {
      $q1 = 'SELECT COUNT(username) FROM users WHERE username=? AND type="manager"';
      $stmt1 = $this->conn->prepare($q1);
      $stmt1->bind_param('s', $manager);
      $stmt1->execute();
      $stmt1->store_result();
      if ($stmt1->num_rows() !== 1) {
        ($this->conn)->rollback();
        return false;
      }
      $stmt1->bind_result($manager_cnt);
      $stmt1->fetch();
      if ($manager_cnt !== 1) {
        ($this->conn)->rollback();
        return false;
      }
      $q = 'INSERT INTO branch (id, name, location, manager_id, created_by) VALUES (?, ?, ?, ?, ?);';
      $stmt = $this->conn->prepare($q);
      $stmt->bind_param('sssss', $branch_id, $branch_name, $location, $manager, $creator);
      $status = $stmt->execute();
      $stmt->close();
      ($this->conn)->commit();
      return $status;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return false;
    }
    return false;
  }

  public function apply_loan(string $fix_acc, float $amount, float $duration, string $owner_id)
  {
    if (!($this->conn instanceof mysqli)) return false;
    if ($fix_acc && $amount && $owner_id) {
      ($this->conn)->begin_transaction();
      $response = ['result' => false, 'reason' => ''];
      try {
        $q0 = 'SELECT balance FROM accounts WHERE owner_id = ? and acc_no = ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('ss', $owner_id, $fix_acc);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows() == 0) {
          $response['reason'] = 'No account associate with the entered number!';
          return $response;
        }
        $stmt->bind_result($balance);
        $stmt->fetch();
        $stmt->close();
        if ($balance * 0.6 < $amount || $amount > 500000) {
          $response['reason'] = 'Fixed deposit is not enough or amount > 500000';
          return $response;
        }

        $q1 = 'SELECT savings_acc_no FROM fixed_deposits WHERE acc_no = ?';
        $stmt = $this->conn->prepare($q1);
        $stmt->bind_param('s', $fix_acc);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows() == 0) {
          $response['reason'] = 'No fixed account associate with the entered number!';
          return $response;
        }
        $stmt->bind_result($savings_acc_no);
        $stmt->fetch();
        $stmt->close();

        $date = date("Y-m-d");
        $paid = 0;
        $installment = (($amount + ($amount * 0.2 / 12) * 24) / $duration); //20% for year
        $q2 = 'INSERT INTO loans ( total_amount,paid_amount, date, customer,savingsAccount,duration,installment) VALUES (?, ?, ?, ? , ? , ? , ?);';
        $stmt = $this->conn->prepare($q2);
        $stmt->bind_param('ddsssdd', $amount, $paid, $date, $owner_id, $savings_acc_no, $duration, $installment);
        $status0 = $stmt->execute();
        $stmt->close();

        if ($status0) { //update saving account balance
          $q3 = 'UPDATE accounts SET balance = balance + ? WHERE acc_no = ?;';
          $stmt = $this->conn->prepare($q3);
          $stmt->bind_param('ds', $amount, $savings_acc_no);
          $status1 = $stmt->execute();
        }
        ($this->conn)->commit();
        $response['reason'] = 'Loan added successfully!';
        $response['result'] = true;

        return $response;
      } catch (Exception $e) {
        ($this->conn)->rollback();
        $response['reason'] = 'Error!';
        return $response;
      }
    }
    $response['reason'] = 'Error!';
    return $response;
  }

  public function enter_Installment(string $loan_id, float $amount)
  {
    if (!($this->conn instanceof mysqli)) return false;
    if ($loan_id && $amount) {
      ($this->conn)->begin_transaction();
      $response = ['result' => false, 'reason' => ''];
      try {
        $q0 = 'SELECT total_amount,paid_amount FROM loans WHERE loanID = ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('s', $loan_id);
        $stmt->execute();
        $stmt->store_result();
      
        if ($stmt->num_rows() == 0) {
          $response['reason'] = 'No loan associate with the entered ID';
          return $response;
        }
        $stmt->bind_result($total_amount, $paid_amount);
        $stmt->fetch();
        $stmt->close();
        if ($total_amount< $paid_amount+ $amount) {
          $response['reason'] = 'Exceed the total amount';
          return $response;
        }

        $q3 = 'UPDATE loans SET paid_amount = paid_amount + ? WHERE loanID = ?;';
        $stmt = $this->conn->prepare($q3);
        $stmt->bind_param('ds', $amount, $loan_id);
        $response['result'] = $stmt->execute();
        $response['reason'] = 'Installment entered correctly.';

        ($this->conn)->commit();
        return $response;
      } catch (Exception $e) {
        ($this->conn)->rollback();
        return false;
      }
    }
    return false;
  }

  public function getLateLoans($username): ?array
  {
    if (!($this->conn instanceof mysqli)) return null;
    try {
      $q = 'SELECT id FROM branch WHERE manager_id=?';
      $stmt = $this->conn->prepare($q);
      $stmt->bind_param('s', $username);
      $stmt->execute();
      $stmt->store_result();
      $rowcount = $stmt->num_rows();
      if ($rowcount != 1) return null;
      $stmt->bind_result($branch_id);
      $stmt->fetch();
      $stmt->close();
      return null;
    } catch (Exception $e) {
      return null;
    }
    return null;
  }

  public function check_balance(string $owner_id, string $acc_no)
  {
    if (!($this->conn instanceof mysqli)) return -1;
    ($this->conn)->begin_transaction();
    try {
      if (!$owner_id || !$acc_no) {
        return -1;
      } else {
        $q0 = 'SELECT balance FROM Accounts WHERE owner_id = ? and acc_no = ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('ss', $owner_id, $acc_no);
      }
      $stmt->execute();
      $stmt->store_result();
      if ($stmt->num_rows() == 0) {
        return -1;
      }
      $stmt->bind_result($balance);
      $stmt->fetch();
      $stmt->close();
      ($this->conn)->commit();
      return $balance;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return -1;
    }
  }

  public function transaction(string $from_acc, string $to_acc, string $init_id, float $amount)
  {
    if (!($this->conn instanceof mysqli)) return false;

    ($this->conn)->begin_transaction();
    ($this->conn)->autocommit(false);
    try {
      if ($from_acc != null) {
        $q1 = 'UPDATE accounts SET balance = balance - ? WHERE acc_no = ?';
        $stmt1 = $this->conn->prepare($q1);
        $stmt1->bind_param('ds', $amount, $from_acc);
        if (!($stmt1->execute())) {
          $this->conn->rollback();
          return false;
        }
      }
      if ($this->check_account($_POST["from_acc"]) === 'savings') {
        $q2 = 'UPDATE savings_accounts SET transactions = transactions + 1  WHERE acc_no = ?';
        $stmt2 = $this->conn->prepare($q2);
        $stmt2->bind_param('s', $from_acc);
        if (!($stmt2->execute())) {
          $this->conn->rollback();
          return false;
        }
      }

      $q3 = 'UPDATE accounts SET balance = balance + ? WHERE acc_no = ?';
      $stmt3 = $this->conn->prepare($q3);
      $stmt3->bind_param('ds', $amount, $to_acc);
      if (!($stmt3->execute())) {
        $this->conn->rollback();
        return false;
      }

      $q4 = 'INSERT INTO transactions (from_acc, to_acc, init_id, trans_time, amount) VALUES (?, ?, ?, ?, ?)';
      $stmt4 = $this->conn->prepare($q4);
      $date = date('Y-m-d H:i:s');
      $stmt4->bind_param('ssssd', $from_acc, $to_acc, $init_id, $date, $amount);
      if (!($stmt4->execute())) {
        $this->conn->rollback();
        return false;
      }
      ($this->conn)->commit();
      return true;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return false;
    }
  }

  public function get_accounts_list(string $owner_id)
  {

    if (!($this->conn instanceof mysqli)) return null;

    ($this->conn)->begin_transaction();
    try {
      $arr = array();
      $q1 = 'SELECT acc_no FROM accounts WHERE (owner_id = ? and (type = "checking" or type = "savings"))';
      $stmt = $this->conn->prepare($q1);
      $stmt->bind_param('s', $owner_id);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) {
        $acc_id = $row['acc_no'];
        array_push($arr, $acc_id);
      }
      ($this->conn)->commit();
      return $arr;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return [];
    }
  }

  public function check_account(String $acc_no)
  {

    if (!($this->conn instanceof mysqli)) return null;

    $q1 = 'SELECT * FROM accounts WHERE acc_no = ? ';
    $stmt = $this->conn->prepare($q1);
    $stmt->bind_param('s', $acc_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $account = $result->fetch_assoc();

    if ($account == null) return null;
    return $account['type'];
  }

  public function get_account_ownership(string $acc_no, string $username)
  {

    $q1 = 'SELECT owner_id FROM accounts WHERE acc_no = ? ';
    $stmt = $this->conn->prepare($q1);
    $stmt->bind_param('s', $acc_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $account = $result->fetch_assoc();

    if ($account['owner_id'] === $username) return true;
    return false;
  }

  public function check_username(string $username)
  {
    if (!($this->conn instanceof mysqli)) return null;

    $q1 = 'SELECT * FROM accounts WHERE owner_id = ? ';
    $stmt = $this->conn->prepare($q1);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $name = $result->fetch_assoc();

    if ($name == null) return false;
    return true;
  }


  public function check_transaction_count(String $acc_no)
  {

    if (!($this->conn instanceof mysqli)) return null;

    $q1 = 'SELECT transactions FROM savings_accounts WHERE acc_no = ? ';
    $stmt = $this->conn->prepare($q1);
    $stmt->bind_param('s', $acc_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc();

    return $count['transactions'];
  }

  public function view_transaction_history(string $owner_id, string $acc_no, $start_date, $end_date)
  {
    if (!($this->conn instanceof mysqli)) return null;
    ($this->conn)->begin_transaction();
    try {
      $arr = array();
      if ($acc_no == null) {
        return $arr;
      }
      $q = 'SELECT * FROM Accounts WHERE owner_id = ? and acc_no = ?';
      $ps = $this->conn->prepare($q);
      $ps->bind_param('ss', $owner_id, $acc_no);
      $ps->execute();
      $ps->store_result();
      $ps->fetch();
      if ($ps->num_rows() == 0) {
        return $arr;
      } else if (is_null($start_date) && is_null($end_date)) {
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount FROM Transactions WHERE from_acc = ? or to_acc = ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('ss', $acc_no, $acc_no);
      } else if (is_null($end_date)) {
        $start_date_str = $start_date->format('Y-m-d H:i:s');
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount FROM Transactions WHERE (from_acc = ? or to_acc = ?) and trans_time >= ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('sss', $acc_no, $acc_no, $start_date_str);
      } else if (is_null($start_date)) {
        $end_date_str = $end_date->format('Y-m-d H:i:s');
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount FROM Transactions WHERE (from_acc = ? or to_acc = ?) and trans_time <= ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('sss', $acc_no, $acc_no, $end_date_str);
      } else {
        $start_date_str = $start_date->format('Y-m-d H:i:s');
        $end_date_str = $end_date->format('Y-m-d H:i:s');
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount FROM Transactions WHERE (from_acc = ? or to_acc = ?) and trans_time >= ? and trans_time <= ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('sss', $acc_no, $acc_no, $start_date_str, $end_date_str);
      }
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
        $trans_id = $row['trans_id'];
        $from_acc = $row['from_acc'];
        $to_acc = $row['to_acc'];
        $init_id = $row['init_id'];
        $trans_time = $row['trans_time'];
        $amount = $row['amount'];
        array_push($arr, array('trans_id' => $trans_id, 'from_acc' => $from_acc, 'to_acc' => $to_acc, 'init_id' => $init_id, 'trans_time' => $trans_time, 'amount' => $amount));
      }
      ($this->conn)->commit();
      return $arr;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return [];
    }
  }

  public function create_account(string $owner_id, string $acc_no, string $acc_type, float $balance, string $branch_id, $saving_acc_no, $duration)
  {
    if (!($this->conn instanceof mysqli)) return null;
    ($this->conn)->begin_transaction();
    $common_query = 'INSERT into Accounts (owner_id, acc_no, type, balance, opened_date, branch_id) values (?, ?, ?, ?, ?, ?)';
    $date_str = gmdate('Y-m-d');
    $stmt = $this->conn->prepare($common_query);
    $stmt->bind_param('sssdss', $owner_id, $acc_no, $acc_type, $balance, $date_str, $branch_id);
    $stmt->execute();
    $response = ['result' => false, 'created_acc' => ''];
    try {
      if ($acc_type === "checking") {
        $q0 = 'INSERT into checking_accounts (acc_no) values (?)';
        $stmt0 = $this->conn->prepare($q0);
        $stmt0->bind_param('s', $acc_no);
        $response['result'] = $stmt0->execute();
        $response['created_acc'] = "Checking";
      } elseif ($acc_type === "savings") {
        $customer_type = $this->get_savings_acc_type($owner_id);
        if ($customer_type === "") {
          return $response;
        }
        $q0 = 'INSERT into savings_accounts (acc_no, customer_type, transactions) values (?, ?, 0)';
        $stmt0 = $this->conn->prepare($q0);
        $stmt0->bind_param('ss', $acc_no, $customer_type);
        $response['result'] = $stmt0->execute();
        $response['created_acc'] = 'Savings - ' . $customer_type;
      } elseif ($acc_type === "fd") {
        $q0 = 'INSERT into fixed_deposits (acc_no, savings_acc_no, duration) values (?, ?, ?)';
        $stmt0 = $this->conn->prepare($q0);
        $stmt0->bind_param('ssi', $acc_no, $saving_acc_no, $duration);
        $response['result'] = $stmt0->execute();
        $response['created_acc'] = 'Fixed Deposit';
      }
      ($this->conn)->commit();
      return $response;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return $response;
    }
  }

  public function get_savings_acc_type(string $owner_id)
  {
    if (!($this->conn instanceof mysqli)) return null;
    ($this->conn)->begin_transaction();
    try {
      $q0 = 'SELECT type FROM customer WHERE username=?';
      $stmt0 = $this->conn->prepare($q0);
      $stmt0->bind_param('s', $owner_id);
      $stmt0->execute();
      $stmt0->store_result();
      if ($stmt0->num_rows() == 0) {
        return "";
      }
      $stmt0->bind_result($type);
      $stmt0->fetch();
      $stmt0->close();
      if ($type == "organization") {
        return "adult";
      }
      $q = 'SELECT DOB FROM individual WHERE username=?';
      $stmt = $this->conn->prepare($q);
      $stmt->bind_param('s', $owner_id);
      $stmt->execute();
      $stmt->store_result();
      if ($stmt->num_rows() == 0) {
        return "";
      }
      $stmt->bind_result($dob);
      $stmt->fetch();
      $stmt->close();
      ($this->conn)->commit();
      $today = date("Y-m-d");
      $diff = date_diff(date_create($dob), date_create($today));
      $age = (int)$diff->format('%y');
      if ($age <= 12) {
        return "child";
      } else if ($age < 18) {
        return "teen";
      } else if ($age < 60) {
        return "adult";
      } else {
        return "senior";
      }
      return "";
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return "";
    }
  }

  public function close_conn()
  {
    if (DatabaseConn::$dbconn != null && $this->conn instanceof mysqli) {
      $this->conn->close();
    }
    $this->__destruct();
  }

  private function validate($username, $pw): Bool
  {
    $username = htmlspecialchars($username);
    $pw = htmlspecialchars($pw);
    $username_pattern = '/^[a-zA-Z0-9._]{5,12}$/';
    $pw_pattern = '/^[\x21-\x7E]{8,15}$/';
    //$pw_pattern = '/^\S*(?=\S{8,15})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/';
    if (preg_match($username_pattern, $username) && preg_match($pw_pattern, $pw)) {
      return true;
    }
    return false;
  }

  public function __destruct()
  {
  }
}
