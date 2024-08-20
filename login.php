<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nip']) && isset($_POST['mdp'])) {
        $nip = $_POST['nip'];
        $mdp = $_POST['mdp'];

        $base_url = "https://esco-iut.unc.nc/but/index.php";

        $ch = curl_init();
        $cookie_file = tempnam(sys_get_temp_dir(), 'cookie');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_COOKIEJAR => $cookie_file,
            CURLOPT_COOKIEFILE => $cookie_file,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        function executeCurl($ch) {
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                die('Erreur cURL : ' . curl_error($ch));
            }
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($http_code != 200) {
                die("Erreur HTTP : $http_code");
            }
            return $response;
        }

        curl_setopt($ch, CURLOPT_URL, $base_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['nip' => $nip, 'e1' => 'Etape 1']));
        $response = executeCurl($ch);

        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['mdp' => $mdp, 'e2' => 'Etape 2']));
        $response = executeCurl($ch);

        curl_setopt($ch, CURLOPT_URL, $base_url);
        curl_setopt($ch, CURLOPT_POST, 0);
        $response = executeCurl($ch);

        curl_close($ch);

        $dom = new DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);

        $getUserInfo = function($query) use ($xpath) {
            $result = $xpath->query($query);
            return $result->length ? trim($result->item(0)->textContent) : 'N/A';
        };

        $code_nip = $getUserInfo('//span[contains(text(), "Code nip")]/following-sibling::span');
        $civility = $getUserInfo('//span[contains(text(), "Civilité")]/following-sibling::span');
        $email = $getUserInfo('//span[contains(text(), "Courriel")]/following-sibling::span');

        $modules = [];
        foreach ($xpath->query('//div[@class="ue"]') as $ueNode) {
            $moduleTitle = trim($ueNode->getElementsByTagName('h4')->item(0)->textContent);
            $evaluations = [];
            foreach ($ueNode->getElementsByTagName('p') as $evaluationNode) {
                if ($evaluationNode->getElementsByTagName('span')->length > 0) {
                    $date = trim(explode(':', $evaluationNode->textContent)[0]);
                    $note = trim($evaluationNode->getElementsByTagName('span')->item(0)->textContent);
                    $description = trim(explode('(', $evaluationNode->textContent)[1] ?? 'Pas de description');
                    $evaluations[] = ["date" => $date, "note" => $note, "description" => rtrim($description, ')')];
                }
            }
            $modules[] = ["title" => $moduleTitle, "evaluations" => $evaluations];
        }

        $filiere = 'lp-miaw';
        if (strpos($email, 'mmi-ux') !== false) {
            $filiere = 'mmi-ux';
        } elseif (strpos($email, 'mmi-dweb-di') !== false) {
            $filiere = 'mmi-dweb-di';
        }

        $_SESSION['code_nip'] = $code_nip;
        $_SESSION['civility'] = $civility;
        $_SESSION['email'] = $email;
        $_SESSION['filiere'] = $filiere;
        $_SESSION['modules'] = $modules;

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - LP MIAW</title>
    <link rel="icon" href="img/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="text-center">
        <img src="img/logo.png" alt="Logo" class="logo">
    </div>
    <h1 class="mb-4 text-center">Connexion</h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="nip" class="form-label">Numéro d'étudiant</label>
            <input type="number" class="form-control" id="nip" name="nip" required>
        </div>
        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="mdp" name="mdp" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>
</div>
</body>
</html>
