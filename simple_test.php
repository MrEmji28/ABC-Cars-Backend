<?php

// Simple test without Sanctum middleware
$baseUrl = 'http://localhost:8000';

echo "Testing API endpoints...\n\n";

// Test 1: Get cars (public endpoint)
echo "1. Testing GET /api/cars (public):\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/cars');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode == 200) {
    $cars = json_decode($response, true);
    echo "✅ Success: Found " . count($cars) . " cars\n";
    if (count($cars) > 0) {
        echo "   First car: " . $cars[0]['title'] . "\n";
    }
} else {
    echo "❌ Failed with HTTP code: $httpCode\n";
    echo "   Response: " . substr($response, 0, 200) . "...\n";
}

echo "\n2. Testing POST /register (create new user):\n";
$userData = [
    'name' => 'Test User ' . time(),
    'email' => 'test' . time() . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

curl_setopt($ch, CURLOPT_URL, $baseUrl . '/register');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode == 201 || $httpCode == 200) {
    $result = json_decode($response, true);
    echo "✅ Registration successful\n";
    if (isset($result['token'])) {
        echo "   Token received: " . substr($result['token'], 0, 20) . "...\n";
        $token = $result['token'];
        
        // Test authenticated endpoint
        echo "\n3. Testing GET /api/profile (authenticated):\n";
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/api/profile');
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, null);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($httpCode == 200) {
            $profile = json_decode($response, true);
            echo "✅ Profile retrieved: " . $profile['name'] . "\n";
        } else {
            echo "❌ Profile failed with HTTP code: $httpCode\n";
        }
    }
} else {
    echo "❌ Registration failed with HTTP code: $httpCode\n";
    echo "   Response: " . substr($response, 0, 200) . "...\n";
}

curl_close($ch);
echo "\nTest completed!\n";