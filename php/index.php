<!-- 
    Example of how to initiate a payment using the fapshi PHP SDK
    Before running this script, make sure to add your apiuser and apikey to your Fapshi.php file
-->
<?php
include 'Fapshi.php';

$fapshi = new Fapshi();
$payment= array(
    'amount'=> 500, //fapshi
    'email'=> 'myuser@email.com',
    'externalId'=> '12345',
    'userId'=> 'abcde',
    'redirectUrl'=> 'https://mywebsite.com',
    'message'=> 'testing php SDK',
); 
$resp = $fapshi->initiate_pay($payment);
echo json_encode($resp);


// Example of how to search/filter transctions using the fapshi PHP SDK
$query= array(
    // 'amt'=> '500',
    // 'status'=> 'SUCCESSFUL',
    // 'medium'=> 'orange money',
    'limit'=> 3,
    'sort'=> 'asc',
    'start'=> '2023-12-01',
    'end'=> '2024-12-01',
); 
$trans = $fapshi->search($query);
echo json_encode($trans);


