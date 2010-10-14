class ChapSevenController < ApplicationController
  
  def get_pages
    %w(server_boundary server_common_point server_clustering client_boundary client_common_point client_clustering)
  end  

  def client_boundary
    @points = Tower.find(:all,
          :select=>"latitude, longitude",
          :conditions=>['longitude > ? AND longitude < ? AND latitude <= ? AND latitude >= ?',-73.3996,-71.7517,42.5894,41.570251]) 
    render :template=>'app/map_div'
  end

  def client_common_point
    @points = Tower.find(:all,
          :select=>"latitude, longitude",
          :conditions=>['longitude > ? AND longitude < ? AND latitude <= ? AND latitude >= ?',-73.3996,-71.7517,42.5894,41.570251]) 
    render :template=>'app/map_div'
  end

  def client_clustering
    @points = Tower.find(:all,
          :select=>"latitude, longitude",
          :conditions=>['longitude > ? AND longitude < ? AND latitude <= ? AND latitude >= ?',-73.3996,-71.7517,42.5894,41.570251]) 
    
    @points=@points.collect{|p|p.attributes}
          
    render :template=>'app/map_div'
  end
  
  def cities_within_bounds
    ne = params[:ne].split(',').collect{|e|e.to_f}  
    sw = params[:sw].split(',').collect{|e|e.to_f}
    
    # if the NE longitude is less than the SW longitude, 
    # it means we are split over the meridian.
    if ne[1] > sw[1] 
      conditions = 'lng > ? AND lng < ? AND lat <= ? AND lat >= ?'
    else
      conditions = '(lng >= ? OR lng < ?) AND lat <= ? AND lat >= ?'    
    end
    
    cities=CapitalCity.find :all,
      :conditions => [conditions,sw[1],ne[1],ne[0],sw[0]]
    
    render :text=>cities.collect{|c|c.attributes}.to_json
  end
  
  
  def nearby_towers
    lat,lng=params[:ll].split(',').collect{|e|e.to_f}
    ne = params[:ne].split(',').collect{|e|e.to_f}  
    sw = params[:sw].split(',').collect{|e|e.to_f}
    
    #convert to radians
    lat_radians=(lat / 180) * Math::PI
    lng_radians=(lng / 180) * Math::PI
    distance_sql=<<-SQL_END
    (acos(cos(#{lat_radians})*cos(#{lng_radians})*cos(radians(latitude))*cos(radians(longitude)) +
  cos(#{lat_radians})*sin(#{lng_radians})*cos(radians(latitude))*sin(radians(longitude)) +
  sin(#{lat_radians})*sin(radians(latitude))) * 3693)
    SQL_END
    
    towers = Tower.find(:all,
          :select=>"latitude, longitude, #{distance_sql} as distance",
          :conditions=>['longitude > ? AND longitude < ? AND latitude <= ? AND latitude >= ?',sw[1],ne[1],ne[0],sw[0]],
          :order => 'distance asc',
          :limit => 20)
          
    render :text=>towers.collect{|c|c.attributes}.to_json
  end  
  
  def clustered_towers
    ne = params[:ne].split(',').collect{|e|e.to_f}  
    sw = params[:sw].split(',').collect{|e|e.to_f}
  
    # get all the towers within the bounds. No need to worry about the meridian here, since all towers are in the US    
    towers=Tower.find :all,
      :select=>['latitude, longitude, structure_type, owner_name'],
      :conditions => ['longitude > ? AND longitude < ? AND latitude <= ? AND latitude >= ?',sw[1],ne[1],ne[0],sw[0]]  

    # limit to 30 markers
    max_markers = 30
    lng_span=0
    lat_span=0   
    clustered=Hash.new
    
    logger.debug("Starting clustering: #{towers.length} towers.")
    
    # we'll probably have to loop a few times to get the number of clustered markers
    # below the max_markers value
    loop do  
      # Initially, each cell in the grid is 1/30th of the longitudinal span,
      # and 1/30th of the latitudinal span. With multiple iterations of the loop,
      # the spans get larger (and therefore clusters get more markers)
      lng_span+=(ne[1]-sw[1])/30
      lat_span+=(ne[0]-sw[0])/30
      
      # This is where we're going to be puting the towers we've clustered into groups
      # the key of the hash is the coordinates of the grid cell, 
      # and the value is an array of towers which have been assigned to the cell
      clustered=Hash.new
 
      # we're going to be iterating however many times we need to,
      towers.each do |tower|
        # determine which grid square this marker should be in
        grid_y=((tower.latitude-sw[0])/lat_span).floor
        grid_x=((tower.longitude-sw[1])/lng_span).floor
        
        # create a new array if it doesn't exist
        key="#{grid_x}_#{grid_y}"
        (clustered[key]=Array.new) if !clustered.has_key?(key) 
        clustered[key].push(tower)  
      end # end of iterating through each tower
      
      logger.debug "Current clustering has #{clustered.size} elements."
      break unless clustered.length > max_markers
    end
    
    # At this point we've got max_markers or less points to render.
    # Now, let's go through and determine which cells have multiple markers
    # (which needs to be rendered as a cluster), and which cells have a single marker
    result=Array.new
    clustered.each_value do |tower_array|
      # regardless of whether this is a cluster or an individual, set the coordinates to  
      # the tower in the array
      marker = {:latitude => tower_array[0].latitude,
                :longitude => tower_array[0].longitude,
                :structure_type => tower_array[0].structure_type, 
                :type=>'m'}

      # here's where we differentiate between clusters and individual markers
      marker[:type] = 'c' if tower_array.size > 1
      result.push(marker)
    end 
      
    render :text=>result.to_json
  end
  
  # don't forget the require and include at the top of this file
  def get_overlay
    ne = params[:ne].split(',').collect{|e|e.to_f}  
    sw = params[:sw].split(',').collect{|e|e.to_f}
    nePx = params[:nePixels].split(',').collect{|e|e.to_i}  
    swPx = params[:swPixels].split(',').collect{|e|e.to_i}  
    
    width = (nePx[0]-swPx[0]).abs.to_i
    height = (nePx[1]-swPx[1]).abs.to_i
    zoom = (params[:zoom]).to_i

    towers=Tower.find :all,
      :select=>['latitude, longitude, structure_type, owner_name'],
      :conditions => ['longitude > ? AND longitude < ? AND latitude <= ? AND latitude >= ?',sw[1],ne[1],ne[0],sw[0]]  

    #calculate the Mercator coordinate position of the top
    #latitude and longitude and normalize from 0-1
    merc_top = 0.5-(Math::asinh(Math::tan((ne[0]/180.0)*Math::PI)) / Math::PI / 2)
    
    #calculate the sacle and y position of the the google map
    scale = (1<< zoom)*256
    y_top = merc_top * scale
    
    #calculate the pixels per degree of longitude
    lng_span=ne[1]-sw[1]
    pixels_per_deg_lng=(width/lng_span).abs

    #create the image 
    canvas = Magick::Image.new(width, height) {self.background_color='transparent'}
    canvas.format = "GIF"

    gc = Magick::Draw.new 
   
    gc.stroke('white')
    gc.fill('black')
    gc.stroke_width(1)

    rails = Magick::ImageList.new("public/images/rails.png")
    rails.resize!(20,20)
  

    towers.each do |tower|
      x = (((tower.longitude-sw[1]).abs)*pixels_per_deg_lng).ceil
      # calculate the Mercator coordinate position of this point's
      # latitude and longitude and normalize from 0-1
      y_merc = 0.5-(Math::asinh(Math::tan((tower.latitude/180.0)*Math::PI)) / Math::PI / 2)
      #calculate the y position on the Google map
      y_map = y_merc * scale
      #calculate the y position in the overlay
      y = y_map - y_top
      canvas.composite!(rails, x,y,OverCompositeOp)
      #gc.circle(x,y, x+(0.5*zoom).ceil,y+(0.5*zoom).ceil)
    end
    
    gc.draw(canvas)
    
    send_data canvas.to_blob, :type => 'image/gif', :disposition => 'inline'
  end
  
  # custom tile controller
  def get_tile
    x,y,zoom = [params[:x].to_i,params[:y].to_i,params[:zoom].to_i]
    #make sure our tile store exists, create it if it doesn't
    directory='public/map_tiles'
    if ! File.exists? directory
      FileUtils.mkdir_p directory
    end
    
    #create a unique file name for this file
    filename = "tile-#{x}-#{y}-#{zoom}.png" 
    full_path = File.join(directory, filename)
    
    # create the file if it doesn't already exist
    if !File.exist?(full_path)
  
      # create an array of marker size at each zoom level
      marker_sizes=[1,1,1,1,1,1,2,2,2,2,3,3,3,3,3,3,4,4,4,4,5,5,5,5,6,6]
    
      # get the lat/lng bounds of ths time from the helper method
      rect = GoogleMapsUtil.get_tile_rect(x,y,zoom)

      #init some vars
      swlat = rect.y
      swlng = rect.x
      nelat = swlat+rect.height
      nelng = swlng+rect.width

      # get the towers
      towers=Tower.find :all,
        :select=>['latitude, longitude, structure_type, height'],
        :conditions => ['longitude >= ? AND longitude <= ? AND latitude <= ? AND latitude >= ?',swlng,nelng,nelat,swlat]  
    
      canvas = Magick::Image.new(GoogleMapsUtil::TILE_SIZE, GoogleMapsUtil::TILE_SIZE){
        self.background_color='transparent'}
      canvas.format="PNG"
    
      gc = Magick::Draw.new
      #gc.stroke_width(1)

      towers.each do |tower|
        point = GoogleMapsUtil.get_pixel_offset_in_tile(tower.latitude,tower.longitude,zoom)

        # pick a color based on the tower's height
        case tower.height
          when 0...20
            color='darkred'
          when 21...40
            color='red'
          when 41...80
            color='darkgreen'
          when 81...120
            color='green'
          when 121...200
            color='darkblue'
          else
            color='blue'
        end
        gc.stroke('white')
        gc.fill(color)
  
        # get the size
        size=marker_sizes[zoom]
        if zoom < 2
          canvas.pixel_color point.x, point.y, color
        else 
          gc.circle(point.x-size,point.y-size,point.x+size,point.y+size)        
        end  
        
      end
        
      gc.draw(canvas)
      
      text = Magick::Draw.new
      text.annotate(canvas, 0, 0, 0, 0, "#{towers.size} points in tile #{x},#{y} @ zoom #{zoom}.") {
        self.gravity = Magick::SouthGravity
        self.fill = 'black'
        self.text_antialias=true;
        }
      canvas.write(full_path)
    end
    
    send_file full_path, :type => 'image/png', :disposition => 'inline'
  end
end

