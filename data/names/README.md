# names Table 

([back to data docs](../README.md))

The names table is the main data of the repository. Other tables are just utilities to help present the data. In the repository the names table is split between multiple CSV files, about 24k of them. The rows are split on the following basis:

1. Single word names (excluding genera) are exported to the /data/names/higher_names.csv file.
2. Multi word names such as species, subspecies and varieties along with generic names are exported to files based on the first word of the name and these are stored in directories with the first letter of the name e.g. /data/names/R/Rhododendron.csv.
3. Within CSV files names are ordered alphabetically.
4. Each CSV file has a header with the column names.

The upshot of this is that each genus (with its species, subspecies and varieties) will be in an easily found file of its own. All other names (including all subgenera and sections) are lumped together in the higher_names.csv file. All files will be of a manageable size. Even the higher_names.csv file has fewer than 2k rows. If you want to work on a genus you will only need one file (or two if you are doing infrageneric work).

## id

This is a unique identifier for the name row. We need these so we can disambiguate homonyms. It is a [Version 1 UUID](https://en.wikipedia.org/wiki/Universally_unique_identifier#Version_1_(date-time_and_MAC_address)). This means that anyone can create a new row for a new name without having to consult a central authority beforehand and then contribute data to the core.

If you are manually editing a spreadsheet and adding the odd name you could use an online generator ([such as this one](https://www.uuidgenerator.net/)) to get UUIDs. If you are a programmer you will know what to do. Version 1 UUIDs are the default in MySQL. Please don't use other UUID versions.

Note that these are functionally globally unique identifiers but they are not so called PIDs (Persistent Identifiers). Nobody "owns" the names data so there is no way to have an official resolution mechanism. A row may be deleted from this or another version of the repository at a later date so they don't persist in that way. If a row is deleted the UUID will however serve to show which row has gone and in this sense they are immortal. (Remember nothing is forever not even diamonds - cue Shirley Bassey). The dataset as a whole is archived with a DOI in Zenodo so these IDs could be thought of anchors within that resource if you need to cite a row in a publication.

## rank

The rank the name was published at. Currently this is restricted to: 'phylum', 'class', 'order', 'family', 'genus', 'subgenus', "section", "series", 'species', 'subspecies', 'variety' and 'form'. This may change in the future but there is no intention to include all the ranks that have ever been invented. 

## name

All names consist of a single word. If they are below genus level (binomials or trinomials) then the other parts of the name are given in the genus and species fields. **Hybrid symbols are never included. They are used to qualify a name when used in a taxon but do not form part of the name.** 

## genus

If the name is below genus rank then this is the genus name in the binomial or trinomial.

## species

If the name is below the species rank (i.e. subspecies or variety) this is the specific epithet part of the trinomial.

## authors

The authors of the name, abbreviated as per common practice. Abbreviations should be in Wikidata.

## author_ids

A comma separated list of Wikidata Q numbers for authors mentioned in the authors string.

## year

An integer of the year of publication of the name.

## status

This is the *nomenclatural* status of the name and has nothing to do with whether it is accepted as the name of a taxon or not.
Invalid names and simple spelling mistakes are not tracked here. Accepted values are:

- **illegitimate** Add detail in notes field. later_homonym or superfluous should be used if know as these are the main cause of illegitimacy.
- **later_homonym** [ICBN Art. 53](https://www.iapt-taxon.org/icbn/frameset/0058Ch5RejoNa53.htm)
- **superfluous** [ICBN Art. 52](https://www.iapt-taxon.org/icbn/frameset/0057Ch5RejoNa52.htm). Add details in the note field.
- **conserved** [ICBN Art. 14](https://www.iapt-taxon.org/icbn/frameset/0018Ch2Sec4a014.htm). At this stage a note is the notes is sufficient to indicate what name(s) this name is conserved over.
- **sanctioned** [ICBN Art. 15](https://www.iapt-taxon.org/icbn/frameset/0019Ch2Sec4a015.htm). Details in the note field.
- **valid** A name effectively published and in accordance with Art. 32-45 or H.9 (Art. 6.2). This name is *available* for use and may be used as the name of a taxon, or maybe not. 
- **NULL** - The status isn't explicitly stated.

## citation_micro'

This is the short style of citing the publication of a name as used in monographs and floras as well as other nomenclators. It isn't always intelligible to non-botanists or even to botanists who work on a different group of plants.  

## citation_full

This is the full citation of the place of publication as it might be used in a scientific journal following APA style. This field is ideally generated from the citation_id field using a service like that at https://citation.js.org

## citation_id'

The Wikidata ID (Q number) of the name publication citation.

## publication_id'

The Wikidata ID (Q number) of the publication (book or journal) mentioned in the citation_micro. This field is a fallback for if the citation_id field (and therefore the citation_full field) isn't populated. The publication abbreviations used in citation_micro are often very cryptic!

## basionym_id

The UUID of a record that represents the original publication of the name (not in this combination). If this record is know to represent the original publication (not comb. nov., avowed substitute or replacement name) then basionym_id should contain the same UUID as in the record id. It is known to be its own basionym.  

## type_id

A URI for the type specimen. CETAF specimen IDs are recommended.

## note

Edge cases should be dealt with by adding a note until it is recognized there are so many of them the data structure needs to change. Handling every single nomenclatural nuance in the data schema would result in a schema too complex to share.

## ipni_id

**Deprecated:** This is here for matching against other data sources in early days of population. There is not guarantee that there is a 1:1 relationship between records here and records in IPNI so this column may eventually be deleted.

## wfo_id

**Deprecated:** This is here for matching against other data sources in early days of population. There is not guarantee that there is a 1:1 relationship between records here and records in WFO so this column may eventually be deleted.

## gbif_id

**Deprecated:** This is here for matching against other data sources in early days of population. There is not guarantee that there is a 1:1 relationship between records here and records in GBIF so this column may eventually be deleted.

## indexfungorum_id

**Deprecated:** This is here for matching against other data sources in early days of population. There is not guarantee that there is a 1:1 relationship between records here and records in IndexFungorum so this column may eventually be deleted.



