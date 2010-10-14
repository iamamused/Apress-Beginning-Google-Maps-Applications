class GoogleMapsUtil
  # constants
  TILE_SIZE = 256

  # Structures to represent points and boundries
  Point = Struct.new( "Point", :x, :y )
  Boundry = Struct.new( "Boundry", :x, :y, :width, :height )

  
  # calculate the pixesl offset withing a specific tile for the given latitude and longitude
  def self.get_pixel_offset_in_tile(lat,lng,zoom)
    pixel_coords = GoogleMapsUtil.to_zoomed_pixel_coords(lat,lng,zoom)
    return Point.new(pixel_coords.x % GoogleMapsUtil::TILE_SIZE , pixel_coords.y % GoogleMapsUtil::TILE_SIZE)
  end
  
  # convert from latitude and longitude to Mercator coordinates
  def self.to_mercator_coords(lat,lng)
    if lng > 180
      lng -= 360
    end
    
    lng = lng / 360.0
    lat = Math::asinh(Math::tan((lat/180.0)*Math::PI))/Math::PI/2.0
    result = Point.new(lng,lat)
    return result
  end

  # normalize the mercator coordinates
  def self.to_normalized_mercator_coords(point)
    point.x += 0.5
    point.y = ((point.y - 0.5).abs)
    return point
  end

  # translate to pixel coordinates within a tile
  def self.to_zoomed_pixel_coords(lat,lng,zoom)
    normalized = GoogleMapsUtil.to_normalized_mercator_coords(
      GoogleMapsUtil.to_mercator_coords(lat,lng))
      
    scale = (1 << zoom) * GoogleMapsUtil::TILE_SIZE
    
    return Point.new((normalized.x * scale).to_i,(normalized.y * scale).to_i)
  end

  # determine the geographical bounding box for the specified tile index and zoom level
  def self.get_tile_rect(x,y,zoom)
    tiles_at_this_zoom = (1 << zoom)
    lng_width = 360.0 / tiles_at_this_zoom
    lng = -180 + (x * lng_width)
    
    lat_height_merc = 1.0 / tiles_at_this_zoom
    top_lat_merc = y * lat_height_merc
    bottom_lat_merc = top_lat_merc + lat_height_merc
    
    bottom_lat = (180 / Math::PI) * ((2*Math.atan(Math.exp(Math::PI * (1 - (2 * bottom_lat_merc)))))-(Math::PI/2))
    top_lat = (180 / Math::PI) * ((2*Math.atan(Math.exp(Math::PI * (1 - (2 * top_lat_merc)))))-(Math::PI/2))
 
    lat_height = top_lat - bottom_lat
 
    return Boundry.new(lng,bottom_lat,lng_width,lat_height)
  end

end