class CreateUkPostcodes < ActiveRecord::Migration
  def self.up
    create_table :uk_postcodes do |t|
      t.column :outcode, :string, :limit=>4, :null=>false
      t.column :latitude, :decimal, :precision => 15, :scale => 10, :null=>false
      t.column :longitude, :decimal, :precision => 15, :scale => 10, :null=>false
    end
  end

  def self.down
    drop_table :uk_postcodes
  end
end
