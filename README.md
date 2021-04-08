# Open Plant Names

Collection of data about names governed by the ICBN (International Code of Botanical Nomenclature).

There are many projects around the world that use plant nomenclature for various purposes.

Each of these projects puts work into cleaning up and linking data but these tend to be siloed within projects
and institutions or become dated or lost. This is especially so as much of the data is locked away in SQL databases and so not
easy to work on collaboratively.

The purpose of this repository is to act as a shared authority file containing plant names links to other sources.

## Principles

0. Just plants - focussed on ICBN rules so we don't get all the semantics messed doing double duty with ICZN nomenclature. We will have our basionyms!
0. Links not data - Keeping short strings (like citations) and links to full resources. Never the full data.
0. Open to read AND write. Not only CC-0 but the ability to forked on GitHub and to issue Pull requests for your improvements to be incorporated into the core.
0. Easy to edit.
0. Wikidata as a default authority file for other data (e.g places & things).

## How it works

The data is a series of CSV files. This allows us to do diffs and merges to incorporated changes.

For bigger projects it is assumed that utilities will be used to import/export the data structure to SQL or other non-relational databases for editing but it should be
equally possible to clone the repo, edit the files with a text editor or spreadsheet, and issue a pull request to make a contributions.

For small projects (individual taxonomists) they should be able to just download a file, edit it and submit it by email.

Utilities will be used to unit test different aspects of the data and then acceptance test the whole dataset before it is versioned.

In short: This is an attempt to treat nomenclatural data like open source software.

## Tables

### names

FIXME - description of fields coming





