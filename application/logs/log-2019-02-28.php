<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-02-28 02:10:35 --> Query error: Unknown column 'bank_accounts' in 'where clause' - Invalid query: SELECT `bank_accounts`.`bankAccountID`, `bank_accounts`.`bankID`, `bank_accounts`.`accountName`, `bank_accounts`.`accountType`, `bank_accounts`.`accountNo`, `bank_accounts`.`bankAccountType`, `bank_accounts`.`email`
FROM `bank_accounts`
WHERE `bank_accounts`.`ownerID` = '2'
AND  bank_accounts  LIKE '%2019-02-28%' ESCAPE '!' 
ERROR - 2019-02-28 02:11:38 --> Query error: Unknown column 'bank_accountsdepositDate' in 'where clause' - Invalid query: SELECT `bank_accounts`.`bankAccountID`, `bank_accounts`.`bankID`, `bank_accounts`.`accountName`, `bank_accounts`.`accountType`, `bank_accounts`.`accountNo`, `bank_accounts`.`bankAccountType`, `bank_accounts`.`email`
FROM `bank_accounts`
WHERE `bank_accounts`.`ownerID` = '2'
AND  bank_accountsdepositDate  LIKE '%2019-02-28%' ESCAPE '!' 
ERROR - 2019-02-28 02:11:49 --> Query error: Unknown column 'bank_accounts.depositDate' in 'where clause' - Invalid query: SELECT `bank_accounts`.`bankAccountID`, `bank_accounts`.`bankID`, `bank_accounts`.`accountName`, `bank_accounts`.`accountType`, `bank_accounts`.`accountNo`, `bank_accounts`.`bankAccountType`, `bank_accounts`.`email`
FROM `bank_accounts`
WHERE `bank_accounts`.`ownerID` = '2'
AND  bank_accounts.depositDate  LIKE '%2019-02-28%' ESCAPE '!' 
ERROR - 2019-02-28 02:20:52 --> Severity: Parsing Error --> syntax error, unexpected 'else' (T_ELSE), expecting function (T_FUNCTION) C:\wamp\www\project\special\kitrol\application\controllers\api\Deposit.php 78
ERROR - 2019-02-28 02:21:26 --> Severity: Parsing Error --> syntax error, unexpected 'else' (T_ELSE), expecting function (T_FUNCTION) C:\wamp\www\project\special\kitrol\application\controllers\api\Deposit.php 80
ERROR - 2019-02-28 02:21:48 --> Severity: Parsing Error --> syntax error, unexpected 'else' (T_ELSE), expecting function (T_FUNCTION) C:\wamp\www\project\special\kitrol\application\controllers\api\Deposit.php 80
ERROR - 2019-02-28 02:22:24 --> Severity: Parsing Error --> syntax error, unexpected 'else' (T_ELSE), expecting function (T_FUNCTION) C:\wamp\www\project\special\kitrol\application\controllers\api\Deposit.php 93
ERROR - 2019-02-28 04:07:24 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 04:07:24 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 04:07:25 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 04:07:25 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 04:07:30 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 04:07:30 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 04:07:39 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 04:07:39 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 04:07:42 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 04:07:42 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 05:04:34 --> Severity: Parsing Error --> syntax error, unexpected 'else' (T_ELSE), expecting function (T_FUNCTION) C:\wamp\www\project\special\kitrol\application\controllers\api\Inventory.php 62
ERROR - 2019-02-28 05:22:17 --> Query error: Unknown column 'products.ownerID' in 'where clause' - Invalid query: SELECT `products`.*, `unit_measurements`.`umsr`
FROM `products`
LEFT JOIN `unit_measurements` ON `products`.`umsr`=`unit_measurements`.`umsr`
WHERE `products`.`ownerID` = '2'
ERROR - 2019-02-28 05:29:17 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '.qty) AS totalAmount, `unit_measurements`.`umsr`
FROM `products`
LEFT JOIN `unit' at line 1 - Invalid query: SELECT `products`.`name`, `products`.`qty`, SUM('products'.qty) AS totalAmount, `unit_measurements`.`umsr`
FROM `products`
LEFT JOIN `unit_measurements` ON `products`.`umsr`=`unit_measurements`.`umsrID`
WHERE `products`.`name` = 'DIESEL'
ERROR - 2019-02-28 05:29:36 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '.qty) AS totalAmount
FROM `products`
LEFT JOIN `unit_measurements` ON `products`' at line 1 - Invalid query: SELECT SUM('products'.qty) AS totalAmount
FROM `products`
LEFT JOIN `unit_measurements` ON `products`.`umsr`=`unit_measurements`.`umsrID`
WHERE `products`.`name` = 'DIESEL'
ERROR - 2019-02-28 06:03:09 --> 404 Page Not Found: api/Inventorydate/2019-02-27
ERROR - 2019-02-28 06:03:44 --> Query error: Unknown column 'unit_measurements.qty' in 'field list' - Invalid query: SELECT `unit_measurements`.`umsr`, `unit_measurements`.`qty`
FROM `products`
LEFT JOIN `unit_measurements` ON `products`.`umsr`=`unit_measurements`.`umsrID`
WHERE  products.dateInserted  LIKE '%2019-02-27%' ESCAPE '!' 
ERROR - 2019-02-28 10:05:53 --> Query error: Unknown column 'products.acecost' in 'field list' - Invalid query: SELECT `products`.`dateInserted`, `products`.`name`, `products`.`qty`, `products`.`acecost`, `unit_measurements`.`umsr`
FROM `products`
LEFT JOIN `unit_measurements` ON `products`.`umsr`=`unit_measurements`.`umsrID`
WHERE  products.dateInserted  LIKE '%2019-02%' ESCAPE '!' 
ERROR - 2019-02-28 14:01:50 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 14:01:50 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 14:01:50 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 14:01:50 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 14:01:59 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 14:01:59 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 14:02:07 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 14:02:07 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 14:02:10 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 14:02:10 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 22:24:37 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 22:24:38 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 22:34:15 --> Severity: Warning --> include(modal.php): failed to open stream: No such file or directory C:\wamp\www\project\special\kitrol\application\views\footer.php 2
ERROR - 2019-02-28 22:34:15 --> Severity: Warning --> include(): Failed opening 'modal.php' for inclusion (include_path='.;C:\php\pear') C:\wamp\www\project\special\kitrol\application\views\footer.php 2
