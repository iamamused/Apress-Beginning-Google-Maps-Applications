
# Filters added to this controller will be run for all controllers in the application.
# Likewise, all the methods added will be available for all controllers.
class ApplicationController < ActionController::Base 
  session :off
  before_filter :check_dynamic_methods

  def index
    render :template=>'app/chapter_index', :layout=>'navigation'
  end
  
  private
  def check_dynamic_methods
    if  !public_methods.include?(params[:action]) && get_pages.include?(params[:action])
      template=File.join(RAILS_ROOT,'app','views',controller_name,params[:action]+'.rhtml')

      if File.exists?(template)
        render(:template=>"#{controller_name}/#{params[:action]}", :layout=>false)
      else
        render(:template=>'app/map_div')
      end 
    end
  end
end
