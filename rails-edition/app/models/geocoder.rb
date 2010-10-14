require 'open-uri'
require 'rexml/document'

class Geocoder
  attr_accessor :google_key, :yahoo_key
  
  def google(address)
    xml=open("http://maps.google.com/maps/geo?q=#{CGI.escape(address)}&output=xml&key=#{@google_key}").read
    doc=REXML::Document.new(xml)

    if doc.elements['//kml/Response/Status/code'].text == '200'
      coordinates=doc.elements['//coordinates'].text.to_s.split(',')

      result ={:success=> true, 
              :lat=> coordinates[1], 
              :lng=> coordinates[0],
              :city=> doc.elements['//LocalityName'].text,
              :state=> doc.elements['//AdministrativeAreaName'].text,
              :country_code=> doc.elements['//CountryNameCode'].text,
              :full_address => doc.elements['//address'].text,
              :zip=> doc.elements['//PostalCodeNumber'].text,
              :street_address => doc.elements['//ThoroughfareName'].text,
              :precision=>'',
              :warning=>'',
              :full_result=>xml}
      return result    
    else 
      puts "Invalid address: "+address
      return {:success=> false}
    end   
    
    rescue 
      puts "Caught an error during geocoding call: "+$!
      return {:success=>false}    
  end 

  def yahoo(address)
    url="http://api.local.yahoo.com/MapsService/V1/geocode?appid=#{@yahoo_key}&location=#{CGI.escape(address)}"
    xml=open(url).read
    doc=REXML::Document.new(xml)

    if doc.elements['//ResultSet']
      result ={:success=> true, 
              :lat=> doc.elements['//Latitude'].text,
              :lng=> doc.elements['//Longitude'].text,
              :city=> doc.elements['//City'].text,
              :state=> doc.elements['//State'].text,
              :country_code=> doc.elements['//Country'].text,
              :full_address => '',
              :zip=> doc.elements['//Zip'].text,
              :street_address => doc.elements['//Address'].text,
              :precision=>'',
              :warning=>'',
              :full_result=>xml}
      return result    
    else 
      puts "Invalid address: "+address
      return {:success=> false}
    end   
    
    rescue 
      puts "Caught an error during geocoding call: "+$!
      return {:success=>false}    
  end

  def geocoder_us(address)
    url="http://geocoder.us/service/csv/geocode?address=#{CGI.escape(address)}"
    res=open(url).read.chomp
    array=res.split(',')
    return {:success=> false} if array.length != 6  
    lat,lng,address,city,state,zip=res
    result ={:success=> true, 
            :lat=> lat,
            :lng=> lng,
            :city=> city,
            :state=> state,
            :country_code=> 'US',
            :full_address => "#{address}, #{city}, #{state}, #{zip}",
            :zip=> zip,
            :street_address =>address,
            :precision=>'',
            :warning=>''}
    
    rescue 
      puts "Caught an error during geocoding call: "+$!
      return {:success=>false}    
  end

end
