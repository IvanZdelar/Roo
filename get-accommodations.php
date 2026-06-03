<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id']) && !try_remember_login($pdo)) {
    echo json_encode(['success' => false, 'message' => 'Nisi prijavljen.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$cities = $input['cities'] ?? [];
$type = trim($input['type'] ?? '');
$maxPrice = (int)($input['max_price'] ?? 300);

if (!is_array($cities)) $cities = [];

$allowedTypes = ['hotel_motel', 'hostel_apartment'];
if (!in_array($type, $allowedTypes, true)) {
    echo json_encode(['success' => false, 'message' => 'Krivi tip smještaja.']);
    exit;
}

$cityMap = [
    'zagreb' => 'Zagreb',
    'split' => 'Split',
    'rijeka' => 'Rijeka',
    'osijek' => 'Osijek',
    'zadar' => 'Zadar',
    'velika gorica' => 'Velika Gorica',
    'pula' => 'Pula',
    'slavonski brod' => 'Slavonski Brod',
    'karlovac' => 'Karlovac',
    'varaždin' => 'Varaždin',
    'varazdin' => 'Varaždin',
    'Varazdin' => 'Varaždin',
    'šibenik' => 'Šibenik',
    'sibenik' => 'Šibenik',
    'Sibenik' => 'Šibenik',
    'dubrovnik' => 'Dubrovnik',
    'sisak' => 'Sisak',
    'kaštela' => 'Kaštela',
    'kastela' => 'Kaštela',
    'Kastela' => 'Kaštela',
    'samobor' => 'Samobor',
    'bjelovar' => 'Bjelovar',
    'vinkovci' => 'Vinkovci',
    'koprivnica' => 'Koprivnica',
    'čakovec' => 'Čakovec',
    'cakovec' => 'Čakovec',
    'Cakovec' => 'Čakovec',
    'solin' => 'Solin',
    'zaprešić' => 'Zaprešić',
    'zapresic' => 'Zaprešić',
    'Zapresic' => 'Zaprešić',
    'đakovo' => 'Đakovo',
    'dakovo' => 'Đakovo',
    'Dakovo' => 'Đakovo',
    'sinj' => 'Sinj',
    'vukovar' => 'Vukovar',
    'požega' => 'Požega',
    'pozega' => 'Požega',
    'Pozega' => 'Požega',
    'petrinja' => 'Petrinja',
    'kutina' => 'Kutina',
    'virovitica' => 'Virovitica',
    'križevci' => 'Križevci',
    'krizevci' => 'Križevci',
    'Krizevci' => 'Križevci',
    'sveta nedelja' => 'Sveta Nedelja',
    'dugo selo' => 'Dugo Selo',
    'poreč' => 'Poreč',
    'porec' => 'Poreč',
    'Porec' => 'Poreč',
    'metković' => 'Metković',
    'metkovic' => 'Metković',
    'Metkovic' => 'Metković',
    'sveti ivan zelina' => 'Sveti Ivan Zelina',
    'jastrebarsko' => 'Jastrebarsko',
    'našice' => 'Našice',
    'nasice' => 'Našice',
    'Nasice' => 'Našice',
    'omiš' => 'Omiš',
    'omis' => 'Omiš',
    'Omis' => 'Omiš',
    'makarska' => 'Makarska',
    'ivanić-grad' => 'Ivanić-Grad',
    'ivanic-grad' => 'Ivanić-Grad',
    'Ivanic-Grad' => 'Ivanić-Grad',
    'Ivanić grad' => 'Ivanić-Grad',
    'Ivanić grad' => 'Ivanić-Grad',
    'ivanic grad' => 'Ivanić-Grad',
    'Ivanic grad' => 'Ivanić-Grad',
    'vrbovec' => 'Vrbovec',
    'rovinj' => 'Rovinj',
    'ivanec' => 'Ivanec',
    'umag' => 'Umag',
    'trogir' => 'Trogir',
    'ogulin' => 'Ogulin',
    'novi marof' => 'Novi Marof',
    'nova gradiška' => 'Nova Gradiška',
    'nova gradiska' => 'Nova Gradiška',
    'Nova Gradiska' => 'Nova Gradiška',
    'knin' => 'Knin',
    'krapina' => 'Krapina',
    'slatina' => 'Slatina',
    'gospić' => 'Gospić',
    'gospic' => 'Gospić',
    'Gospic' => 'Gospić',
    'novska' => 'Novska',
    'opatija' => 'Opatija',
    'labin' => 'Labin',
    'popovača' => 'Popovača',
    'popovaca' => 'Popovača',
    'Popovaca' => 'Popovača',
    'duga resa' => 'Duga Resa',
    'kastav' => 'Kastav',
    'daruvar' => 'Daruvar',
    'crikvenica' => 'Crikvenica',
    'valpovo' => 'Valpovo',
    'benkovac' => 'Benkovac',
    'imotski' => 'Imotski',
    'županja' => 'Županja',
    'zupanja' => 'Županja',
    'Zupanja' => 'Županja',
    'pleternica' => 'Pleternica',
    'belišće' => 'Belišće',
    'belisce' => 'Belišće',
    'Belisce' => 'Belišće',
    'zabok' => 'Zabok',
    'vodice' => 'Vodice',
    'garešnica' => 'Garešnica',
    'garesnica' => 'Garešnica',
    'Garesnica' => 'Garešnica',
    'ludbreg' => 'Ludbreg',
    'otočac' => 'Otočac',
    'otocac' => 'Otočac',
    'Otocac' => 'Otočac',
    'pazin' => 'Pazin',
    'ploče' => 'Ploče',
    'ploce' => 'Ploče',
    'Ploce' => 'Ploče',
    'trilj' => 'Trilj',
    'donji miholjac' => 'Donji Miholjac',
    'beli manastir' => 'Beli Manastir',
    'bakar' => 'Bakar',
    'mali lošinj' => 'Mali Lošinj',
    'mali losinj' => 'Mali Lošinj',
    'Mali Losinj' => 'Mali Lošinj',
    'đurđevac' => 'Đurđevac',
    'durdevac' => 'Đurđevac',
    'Durdevac' => 'Đurđevac',
    'rab' => 'Rab',
    'glina' => 'Glina',
    'pakrac' => 'Pakrac',
    'prelog' => 'Prelog',
    'lepoglava' => 'Lepoglava',
    'čazma' => 'Čazma',
    'cazma' => 'Čazma',
    'Cazma' => 'Čazma',
    'krk' => 'Krk',
    'drniš' => 'Drniš',
    'drnis' => 'Drniš',
    'Drnis' => 'Drniš',
    'buzet' => 'Buzet',
    'senj' => 'Senj',
    'pregrada' => 'Pregrada',
    'mursko središće' => 'Mursko Središće',
    'mursko sredisce' => 'Mursko Središće',
    'Mursko Sredisce' => 'Mursko Središće',
    'vodnjan' => 'Vodnjan',
    'ozalj' => 'Ozalj',
    'oroslavje' => 'Oroslavje',
    'vrgorac' => 'Vrgorac',
    'biograd na moru' => 'Biograd na Moru',
    'zlatar' => 'Zlatar',
    'varaždinske toplice' => 'Varaždinske Toplice',
    'varazdinske toplice' => 'Varaždinske Toplice',
    'Varazdinske Toplice' => 'Varaždinske Toplice',
    'korčula' => 'Korčula',
    'korcula' => 'Korčula',
    'Korcula' => 'Korčula',
    'grubišno polje' => 'Grubišno Polje',
    'grubisno polje' => 'Grubišno Polje',
    'Grubisno Polje' => 'Grubišno Polje',
    'donja stubica' => 'Donja Stubica',
    'delnice' => 'Delnice',
    'lipik' => 'Lipik',
    'ilok' => 'Ilok',
    'otok' => 'Otok',
    'kutjevo' => 'Kutjevo',
    'orahovica' => 'Orahovica',
    'buje' => 'Buje',
    'novi vinodolski' => 'Novi Vinodolski',
    'supetar' => 'Supetar',
    'slunj' => 'Slunj',
    'kraljevica' => 'Kraljevica',
    'hvar' => 'Hvar',
    'novigrad' => 'Novigrad',
    'vrbovsko' => 'Vrbovsko',
    'novalja' => 'Novalja',
    'obrovac' => 'Obrovac',
    'skradin' => 'Skradin',
    'čabar' => 'Čabar',
    'cabar' => 'Čabar',
    'Cabar' => 'Čabar',
    'pag' => 'Pag',
    'opuzen' => 'Opuzen',
    'stari grad' => 'Stari Grad',
    'cres' => 'Cres',
    'nin' => 'Nin',
    'klanjec' => 'Klanjec',
    'vis' => 'Vis',
    'hrvatska kostajnica' => 'Hrvatska Kostajnica',
    'vrlika' => 'Vrlika',
    'komiža' => 'Komiža',
    'komiza' => 'Komiža',
    'Komiza' => 'Komiža',
];

$cleanCities = [];

foreach ($cities as $city) {
    $key = strtolower(trim($city));
    if (isset($cityMap[$key])) {
        $cleanCities[] = $cityMap[$key];
    }
}

$cleanCities = array_values(array_unique($cleanCities));

if (empty($cleanCities)) {
    echo json_encode(['success' => true, 'accommodations' => []]);
    exit;
}

$placeholders = implode(',', array_fill(0, count($cleanCities), '?'));

$sql = "
    SELECT id, city, name, accommodation_type, max_price_per_night, image, description
    FROM accommodations
    WHERE city IN ($placeholders)
    AND accommodation_type = ?
    AND max_price_per_night <= ?
    ORDER BY city ASC, max_price_per_night ASC, name ASC
";

$params = array_merge($cleanCities, [$type, $maxPrice]);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode([
    'success' => true,
    'accommodations' => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);