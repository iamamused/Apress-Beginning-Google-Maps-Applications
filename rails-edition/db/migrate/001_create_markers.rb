class CreateMarkers < ActiveRecord::Migration
  def self.up
    create_table :markers do |t|
      t.column :lat, :decimal, :precision => 15, :scale => 10
      t.column :lng, :decimal, :precision => 15, :scale => 10
      t.column :found, :string, :limit=>100
      t.column :left, :string, :limit=>100
    end
  end

  def self.down
    drop_table :markers
  end
end
