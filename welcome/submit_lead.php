<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $goal = htmlspecialchars($_POST['goal']);
    $buyer_type = htmlspecialchars($_POST['buyer_type']);
    $loan_situation = htmlspecialchars($_POST['loan_situation']);
    $purchase_type = htmlspecialchars($_POST['purchase_type']);
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $budget = htmlspecialchars($_POST['budget']);
    $notes = htmlspecialchars($_POST['notes']);
    
    // Format the email message
    $to = "vincent@realandproper.co.za"; // REPLACE WITH YOUR EMAIL
    $subject = "New Real Estate Lead: $name";
    
    $message = "New Real Estate Lead Submission\n\n";
    $message .= "Name: $name\n";
    $message .= "Email: $email\n";
    $message .= "Phone: $phone\n";
    $message .= "Interested in: " . formatOption($goal) . "\n";
    
    if ($goal === 'buy') {
        $message .= "Buyer Type: " . formatOption($buyer_type) . "\n";
        
        if ($buyer_type === 'first-time') {
            $message .= "Loan Situation: " . formatOption($loan_situation) . "\n";
        } else if ($buyer_type === 'homeowner') {
            $message .= "Purchase Type: " . formatOption($purchase_type) . "\n";
        }
    }
    
    $message .= "Budget Range: " . ($budget ?: 'Not specified') . "\n";
    $message .= "Additional Notes: " . ($notes ?: 'None') . "\n\n";
    $message .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";
    $message .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $message .= "User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n";
    
    // Email headers
    $headers = "From: PrimeRealty Lead Form <noreply@realandproper.co.za>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    if (mail($to, $subject, $message, $headers)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "Invalid request method.";
}

function formatOption($option) {
    if (!$option) return 'N/A';
    
    $optionMap = [
        'first-time' => 'First Time Buyer',
        'homeowner' => 'Current Homeowner',
        'have-loan' => 'Has Loan Pre-approval',
        'need-loan' => 'Needs Home Loan',
        'rent-to-buy' => 'Rent to Buy',
        'buy-property' => 'Buy Property',
        'rent' => 'Rent',
        'buy' => 'Buy'
    ];
    
    return $optionMap[$option] ?? $option;
}
?>