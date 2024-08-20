<?php
session_start();

if (!isset($_SESSION['code_nip'])) {
    header("Location: index.php");
    exit;
}

$code_nip = $_SESSION['code_nip'];
$civility = $_SESSION['civility'];
$email = $_SESSION['email'];
$filiere = $_SESSION['filiere'];
$modules = $_SESSION['modules']; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - LP MIAW</title>
    <link rel="icon" href="img/favicon.png" type="image/png">
<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-center align-items-center mb-4">
        <img src="img/logo.png" alt="Logo" class="logo">
    </div>
    <form method="post" action="logout.php" class="d-inline">
        <button type="submit" name="deco" class="btn btn-secondary">Se déconnecter</button>
    </form><br><br><br>
    <h1 class="mb-4">Mon Emploi du Temps</h1>
    <div class="mb-4">
        <ul class="nav nav-tabs justify-content-center" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php echo ($filiere == 'lp-miaw') ? 'active' : ''; ?>" id="tab-lp-miaw" href="#lp-miaw" data-bs-toggle="tab" role="tab">LP MIAW</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($filiere == 'mmi-ux') ? 'active' : ''; ?>" id="tab-mmi-ux" href="#mmi-ux" data-bs-toggle="tab" role="tab">MMI UX</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($filiere == 'mmi-dweb-di') ? 'active' : ''; ?>" id="tab-mmi-dweb-di" href="#mmi-dweb-di" data-bs-toggle="tab" role="tab">MMI DWEB DI</a>
            </li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade <?php echo ($filiere == 'lp-miaw') ? 'show active' : ''; ?>" id="lp-miaw" role="tabpanel">
            <iframe src="proxy.php?url=https%3A%2F%2Fapplis.univ-nc.nc%2Fgedfs%2Fedtweb2%2F2407260950.0%2FPDF_EDT_17725_31_2024.pdf" width="100%" height="600px" frameborder="0"></iframe>
            <p>Si le fichier ne s'affiche pas, vous pouvez le <a href="proxy.php?url=https%3A%2F%2Fapplis.univ-nc.nc%2Fgedfs%2Fedtweb2%2F2407251552.0%2FPDF_EDT_17820_31_2024.pdf">télécharger ici</a>.</p>
        </div>
        <div class="tab-pane fade <?php echo ($filiere == 'mmi-ux') ? 'show active' : ''; ?>" id="mmi-ux" role="tabpanel">
            <iframe src="proxy.php?url=https%3A%2F%2Fapplis.univ-nc.nc%2Fgedfs%2Fedtweb2%2F2407251552.0%2FPDF_EDT_17820_31_2024.pdf" width="100%" height="600px" frameborder="0"></iframe>
            <p>Si le fichier ne s'affiche pas, vous pouvez le <a href="proxy.php?url=https%3A%2F%2Fapplis.univ-nc.nc%2Fgedfs%2Fedtweb2%2F2407260902.0%2FPDF_EDT_17823_31_2024.pdf">télécharger ici</a>.</p>
        </div>
        <div class="tab-pane fade <?php echo ($filiere == 'mmi-dweb-di') ? 'show active' : ''; ?>" id="mmi-dweb-di" role="tabpanel">
            <iframe src="proxy.php?url=https%3A%2F%2Fapplis.univ-nc.nc%2Fgedfs%2Fedtweb2%2F2407260902.0%2FPDF_EDT_17823_31_2024.pdf" width="100%" height="600px" frameborder="0"></iframe>
            <p>Si le fichier ne s'affiche pas, vous pouvez le <a href="proxy.php?url=https%3A%2F%2Fapplis.univ-nc.nc%2Fgedfs%2Fedtweb2%2F2407260950.0%2FPDF_EDT_17725_31_2024.pdf">télécharger ici</a>.</p>
        </div>
    </div>
    <h1 class="mb-4">Mes Informations</h1>
    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Code NIP:</th>
                        <td><?php echo htmlspecialchars($code_nip); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Civilité:</th>
                        <td><?php echo htmlspecialchars($civility); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Courriel:</th>
                        <td><?php echo htmlspecialchars($email); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <h1 class="mb-4">Mes Notes</h1>
    <div class="row">
        <?php if (!empty($modules)): ?>
            <?php foreach ($modules as $module): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo md5($module['title']); ?>" aria-expanded="true" aria-controls="collapse-<?php echo md5($module['title']); ?>">
                                <?php echo htmlspecialchars($module['title']); ?>
                            </button>
                        </div>
                        <div id="collapse-<?php echo md5($module['title']); ?>" class="collapse show">
                            <div class="card-body">
                                <?php if (!empty($module['evaluations'])): ?>
                                    <?php foreach ($module['evaluations'] as $evaluation): ?>
                                        <p class="note"><?php echo htmlspecialchars($evaluation['date']); ?> : <?php echo htmlspecialchars($evaluation['note']); ?> - <?php echo htmlspecialchars($evaluation['description']); ?></p>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="note">Pas de notes</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune note disponible.</p>
        <?php endif; ?>
    </div>
    <div class="alert alert-info" role="alert" id="average-alert">Moyenne des notes : N/A</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function calculateAverage() {
        let notes = document.querySelectorAll('.note');
        let total = 0;
        let count = 0;
        notes.forEach(note => {
            let noteParts = note.textContent.split(':');
            if (noteParts.length > 1) {
                let noteValue = noteParts[1].match(/(\d+(\.\d+)?)/);
                if (noteValue) {
                    total += parseFloat(noteValue[0]);
                    count++;
                }
            }
        });
        return count ? (total / count).toFixed(2) : 'N/A';
    }

    let averageAlert = document.getElementById('average-alert');
    if (averageAlert) {
        let average = calculateAverage();
        averageAlert.textContent = Moyenne des notes : ${average};
    }
});
</script>
</body>
</html>