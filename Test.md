# Redaction des Tests

### Test pour "créé un compte d'ado"

1. Nom > 3 string, acceptee
1. Nom = 3 string, acceptee
1. Nom < 3 string, refusée
1. Depot de base > 0, acceptee
1. Depot de base = 0, acceptee
1. Depot de base < 0, refusee

### Tests pour "Enregistrer une dépense"

1. Dépense < Solde → acceptée, solde mis à jour
2. Dépense > Solde → refusée, solde inchangé
3. Dépense = Solde → acceptée, solde = 0
4. Dépense <= 0 → refusée (valeur invalide)
   (String)
5. Description vide/null → acceptée (description optionnelle)
6. Description valide → acceptée

### Tests pour "déposer de l'argent"

1. Dépôt > 0 → accepté, solde augmenté du montant
2. Dépôt = 0 → refusé, solde non mis a jour
3. Dépôt < 0 → refusé, solde non mis a jour

### Test pour "faire allocation hebdomadaire automatique"

1. Allocation > 0, accepté, solde augmenté du montant
2. Allocation = 0, refusé, solde non mis a jour
3. Allocation < 0, refusé, solde non mis a jour

### Tests pour "Appliquer l'allocation hebdomadaire"

1. 7 jours écoulés + allocation définie → solde augmenté
2. < 7 jours écoulés → allocation non appliquée
3. Aucune allocation définie → rien ne se passe
4. Allocation déjà appliquée cette semaine → ne se réapplique pas
