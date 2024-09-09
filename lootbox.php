<?php
include 'config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$lootbox_id = $_POST['lootbox_id'];

// Pobierz przedmioty ze skrzynki
$items = [];
$result = $conn->query("SELECT * FROM items WHERE lootbox_id = $lootbox_id");
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Wylosuj przedmiot
$random_item = $items[array_rand($items)];
$item_id = $random_item['id'];

// Przypisz przedmiot do użytkownika
$stmt = $conn->prepare("INSERT INTO user_items (user_id, item_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $item_id);
$stmt->execute();
$stmt->close();

echo "You received: " . $random_item['name'];
?>
