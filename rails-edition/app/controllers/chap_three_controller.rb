class ChapThreeController < ApplicationController
  def get_pages
    %w(final final_with_prototype)
  end

  def create   
    marker = Marker.new(params[:m])
    if marker.save
      res={:success=>true,:icon=>marker.icon,:content=>"<div><strong>found </strong>#{marker.found}</div><div><strong>left </strong>#{marker.left}</div>"}
    else
      res={:success=>false,:content=>"Could not save the marker"}
    end
    render :text=>res.to_json
  end
  
  def list
    render :text=>(Marker.find :all).to_json
  end
end
