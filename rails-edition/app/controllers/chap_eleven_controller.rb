require 'chap_eleven/tiger_line_geocoder'

class ChapElevenController < ApplicationController
  def get_pages
    %w(tigerline_geocoder)
  end

  def get_uk_geocode
    code = params[:code].upcase.gsub(/[^A-Z0-9]/,'')[0..4]
    
    result=UkPostcode.find_by_outcode(code)    
    
    if result
      render :xml=>result.to_xml(:except => :id,:root=>'result')
    else 
      render :xml=>{:error=>'No Matches'}.to_xml(:root=>'result')
    end
  end

  def get_uk_geocode_fuzzy    
    code = params[:code].upcase.gsub(/[^A-Z0-9]/,'')[0..4]
    result=UkPostcode.fuzzy_find_by_outcode(code)    
    
    if result
      render :xml=>result.to_xml(:except => :id,:root=>'result')
    else 
      render :xml=>{:error=>'No Matches'}.to_xml(:root=>'result')
    end
  end

  def manual
    result=geocode(params[:number],params[:street], params[:city], params[:state])
    if result
      render :xml=>result.to_xml(:root=>'result')
    else 
      render :xml=>{:error=>'No Matches'}.to_xml(:root=>'result')
    end
  end
  
  
  def tigerline_geocoder
    if request.post?
      @number = params[:input][:number].to_i
      @street = params[:input][:street]
      city="San Francisco"
      state="California"
      @geo=geocode(@number, @street, city, state)
    end
  end
  
end
