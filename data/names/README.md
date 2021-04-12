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





