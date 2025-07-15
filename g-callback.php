<?php

require_once "./Database.php";
session_start();

if ($_GET['code']) {
  $code = $_GET['code'];
  $postFields = [
    "code" => $code,
    "client_id" => GOOGLE_CLIENT_ID,
    "client_secret" => GOOGLE_CLIENT_SECRET,
    "redirect_uri" => GOOGLE_REDIRECT_URI,
    "grant_type" => "authorization_code"
  ];

  // Initialize cURL
  $ch = curl_init("https://oauth2.googleapis.com/token");

  // Set cURL options
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
  ]);

  // Execute the request
  $response = curl_exec($ch);

  // Check for cURL errors
  if (curl_errno($ch)) {
    header("location: login.php");
  } else {
    // json decode 
    $result = json_decode($response, true);
    $id_token = $result['id_token'];

    // result second part get 
    $parts = explode('.', $id_token);

    // decode into base64
    $payload = base64_decode($parts[1]); // Fix base64 URL format
    $data = json_decode($payload, true);


    $query_search_res = $DB->read("users", [
      "where" => [
        "email" => [
          "=" => $data['email']
        ]
      ]
    ]);


    if (mysqli_num_rows($query_search_res) > 0) {
      $exsting = mysqli_fetch_assoc($query_search_res);
      if ($exsting['is_verified'] == 0) {
        header("location: login.php?error=Contect to Admin for the approved to account.");
        exit();
      }
      $_SESSION['user_id'] = $exsting['id'];
      $_SESSION['email'] = $exsting['email'];
      $_SESSION['role'] = $exsting['role'];
      if ($exsting['role'] === 'admin') {
        header("Location: ./admin");
      } else {
        header("Location: ./staff");
      }
    } else {
      $insert_res = $DB->create("users", ["email", "name", "profile_picture", "auth_provider"], [$data['email'], $data['name'], $data['picture'], "google"]);
      header("location: login.php?error=Contect to Admin for the approved to account.");
        exit();
    }
  }


  // Close cURL
  curl_close($ch);
}
