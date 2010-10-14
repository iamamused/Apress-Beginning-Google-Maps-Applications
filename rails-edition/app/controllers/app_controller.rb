class AppController < ApplicationController
  def geocoders_redirect
    redirect_to 'http://googlemapsbook.com/geocoders'
  end
  
  def index
    render :layout=>'navigation'
  end
  
end
