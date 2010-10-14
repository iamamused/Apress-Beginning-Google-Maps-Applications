class AddIcon < ActiveRecord::Migration
  def self.up
    add_column :markers, :icon, :string, :limit=>100, :default=>''
  end

  def self.down
    remove_column :markers, :icon
  end
end
