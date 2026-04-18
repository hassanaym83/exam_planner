<?php
require_once 'includes/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        try {
            $stmt->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':password' => $hashed,
            ]);
            $success = "Utilisateur ajouté avec succès !";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Cet email existe déjà.";
            } else {
                $error = "Erreur : " . $e->getMessage();
            }
        }
    }
}

require_once 'includes/header.php';
?>

<h1>Ajouter un Utilisateur</h1>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<form method="POST" action="add_user.php" class="form-card">
    <div class="form-group">
        <label for="name">Nom</label>
        <input type="text" id="name" name="name" placeholder="Ex: Hassan Essadik" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Ex: hassan@est.uca.ma" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="Min 6 caractères" required>
    </div>
    <button type="submit" class="btn">Ajouter</button>
</form>

<?php require_once 'includes/footer.php'; ?>