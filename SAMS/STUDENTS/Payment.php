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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_payment'])) {
    $method = $_POST['payment_method'] ?? '';
    $ref_num = $_POST['ref_number'] ?? '';
    $payer_name = $_POST['payer_name'] ?? '';
    $remarks = $_POST['remarks'] ?? '';
    $date_paid = date('Y-m-d H:i:s');
    $status = "Success";

    // ✅ I-SAVE SA SESSION
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

    // ✅ PAGKATAPOS → DEDERETSO SA HISTORY
    echo "<script>alert('✅ Payment Successful! Redirecting...'); window.location.href='payment_history.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Payment | SAMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #1E2230;
            color: #E2E8F0;
            padding: 15px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ✅ CONTAINER - RESPONSIVE WIDTH */
        .payment-wrapper {
            width: 100%;
            max-width: 500px; /* ✅ Sakto lang, hindi malaki */
            margin: auto;
        }

        .card {
            background-color: #272C3F;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #32384E;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            width: 100%;
        }

        .card-header {
            font-size: 16px;
            font-weight: 600;
            color: #93C5FD;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 1px solid #32384E;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fee-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            flex-wrap: wrap;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            margin-top: 8px;
            font-size: 18px;
            font-weight: 700;
            color: #3B82F6;
            border-top: 1px dashed #32384E;
        }

        .btn-proceed {
            width: 100%;
            padding: 11px;
            background-color: #2563EB;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.2s ease;
        }

        .btn-proceed:hover {
            background-color: #1D4ED8;
        }

        /* ✅ PAYMENT OPTIONS (Nakatago muna) */
        .payment-section {
            display: none;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #32384E;
        }

        .sub-header {
            font-size: 14px;
            color: #94A3B8;
            margin-bottom: 12px;
        }

        .method-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            background-color: #32384E;
            border: 1px solid #475569;
            border-radius: 6px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            width: 100%;
        }

        .method-option:hover, 
        .method-option.active {
            border-color: #3B82F6;
            background-color: rgba(59, 130, 246, 0.1);
        }

        .method-option input {
            accent-color: #3B82F6;
            transform: scale(1.1);
        }

        .form-group {
            margin-top: 12px;
        }

        .form-group label {
            font-size: 13px;
            color: #94A3B8;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input, 
        .form-group textarea {
            width: 100%;
            padding: 9px 12px;
            background-color: #32384E;
            border: 1px solid #475569;
            border-radius: 4px;
            color: #F8FAFC;
            font-size: 14px;
            transition: border 0.2s ease;
        }

        .form-group input:focus, 
        .form-group textarea:focus {
            outline: none;
            border-color: #3B82F6;
        }

        .btn-confirm {
            width: 100%;
            padding: 11px;
            background-color: #10B981;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.2s ease;
        }

        .btn-confirm:hover {
            background-color: #059669;
        }

        /* ✅ EXTRA RESPONSIVE FIX PARA SA MALILIIT NA SCREEN */
        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            .card {
                padding: 16px;
            }
            .fee-row, .total-row {
                font-size: 13px;
            }
            .total-row {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <div class="payment-wrapper">
        <div class="card">
            <!-- ✅ HEADER -->
            <div class="card-header">
                <span>🧾 Assessment & Payment</span>
            </div>

            <!-- ✅ DETALYE NG BAYARAN -->
            <div class="fee-row">
                <span>Total Units (<?php echo $total_units; ?> units)</span>
                <span>₱ <?php echo number_format($total_tuition, 2); ?></span>
            </div>
            <div class="fee-row">
                <span>Miscellaneous Fee</span>
                <span>₱ <?php echo number_format($misc_fee, 2); ?></span>
            </div>
            <div class="fee-row">
                <span>Laboratory Fee</span>
                <span>₱ <?php echo number_format($lab_fee, 2); ?></span>
            </div>

            <div class="total-row">
                <span>TOTAL AMOUNT</span>
                <span>₱ <?php echo number_format($total_amount, 2); ?></span>
            </div>

            <!-- ✅ PROCEED BUTTON -->
            <button class="btn-proceed" id="proceedBtn">PROCEED TO PAYMENT</button>

            <!-- ✅ PAYMENT OPTIONS FORM (Lalabas pag pinindot ang proceed) -->
            <div class="payment-section" id="paymentSection">
                <div class="sub-header">💳 Select Payment Method</div>

                <form method="POST" action="">
                    <!-- GCash -->
                    <div class="method-option" onclick="selectMethod(this, 'gcash')">
                        <input type="radio" name="payment_method" value="GCash" id="gcash" required>
                        <label for="gcash">GCash - Mobile Wallet</label>
                    </div>

                    <!-- Card -->
                    <div class="method-option" onclick="selectMethod(this, 'card')">
                        <input type="radio" name="payment_method" value="Credit/Debit Card" id="card" required>
                        <label for="card">Credit / Debit Card</label>
                    </div>

                    <!-- Over the Counter -->
                    <div class="method-option" onclick="selectMethod(this, 'otc')">
                        <input type="radio" name="payment_method" value="Over The Counter" id="otc" required>
                        <label for="otc">Personal / Cashier Payment</label>
                    </div>

                    <!-- INPUT FIELDS -->
                    <div class="form-group">
                        <label for="ref">Reference / Receipt No.</label>
                        <input type="text" id="ref" name="ref_number" placeholder="Enter transaction or receipt number" required>
                    </div>

                    <div class="form-group">
                        <label for="payer">Name of Payer</label>
                        <input type="text" id="payer" name="payer_name" placeholder="Full name of who paid" required>
                    </div>

                    <div class="form-group">
                        <label for="note">Remarks (Optional)</label>
                        <textarea id="note" name="remarks" rows="2" placeholder="Any additional notes..."></textarea>
                    </div>

                    <!-- CONFIRM BUTTON -->
                    <button type="submit" name="confirm_payment" class="btn-confirm">✅ CONFIRM PAYMENT</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // ✅ SHOW PAYMENT OPTIONS
        document.getElementById('proceedBtn').addEventListener('click', function() {
            document.getElementById('paymentSection').style.display = 'block';
            this.style.display = 'none'; // Hide proceed button
            window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'}); // Scroll down smoothly
        });

        // ✅ HIGHLIGHT SELECTED METHOD
        function selectMethod(element, id) {
            // Remove active class from all
            let items = document.querySelectorAll('.method-option');
            items.forEach(el => el.classList.remove('active'));
            // Add to selected
            element.classList.add('active');
            // Check the radio input
            document.getElementById(id).checked = true;
        }
    </script>

</body>
</html>