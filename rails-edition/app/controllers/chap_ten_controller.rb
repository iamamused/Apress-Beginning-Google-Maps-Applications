class ChapTenController < ApplicationController

  def get_pages
    %w(flat_area polyline client_geocoder)
  end
  
  def flat_area
    render :layout=>false
  end

  def polyline
    render :layout=>false
  end

  def client_geocoder
    render :layout=>false
  end  
end
