<?php
$page = basename(__FILE__, ".php");
require_once "../app/functions/session_manager.php";
require "./navbars/navigation_signed_in.php";

$user_obj = new user($_SESSION["email"]);
$id = $_GET["id"] ?? null;
can_access_page($id);
logout_if_no_session();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://projet03-wserveur.alwaysdata.net/private/css/style.css" rel="stylesheet">
</head>
<body>
    <form id="form" method="post">
    <div id="container">
        <p id="header" style="text-align: center;">Mon profil</p>
        <p>Statut d'employé</p> <?php ?>
        <p>No. d'employé</p> <input name="no-employe" class="form-control" style="width: 100px;" type="number" value="<?php ?>">
        <p>Nom de famille <span id="required">*</span> </p> <input id="nom-famille" name="nom-famille" class="form-control" style="width: 250px; margin-bottom: 0px;" type="text" value="<?php ?>">
        <label hidden="true" id="err_lname" class="invalid-fields">Veuillez entrer votre nom de famille</label>

        <p>Prénom <span id="required">*</span> </p> <input id="prenom" name="prenom"class="form-control" style="width: 250px; margin-bottom: 0px;" type="text" value="<?php ?>">
        <label hidden="true" id="err_fname" class="invalid-fields">Veuillez entrer votre prénom</label>

        <p>Courriel</p> <input name="courriel" disabled class="form-control" style="width: 250px;" type="text" value="<?php ?>">
        <p>Téléphone à la maison</p> <input id="tel-maison" name="tel-maison" class="form-control" style="width: 250px;" type="text" value="<?php ?>">
        <p>Téléphone au travail</p> <input id="tel-travail" name="tel-travail" class="form-control" style="width: 250px;" type="text" value="<?php ?>">
        <p>Téléphone cellulaire</p> <input id="tel-cell=" name="tel-cell" class="form-control" style="width: 250px;" type="text" value="<?php ?>">
        <p id="legend">Légende: <span id="required">* requis</span></p>
        <input style="margin-top: 10px;" class="btn btn-primary" type="button" value="Enregistrer" onclick="validateForm()">
    </div>
    </form>
    <script>
        let canSendForm = true;
        function validateForm() {
            // a mettre statut empl qui sera une liste déroulante
            let nom_famille = document.getElementById("nom-famille");
            let prenom = document.getElementById("prenom");
            let err_lname = document.getElementById("err_lname");
            let err_fname = document.getElementById("err_fname");

            err_lname.hidden = nom_famille.value.trim() != ""
            err_fname.hidden = prenom.value.trim() != ""
            canSendForm = nom_famille.value.trim() !== "" && prenom.value.trim() !== ""
            if (canSendForm) {sendForm();}
        }
        function sendForm() {
            // a mettre statut empl
            document.getElementById("form").submit()
        }
    </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $no_empl = $_POST["no-employe"] ?? "";
    $nom_famille = $_POST["nom-famille"] ?? "";
    $prenom = $_POST["prenom"] ?? "";
    $tel_maison = $_POST["tel-maison"] ?? "";
    $tel_travail = $_POST["tel-travail"] ?? "";
    $tel_cell = $_POST["tel-cell"] ?? "";

    $user_obj->set_no_empl($no_empl);
    $user_obj->set_nom($nom_famille);
    $user_obj->set_prenom($prenom);
    $user_obj->set_tel_maison($tel_maison);
    $user_obj->set_tel_travail($tel_travail);
    $user_obj->set_tel_cellulaire($tel_cell);

    header("Location: index.php");
}
