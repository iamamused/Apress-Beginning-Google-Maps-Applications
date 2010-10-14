class ChapFiveController < ApplicationController
  def get_pages
    @actions=%w(map)
  end

  def map
    @points=Tower.find :all, :conditions=>['state = ? AND latitude < ? AND longitude > ?  AND latitude > ? AND longitude < ?','HI',20.40,-156.34,18.52,-154.67] 
    @points=@points.collect{|t|t.attributes}
    render :template=>'app/map_div'
  end
end
