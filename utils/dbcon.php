<?php
require_once('User.php');
require_once('patterns.php');

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
                  $q3 = 'SELECT owner_NIC FROM organization WHERE username=?';
                  $stmt3 = $this->conn->prepare($q3);
                  $stmt3->bind_param('s', $username);
                  $stmt3->execute();
                  $stmt3->store_result();
                  $rowcount = $stmt3->num_rows();
                  if ($rowcount == 1) {
                    $stmt3->bind_result($ownerNIC);
                    $stmt3->fetch();
                    $details['ownerNIC'] = $ownerNIC;
                  }
                  $stmt3->close();
                }
              }
              $stmt1->close();
            } else if ($type === 'employee') {
              $q4 = 'SELECT branch FROM employee WHERE username=?';
              $stmt4 = $this->conn->prepare($q4);
              $stmt4->bind_param('s', $username);
              $stmt4->execute();
              $stmt4->store_result();
              $rowcount = $stmt4->num_rows();
              if ($rowcount == 1) {
                $stmt4->bind_result($branch);
                $stmt4->fetch();
                $details['branch'] = $branch;
              }
              $stmt4->close();
            }
            try {
              $user = User::createUser($details);
              return $user;
            } catch (Throwable $e) {
              return null;
            }
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
            $q3 = 'INSERT INTO individual (username, NIC, DoB) VALUES (?, ?, ?);';
            $stmt3 = $this->conn->prepare($q3);
            $NIC = $user->getNIC();
            $dobStr = $user->getDoB()->format('D M d Y \G\M\TO');
            $stmt3->bind_param('sss', $username, $NIC, $dobStr);
            $status &= $stmt3->execute();
            $stmt3->close();
          }
        } else if ($status && $user instanceof Employee) {
          $branch = $user->getBranch();
          $q4 = 'INSERT INTO employee (username, branch) VALUES (?, ?);';
          $stmt4 = $this->conn->prepare($q4);
          $stmt4->bind_param('ss', $username, $branch);
          $status &= $stmt4->execute();
          $stmt4->close();
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

  public function changePasswd(string $username, string $curpass, string $newpass): bool
  {
    if (!($this->conn instanceof mysqli)) return false;
    try {
      ($this->conn)->begin_transaction();
      $q = 'SELECT password FROM users WHERE username=?';
      $stmt = $this->conn->prepare($q);
      $stmt->bind_param('s', $username);
      $stmt->execute();
      $stmt->store_result();
      $rowcount = $stmt->num_rows();
      if ($rowcount !== 1) return false;
      $stmt->bind_result($pw_hash);
      $stmt->fetch();
      $stmt->close();
      if (!password_verify($curpass, $pw_hash)) return false;

      $hashed = password_hash($newpass, PASSWORD_BCRYPT, ['cost' => 12]);
      $q1 = 'UPDATE users SET password = ? WHERE username = ?';
      $stmt1 = $this->conn->prepare($q1);
      $stmt1->bind_param('ss', $hashed, $username);
      if (!($stmt1->execute())) {
        $this->conn->rollback();
        return false;
      }
      $this->conn->commit();
      return true;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return false;
    }
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

  public function apply_loan(string $fix_acc, float $amount, int $duration, string $owner_id)
  {
    if (!($this->conn instanceof mysqli)) return ['result' => false, 'reason' => '"Something went wrong!"'];
    if ($fix_acc && $amount && $owner_id) {
      ($this->conn)->begin_transaction();
      $response = ['result' => false, 'reason' => ''];
      try {
        $q0 = 'SELECT balance,savings_acc_no FROM Accounts INNER JOIN fixed_deposits ON Accounts.acc_no = fixed_deposits.acc_no WHERE Accounts.owner_id = ? and fixed_deposits.acc_no = ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('ss', $owner_id, $fix_acc);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows() == 0) {
          $response['reason'] = 'No fixed deposit account associate with the entered number!';
          return $response;
        }
        $stmt->bind_result($balance, $savings_acc_no);
        $stmt->fetch();
        $stmt->close();
        if ($balance * 0.6 < $amount || $amount > 500000) {
          $response['reason'] = 'Fixed deposit is not enough or amount > 500000';
          return $response;
        }


        $q = 'SELECT fixedAccount FROM loans WHERE fixedAccount = ? and loanStatus = 1';
        $stmt = $this->conn->prepare($q);
        $stmt->bind_param('s', $fix_acc);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows() > 0) {
          $response['reason'] = 'Only one loan can apply from a fixed deposit!';
          return $response;
        }
        $stmt->fetch();
        $stmt->close();

        $date = date("Y-m-d");
        $paid = 0;
        $loanStatus = 1;
        $installment = (($amount + ($amount * 0.2 / 12) * $duration) / $duration); //20% for year
        $q2 = 'INSERT INTO loans ( total_amount,paid_amount, date,savingsAccount,fixedAccount,duration,installment,loanStatus) VALUES (?, ?, ?, ?,? , ? , ? , ?);';
        $stmt = $this->conn->prepare($q2);
        $stmt->bind_param('ddsssidi', $amount, $paid, $date, $savings_acc_no, $fix_acc, $duration, $installment, $loanStatus);
        $status0 = $stmt->execute();
        $stmt->close();

        if ($status0) { //update saving account balance
          $tr_status = $this->transaction(null, $savings_acc_no, $owner_id, $amount, "LOAN");
          if ($tr_status && isset($tr_status['success']) && $tr_status['success']) {
            $response['reason'] = 'Loan added successfully!';
            $response['result'] = true;
          } else {
            ($this->conn)->rollback();
            $response['reason'] = 'Something Went Wrong!';
            return $response;
          }
        } else {
          $response['reason'] = 'Something Went Wrong!';
          return $response;
        }

        ($this->conn)->commit();



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

  public function requestLoan(string $sav_acc, float $amount, float $duration, string $employee)
  {
    if (!($this->conn instanceof mysqli)) return ['result' => false, 'reason' => '"Something went wrong!"'];
    if ($sav_acc && $amount) {
      ($this->conn)->begin_transaction();
      $response = ['result' => false, 'reason' => ''];
      try {
        $q0 = 'SELECT type FROM Accounts WHERE acc_no = ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('s', $sav_acc);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows() == 0) {
          $response['reason'] = 'No account associate with the entered number!';
          return $response;
        }
        $stmt->bind_result($type);
        $stmt->fetch();
        $stmt->close();
        if ($type != "savings") {
          $response['reason'] = 'Account is not associated with a savings account!';
          return $response;
        }


        $date = date("Y-m-d");
        $paid = 0;
        $loanStatus = 0;
        $installment = (($amount + ($amount * 0.2 / 12) * $duration) / $duration); //20% for year
        $q2 = 'INSERT INTO loans ( total_amount,paid_amount, date,savingsAccount,duration,installment,loanStatus) VALUES ( ?, ?, ?,? , ? , ? , ?);';
        $stmt = $this->conn->prepare($q2);
        $stmt->bind_param('ddssddi', $amount, $paid, $date, $sav_acc, $duration, $installment, $loanStatus);
        $status0 = $stmt->execute();
        $stmt->close();

        if (!$status0) {
          $response['reason'] = 'Something Went Wrong!';
          return $response;
        } else {
          $q3 = 'INSERT INTO loan_requests (loanID,employee) VALUES ( ?, ?);';
          $stmt = $this->conn->prepare($q3);
          $insert_id = $this->conn->insert_id;
          $stmt->bind_param('ss', $insert_id, $employee);
          $status3 = $stmt->execute();
          $stmt->close();
          if ($status3) {
            $response['reason'] = 'Loan requested successfully!';
            $response['result'] = true;
          } else {
            ($this->conn)->rollback();
            $response['reason'] = 'Something Went Wrong!';
            return $response;
          }
        }

        ($this->conn)->commit();


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

  public function enter_Installment(string $loan_id, float $amount, string $employee)
  {
    if (!($this->conn instanceof mysqli)) return ['result' => false, 'reason' => '"Something went wrong!"'];
    if ($loan_id && $amount) {
      ($this->conn)->begin_transaction();
      $response = ['result' => false, 'reason' => ''];
      try {
        $q0 = 'SELECT total_amount,paid_amount,installment,duration,loanStatus FROM loans WHERE loanID = ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('s', $loan_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows() == 0) {
          $response['reason'] = 'No loan associate with the entered ID';
          return $response;
        }
        $stmt->bind_result($total_amount, $paid_amount, $installment, $duration, $loanStatus);
        $stmt->fetch();
        $stmt->close();
        if ($loanStatus == 0) {
          $response['reason'] = 'Loan is not approved yet!';
          return $response;
        } else if ($loanStatus == 2) {
          $response['reason'] = 'Loan is already completely paid!';
          return $response;
        }
        if (round($installment * $duration, 2) < round($paid_amount + $amount, 2)) {
          $response['reason'] = 'Exceed the total amount';
          return $response;
        } else if (round($installment * $duration, 2) == round($paid_amount + $amount, 2)) {
          $q3 = 'UPDATE loans SET paid_amount = paid_amount + ? , loanStatus = 2 WHERE loanID = ?;';
          $stmt = $this->conn->prepare($q3);
          $stmt->bind_param('ds', $amount, $loan_id);
          $status = $stmt->execute();
          $response['reason'] = 'Installment entered correctly and it covers the full amount of the loan.';
        } else {
          $q3 = 'UPDATE loans SET paid_amount = paid_amount + ? WHERE loanID = ?;';
          $stmt = $this->conn->prepare($q3);
          $stmt->bind_param('ds', $amount, $loan_id);
          $status = $stmt->execute();
          $response['reason'] = "Installment entered correctly!";
        }
        if (!$status) {
          $response['reason'] = 'Something Went Wrong!';
          return $response;
        } else {

          $date = date("Y-m-d");
          $q4 = 'INSERT INTO loan_payments (loanID,amount,date,employee ) VALUES ( ?, ?, ?,?);';
          $stmt = $this->conn->prepare($q4);
          $stmt->bind_param('sdss', $loan_id, $amount, $date, $employee);
          $status0 = $stmt->execute();
          $stmt->close();
        }

        if (!$status0) {
          ($this->conn)->rollback();
          $response['reason'] = 'Something Went Wrong!';
          return $response;
        } else {
          $response['result'] = true;
        }



        ($this->conn)->commit();
        return $response;
      } catch (Exception $e) {
        ($this->conn)->rollback();
        $response['reason'] = 'Error!';
        return $response;
      }
    }
    $response['reason'] = 'Something Went Wrong!';
    return $response;
  }

  public function getPendingApprovalLoans(string $manager): ?array
  {
    if (!($this->conn instanceof mysqli)) return null;
    try {
      $q0 = 'SELECT id FROM branch WHERE manager_id = ?';
      $stmt = $this->conn->prepare($q0);
      $stmt->bind_param('s', $manager);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows() == 0) {
        $response['reason'] = 'No branch manager with this username';
        return $response;
      }
      $stmt->bind_result($branchId);
      $stmt->fetch();
      $stmt->close();

      $q1 = 'SELECT * FROM pending_loans WHERE branch = ?';
      $stmt1 = $this->conn->prepare($q1);
      $stmt1->bind_param('s', $branchId);
      $stmt1->execute();
      $result = $stmt1->get_result();
      $data = [];
      while ($row = $result->fetch_assoc()) {
        $arr = [
          $row['loanID'], $row['total_amount'], $row['date'], $row['savingsAccount'], $row['duration']
        ];
        array_push($data, $arr);
      }
      ($this->conn)->commit();
      return $data;
    } catch (Exception $e) {
      return null;
    }
    return null;
  }

  public function loanApprove(string $loanID, string $managerID)
  {
    if (!($this->conn instanceof mysqli)) return false;
    if ($loanID) {
      ($this->conn)->begin_transaction();
      $response = ['result' => false, 'reason' => ''];
      try {

        $q = 'SELECT loanStatus,total_amount,savingsAccount FROM loans WHERE loanID = ?;';
        $stmt = $this->conn->prepare($q);
        $stmt->bind_param('s', $loanID);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows() == 0) {
          $response['reason'] = 'No loan associated with this loan ID';
          return $response;
        }
        $stmt->bind_result($loanStatus, $total_amount, $savingsAccount);
        $stmt->fetch();
        $stmt->close();

        if ($loanStatus != 0) {
          $response['reason'] = 'This loan is already approved!';
          return $response;
        }
        $date = date("Y-m-d");
        $q0 = 'UPDATE loans SET loanStatus = 1,date=? WHERE loanID = ?;';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('ss', $date, $loanID);
        $status = $stmt->execute();



        if (!$status) {
          $response['reason'] = 'Something Went Wrong!';
          return $response;
        } else {
          $tr_status = $this->transaction(null, $savingsAccount, $managerID, $total_amount, "LOAN");
          if ($tr_status && isset($tr_status['success']) && $tr_status['success']) {
            $response['reason'] = 'Loan added successfully!';
            $response['result'] = true;
          } else {
            ($this->conn)->rollback();
            $response['reason'] = 'Something Went Wrong!';
            return $response;
          }
        }

        ($this->conn)->commit();


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

      $q1 = 'SELECT loanID, customer, should_paid, paid_amount, difference FROM late_loan_view WHERE branch_id=? ORDER BY loanID';
      $stmt1 = $this->conn->prepare($q1);
      $stmt1->bind_param('i', $branch_id);
      $stmt1->execute();
      $result = $stmt1->get_result();
      $data = [];
      while ($row = $result->fetch_assoc()) {
        $arr = [$row['loanID'], $row['customer'], $row['should_paid'], $row['paid_amount'], $row['difference']];
        array_push($data, $arr);
      }
      ($this->conn)->commit();
      return [['Loan ID', 'Customer', 'Should paid', 'Paid amount', 'Difference'], $data];
    } catch (Exception $e) {
      return null;
    }
    return null;
  }

  public function getTransactions($username): ?array
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

      $q1 = 'SELECT trans_id, from_acc, to_acc, amount, trans_type, trans_time FROM Transaction_view WHERE from_branch=? OR to_branch=? ORDER BY trans_id';
      $stmt1 = $this->conn->prepare($q1);
      $stmt1->bind_param('ii', $branch_id, $branch_id);
      $stmt1->execute();
      $result = $stmt1->get_result();
      $data = [];
      while ($row = $result->fetch_assoc()) {
        $arr = [$row['trans_id'], $row['from_acc'], $row['to_acc'], $row['amount'], $row['trans_type'], $row['trans_time']];
        array_push($data, $arr);
      }
      ($this->conn)->commit();
      return [['ID', 'From', 'To', 'Amount', 'Type', 'Date'], $data];
    } catch (Exception $e) {
      return null;
    }
    return null;
  }

  public function check_balance(string $owner_id, string $acc_no)
  {
    if (!($this->conn instanceof mysqli)) return -1;
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
      return $balance;
    } catch (Exception $e) {
      return -1;
    }
  }

  public function check_min_balance(string $owner_id, string $acc_no)
  {
    if (!($this->conn instanceof mysqli)) return -1;
    try {
      if (!$owner_id || !$acc_no) {
        return -1;
      }
      $q0 = 'SELECT type FROM Accounts WHERE owner_id = ? and acc_no = ?';
      $stmt = $this->conn->prepare($q0);
      $stmt->bind_param('ss', $owner_id, $acc_no);
      $stmt->execute();
      $result = $stmt->get_result();
      $type = $result->fetch_assoc();
      $t = $type['type'];
      if ($t === 'savings') {
        $q1 = 'SELECT customer_type FROM savings_accounts WHERE  acc_no = ?';
        $stmt = $this->conn->prepare($q1);
        $stmt->bind_param('s', $acc_no);
        $stmt->execute();
        $result = $stmt->get_result();
        $c_type = $result->fetch_assoc();
        $customer = $c_type['customer_type'];
        if ($customer === 'child') {
          return $this->check_balance($owner_id, $acc_no);
        } elseif ($customer == 'teen') {
          return ($this->check_balance($owner_id, $acc_no) - 500);
        } elseif ($customer == 'adult') {
          return ($this->check_balance($owner_id, $acc_no) - 1000);
        } elseif ($customer == 'senior') {
          return ($this->check_balance($owner_id, $acc_no) - 1000);
        } else {
          return -1;
        }
      } elseif ($t === 'checking') {
        return $this->check_balance($owner_id, $acc_no);
      } else {
        return -1;
      }
    } catch (Exception $e) {
      return -1;
    }
  }

  public function transaction(?string $from_acc, ?string $to_acc, string $init_id, float $amount, string $t_type): array
  {
    $res = ['success' => false];
    if (!($this->conn instanceof mysqli)) return $res;

    ($this->conn)->begin_transaction();
    ($this->conn)->autocommit(false);
    try {
      if ($from_acc != null) {
        $q1 = 'UPDATE Accounts SET balance = balance - ? WHERE acc_no = ?';
        $stmt1 = $this->conn->prepare($q1);
        $stmt1->bind_param('ds', $amount, $from_acc);
        if (!($stmt1->execute())) {
          $this->conn->rollback();
          return $res;
        }

        if ($this->check_account($from_acc) === 'savings') {
          $q2 = 'UPDATE savings_accounts SET transactions = transactions + 1  WHERE acc_no = ?';
          $stmt2 = $this->conn->prepare($q2);
          $stmt2->bind_param('s', $from_acc);
          if (!($stmt2->execute())) {
            $this->conn->rollback();
            return $res;
          }
        }
      }
      if ($to_acc != null) {
        $q3 = 'UPDATE Accounts SET balance = balance + ? WHERE acc_no = ?';
        $stmt3 = $this->conn->prepare($q3);
        $stmt3->bind_param('ds', $amount, $to_acc);
        if (!($stmt3->execute())) {
          $this->conn->rollback();
          return $res;
        }
      }
      $q4 = 'INSERT INTO Transactions (from_acc, to_acc, init_id, trans_time, amount,trans_type) VALUES (?, ?, ?, ?, ?,?)';
      $stmt4 = $this->conn->prepare($q4);
      $date = date('Y-m-d H:i:s');
      $amount = abs($amount);
      $stmt4->bind_param('ssssds', $from_acc, $to_acc, $init_id, $date, $amount, $t_type);
      if (!($stmt4->execute())) {
        $this->conn->rollback();
        return $res;
      }
      ($this->conn)->commit();
      $res['success'] = true;
      return $res;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      $msg = $e->getMessage();
      $tag = "CustomError: ";
      if ($msg && strncmp($msg, $tag, strlen($tag)) == 0) {
        $res['msg'] = substr($msg, strlen($tag));
      }
    }
    return $res;
  }

  public function get_accounts_list(string $owner_id)
  {

    if (!($this->conn instanceof mysqli)) return null;

    ($this->conn)->begin_transaction();
    try {
      $arr = array();
      $q1 = 'SELECT acc_no FROM Accounts WHERE (owner_id = ? and (type = "checking" or type = "savings"))';
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
    try {
      $q1 = 'SELECT * FROM Accounts WHERE acc_no = ? ';
      $stmt = $this->conn->prepare($q1);
      $stmt->bind_param('s', $acc_no);
      $stmt->execute();
      $result = $stmt->get_result();
      $account = $result->fetch_assoc();

      if ($account == null) return null;
      return $account['type'];
    } catch (Exception $e) {
      return null;
    }
  }

  public function get_account_ownership(string $acc_no, string $username)
  {
    try {
      $q1 = 'SELECT owner_id FROM Accounts WHERE acc_no = ? ';
      $stmt = $this->conn->prepare($q1);
      $stmt->bind_param('s', $acc_no);
      $stmt->execute();
      $result = $stmt->get_result();
      $account = $result->fetch_assoc();

      if ($account['owner_id'] === $username) return true;
      return false;
    } catch (Throwable $th) {
      return false;
    }
  }

  public function check_username(string $username)
  {
    if (!($this->conn instanceof mysqli)) return null;
    try {
      $q1 = 'SELECT * FROM Accounts WHERE owner_id = ? ';
      $stmt = $this->conn->prepare($q1);
      $stmt->bind_param('s', $username);
      $stmt->execute();
      $result = $stmt->get_result();
      $name = $result->fetch_assoc();

      if ($name == null) return false;
      return true;
    } catch (Throwable $th) {
      return false;
    }
  }


  public function check_transaction_count(String $acc_no)
  {

    if (!($this->conn instanceof mysqli)) return null;

    try {
      $q1 = 'SELECT transactions FROM savings_accounts WHERE acc_no = ? ';
      $stmt = $this->conn->prepare($q1);
      $stmt->bind_param('s', $acc_no);
      $stmt->execute();
      $result = $stmt->get_result();
      $count = $result->fetch_assoc();
      return $count['transactions'];
    } catch (Throwable $th) {
      return 6;
    }
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
      $q = 'SELECT type FROM Accounts WHERE owner_id = ? and acc_no = ?';
      $ps = $this->conn->prepare($q);
      $ps->bind_param('ss', $owner_id, $acc_no);
      $ps->execute();
      $ps->store_result();
      $num_rows = $ps->num_rows();
      $ps->bind_result($type);
      $ps->fetch();
      $ps->close();
      if ($num_rows == 0) {
        return $arr;
      } else if ($type == "fd") {
        return $arr;
      } else if (is_null($start_date) && is_null($end_date)) {
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount, trans_type FROM Transactions WHERE from_acc = ? or to_acc = ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('ss', $acc_no, $acc_no);
      } else if (is_null($end_date)) {
        $start_date_str = $start_date->format('Y-m-d H:i:s');
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount, trans_type FROM Transactions WHERE (from_acc = ? or to_acc = ?) and trans_time >= ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('sss', $acc_no, $acc_no, $start_date_str);
      } else if (is_null($start_date)) {
        $end_date_str = $end_date->format('Y-m-d') . ' 23:59:59';
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount, trans_type FROM Transactions WHERE (from_acc = ? or to_acc = ?) and trans_time <= ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('sss', $acc_no, $acc_no, $end_date_str);
      } else {
        $start_date_str = $start_date->format('Y-m-d H:i:s');
        $end_date_str = $end_date->format('Y-m-d') . ' 23:59:59';
        $q0 = 'SELECT trans_id, from_acc, to_acc, init_id, trans_time, amount, trans_type FROM Transactions WHERE (from_acc = ? or to_acc = ?) and trans_time >= ? and trans_time <= ?';
        $stmt = $this->conn->prepare($q0);
        $stmt->bind_param('ssss', $acc_no, $acc_no, $start_date_str, $end_date_str);
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
        $trans_type = $row['trans_type'];
        array_push($arr, array('trans_id' => $trans_id, 'from_acc' => $from_acc, 'to_acc' => $to_acc, 'init_id' => $init_id, 'trans_time' => $trans_time, 'amount' => $amount, 'trans_type' => $trans_type));
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
    $response = ['result' => false, 'created_acc' => '', 'reason' => ''];

    try {

      $common_query = 'INSERT into Accounts (owner_id, acc_no, type, balance, opened_date, branch_id) values (?, ?, ?, ?, ?, ?)';
      $date_str = gmdate('Y-m-d');
      $stmt = $this->conn->prepare($common_query);
      $stmt->bind_param('sssdss', $owner_id, $acc_no, $acc_type, $balance, $date_str, $branch_id);

      if ($acc_type === "checking") {
        $stmt->execute();
        $q0 = 'INSERT into checking_accounts (acc_no) values (?)';
        $stmt0 = $this->conn->prepare($q0);
        $stmt0->bind_param('s', $acc_no);
        $response['result'] = $stmt0->execute();
        $response['created_acc'] = "Checking";
      } elseif ($acc_type === "savings") {
        $customer_type = $this->get_savings_acc_type($owner_id);
        if (is_null($customer_type)) {
          return $response;
        }
        $balance_query = 'SELECT minimum FROM savings_interest WHERE c_type=?';
        $balance_stmt = $this->conn->prepare($balance_query);
        $balance_stmt->bind_param('s', $customer_type);
        $balance_stmt->execute();
        $balance_stmt->store_result();
        if ($balance_stmt->num_rows() == 0) {
          return $response;
        }
        $balance_stmt->bind_result($minimum_balance);
        $balance_stmt->fetch();
        $balance_stmt->close();
        if ($balance < $minimum_balance) {
          $response['reason'] = "Insufficient balance";
          return $response;
        }
        $stmt->execute();
        $q0 = 'INSERT into savings_accounts (acc_no, customer_type, transactions) values (?, ?, 0)';
        $stmt0 = $this->conn->prepare($q0);
        $stmt0->bind_param('ss', $acc_no, $customer_type);
        $response['result'] = $stmt0->execute();
        $response['created_acc'] = 'Savings - ' . $customer_type;
      } elseif ($acc_type === "fd") {
        $query = "SELECT type from Accounts where owner_id=? and acc_no=? and type='savings'";
        $statement = $this->conn->prepare($query);
        $statement->bind_param('ss', $owner_id, $saving_acc_no);
        $statement->execute();
        $statement->store_result();
        if ($statement->num_rows() == 0) {
          $response['reason'] = "No such savings account found under your profile";
          return $response;
        }
        $stmt->execute();
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
      $response['reason'] = "Sorry, error occurred";
      return $response;
    }
  }

  public function fd_account_types()
  {
    if (!($this->conn instanceof mysqli)) return null;
    $arr = array();
    ($this->conn)->begin_transaction();
    try {
      $arr = array();
      $q = 'SELECT duration, name FROM fd_interest';
      $stmt = $this->conn->prepare($q);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($row = $result->fetch_assoc()) {
        $duration = $row['duration'];
        $name = $row['name'];
        $arr[$duration] = $name;
      }
      ($this->conn)->commit();
      return $arr;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return [];
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
        return null;
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
        return null;
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
      return null;
    } catch (Exception $e) {
      ($this->conn)->rollback();
      return null;
    }
  }

  private function validate($username, $pw): bool
  {
    if (preg_match(USERNAME_PATTERN, $username) && preg_match(PASSWORD_PATTERN, $pw)) {
      return true;
    }
    return false;
  }

  public function close_conn()
  {
    if (DatabaseConn::$dbconn != null && $this->conn instanceof mysqli) {
      $this->conn->close();
    }
    $this->__destruct();
  }

  public function __destruct()
  {
  }
}
