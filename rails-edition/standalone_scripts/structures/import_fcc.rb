require 'parsedate'

# This script takes three separate files -- RA.dat, EN.dat, and CO.dat -- and 
# processes each one to prepare it to be imported via MySqlImport.
# In reality, you will want to assemble the three files before importing them into
# the database -- assemble.rb takes care of this.

# We'll call this three times, once for each .dat file
# Notice that the main loop yields execution to the specialized line parser.
# Each invocation of this function passes a different block to do the actual
# processing distinct to the specific file.
def process_file (input_file, output_file)
  out = File.new(output_file, "w")
  puts "starting on #{input_file}"
  IO.foreach(input_file) do |line|
    out.puts yield(line)
  end
  out.close
  puts " . . done with #{input_file}, created #{output_file}"
end

# RA.dat contains fcc structures. The block passed to process_file selects a subset of the fields, and parses the two date fields to a mysql-friendly format
process_file('RA.dat','fcc_structures.dat')  do |line|
  unique_si,date_constructed, date_dismantled, address, city, state, height, elevation, ohag, ohamsl, structure_type = line.split('|').values_at(4,12,13,23,24,25,26,27,28,29,30)
  date_contstructed = ParseDate::parsedate(date_constructed).values_at(0,1,2).join('-')
  date_dismantled = ParseDate::parsedate(date_dismantled).values_at(0,1,2).join('-')
  [unique_si, date_constructed, date_dismantled, address, city, state, height, elevation, ohag, ohamsl, structure_type].join('|')   
end

# EN.dat contains owner data. The block passed to process_file just selects a subset of fields
process_file('EN.dat','fcc_owners.dat')  do |line|
  line.split('|').values_at(4,7,14,16,17,18).join('|')
end

# CO.dat contains locations data. The block passed to process_file selects a subset of fields, and translates the 'S' and 'W' in the lat/lng fields into positive/negative float values
process_file('CO.dat','fcc_locations.dat')  do |line|
  unique_si, lat_deg, lat_min, lat_sec, lat_dir, long_deg, long_min, long_sec, long_dir = line.split('|').values_at(4,6,7,8,9,11,12,13,14)
  sign = (lat_dir == 'S') ? -1 : 1
  latitude = sign * (lat_deg.to_f + lat_min.to_f / 60 + lat_sec.to_f/3600)
  sign = (long_dir == 'W') ? -1 : 1
  longitude = sign * (long_deg.to_f + long_min.to_f / 60 + long_sec.to_f/3600)
  [unique_si, lat_deg, lat_min, lat_sec, lat_dir, latitude, long_deg, long_min, long_sec, long_dir, longitude].join('|') 
end

puts "Complete"
