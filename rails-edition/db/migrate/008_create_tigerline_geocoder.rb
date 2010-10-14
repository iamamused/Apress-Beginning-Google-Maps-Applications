class CreateTigerlineGeocoder < ActiveRecord::Migration
  def self.up
    create_table :places, :id=>false do |t|
      t.column :id, :string, :limit=>14, :null=>false, :default=>''
      t.column :state_fips, :string, :limit=>2, :null=>false, :default=>''
      t.column :county_fips, :string, :limit=>3, :null=>false, :default=>''
      t.column :place_fips, :string, :limit=>5, :null=>false, :default=>''
      t.column :state_name, :string, :limit=>60, :null=>false, :default=>''
      t.column :county_name, :string, :limit=>30, :null=>false, :default=>''
      t.column :place_name, :string, :limit=>30, :null=>false, :default=>''
    end
    execute 'ALTER TABLE places ADD PRIMARY KEY (id)'    
    
    create_table :street_names do |t|
      t.column :tlid, :integer, :null=>false, :default=>0
      t.column :place_id, :string, :limit=>15, :null=>false, :default=>''
      t.column :cfcc, :string, :limit=>3, :null=>false, :default=>''
      t.column :dir_prefix, :string, :limit=>2, :null=>false, :default=>''
      t.column :name, :string, :limit=>30, :null=>false, :default=>''
      t.column :street_type, :string, :limit=>4, :null=>false, :default=>''
      t.column :dir_suffix, :string, :limit=>2, :null=>false, :default=>''
      t.column :cfcc, :string, :limit=>3, :null=>false, :default=>''
    end
    add_index :street_names, [:tlid,:name]

    create_table :address_ranges do |t|
      t.column :tlid, :integer, :null=>false, :default=>0
      t.column :range_id, :integer, :null=>false, :default=>0
      t.column :first, :string, :limit=>11, :null=>false, :default=>0
      t.column :last, :string, :limit=>11, :null=>false, :default=>0
    end
    add_index :address_ranges, :tlid
    
    create_table :complete_chains do |t|
      t.column :tlid, :integer, :null=>false, :default=>0
      t.column :seq, :integer, :null=>false, :default=>0
      t.column :latitude, :decimal, :precision => 15, :scale => 10, :null=>false, :default=>0
      t.column :longitude, :decimal, :precision => 15, :scale => 10, :null=>false, :default=>0
    end
    add_index :complete_chains, :tlid
  end

  def self.down
    drop_table :places
    drop_table :street_names
    drop_table :address_ranges
    drop_table :complete_chains
  end
end
