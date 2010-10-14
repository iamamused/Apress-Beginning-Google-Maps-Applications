# All of these tasks are expecting TIGERLINE_DIR to be set to 
# the directory in which your TIGER/Line data lives.
# I set TIGERLINE_DIR in environment.rb
#
# The first task is also expecting DATA_DIR -- the directory in which
# the uk-postcodes.csv file lives


task :import_uk_postcodes => :environment do
  begin
    csv=CSV.open("#{DATA_DIR}/uk-postcodes.csv", "r")
    header =csv.shift  # skip header row
    csv.each_with_index do |row,i|
      outcode,x,y,latitude,longitude = row
      UkPostcode.create(:outcode => outcode, :latitude => latitude, :longitude => longitude)
      puts "#{i} postcodes imported" if i % 50 == 0 
    end
  ensure
    csv.close unless csv.nil?
  end
end

# clear out the geocoder tables
task :clear_geo => :environment do
  tables = ['places','street_names','address_ranges','complete_chains']
  
  tables.each do |table| 
    sql = "truncate table "+table
    ActiveRecord::Base.connection.execute(sql)
    puts "EXECUTED: "+sql
  end

end

# required for the import tasks
require 'application'

task :import_rt1 => :environment do
  #set state and county
  state='06'
  county='075'
  type='rt1'
    
  file_prefix="TGR#{state}#{county}"
  
  i=0
  tlids=Hash.new
  parser=LineParser.new(TIGERLINE_DIR,type)
  IO.foreach("#{TIGERLINE_DIR}/#{file_prefix}.#{type.upcase}") do |line|
    # parse the line
    l=parser.parse(line)
    
    # we're not interested in the line of data in the following cases
    # 1. its CFCC type is not group A (i.e., it's not a road)
    next if l[:CFCC][0,1] != 'A'
    # 2. there are no addresses for either side of the street
    next if l[:FRADDL] == '' and l[:FRADDR] == ''
    # 3. if no city is associated with the road, it'll be hard to identify
    next if l[:PLACEL] == '' and l[:PLACER] == ''
    
    #parse the from and to lat/lngs
    from_latitude = l[:FRLAT][0,l[:FRLAT].size-6] + '.' + l[:FRLAT][l[:FRLAT].size-6,6]
    from_longitude = l[:FRLONG][0,l[:FRLONG].size-6] + '.' + l[:FRLONG][l[:FRLONG].size-6,6]
    to_latitude = l[:TOLAT][0,l[:TOLAT].size-6] + '.' + l[:TOLAT][l[:TOLAT].size-6,6]
    to_longitude = l[:TOLONG][0,l[:TOLONG].size-6] + '.' + l[:TOLONG][l[:TOLONG].size-6,6]

    #decide if this is a boundary of a place
    places = Array.new
    if l[:PLACEL] != l[:PLACER]
      places.push(l[:PLACEL]) if l[:PLACEL] !=''
      places.push(l[:PLACER]) if l[:PLACER] !=''
    else
      places.push(l[:PLACEL])
    end
    
    # we're only doing the transaction because it's faster
    StreetName.transaction do
      # loop and process the places array ( there will be up to two elments in the array)
      places.each do |place_fips|
        # 1. create a street_name
        StreetName.create(:tlid=>l[:TLID], :place_id=>"#{state}#{county}#{place_fips}",
            :cfcc=>l[:CFCC], :dir_prefix=>l[:FEDIRP], :name=>l[:FENAME],
            :street_type=>l[:FETYPE],:dir_suffix=>l[:FEDIRS]) 
            
        # 2. create one or two address_ranges
        AddressRange.create(:tlid=>l[:TLID], :range_id=>-1,:first=>l[:FRADDR] ,:last=>l[:TOADDR]) if l[:FRADDR] !=''
        AddressRange.create(:tlid=>l[:TLID], :range_id=>-2,:first=>l[:FRADDL] ,:last=>l[:TOADDL]) if l[:FRADDL] !=''
        
        # 3. create two complete_chains
        CompleteChain.create(:tlid=>l[:TLID], :seq=>0, :latitude=>from_latitude, :longitude=>from_longitude)
        CompleteChain.create(:tlid=>l[:TLID], :seq=>5000, :latitude=>to_latitude, :longitude=>to_longitude)        
                
      end # end of loop through places
    end # end of the transaction

    tlids[l[:TLID]]=true
    i=i+1
    puts "imported #{i} #{type} records" if i % 200 == 0
  end
  
  # dump the tlid's to a yaml file so we can use them in the next task
  File.open(File.join(TIGERLINE_DIR,"tlids.yaml"),'w') do |f|
    YAML.dump(tlids, f )
  end
