<?php
// Script pour réinitialiser la session
session_start();
session_destroy();
session_start();
$_SESSION['parents'] = [];

echo "Session réinitialisée ! <a href='/'>Retour à l'accueil</a>";
