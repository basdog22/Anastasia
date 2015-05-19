<?php

trait Translatable
{

    public function translate()
    {
        $modelname = get_called_class();
        //check if the current locale is the default one.
        $current_locale = get_current_locale();
        //If default, then we need to return the field as is
        if ($this->is_default_locale($current_locale)) {

        } else {

            //else return the translated one in the current locale
            foreach ($this->attributes as $key => $attr) {
                if (in_array($key, $this->translatable)) {
                    $translated = DB::table('translations')
                        ->where('locale', '=', $current_locale)
                        ->where('ref_id', '=', $this->id)
                        ->where('translated_model', '=', $modelname)
                        ->where('field', '=', $key)
                        ->first(array('translation'));
                    if (is_null($translated)) {
                        $this->attributes[$key] = $attr;
                    } else {
                        $this->attributes[$key] = $translated->translation;
                    }
                } else {
                    $this->attributes[$key] = $attr;
                }
            }
        }
    }

    protected function getAttributeValue($key)
    {
        if (in_array($key, $this->translatable)) {
            $this->translate();
        }
        return parent::getAttributeValue($key);
    }


    public function save(array $options = array())
    {
        $current_locale = get_current_locale();
        $modelname = get_called_class();
        if ($this->is_default_locale($current_locale) || !isset($this->id)) {
            return parent::save($options);
        } elseif (isset($this->id)) {
            foreach ($this->attributes as $key => $value) {
                if (in_array($key, $this->translatable) && $this->isDirty($key)) {
                    //check if already inserted

                    $translated = DB::table('translations')
                        ->where('locale', '=', $current_locale)
                        ->where('ref_id', '=', $this->id)
                        ->where('translated_model', '=', $modelname)
                        ->where('field', '=', $key)
                        ->first();


                    if(is_null($translated)){
                        DB::table('translations')->insert(array(
                            'locale' => $current_locale,
                            'ref_id' => $this->id,
                            'translated_model' => $modelname,
                            'field' => $key,
                            'translation' => $value
                        ));
                    }else{
                        DB::table('translations')->where('id',$translated->id)->update(array(
                            'translation' => $value
                        ));
                    }


                }
            }
            return true;
        }
    }


    public function is_default_locale($current_locale)
    {
        $default_locale = get_default_locale();

        if ($default_locale == $current_locale) {
            return true;
        }
        return false;
    }
}