[![version](https://img.shields.io/badge/Version-none-brightgreen.svg)]()
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![Minimum Node Version](https://img.shields.io/badge/node-%3E%3D%2012-brightgreen.svg)](https://nodejs.org/fr/)
[![Github Actions NodeJS](https://github.com/Horyzone/gcp/workflows/NodeJS/badge.svg)](https://github.com/Horyzone/gcp/actions)
[![Github Actions PHP](https://github.com/Horyzone/gcp/workflows/PHP/badge.svg)](https://github.com/Horyzone/gcp/actions)
[![GitHub license](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/Horyzone/gcp/blob/master/LICENSE)

GCP pour Gestionnaire de Congés Professionnel est une application web pour faciliter l'organisation des jours de congés de vos salariés.

Pour toutes les contributions sur github, veuillez lire le document [CONTRIBUTING.md](https://github.com/Horyzone/gcp/blob/master/.github/CONTRIBUTING.md).

#### Ce projet est toujours en cours de développement...
Vous pouvez accéder au [trello du projet](https://trello.com/b/tJCPkZ4N/gestion-de-cong%C3%A9s-professionnel) pour voir l'avancement.

## Installation

```bash
$ git clone https://github.com/Horyzone/gcp.git
$ cd gcp
$ composer install
$ npm install # Uniquement pour le développement
```
Vérifiez que le fichier `.env` a bien été créé, c'est le fichier de configuration de votre environnement ou vous définissez la connexion à la base de données, l'environnement `dev` ou `prod` et l'activation du cache de twig.

Si le fichier n'a pas été créé, faites-le manuellement en dupliquant le fichier `.env.example`.

N'oubliez pas de vérifier que la configuration de votre environnement de base de données correspond bien.


## Permissions

Autoriser le dossier `storage` à écriture pour votre serveur Web.


## Documentation

Vous avez à disposition la [documentation utilisateur](https://horyzone.github.io/gcp/) pour plus de détails.
