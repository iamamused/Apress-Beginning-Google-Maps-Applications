task :import_towers => :environment do
  start = Time.now
  count=0
  IO.foreach("/path/to/towers.dat") do |line|
    c = line.split('|')
    Tower.create(
      :latitude =>c[1],
      :longitude =>c[2],
      :owner_name =>c[3],
      :owner_address =>c[4],
      :owner_city =>c[5],
      :owner_state =>c[6],
      :owner_zip =>c[7],
      :address =>c[8],
      :city =>c[9],
      :state =>c[10],
      :height =>c[11],
      :elevation =>c[12],
      :ohag =>c[13],
      :ohamsl =>c[14],
      :structure_type =>c[15].chomp)
      
    count += 1
    puts "imported #{count} towers in #{Time.now - start} seconds" if count % 200 == 0
    
  end
end