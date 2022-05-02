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

    <label for="to_acc">TO ACCOUNT</label>
    <input type="text" name="to_acc" id="to_acc" required>


    <div>
    <label for="from acc">FROM ACCOUNT</label>
    <select name="from_acc" id="from_acc" required>
    <option value="" disabled selected></option>
    <option value="#acc_id1">acc1_no</option>
    <option value="#acc_id2">acc2_no</option>
    <option value=#acc_id3">acc3_no</option>

    </select>

    <!-- ... -->
    </div>

    <label for="amount">AMOUNT</label>
    <input type="number" min="0.00"  step="0.01" required />

    <input type="submit" value="submit">
   
  
</form>
</body>
</html>