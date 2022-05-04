<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>

</head>

<body>

    <form action="" method="post">

        <label for="fix_acc">Fixed Deposit Account Number : </label>
        <input type="text" name="fix_acc" id="fix_acc" required>

        <label for="amount">AMOUNT</label>
        <input type="number" min="0.00" step="0.01" required />

        <input type="submit" value="submit">


    </form>
</body>

</html>