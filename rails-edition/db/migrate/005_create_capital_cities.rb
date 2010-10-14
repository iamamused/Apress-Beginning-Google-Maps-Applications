class CreateCapitalCities < ActiveRecord::Migration
  def self.up
    create_table :capital_cities do |t|
      t.column :country, :string, :null=>false
      t.column :capital, :string, :null=>false
      t.column :lat, :decimal, :precision => 15, :scale => 10, :default=>0
      t.column :lng, :decimal, :precision => 15, :scale => 10, :default=>0

    end
  end

  def self.down
    drop_table :capital_cities
  end
end
