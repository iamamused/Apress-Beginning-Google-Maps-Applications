# TIGERL/Line geocoder. This is the main method.  
def geocode(number,street,city,state)
  # variable prep
  number=number.to_i
  
  # get the place, and only continue if we find it
  place=Place.find_by_place_name_and_state_name(city,state)
  
  if place != nil
    # get the street name and address, and only continue if we find it
    address_range=AddressRange.find :first,
      :select => "ar.tlid as tlid, first, last, (#{number}-first) as diff",
      :joins => 'AS ar INNER JOIN street_names AS sn ON ar.tlid=sn.tlid',
      :conditions => ["sn.place_id = ? AND sn.name = ? AND ? BETWEEN ar.first AND ar.last", place.id, street, number ],
      :order=>'diff'
    
    if address_range != nil
      first_address = address_range.first.to_i
      last_address = address_range.last.to_i
      
      # we now have a single tlid, grab all the points in the chain
      chains = CompleteChain.find_all_by_tlid address_range.tlid, :order=> 'seq'
      
      segment_lengths = Array.new
      num_segments=chains.length-1    
      total_length=0
      i=0
      # compute the lengths of all the segments in the chain        
      while (i < num_segments)
        length=line_length(chains[i].latitude,chains[i].longitude,chains[i+1].latitude,chains[i+1].longitude)
        segment_lengths.push(length)      
        total_length += length
        i +=1
      end

      # avoid division-by-zero errors.
      # if total legth is 0, return with the lat/lng of end point      
      if total_length == 0 
        result={:sucess=>true, :lat=>chain[0].latitude, :lng=>chain[0].longitude, :segment_num=>0,:chains=>chains.collect{|c|c.attributes}}
      else
        # compute how far along the chain our address is
        address_position = (number - first_address).abs.to_f
        num_addresses = (first_address - last_address).abs.to_f
        distance_along_line = (address_position / num_addresses) * total_length

        # figure out which segment our address is in, and interpolate its location
        travel_distance = 0
        i=0
        while i < num_segments
          bottom_address = first_address + (travel_distance / total_length * num_addresses)
          travel_distance += segment_lengths[i]
          
          if travel_distance >= distance_along_line
            # we've found our segment, do the final computation
            top_address = first_address + ((travel_distance / total_length)*num_addresses)
            
            #determine how far along this segment our address is
            seg_addr_total = (top_address - bottom_address).abs.to_f
            addr_position = (number - bottom_address).abs / seg_addr_total
                        
            # determine the deltas within this segment
            delta_y = (chains[i+1].latitude - chains[i].latitude).to_f
            delta_x = (chains[i+1].longitude - chains[i].longitude).to_f
                          
            x=chains[i].longitude + delta_x*addr_position
            y=chains[i].latitude + delta_y*addr_position
            
            segment_num=i+1
            break
            end # end if we've found our segment
          i +=1
        end # end iteration through segments
        
        result={:success=>true, :lat=>y, :lng=>x, :segment_num=>segment_num,:chains=>chains.collect{|c|c.attributes}}
      end # end if total_length == 0
    end # end if address_range != nil  
  end # enf if place != nil
  
    return result
  end
    
  def line_length(x1,y1,x2,y2)
    delta_x = (x1-x2).abs
    delta_y = (y1-y2).abs
    return Math.sqrt(delta_x**2 + delta_y**2)
  end

