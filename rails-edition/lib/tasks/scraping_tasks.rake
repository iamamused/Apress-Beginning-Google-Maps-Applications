require 'scrapi'
require 'open-uri'

task :scrape_capital_cities => :environment do
  # retrieve html
  url='http://googlemapsbook.com/chapter5/scrape_me.html'
  html = open(url).read
  puts "retrieved html from #{url}"
  
  # helper function to scrape individual rows in the table
  get_details= Scraper.define do
    process "td:nth-child(1)", :country => :text
    process "td:nth-child(2)", :capital => :text
    process "td:nth-child(3)", :lat => :text
    process "td:nth-child(4)", :lng => :text
    
    result :country, :capital, :lat, :lng
  end
  
  city_scraper = Scraper.define do
    array :cities
    process "table tr", :cities => get_details
    result :cities
  end
  
  cities= city_scraper.scrape(html)
  
  cities.each do |c|
    next if c.country =='Country'
    puts "saving city #{c.capital}"
    lat = c.lat.to_f
    lat *= -1 if c.lat.match(/S/)
    lng = c.lng.to_f
    lng *= -1 if c.lng.match(/W/)
    
    CapitalCity.create(:country=>c.country, :capital=>c.capital, :lat=>lat, :lng=>lng)
  end
  
  puts 'done'
end # end rake task