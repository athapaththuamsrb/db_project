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
        <input type="text" name="owner_id" id="owner_id" required><br>
        Account No
        <input type="text" name="acc_no" id="acc_no" required><br>
        Start Date
        <input type="date" name="start_date" id ="start_date"><br>
        End Date
        <input type="date" name="end_date" id="end_date"><br>
        <button type="submit">Check Balance</button>
    </form>
</body>
</html>