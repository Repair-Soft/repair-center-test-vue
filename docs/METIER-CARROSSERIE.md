# Le devis en carrosserie — contexte métier

> Ce document décrit le **métier** que la feature doit servir. Il n'impose **aucune**
> solution technique : libre à toi de décider comment modéliser et calculer tout ça.
> Considère-le comme la connaissance qu'un collègue de l'atelier te transmettrait
> avant de te lancer.

---

## 1. Le parcours, en une phrase

Un client dépose son véhicule à l'atelier, on ouvre un **Ordre de Réparation (OR)**,
et avant de réparer on établit un **devis** qui chiffre les pièces et la main d'œuvre.
Le client valide (ou non) ce devis, puis la réparation suit son cours.

```
Client ─┐
        ├─► Ordre de Réparation (OR) ─► Devis ─► Lignes (pièces + main d'œuvre) ─► Totaux
Véhicule┘                                                              │
                                                                       └─► HT / Remise / TVA / TTC
```

> **Note de périmètre** : le *véhicule* fait partie du métier (on en parle plus bas
> pour que tu comprennes le contexte) mais il **n'est pas fourni** dans le code de
> départ. À toi de voir si tu l'introduis ou non.

---

## 2. Anatomie d'un devis

Un devis, c'est trois choses :

1. **Un en-tête** : une référence, une date, le client (et le véhicule concerné).
2. **Des lignes** : le cœur du devis.
3. **Des totaux** : calculés à partir des lignes.

Chaque **ligne** est de l'un de ces deux types :

### a) Ligne « pièce »

C'est une pièce détachée qu'on vend au client. Elle provient du **catalogue de pièces**
de l'atelier (fourni en base, voir `Part`). Une pièce a :

| Donnée            | Exemple                  |
|-------------------|--------------------------|
| Référence         | `PRC-AV-001`             |
| Libellé           | `Pare-chocs avant`       |
| Prix de vente HT  | `180,00 €`               |

Sur le devis, la ligne pièce ajoute une **quantité** :

```
Montant ligne HT = prix de vente unitaire HT × quantité
```

### b) Ligne « main d'œuvre » (MO)

C'est le **temps de travail** facturé. Une ligne de MO a :

| Donnée            | Exemple                  |
|-------------------|--------------------------|
| Type de travail   | `Tôlerie`                |
| Temps             | `2,5 h`                  |
| Taux horaire      | `65,00 €/h`              |

```
Montant ligne HT = temps (heures) × taux horaire HT
```

**Point essentiel** : le taux horaire **dépend du type de travail** (voir §3).
---

## 3. Les taux horaires de main d'œuvre (spécificité carrosserie)

Un atelier de carrosserie facture la main d'œuvre à des **taux différents selon le
métier mobilisé**. C'est l'une des règles les plus structurantes du devis.

| Type de MO   | Description                                   | Taux indicatif |
|--------------|-----------------------------------------------|----------------|
| **Tôlerie**  | Débosselage, redressage, remplacement d'éléments de carrosserie | ~65 €/h |
| **Peinture** | Préparation, apprêt, mise en peinture, vernis | ~70 €/h        |
| **Mécanique**| Dépose/repose mécanique, organes             | ~60 €/h        |

> Les valeurs sont **indicatives** : elles varient selon l'atelier, la région et le
> type de véhicule. L'enjeu de modélisation est : *où vivent ces taux, et comment
> une ligne de MO connaît-elle le sien ?*

---

## 4. Remise et TVA

- **Remise** : on peut accorder une remise, généralement exprimée en **pourcentage**.
  Selon les ateliers elle s'applique **par ligne** et/ou **globalement** sur le devis.
  Pour ce test, une remise **par ligne** suffit (tu peux faire plus si tu veux).
- **TVA** : en France le taux normal est de **20 %**. C'est le taux par défaut sur la
  carrosserie. (Certains cas relèvent d'autres taux, on les ignore ici.)

Ordre de calcul sur une ligne :

```
Montant brut HT   = base × quantité|temps
Montant remisé HT = Montant brut HT × (1 − remise%)
Montant TVA       = Montant remisé HT × TVA%
Montant TTC       = Montant remisé HT + Montant TVA
```

---

## 5. Les totaux du devis

À partir des lignes, le devis affiche au minimum :

| Total          | Définition                                              |
|----------------|---------------------------------------------------------|
| **Total HT**   | Somme des montants remisés HT de toutes les lignes      |
| **Total remise**| Somme des remises accordées                            |
| **Total TVA**  | Somme de la TVA de toutes les lignes                    |
| **Total TTC**  | Total HT + Total TVA                                    |

> **Attention à l'argent.** Le code de départ stocke les montants en `float`
> (`double precision`). Les flottants accumulent des erreurs d'arrondi : `0.1 + 0.2`
> ne fait pas `0.3`. Sur un devis qui additionne des dizaines de lignes puis applique
> une TVA, ça se voit. Réfléchis à **comment représenter une somme d'argent** et à
> **où arrondir** (à la ligne ? au total ?). C'est un vrai choix de conception, pas un
> détail.

---

## 6. Cycle de vie de l'Ordre de Réparation

L'OR existant porte un **statut**. Le cycle de vie typique :

```
PENDING ──► IN_PROGRESS ──► WAITING_PARTS ──► DONE ──► DELIVERED
   │              │                                        
   └──────────────┴───────────────► CANCELLED              
```

Règles métier déjà présentes (et à fiabiliser) :

- Un OR **annulé** (`CANCELLED`) ne peut plus changer d'état.
- Un OR **livré** (`DELIVERED`) ne peut plus qu'être annulé.
- On n'ajoute pas de pièce/MO à un OR livré ou annulé.

> Ces règles décrivent une *machine à états*. Jette un œil à la façon dont elles sont
> implémentées dans le code existant.

---

## 7. Glossaire

| Terme              | Signification |
|--------------------|---------------|
| **OR**             | Ordre de Réparation : le dossier d'intervention sur un véhicule. |
| **Devis**          | Chiffrage prévisionnel des pièces + MO, soumis au client avant réparation. |
| **Ligne**          | Un poste du devis : une pièce *ou* de la main d'œuvre. |
| **MO**             | Main d'œuvre : le temps de travail facturé. |
| **Tôlerie**        | Travail sur la structure/carrosserie (redressage, remplacement). |
| **Peinture**       | Préparation et application de la peinture et du vernis. |
| **Ingrédients / consommables** | Produits consommés en peinture (apprêt, mastic, abrasifs) — souvent vendus comme des pièces. |
| **Barème de temps**| Temps standard alloué à une opération (ici, on reste en heures). |
| **HT / TTC**       | Hors Taxes / Toutes Taxes Comprises. |
| **PV HT**          | Prix de Vente Hors Taxes. |
