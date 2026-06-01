<?php
session_start();

// ✅ KUNIN ANG DETALYE NG BAYAD
$fee_per_unit = 450;
$misc_fee = 1250;
$lab_fee = 800;
$total_units = 0;

$all_subjects = [
    1 => ['id'=>1, 'code'=>'IT101', 'name'=>'Introduction to Computing', 'units'=>3],
    2 => ['id'=>2, 'code'=>'CS101', 'name'=>'Computer Programming 1', 'units'=>3],
    3 => ['id'=>3, 'code'=>'GE101', 'name'=>'Understanding the Self', 'units'=>3],
    4 => ['id'=>4, 'code'=>'MATH101', 'name'=>'College Algebra', 'units'=>3],
    5 => ['id'=>5, 'code'=>'IT102', 'name'=>'Data Structures and Algorithms', 'units'=>3],
    6 => ['id'=>6, 'code'=>'GE102', 'name'=>'Readings in Philippine History', 'units'=>3],
    7 => ['id'=>7, 'code'=>'IT103', 'name'=>'Computer Networks', 'units'=>3],
];

if(!empty($_SESSION['enrolled_ids'])){
    foreach($_SESSION['enrolled_ids'] as $id){
        if(isset($all_subjects[$id])) $total_units += $all_subjects[$id]['units'];
    }
}

$total_tuition = $total_units * $fee_per_unit;
$total_amount = $total_tuition + $misc_fee + $lab_fee;

// ✅ TUMANGGAP NG MULA SA FORM
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $method = $_POST['payment_method'] ?? '';
    $ref_num = $_POST['ref_number'] ?? '';
    $payer_name = $_POST['payer_name'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    $date_paid = date('Y-m-d H:i:s');
    $status = "Success";

    // ✅ I-SAVE SA SESSION (PARANG DATABASE)
    if (!isset($_SESSION['payment_records'])) {
        $_SESSION['payment_records'] = [];
    }

    $_SESSION['payment_records'][] = [
        'trans_id' => 'TRN-' . strtoupper(uniqid()),
        'amount' => $total_amount,
        'method' => $method,
        'ref_num' => $ref_num,
        'payer_name' => $payer_name,
        'remarks' => $remarks,
        'date' => $date_paid,
        'status' => $status,
        'semester' => $_SESSION['selected_sem'] ?? '1st Semester'
    ];

    // ✅ PAGKATAPOS MA-SAVE → DEDERETSO AGAD SA PAYMENT HISTORY
    echo "<script>alert('✅ Payment Successful! Redirecting to History...'); window.location.href='payment_history.php';</script>";
    exit;
}
?>