class Store < ActiveRecord::Base
  # ACL: added for chapter 6
  #cattr_accessor :public_fields
  #@@public_fields = [:id, :name]

  #def to_json
  #  self.attributes(:only => @@public_fields).to_json
  #end
  # ACL: end added for chapter 6

  def full_address
    "#{address}, #{city}, #{state}, #{zip}"
  end
end
