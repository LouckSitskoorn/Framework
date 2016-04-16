<?
  //NAMESPACE
  namespace SB\Functions;

  //USE classes

  //INCLUDE functions

  function preg_replace_callback_multi($pattern, $callback, $subject, $limit=-1) {
    if (is_array($subject)) {
      foreach ($subject as &$value) {
        $value=preg_replace_callback_multi($pattern, $callback, $value, $limit);
      }
      return $subject;
    } else {
      return preg_replace_callback($pattern, $callback, $subject, $limit);
    }
  }
