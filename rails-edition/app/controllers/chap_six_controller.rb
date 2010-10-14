class ChapSixController < ApplicationController
  before_filter :get_towers
  layout false, :except=>'index'
  
  def get_pages
    ['fullscreen_map','map_with_toolbar','map_with_sidebar','show_hide_links','three_panels','populated_sidebar','finished_map']
  end

  def fullscreen_map
  end 
  
  def map_with_toolbar
  end
  
  def map_with_sidebar
  end
    
  def show_hide_links
  end

  def three_panels
  end
  
  def populated_sidebar
  end
  
  def finished_map
  end
    
  private
  def get_towers
    @towers=Tower.find :all, :conditions=>['state = ? AND latitude < ? AND longitude > ?  AND latitude > ? AND longitude < ?','HI',20.40,-156.34,18.52,-154.67]    
    @types=@towers.collect{|tower|tower.structure_type}.uniq    
  end


end
