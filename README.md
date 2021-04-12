# Open Plant Names

==NOTE: (April 2021): This is trying out an idea. At this point I'm roughing out how it would all work and looking for feedback.==

A collection of data about names governed by the ICBN (International Code of Botanical Nomenclature).

There are many projects around the world that use plant nomenclature for various purposes. Each of these projects puts work into cleaning up and linking data but these tend to be siloed within projects and institutions or become dated or lost. This is especially so as much of the data is locked away in SQL databases and so not easy to work on collaboratively.

The purpose of this repository is to act as a shared authority file containing plant names and links to other sources.

## Principles

1. **Just plants** - focussed on ICBN rules so we don't get all the semantics messed up doing double duty with ICZN nomenclature. We will have our basionyms!
1. **Just names** - The facts that govern how the rules of nomenclature are applied are universal and can therefore be the subject of a single authority file we all share. Whether a name is the accepted name of a taxon or not is a matter of taxonomic opinion and varies between experts. This repository is just about nomenclature. You won't find what the accepted name for a species here.
1. **All plant names** - Other nomenclators have tended to focus on major groups; vascular plants, bryophytes or fungi. Here we cover the names governed by ICBN (i.e. within the ICBN 'namespace'). This is the only way to track homonyms effectively. We really are taxonomy agnostic.
1. **Links not data** - Keeping short strings (like citations) and links to full resources. Never the full data.
1. **Open to read AND write** - Not only is the data licensed as CC-0 but the ability to fork on GitHub and to issue Pull requests for your improvements to be incorporated into the head make this truly open. Or, if you don't like the way it is being to managed, you are free to fork permanently and start your own project.
1. **Easy to edit** - Excel/OpenOffice for small edits.
1. **Wikidata** as a default authority file for other data (e.g dead people, places & things).
1. Zenodo for archiving of releases with a **DOI**.
1. **Prioritized ranks** - A lot of funky stuff has happened in nomenclature in the last two centuries and it can get really complex. We will therefore prioritize genera, species, subspecies and varieties over other names where needed.
1. **Prioritize names**  We will track legitimate and illegitimate published names but not invalid names, typographic errors or orthographic variants (unless formally conserved). It would be counterproductive to perpetuate and amplify every error ever made. The default is to quietly expunge these from the record and move on.


## How it works

The data is a series of CSV files. This allows us to do diffs and merges to incorporated changes.

For bigger projects it is assumed that utilities will be used to import/export the data structure to SQL or other non-relational databases for editing but it should be equally possible to clone the repo, edit the files with a text editor or spreadsheet, and issue a pull request to make a contributions.

For small projects (individual taxonomists) it should be possible to download a file, edit it and submit it by email to [Roger Hyam](mailto:rhyam@rbge.org.uk)

Utilities will be used to unit test different aspects of the data and then acceptance test the whole dataset before it is versioned. When versioned it will also be submitted to Zenodo for archival storage.

In short: This is an attempt to treat nomenclatural data like open source software.

## Documentation

Key documentation is in README.md files alongside the data and utilities. If we ever write more general guides they'll be in the github wiki.

- [Data Documentation](data/README.md)
- [Utilities Documentation](utils/README.md)

## Acknowledgements

This is a synthetic work that has become possible because so many people have made their work openly available for others to use. It will never be possible to acknowledge them all individually but we hope to list some of them here.

Initial seed data has come from the [World Flora Online](http://www.worldfloraonline.org/downloadData), the GBIF Taxonomy backbone and Index Fungorum [(as hosted by GBIF 2016)](https://www.gbif.org/dataset/bf3db7c9-5e5d-4fd0-bd5b-94539eaf9598).

