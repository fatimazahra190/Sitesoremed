# Documentation Sécurité – Projet IAM Laravel

## 1. Authentification & Throttling
- Authentification basée sur Laravel Breeze (ou auth classique)
- Limitation des tentatives de connexion : 5 tentatives/minute (`throttle:5,1` sur la route login)
- Blocages et tentatives échouées journalisés dans la table `security_logs`

## 2. RBAC (Rôles & Permissions)
- Gestion des rôles et permissions via Spatie Laravel Permission
- Middleware `role:` et `permission:` appliqués sur toutes les routes admin sensibles
- Boutons d’action masqués dans les vues si l’utilisateur n’a pas la permission (`@can`)
- Matrice des rôles/permissions documentée dans le seeder

## 3. Journalisation des actions sensibles
- Table `security_logs` : journalise login_failed, login_lockout, user_deleted, role_assigned, admin_panel_access, etc.
- Listener sur les événements Laravel (`Failed`, `Lockout`)
- Journalisation manuelle dans les contrôleurs pour les actions critiques
- Vue d’audit sécurité dans l’admin (permission `view security logs`)

## 4. RGPD
- Consentement explicite requis à l’inscription (case à cocher)
- Date/heure du consentement enregistrée (`consent_accepted_at`)
- Droit à l’oubli : anonymisation du compte utilisateur (nom, email, consentement, rôles, tokens)
- Email chiffré en base (`encrypted` cast Laravel)

## 5. HTTPS/TLS
- Forçage du schéma HTTPS en local (`URL::forceScheme('https')` dans AppServiceProvider)
- Génération d’un certificat auto-signé pour le développement
- Toute tentative d’accès en HTTP est redirigée vers HTTPS

## 6. Bonnes pratiques
- Jamais de comptes de test en production
- Toujours supprimer les comptes de démo avant mise en ligne
- Mettre à jour la fiche de tests à chaque évolution

---

**Pour toute évolution, vérifier la conformité avec cette documentation et la fiche de tests.** 