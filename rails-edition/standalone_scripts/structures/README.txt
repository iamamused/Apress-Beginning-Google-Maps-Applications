These all expect the source files to be in the same directory as the scripts. 

- RA.dat, CO.dat, and EN.dat are the original source files
- import_fcc_data.rb takes the three source files and translates them into mysqlImport ready files: fcc_locations.dat, fcc_owners.dat, and fcc_structures.dat
- assemble.rb assembles the three source files into towers.dat, which is also in a MySqlImport-friendly format