end

task :import_rt2 => :environment do
  #set state and county
  state='06'
  county='075'
  type='rt2'
    
  file_prefix="TGR#{state}#{county}"

  # read in the tlids
  tlids=YAML.load_file( File.join(TIGERLINE_DIR,"tlids.yaml" ))
  puts "Read in #{tlids.keys.size} tlids"
  i=0
  parser=LineParser.new(TIGERLINE_DIR,type)

  IO.foreach("#{TIGERLINE_DIR}/#{file_prefix}.#{type.upcase}") do |line|
    # parse the line
    l=parser.parse(line)

    # Skip this record if we didn't encounter its TLID in rt1
    next if !tlids[l[:TLID]]
    # we're only doing the transaction because it's faster
    seq = l[:RTSQ].to_i
    CompleteChain.transaction do    
      # loop through each of the 10 points, looking for one that's 0,0    
      (1..10).each_with_index do |j,index|
        latitude=l["LAT#{j}".intern]
        longitude=l["LONG#{j}".intern]
        break if longitude.to_i == 0 || latitude.to_i == 0
  
        #parse some data
        latitude = latitude[0,latitude.size-6] + '.' + latitude[latitude.size-6,6]
        longitude = longitude[0,longitude.size-6] + '.' + longitude[longitude.size-6,6]

        #insert the record
        CompleteChain.create(:tlid=>l[:TLID], :seq=>seq, :latitude=>latitude,:longitude=>longitude)
      end #end of 10-loop
    end # end of transaction

    i=i+1
    puts "imported #{i} #{type} records" if i % 200 == 0
  end # foreach
end


task :import_rtc => :environment do
  #set state and county
  state='06'
  county='075'
  type='rtc'
    
  file_prefix="TGR#{state}#{county}"

  i=0
  place_ids = Hash.new
  parser=LineParser.new(TIGERLINE_DIR,type)
  
  IO.foreach("#{TIGERLINE_DIR}/#{file_prefix}.#{type.upcase}") do |line|
  
    # parse the line
    l=parser.parse(line)

    place_id="#{state}#{county}#{l[:FIPS]}"
    # don't process if FIPS is blank or NOT type C
    # don't process if we've already seen this place_id
    next if l[:FIPS] == ''
    next if l[:FIPSTYPE] != 'C'
    next if place_ids[place_id]
    
    place_ids[place_id] = true

    # All looks good. Insert into places
    p=Place.new(:id=>place_id, :state_fips=>state, :county_fips=>county, 
                 :place_fips=>l[:FIPS], :state_name=>'California', 
                 :county_name=>'San Francisco',
                 :place_name=>l[:NAME])
    p.id=place_id
    p.save
    
    i=i+1
    puts "imported #{i} #{type} records"
  end # foreach
end

# LineParser is used by all the TIGER/Line importing tasks in this file.
# It uses the dictionary files (in /dictionaries) to make sense of the
# raw TIGER/Line data
class LineParser
  attr_accessor :type
  
  def initialize(root_path,type)
    @type=type
    @fields = Hash.new
    IO.foreach(File.join(root_path,'dictionaries',"#{type}.dict")) do |line|
      dict_fields=line.split(/\t/)
        @fields[dict_fields[0].intern]={
            :start=>dict_fields[1].to_i,
            :length=>dict_fields[2].to_i,
            :name=>dict_fields[3].chomp}
      end
    end

    def parse(line)
      res=Hash.new
      @fields.each_key {|field| res[field]=(line[@fields[field][:start]-1 , 
                                            @fields[field][:length]]).strip}
    return res
  end
  end