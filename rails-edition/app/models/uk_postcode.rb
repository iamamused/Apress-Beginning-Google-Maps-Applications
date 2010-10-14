class UkPostcode < ActiveRecord::Base

  def self.fuzzy_find_by_outcode(code)
    code = code.upcase.gsub(/[^A-Z0-9]/,'')[0..4]
    result=nil
    loop do
      break if code.length == 0 || result
      result=self.find :first, :conditions=>['outcode like ?', "#{code}%"]
      code.chop! unless result
    end
    
    return result
  end
end
