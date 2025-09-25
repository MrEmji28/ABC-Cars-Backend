<?php

// Simple API test script
$baseUrl = 'http://localhost:8000';

// Test login
$loginData = [
    'email' => 'seller@test.com',
    'password' => 'password'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$loginResult = json_decode($response, true);

echo "Login response: " . $response . "\n";
if (isset($loginResult['token'])) {
    echo "✅ Login successful\n";
    $token = $loginResult['token'];
    
    // Test get cars
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/cars');
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, null);
    curl_setopt($ch, CURLOPT_HTTPHEADER, []);
    
    $carsResponse = curl_exec($ch);
    $cars = json_decode($carsResponse, true);
    
    if (is_array($cars) && count($cars) > 0) {
        echo "✅ Cars retrieved: " . count($cars) . " cars found\n";
    } else {
        echo "❌ No cars found\n";
    }
    
    // Test profile
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/profile');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    
    $profileResponse = curl_exec($ch);
    $profile = json_decode($profileResponse, true);
    
    if (isset($profile['name'])) {
        echo "✅ Profile retrieved: " . $profile['name'] . "\n";
    } else {
        echo "❌ Profile retrieval failed\n";
    }
    
} else {
    echo "❌ Login failed\n";
    echo "Response: " . print_r($loginResult, true) . "\n";
}

curl_close($ch);
echo "\nTest completed. Start server with: php artisan serve\n";