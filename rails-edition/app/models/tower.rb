class Tower < ActiveRecord::Base
  def to_json
    self.attributes(:only=>[:id,:latitude,:longitude,:structure_type,:address,:city,:state,:height]).to_json
  end
end
