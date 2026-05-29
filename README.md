# Test Technique — Outil de devis / chiffrage (atelier carrosserie)

## Contexte

Tu rejoins l'équipe qui développe **RepairSoft**, un logiciel de gestion d'atelier
carrosserie. On te confie une vraie problématique métier : construire l'**outil de
devis / chiffrage**.

Une première version de l'API de gestion des **ordres de réparation** existe déjà.
Elle a été écrite vite, sans soin particulier. À partir de cette base, ton travail est de :

1. **Construire la feature de devis** (le cœur du test) ;
2. **Améliorer l'existant** que tu touches au passage.

> Le sujet est **volontairement ouvert**. Il existe des dizaines d'outils de devis qui
> fonctionnent tous, différemment. On s'intéresse davantage à **tes choix** —
> modélisation, emplacement des règles métier, architecture, qualité — qu'à une
> solution unique attendue.

> 📖 **À lire avant de coder : [docs/METIER-CARROSSERIE.md](docs/METIER-CARROSSERIE.md)**
> — tout le métier du devis carrosserie y est expliqué (lignes pièce / main d'œuvre,
> taux horaires, remise, TVA, totaux, cycle de vie d'un ordre).

---

## Stack technique

- PHP 8.2 / Symfony 6.4
- Doctrine ORM (PostgreSQL)
- Vue 3 / TypeScript (Webpack Encore)
- Docker

---

## Installation

```bash
# 1. Configurer les variables d'environnement
echo 'DATABASE_URL="postgresql://main:main@db:5432/main?serverVersion=16&charset=utf8"' > .env.local

# 2. Démarrer les containers
docker compose up -d --build

# 3. Installer les dépendances PHP
docker compose exec php-rc-test composer install

# 4. Exécuter les migrations
docker compose exec php-rc-test bin/console doctrine:migrations:migrate --no-interaction

# 5. Charger les données de test (clients, catalogue de pièces, ordres d'exemple)
docker compose exec php-rc-test bin/console doctrine:fixtures:load --no-interaction

# 6. Installer les dépendances JS et builder les assets
docker compose exec php-rc-test yarn install
docker compose exec php-rc-test yarn dev
```

L'application est accessible sur : **http://localhost:8080**

---

## Ce qu'on te fournit

| Fourni | Détail |
|--------|--------|
| `RepairOrder` + `Customer` | Entités et une **API REST** qui gère les ordres de réparation. |
| `Part` (catalogue) | Un **catalogue de pièces** : référence, libellé, prix de vente HT, avec fixtures réalistes. |
| Front Vue 3 / TS | Une page qui liste les ordres de réparation et permet quelques actions. |

**Ce qui n'est PAS fourni — c'est à toi de le concevoir :** le **devis** lui-même.
Les lignes (pièce issue du catalogue, main d'œuvre avec type + temps + taux horaire),
la remise, la TVA et les totaux n'existent pas encore. À toi de les modéliser et de
les implémenter comme tu le juges pertinent.

---

## Le travail demandé

### Partie 1 — Construire le devis _(cœur du test)_

À partir du catalogue de pièces et des ordres existants, conçois et implémente le devis
décrit dans la [doc métier](docs/METIER-CARROSSERIE.md).

**Au minimum, on aimerait :**

- [ ] un devis rattaché à un ordre de réparation ;
- [ ] des lignes **pièce** (issues du catalogue, avec quantité) ;
- [ ] des lignes **main d'œuvre** (type de travail, temps, taux horaire) ;
- [ ] le calcul des **totaux** (HT, TVA, TTC) ;

Au-delà de ce socle, tu es **libre** sur le périmètre et le niveau de richesse (remise,
TVA par ligne, etc.).

### Partie 2 — Améliorer l'existant

Le code fourni a été écrit vite et reste **perfectible**. En le parcourant, repère ce
qui te paraît fragile ou mal placé et améliore ce que tu touches — sans tout réécrire.
Montre ton jugement sur ce qui compte vraiment.

### Partie 3 — Tests

Écris quelques **tests unitaires** sur la logique métier que tu juges critique. 
L'important n'est pas la quantité mais qu'ils ciblent la logique au bon endroit.

### Partie 4 — Frontend

Donne à voir le devis côté interface, à ton niveau de confort en Vue / TypeScript.

---

## Ce qu'on regarde _(nos valeurs, pas une recette)_

On **n'impose aucune architecture ni arborescence**. On regarde comment tu raisonnes :

- **Modélisation du domaine** — les concepts métier sont-ils clairs et au bon endroit ?
- **Emplacement des règles métier** — sont-elles testables sans la base ni le framework ?
- **Séparation des responsabilités** — chaque couche a-t-elle un rôle clair ?
- **Qualité** — typage, nommage, absence de duplication.
- **Tests** — pertinents et ciblés.

Il n'y a **pas de bonne réponse unique**. On veut comprendre **comment tu conçois** une
feature métier sur une base imparfaite.

---

## Commandes utiles

```bash
# Tests
docker compose exec php-rc-test composer run tests
docker compose exec php-rc-test composer run tests-unit

# Assets frontend
docker compose exec php-rc-test yarn dev
docker compose exec php-rc-test yarn type-check 

# Console Symfony
docker compose exec php-rc-test bin/console <commande>
```

---

## Durée & rendu

- Donne-toi **~2h**. Mieux vaut un périmètre **réduit, propre et justifié** qu'un
  large périmètre bâclé.
- Si besoin, note tes **choix et compromis** (dans le code, ou un court `NOTES.md`)

Bonne chance !
