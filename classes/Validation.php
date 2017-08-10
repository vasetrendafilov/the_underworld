<?php


class Validation {

    private $_passed = false,
            $_errors = array(),
            $_db     = null;

    public function __construct(){
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array()){
        foreach($items as $item => $rules){
            foreach($rules as $rule => $rule_value){

                $value = trim($source[$item]);
                $item = escape($item);

                if($rule === 'required' && empty($value)){
                    $this->addError("{$item} is rquired.");
                }else if(!empty($value)){

                    switch($rule){
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                            break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$item} should not exceed {$rule_value} characters.");
                            }
                            break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$rule_value} must match {$item}");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()){
                                $this->addError("{$item} already exists.");
                            }
                            break;
                        case 'up/downcase':
                        if($rule_value){
                          if(preg_match('/[a-z]/', $value) && preg_match('/[A-Z]/',$value)){
                          }else if(preg_match('/[a-z]/', $value)) {//dali ima mala bukva
                                $this->addError("{$item} must have at least one uppercase letter.");
                            }else if(preg_match('/[A-Z]/',$value)){//dali ima golema bukva
                               $this->addError("{$item} must have at least one lowercase letter.");
                              }
                          }
                         break;
                        case 'addnumber':
                        if($rule_value){
                            if(!preg_match('/\d/', $value)){//dali ima broj funkcija
                            $this->addError("{$item} must have at least one number.");
                            }
                        }
                          break;
                        case 'addsimbol':
                        if($rule_value){
                            if(!preg_match('/\W/', $value)){//dali ima simbol funkcija
                            $this->addError("{$item} must have at least one simbol.");
                          }
                        }
                          break;
                    }
                }
            }
        }

        if(empty($this->_errors)){
            $this->_passed = true;
        }

        return $this;
    }

    private function addError($error){
        $this->_errors[] = $error;
    }

    public function errors(){
        return $this->_errors;
    }

    public function passed(){
        return $this->_passed;
    }

}
