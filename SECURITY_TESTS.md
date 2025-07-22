# Fiche de tests sécurité & RGPD – Projet IAM Laravel

## Authentification & Throttle
- [ ] Connexion avec bons identifiants → accès OK
- [ ] 5 tentatives échouées → accès bloqué, log créé
- [ ] Message d’erreur affiché à l’utilisateur
- [ ] Tentatives échouées et blocages visibles dans les security logs

## RBAC (Rôles & Permissions)
- [ ] Un “user” ne peut pas accéder à /admin
- [ ] Un “manager” ne peut pas supprimer un utilisateur
- [ ] Un “admin” peut tout faire
- [ ] Les boutons d’action (Add, Edit, Delete) sont masqués si l’utilisateur n’a pas la permission
- [ ] Les routes admin sont protégées par le middleware permission

## RGPD
- [ ] Consentement explicite requis à l’inscription (case à cocher)
- [ ] Date/heure du consentement enregistrée (champ consent_accepted_at)
- [ ] Droit à l’oubli : suppression/anonymisation du compte OK
- [ ] Email chiffré en base (illisible dans la BDD)

## Logs sécurité
- [ ] Accès au dashboard admin logué (action admin_panel_access)
- [ ] Suppression/anonymisation utilisateur loguée (action user_deleted)
- [ ] Changement de rôle logué (action role_assigned)
- [ ] Tentatives de connexion échouées loguées (action login_failed)
- [ ] Blocages (lockout) logués (action login_lockout)
- [ ] Logs consultables dans l’admin (vue security-logs)

## HTTPS
- [ ] Toute tentative d’accès en HTTP est redirigée vers HTTPS
- [ ] Certificat auto-signé fonctionne en local (pas d’avertissement après ajout à la liste de confiance)

---

**À compléter à chaque évolution du projet ou ajout de fonctionnalité sensible.** 