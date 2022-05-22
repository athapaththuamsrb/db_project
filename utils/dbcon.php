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
        $q = 'SELECT password, type FROM users WHERE username=?';
        $stmt = $this->conn->prepare($q);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $rowcount = $stmt->num_rows();
        if ($rowcount == 1) {
          $stmt->bind_result($pw_hash, $type);
          $stmt->fetch();
          if (password_verify($password, $pw_hash)) {
            $stmt->close();
            return new User($username, $type);
          }
        }
        $stmt->close();
      } catch (Exception $e) {
        return null;
      }
    }
    return null;
  }

  public function createUser(string $username, string $password, string $type, string $creator): bool
  {
    if (!($this->conn instanceof mysqli)) return false;
    if ($this->validate($username, $password)) {
      ($this->conn)->begin_transaction();
      try {
        $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $q = 'INSERT INTO users (username, password, type, created_by) VALUES (?, ?, ?, ?);';
        $stmt = $this->conn->prepare($q);
        $stmt->bind_param('ssss', $username, $hashed, $type, $creator);
        $status = $stmt->execute();
        $stmt->close();
        ($this->conn)->commit();
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
      $q1 = 'SELECT username FROM users WHERE username=? AND type="manager"';
      $stmt1 = $this->conn->prepare($q1);
      $stmt1->bind_param('s', $manager);
      $stmt1->execute();
      $stmt1->store_result();
      if ($stmt1->num_rows() !== 1) {
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

  public function check_balance(string $owner_id, string $acc_no)
  {
    if (!($this->conn instanceof mysqli)) return null;
    ($this->conn)->begin_transaction();
    try {
      if (!$owner_id || !$acc_no) {
        return null;
      } else {
        $q0 = 'SELECT balance FROM Accounts WHERE owner_id = ? and acc_no = ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('ss', $owner_id, $acc_no);
      }
      $stmt->execute();
      $stmt->store_result();
      if ($stmt->num_rows() == 0) {
        return null;
      }
      $stmt->bind_result($balance);
      $stmt->fetch();
      $stmt->close();
      ($this->conn)->commit();
      return $balance;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return null;
    }
  }

  public function transaction(string $from_acc, string $to_acc, string $init_id, Datetime $trans_time, float $amount)
  {

    if (!($this->conn instanceof mysqli)) return false;
    if ($from_acc != null) {
      $balance = $this->check_balance($init_id, $from_acc);
      if ($balance == null || ($balance < $amount)) {
        return false;
      }
    }
    ($this->conn)->begin_transaction();
    try {
      if ($from_acc != null) {
        $q1 = 'UPDATE accounts SET balance = balance - ? WHERE acc_no = ?';
        $stmt = $this->conn->prepare($q1);
        $stmt->bind_param('ds', $amount, $from_acc);
      }
      $q2 = 'UPDATE accounts SET balance = balance + ? WHERE acc_no = ?';
      $stmt = $this->conn->prepare($q2);
      $stmt->bind_param('ds', $amount, $to_acc);

      $q3 = 'INSERT INTO transactions (from_acc, to_acc, init_id, trans_time, amount) VALUES (?, ?, ?, ?, ?)';
      $stmt = $this->conn->prepare($q3);
      $trans_time_str = $trans_time->format('Y-m-d');
      $stmt->bind_param('ssssd', $amount, $to_acc, $init_id, $trans_time_str, $amount);

      $status = $stmt->execute();
      $stmt->close();
      ($this->conn)->commit();
      return $status;
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

    $q1 = ' SELECT * FROM accounts WHERE acc_no = ? ';
    $stmt = $this->conn->prepare($q1);
    $stmt->bind_param('s', $acc_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $account = $result->fetch_assoc();

    if ($account == null) return null;
    return $account['type'];
  }


  public function check_transaction_count(String $acc_no)
  {

    if (!($this->conn instanceof mysqli)) return null;

    $q1 = ' SELECT transactions FROM savings_accounts WHERE acc_no = ? ';
    $stmt = $this->conn->prepare($q1);
    $stmt->bind_param('s', $acc_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc();

    return $count['transactions'];
  }

  public function view_transaction_history(string $owner_id, string $acc_no, DateTime $start_date, DateTime $end_date)
  {
    if (!($this->conn instanceof mysqli)) return null;
    ($this->conn)->begin_transaction();
    try {
      $arr = array();
      if ($acc_no == null) {
        return $arr;
      } else if ($start_date == null && $end_date = null) {
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount FROM Transactions WHERE owner_id = ? and (from_acc = ? or to_acc = ?)';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('sss', $owner_id, $acc_no, $acc_no);
      } else if ($end_date == null) {
        $start_date_str = $start_date->format('Y-m-d');
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount FROM Transactions WHERE (from_acc = ? or to_acc = ?) and trans_time >= ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('sss', $acc_no, $acc_no, $start_date_str);
      } else if ($start_date == null) {
        $end_date_str = $end_date->format('Y-m-d');
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount FROM Transactions WHERE (from_acc = ? or to_acc = ?) and trans_time <= ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('sss', $acc_no, $acc_no, $end_date_str);
      } else {
        $start_date_str = $start_date->format('Y-m-d');
        $end_date_str = $end_date->format('Y-m-d');
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount FROM Transactions WHERE (from_acc = ? or to_acc = ?) and trans_time >= ? and trans_time <= ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('sss', $acc_no, $acc_no, $start_date_str, $end_date_str);
      }
      $stmt->execute();
      $result = $stmt->get_result();
      if ($stmt->num_rows() == 0) {
        return $arr;
      }
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

  public function create_account(string $owner_id, string $acc_no, string $type, float $balance, string $branch_id, $saving_acc_no, $duration, $customer_type)
  {
    if (!($this->conn instanceof mysqli)) return null;
    ($this->conn)->begin_transaction();
    $common_query = 'INSERT into Accounts (owner_id, acc_no, type, balance, opened_date, branch_id) values (?, ?, ?, ?, ?, ?)';
    $date_str = gmdate('Y-m-d');
    $stmt = $this->conn->prepare($common_query);
    $stmt->bind_param('sssdss', $owner_id, $acc_no, $type, $balance, $date_str, $branch_id);
    $stmt->execute();
    $result = false;
    try {
      if ($type === "checking") {
        $q0 = 'INSERT into checking_accounts (acc_no) values (?)';
        $stmt0 = $this->conn->prepare($q0);
        $stmt0->bind_param('s', $acc_no);
        $result = $stmt0->execute();
      } elseif ($type === "savings") {
        $q0 = 'INSERT into savings_accounts (acc_no, customer_type, transactions) values (?, ?, 0)';
        $stmt0 = $this->conn->prepare($q0);
        $stmt0->bind_param('ss', $acc_no, $customer_type);
        $result = $stmt0->execute();
      } elseif ($type === "fd") {
        $q0 = 'INSERT into fixed_deposits (acc_no, savings_acc_no, duration) values (?, ?, ?)';
        $stmt0 = $this->conn->prepare($q0);
        $stmt0->bind_param('ssi', $acc_no, $saving_acc_no, $duration);
        $result = $stmt0->execute();
      }
      ($this->conn)->commit();
      return $result;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return false;
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
