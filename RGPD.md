# Documentation RGPD – Projet IAM Laravel

## 1. Consentement explicite
- Case à cocher obligatoire à l’inscription
- Date/heure du consentement enregistrée (`consent_accepted_at`)
- Consentement stocké dans la table `users`

## 2. Droit à l’oubli
- Suppression/anonymisation du compte utilisateur sur demande
- Nom remplacé par “Utilisateur supprimé”
- Email rendu inutilisable
- Consentement révoqué
- Rôles et tokens supprimés

## 3. Chiffrement des données sensibles
- Email chiffré en base (`encrypted` cast Laravel)
- (Optionnel) Autres champs sensibles à chiffrer (téléphone, adresse…)

## 4. Journalisation RGPD
- Toutes les suppressions/anonymisations sont loguées dans la table `security_logs` (action `user_deleted`)
- Consentement journalisé à l’inscription

## 5. Export et portabilité
- (À implémenter) Fonction d’export des données personnelles sur demande utilisateur

## 6. Documentation utilisateur
- L’utilisateur est informé de ses droits RGPD à l’inscription et dans son espace profil

---

**À compléter à chaque évolution du projet ou ajout de fonctionnalité sensible.** 