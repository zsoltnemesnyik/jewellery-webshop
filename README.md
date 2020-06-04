# Jewelry Webshop

Jewelry Webshop is a hobby project created to practice filtering and sorting objects with jQuery created from JSON.

## Installation

Use NPM to install the packages from **package.json**.

```
npm i (install packages)
npm run start (run live-server and watch scss files)
npm run build:css (build)
```

## Used technologies
HTML, CSS (Flexbox, Grid), SCSS, jQuery (modules wrapped by IIFE-s), NPM scripts to process SCSS, minify CSS, build.

## Usage
Sort the items by clicking on ***Sort by*** which sorts them ascending/descending, or filter them by clicking on ***Filters*** which filters the items based on their material.

You can set both select dropdown to 'default' state by clicking on the first option.

After refreshing the page, the last search is restored, since the items get stored in LocalSession. The script contains a small security check to ensure each displayed data is in the correct format and strip the content from harmful html tags.

Click on **Resize** button to toggle 2 column layout to 1 column layout under tablet view.