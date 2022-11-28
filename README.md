<h1 align="center">grasch/magento-2-admin-ui</h1>

<div align="center">
  <img src="https://img.shields.io/badge/magento-2.X-brightgreen.svg?logo=magento&longCache=true" alt="Supported Magento Versions" />
  <a href="https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity" target="_blank"><img src="https://img.shields.io/badge/maintained%3F-yes-brightgreen.svg" alt="Maintained - Yes" /></a>
</div>

## Highlight features for Magento 2 Admin UI
- Use ui components in widgets.
- New ui components.

## How to install Magento 2 Admin UI

### ✓ Install via composer (recommend)

Run the following commands in Magento 2 root folder:

```
composer require grasch/module-admin-ui
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```
### ✓ Install via downloading

Download and copy files into `app/code/Grasch/AdminUi` and run the following commands:
```
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

## Usage Documentation
- UI Components
  - [Entities Selector](docs/ui-components/entities-selector/README.md)
- Widgets
  - [Using UI Components Inside Widgets](docs/widgets/using-ui-components/README.md)

## The MIT License
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

