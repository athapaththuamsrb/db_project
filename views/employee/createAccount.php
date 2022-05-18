<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Check Your Account Balance</h1>
    <form method="POST">
        Owner ID
        <input type="text" name="owner_id" required><br>

        Account No
        <input type="text" name="acc_no" required><br>

        <label for="dog-names">Choose a dog name:</label>
            <select name="type" id="type">
                <option value="savings">Savings</option>
                <option value="checking">Checking</option>
                <option value="fd">Fixed Deposit</option>
            </select>
        Balance
        <input type="text" name="balance" required><br>
        Branch ID
        <input type="text" name="branch_id" required><br>

        <!-- should be visible only if fd is selected for type -->
        Duration
        <input type="text" name="duration" required><br>
        Savings Account Number
        <input type="text" name="savings_acc_no" required><br>

        <!-- should be visible only if savings is selected for type -->
        Customer Type
        <input type="text" name="customer_type" required><br>

        <button type="submit">Check Balance</button>
    </form>
</body>
</html>