<?php
// Your Bot Token and API URL
$token = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
$apiUrl = "https://api.telegram.org/bot$token/";

// Get the incoming update from Telegram
$update = file_get_contents("php://input");
$updateArray = json_decode($update, true);

// Extract chat ID and message text or callback data
$chat_id = $updateArray['message']['chat']['id'] ?? $updateArray['callback_query']['message']['chat']['id'];
$message = $updateArray['message']['text'] ?? null;
$callback_data = $updateArray['callback_query']['data'] ?? null;

// Function to send a plain text or HTML formatted message
function sendMessage($chat_id, $text, $parse_mode = "HTML") {
    global $apiUrl;
    $url = $apiUrl . "sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($text) . "&parse_mode=" . $parse_mode;
    file_get_contents($url);
}

// Function to send an image with an optional caption
function sendPhoto($chat_id, $photo_url, $caption = "") {
    global $apiUrl;
    $url = $apiUrl . "sendPhoto?chat_id=" . $chat_id . "&photo=" . urlencode($photo_url) . "&caption=" . urlencode($caption);
    file_get_contents($url);
}

// Function to send a message with an inline keyboard
function sendInlineKeyboard($chat_id, $text, $keyboard) {
    global $apiUrl;
    $url = $apiUrl . "sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($text) . "&reply_markup=" . json_encode($keyboard);
    file_get_contents($url);
}

// Define the inline keyboard with Web App button
$keyboard = [
    "inline_keyboard" => [
        [
            ["text" => "Channel", "url" => "https://www.youtube.com/Codekinda"],
            ["text" => "Videos", "callback_data" => "videos"]
        ],
        [
            ["text" => "Contact", "callback_data" => "contact"],
            ["text" => "About Us", "web_app" => ["url" => "https://your_own_url/about-us-webapp.php"]]
        ]
    ]
];

// Handle incoming messages
if ($message == "/start") {
    // Send a welcome message with an inline keyboard

 // Sending the image/logo first
    $photo_url = "https://you_own_url/telemini_advanced/codekinda.png"; // Replace with your logo URL
    sendPhoto($chat_id, $photo_url);

    $text = "Welcome to Codekinda! \nChoose an option:";
    sendInlineKeyboard($chat_id, $text, $keyboard);

} elseif ($message == "/photo") {
    // Send an image when the user sends /photo command
    $photo_url = "https://your_own_url/telemini_advanced/coodekinda.png"; // Replace with your image URL
    sendPhoto($chat_id, $photo_url, "Welcome to Codekinda!");

}

// Handle callback data from inline keyboard buttons
if ($callback_data) {
    if ($callback_data == "videos") {
        sendMessage($chat_id, "Here are the latest videos: <a href='https://www.youtube.com/Codekinda/videos'>Videos</a>");
    } elseif ($callback_data == "contact") {
        sendMessage($chat_id, "Contact us on WhatsApp: <a href='https://wa.me/your-number'>WhatsApp: 07047164748</a>");
    }
}


?>
