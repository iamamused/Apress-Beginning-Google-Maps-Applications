class ChapFourController < ApplicationController
  def get_pages
    %w(map)
  end

  def map
    @points = (Store.find :all).collect{|s|s.attributes}
    render :layout=>false
  end
end
